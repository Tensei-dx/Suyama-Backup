<template>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $t('management.remotelock.form.editTitle') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="update-device-form" @submit.prevent="updateDevice">
                    <div class="form-group">
                        <label for="device-serial-number">{{ $t('management.remotelock.form.serialNo') }}</label>
                        <input type="text" id="device-serial-number" class="form-control"
                               :value="device && device.DEVICE_SERIAL_NO" readonly />
                    </div>
                    <div class="form-group">
                        <label for="device-name">{{ $t('management.remotelock.form.deviceName') }}</label>
                        <input id="device-name" v-model.trim="form.device_name" type="text" class="form-control"
                               required autofocus />
                        <small v-if="error && error.device_name"
                               class="form-text text-danger">{{ error.device_name[0] }}</small>
                    </div>
                    <div class="form-group">
                        <label for="room-id">{{ $t('management.remotelock.form.roomName') }}</label>
                        <select id="room-id" v-model.number="form.room_id" class="form-control" required>
                            <option v-for="room in rooms" :key="room.ROOM_ID" :value="room.ROOM_ID">{{ room.ROOM_NAME }}
                            </option>
                        </select>
                        <small v-if="error && error.room_id"
                               class="form-text text-danger">{{ error.room_id[0] }}</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-right">
                <button type="submit" form="update-device-form" class="btn btn-primary" :disabled="isLoading">
                    <i v-if="isLoading" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true" />
                    {{ $t(isLoading ? 'management.remotelock.form.updating' : 'management.remotelock.form.update') }}
                </button>
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
                device_name: null,
                room_id: null,
            },
            error: null,
            isLoading: false,
        }
    },

    methods: {
        /**
         * @name updateDevice
         * @description Update the device information
         *
         * @returns {void}
         */
        updateDevice() {
            this.isLoading = true
            this.error = null

            axios
                .put(`remotelock_devices/${this.device.DEVICE_ID}`, this.form)
                .then(response => {
                    this.$emit('close')
                    this.$swal({
                        type: 'success',
                        title: this.$t('management.remotelock.form.updateSuccess'),
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .catch(e => {
                    if (e.response.status === 422) {
                        this.error = e.response.data.errors
                    } else {
                        this.$emit('close')
                        this.$swal({
                            type: 'error',
                            title: this.$t('management.remotelock.form.updateFailed'),
                            timer: 3000,
                            showConfirmButton: false,
                        })
                    }
                })
                .then(() => (this.isLoading = false))
        },
    },

    watch: {
        device: {
            deep: true,
            handler: function (value) {
                this.form.device_name = value && value.DEVICE_NAME
                this.form.room_id = value && value.ROOM_ID
            },
        },
    },
}
</script>
