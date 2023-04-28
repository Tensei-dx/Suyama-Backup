<template>

    <!-- modal -->
    <div class="modal fade" id="register-nature-remo-device-form" tabindex="-1" data-backdrop="static" role="dialog"
         :aria-labelledby="$t('management.natureRemo.modalTitleRegisterDevice')" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content a_text">

                <!-- form for registering device -->
                <form method="post" @submit.prevent="submit">

                    <div class="modal-header">
                        <h5 class="modal-title" id="register-device-form"
                            v-text="$t('management.natureRemo.modalTitleRegisterDevice')" />
                        <button type="button" class="close" @click="close" aria-label="Close" :disabled="isLoading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <!-- Device Serial Number field -->
                        <div class="form-group">
                            <label for="device-serial-number"
                                   v-text="$t('management.natureRemo.formFields.serialNumber')" />
                            <output type="text" id="device-serial-number" v-text="device && device.DEVICE_SERIAL_NO"
                                    class="form-control" aria-describedby="device-serial-number" />
                            <transition name="fade" mode="out-in" appear>
                                <small v-show="form.error.DEVICE_SERIAL_NO" class="text-form text-danger"
                                       v-text="form.error.DEVICE_SERIAL_NO && form.error.DEVICE_SERIAL_NO[0]" />
                            </transition>
                        </div>

                        <!-- Room field -->
                        <div class="form-group">
                            <label for="room-id" v-text="$t('management.natureRemo.formFields.room')" />
                            <select id="device-room" class="custom-select" :class="{ 'is-invalid': form.error.ROOM_ID }"
                                    v-model.number="form.data.ROOM_ID" @change="form.error.ROOM_ID = null">
                                <option v-for="(room, index) in rooms" :key="index" :value="room.ROOM_ID"
                                        v-text="room.ROOM_NAME" />
                            </select>
                            <transition name="fade" mode="out-in" appear>
                                <small v-show="form.error.ROOM_ID" class="text-form text-danger"
                                       v-text="form.error.ROOM_ID && form.error.ROOM_ID[0]" />
                            </transition>
                        </div>

                        <!-- Device Name field -->
                        <div class="form-group">
                            <label for="device-name" v-text="$t('management.natureRemo.formFields.deviceName')" />
                            <input type="text" id="device-name" v-model.trim="form.data.DEVICE_NAME"
                                   class="form-control" :class="{ 'is-invalid': form.error.DEVICE_NAME }"
                                   aria-describedby="room" autocomplete="off" @input="form.error.DEVICE_NAME = null">
                            <transition name="fade" mode="out-in" appear>
                                <small v-show="form.error.DEVICE_NAME" class="text-form text-danger"
                                       v-text="form.error.DEVICE_NAME && form.error.DEVICE_NAME[0]" />
                            </transition>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary" aria-label="Submit" :disabled="isLoading">
                            <i v-show="isLoading" class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
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
    props: {
        device: Object,
        rooms: Array,
    },

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
         * @description Submit the form for registering device
         *
         * @returns {void}
         */
        async submit() {
            const message = this.$t('management.natureRemo.alert.registerDevice')
            this.isLoading = true

            try {
                await axios.put(`nature_remo_devices/${this.device.DEVICE_ID}/register`, this.form.data)
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
                    this.close()
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
            $('#register-nature-remo-device-form.modal').modal('hide')
        },
    },

    watch: {
        /**
         * Update the form data when the device prop changes
         */
        device: {
            handler: function (val, oldVal) {
                this.form.data.DEVICE_NAME = val && val.DEVICE_NAME
                this.form.data.ROOM_ID = val && val.ROOM_ID
            },
            deep: true,
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
