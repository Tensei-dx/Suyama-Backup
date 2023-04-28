<template>
    <div class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-background" />
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title text-black" v-text="$t('management.keyManagementPage.updateAdminAccount')" />
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close"
                            :disabled="isLoading">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <form id="update-admin-user-form" class="px-2" @submit.prevent="onSubmit">

                        <!-- Username -->
                        <div class="form-group">
                            <label for="username" class="font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.username')" />
                            <input type="text" id="username" v-model="form.username" class="form-control"
                                   @input="errors.username=null" :disabled="isLoading || hasResponded">
                            <template v-for="(message, index) in errors.username">
                                <span :key="index" class="text-danger mr-1" v-text="message" />
                            </template>
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.name')" />
                            <input type="text" id="name" v-model="form.name" class="form-control"
                                   @input="errors.name=null" :disabled="isLoading || hasResponded" />
                            <template v-for="(message, index) in errors.name">
                                <span :key="index" class="text-danger mr-1" v-text="message" />
                            </template>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.email')" />
                            <input type="text" id="email" v-model="form.email" class="form-control"
                                   @input="errors.email=null" :disabled="isLoading || hasResponded">
                            <template v-for="(message, index) in errors.email">
                                <span :key="index" class="text-danger mr-1" v-text="message" />
                            </template>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="phone_number" class="font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.phoneNumber')" />
                            <input type="tel" id="phone_number" v-model="form.phone_number" class="form-control"
                                   @input="errors.phone_number=null" :disabled="isLoading || hasResponded" />
                            <template v-for="(message, index) in errors.phone_number">
                                <span :key="index" class="text-danger mr-1" v-text="message" />
                            </template>
                        </div>

                        <!-- PIN Code -->
                        <div class="form-group form-check">
                            <input type="checkbox" id="update-pin-flag" v-model="form.update_pin_flag"
                                   class="form-check-input" :disabled="isLoading || hasResponded" />
                            <label for="update-pin-flag"
                                   class="font-weight-bold text-uppercase text-black form-check-label"
                                   v-text="$t('remotelock.updatePinFlag')" />
                        </div>

                        <!-- Alert Message -->
                        <div v-if="alert.show" class="alert" :class="alert.class" role="alert">
                            <h4 class="alert-heading" v-text="$t(alert.title)" />
                            <p class="mb-0" v-text="$t(alert.body)" />
                        </div>

                    </form>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">

                    <!-- Cancel -->
                    <button type="button" class="btn cancel-btn" @click="close" :disabled="isLoading || hasResponded">
                        <span v-text="$t('management.keyManagementPage.cancel')" />
                    </button>

                    <!-- Submit -->
                    <button type="submit" form="update-admin-user-form" class="btn btn-primary"
                            :disabled="isLoading || hasResponded">
                        <i v-if="isLoading" class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
                        <span class="font-weight-bold"
                              v-text="$t('management.keyManagementPage.' + (isLoading ? 'updating' : 'update'))" />
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            required: true,
            default: {},
        },
    },

    data() {
        return {
            isLoading: false,
            hasResponded: false,
            userCache: {},
            form: {},
            errors: {},
            errorMessage: [],
            alert: {
                show: false,
                class: null,
                title: null,
                body: null,
            },
        }
    },

    mounted() {
        // set the cache to the latest data of user prop
        this.userCache = { ...this.user }
    },

    watch: {
        userCache: {
            handler() {
                // when the cache is change,
                // set the form data to the latest cache
                this.fillUp()
            },
            // use deep watch for objects and array
            deep: true,
        },
    },

    methods: {
        /**
         * @name fillUp
         * @description Assign the cached data to the form
         *
         * @returns {void}
         */
        fillUp() {
            this.form = {
                username: this.userCache.USERNAME,
                name: this.userCache.FIRST_NAME,
                email: this.userCache.EMAIL,
                phone_number: this.userCache.CONTACT_NUMBER,
                update_pin_flag: false,
            }
        },

        /**
         * @name onSubmit
         * @description Update the access user
         *
         * @returns {void}
         */
        onSubmit() {
            this.isLoading = true
            axios
                .put(`users/${this.userCache.USER_ID}`, this.form)
                .then(response => this.onSuccess())
                .catch(error => {
                    if (error.response.status === 422) {
                        // for status code 422, show the validation error messages
                        this.errors = error.response.data.errors
                    } else {
                        // for general error, show the error message instead
                        this.onFail()
                    }
                })
                .then(() => (this.isLoading = false))
        },

        /**
         * @name onSuccess
         * @description Handle success response, emit event to refresh users table
         *
         * @returns {void}
         */
        onSuccess() {
            this.$bus.emit('refresh-users-table')
            this.hasResponded = true
            this.alert = {
                class: 'alert-success',
                title: 'remotelock.message.success.title',
                body: 'remotelock.message.success.body',
                show: true,
            }
            setTimeout(() => this.close(), 3000)
        },

        /**
         * @name onFail
         * @description Handle fail response
         *
         * @returns {void}
         */
        onFail() {
            this.hasResponded = true
            this.alert = {
                class: 'alert-danger',
                title: 'remotelock.errors.unknownError.title',
                body: 'remotelock.errors.unknownError.redirect',
                show: true,
            }
            setTimeout(() => this.close(), 3000)
        },

        /**
         * @name close
         * @description Clear cache then close modal
         *
         * @returns {void}
         */
        close() {
            this.userCache = {}
            this.isLoading = false
            this.$emit('close')
        },
    },
}
</script>

<style scoped>
.cancel-btn {
    background-color: #aaa;
    border-color: #aaa;
    color: #fff;
}
</style>
