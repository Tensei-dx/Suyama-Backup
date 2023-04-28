<!-- UPDATED: TP Shannie SPRINT_12 TASK233 20211015 -->
<template>
    <div class="w-100 management-scroll">
        <div class="row mx-0 text-black">

            <!-- Back -->
            <div class="col-auto d-block pointer pl-0 pr-3 mt-3" @click="back" :disabled="isLoading">
                <i class="fa fa-arrow-left fa-3x" aria-hidden="true" />
            </div>

            <!-- Title -->
            <div class="col-10 text-center">
                <i class="fa fa-key fa-3x" aria-hidden="true" />
                <span class="font-weight-bold" style="font-size:3rem;" v-text="$t('remotelock.adminTitle')" />
            </div>

            <!-- Form -->
            <div class="col-12 mb-3 pl-0 pr-3">

                <form id="create-admin-user-form" @submit.prevent="onSubmit">

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="font-weight-bold text-uppercase"
                               v-text="$t('remotelock.username')" />
                        <input type="text" id="username" v-model="form.username" class="form-control"
                               @input="errors.username=null" :disabled="isLoading || hasResponded" />
                        <template v-for="(message, index) in errors.username">
                            <span :key="index" class="text-danger mr-1" v-text="message" />
                        </template>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="font-weight-bold text-uppercase" v-text="$t('remotelock.name')" />
                        <input type="text" id="name" v-model="form.name" class="form-control" @input="errors.name=null"
                               :disabled="isLoading || hasResponded" />
                        <template v-for="(message, index) in errors.name">
                            <span :key="index" class="text-danger mr-1" v-text="message" />
                        </template>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-uppercase" v-text="$t('remotelock.email')" />
                        <input type="email" id="email" v-model="form.email" class="form-control"
                               placeholder="example@email.com" @input="errors.email=null"
                               :disabled="isLoading || hasResponded" />
                        <template v-for="(message, index) in errors.email">
                            <span :key="index" class="text-danger mr-1" v-text="message" />
                        </template>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone_number" class="font-weight-bold text-uppercase"
                               v-text="$t('remotelock.phoneNumber')" />
                        <input type="tel" id="phone_number" v-model="form.phone_number" class="form-control"
                               @input="errors.phone_number=null" :disabled="isLoading || hasResponded" />
                        <template v-for="(message, index) in errors.phone_number">
                            <span :key="index" class="text-danger mr-1" v-text="message" />
                        </template>
                    </div>

                    <!-- Alert Message -->
                    <div v-if="alert.show" class="alert" :class="alert.class" role="alert">
                        <h4 class="alert-heading" v-text="$t(alert.title)" />
                        <p class="mb-0" v-text="$t(alert.body)" />
                    </div>

                </form>
            </div>

            <div class="col-12 d-flex justify-content-end">

                <!-- Reset -->
                <button type="reset" form="create-admin-user-form" class="btn cancel-btn mr-3"
                        :disabled="isLoading || hasResponded">
                    <span v-text="$t('management.keyManagementPage.reset')" />
                </button>

                <!-- Submit -->
                <button type="submit" form="create-admin-user-form" class="btn btn-custom-color-rl"
                        :disabled="isLoading || hasResponded">
                    <i v-if="isLoading" class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
                    <span class="font-weight-bold"
                          v-text="$t('management.keyManagementPage.' + (isLoading ? 'creating' : 'create'))" />
                </button>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            hasResponded: false,
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

    methods: {
        /**
         * @name onSubmit
         * @description Submits the form to create an admin user
         *
         * @returns {void}
         */
        onSubmit() {
            this.isLoading = true
            axios
                .post(`users`, this.form)
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
         * @description Handle success response
         *
         * @returns {void}
         */
        onSuccess() {
            this.hasResponded = true
            this.alert = {
                class: 'alert-success',
                title: 'remotelock.message.success.title',
                body: 'remotelock.message.success.body',
                show: true,
            }
            this.redirect()
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
            this.redirect()
        },

        /**
         * @name back
         * @description Back to the previous page
         *
         * @returns {void}
         */
        back() {
            this.$emit('backToGuestList')
        },

        /**
         * @name redirect
         * @description Redirect to guest list after response
         *
         * @returns {void}
         */
        redirect() {
            setTimeout(() => {
                this.$emit('backToGuestList')
                const newURL = window.location.protocol + '//' + window.location.host + window.location.pathname
                window.history.pushState({ path: newURL }, '', newURL)
            }, 3000)
        },
    },
}
</script>

<style scoped>
.btn-custom-color-rl {
    background-color: #b4c7e7;
}
.management-scroll {
    overflow-y: auto;
    height: 100% !important;
    max-height: 665px;
}
</style>
