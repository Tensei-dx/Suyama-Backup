<template>
    <div class="modal d-block" tabindex="-1" role="dialog">
        <div class="modal-background" />
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title text-black" v-text="$t('management.keyManagementPage.deleteAdminAccount')" />
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close"
                            :disabled="isLoading">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body text-black">
                    <form id="delete-admin-user-form" class="px-2" @submit.prevent="onSubmit">

                        <!-- Warning -->
                        <h4 class="d-block mb-4" v-text="$t('management.keyManagementPage.deleteModal')" />

                        <!-- Username -->
                        <div class="form-group row">
                            <label for="username"
                                   class="col-3 col-form-label font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.username')" />
                            <div class="col">
                                <output type="text" id="username" v-text="form.username"
                                        class="form-control-plaintext" />
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="form-group row">
                            <label for="name" class="col-3 col-form-label font-weight-bold text-uppercase text-black"
                                   v-text="$t('remotelock.name')" />
                            <div class="col">
                                <output type="text" id="name" v-text="form.name" class="form-control-plaintext" />
                            </div>
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
                    <button type="submit" class="btn btn-danger" form="delete-admin-user-form"
                            :disabled="isLoading || hasResponded">
                        <i v-if="isLoading" class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
                        <span v-text="$t('management.keyManagementPage.' + (isLoading ? 'deleting' : 'delete'))" />
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
            // user deep watch for objects and array
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
            }
        },

        /**
         * @name onSubmit
         * @description Delete the access user
         *
         * @returns {void}
         */
        onSubmit() {
            this.isLoading = true
            axios
                .delete(`users/${this.userCache.USER_ID}`)
                .then(response => this.onSuccess())
                .catch(error => this.onFail())
                .then(() => (this.isLoading = false))
        },

        /**
         * @name onSuccess
         * @description Handle success response, emit event to refresh the users table
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
