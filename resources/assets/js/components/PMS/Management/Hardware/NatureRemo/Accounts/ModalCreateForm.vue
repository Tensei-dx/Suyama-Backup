<template>

    <!-- modal -->
    <div class="modal fade" id="create-nature-remo-account-form" tabindex="-1" data-backdrop="static" role="dialog"
         :aria-labelledby="$t('management.natureRemo.modalTitleCreateAccount')" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content a_text">

                <!-- form for registering account -->
                <form method="post" @submit.prevent="submit">

                    <div class="modal-header">
                        <h5 class="modal-title" id="create-account-form"
                            v-text="$t('management.natureRemo.modalTitleCreateAccount')" />
                        <button type="button" class="close" @click="close" aria-label="Close" :disabled="isLoading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <!-- Account Name field -->
                        <div class="form-group">
                            <label for="account-name" v-text="$t('management.natureRemo.formFields.accountName')" />
                            <input id="account-name" v-model.trim="form.data.ACCOUNT_NAME" type="text"
                                   class="form-control" :class="{ 'is-invalid': form.error.ACCOUNT_NAME }"
                                   aria-describedby="account-name" autocomplete="off"
                                   @input="form.error.ACCOUNT_NAME = null">
                            <transition name="fade" mode="out-in" appear>
                                <small v-show="form.error.ACCOUNT_NAME" class="text-form text-danger"
                                       v-text="form.error.ACCOUNT_NAME && form.error.ACCOUNT_NAME[0]" />
                            </transition>
                        </div>

                        <!-- Access Token field -->
                        <div class="form-group">
                            <label for="access-token" v-text="$t('management.natureRemo.formFields.accessToken')" />
                            <input id="access-token" v-model.trim="form.data.ACCESS_TOKEN" type="text"
                                   class="form-control" :class="{ 'is-invalid': form.error.ACCESS_TOKEN }"
                                   aria-describedby="access-token" autocomplete="off"
                                   :placeholder="$t('management.natureRemo.formFields.accessTokenPlaceholder')"
                                   @input="form.error.ACCESS_TOKEN = null">
                            <transition name="fade" mode="out-in" appear>
                                <small v-show="form.error.ACCESS_TOKEN" class="text-form text-danger"
                                       v-text="form.error.ACCESS_TOKEN && form.error.ACCESS_TOKEN[0]" />
                            </transition>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <!-- submit button -->
                        <button type="submit" class="btn btn-sm btn-primary" aria-label="Submit" :disabled="isLoading">
                            <i v-show="isLoading" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true" />
                            <span
                                  v-text="$t(isLoading ? 'management.natureRemo.saving' : 'management.natureRemo.save')" />
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                data: {},
                error: {},
            },
            errors: [],
            isLoading: false,
        }
    },

    methods: {
        /**
         * @name submit
         * @description Submit the form for creating account
         *
         * @returns {void}
         */
        async submit() {
            const message = this.$t('management.natureRemo.alert.createAccount')
            this.isLoading = true

            try {
                await axios.post('nature_remo_accounts', this.form.data)
                this.close()
                this.$swal({
                    title: message.onSuccess,
                    type: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                })
            } catch (error) {
                if (error.response.status === 422) {
                    this.form.error = error.response.data.errors
                } else {
                    this.errors.push(error)
                    this.$swal({
                        title: message.onFail,
                        type: 'error',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                }
            }
            this.isLoading = false
        },

        /**
         * @name close
         * @description Reset the form and close the modal
         *
         * @returns {void}
         */
        close() {
            this.$emit('refresh')
            this.form.data = {}
            this.form.error = {}
            $('#create-nature-remo-account-form.modal').modal('hide')
        },
    },
}
</script>
<style>
.a_text {
    text-decoration: none;
    color: black;
}
</style>
