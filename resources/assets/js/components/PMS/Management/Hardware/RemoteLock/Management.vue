<template>
    <div>
        <div class="row mt-3">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3 text-white">
                    {{ $t('management.remotelock.title') }}
                </span>
            </div>
        </div>
        <RemoteLockTable :items="devices" @register="openRegisterModal" @edit="openEditModal" @remove="removeDevice" />

        <div class="mt-4 d-flex justify-content-end">
            <button type="button" class="btn btn-scan" @click="scanDevices" :disabled="isScanning">
                <i v-if="isScanning" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true" />
                {{ $t(isScanning ? 'management.remotelock.scanning' : 'management.remotelock.scan') }}
            </button>
        </div>

        <div id="register-device-modal" class="modal fade" role="dialog" tabindex="-1"
             aria-labelledby="register-device-modal-title" aria-hidden="true">
            <RemoteLockRegisterForm :rooms="rooms" :device="selectedDevice" @close="closeModal" />
        </div>

        <div id="edit-device-modal" class="modal fade" role="dialog" tabindex="-1"
             aria-labelledby="edit-device-modal-title" aria-hidden="true">
            <RemoteLockEditForm :rooms="rooms" :device="selectedDevice" @close="closeModal" />
        </div>
    </div>
</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.10.06
 * @version 1.0.0
 */
import RemoteLockTable from './Table.vue'
import RemoteLockRegisterForm from './Forms/Register.vue'
import RemoteLockEditForm from './Forms/Edit.vue'

export default {
    components: {
        RemoteLockTable,
        RemoteLockRegisterForm,
        RemoteLockEditForm,
    },

    data() {
        return {
            isLoading: false,
            isScanning: false,
            devices: [],
            rooms: [],
            errors: [],
            selectedDevice: null,
        }
    },

    created() {
        this.getDevices()
        this.getRooms()
    },

    mounted() {
        const that = this

        $('.modal')
            .on('show.bs.modal', e => {
                that.getRooms()
            })
            .on('hide.bs.modal', e => {
                that.selectedDevice = null
                that.getDevices()
            })
    },

    methods: {
        /**
         * @name getRooms
         * @description Get all rooms
         *
         * @returns {void}
         */
        getRooms() {
            axios
                .get(`getRoomAll`)
                .then(response => (this.rooms = response.data))
                .catch(error => this.errors.push(error.response))
        },

        /**
         * @name getDevices
         * @description Get Remote Lock devices
         *
         * @returns {void}
         */
        getDevices() {
            this.isLoading = true

            axios
                .get(`remotelock_devices`)
                .then(response => (this.devices = response.data))
                .catch(error => this.errors.push(error.response))
                .then(() => (this.isLoading = false))
        },

        /**
         * @name scanDevices
         * @description Get latest Remote Lock devices from the Remote Lock Cloud API
         *
         * @returns {void}
         */
        scanDevices() {
            this.isScanning = true

            axios
                .post(`remotelock_devices/scan`, {
                    validateStatus: status => status === 204,
                })
                .then(response => {
                    this.$swal({
                        type: 'success',
                        title: this.$t('management.remotelock.scanSuccess'),
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .catch(error => {
                    this.$swal({
                        type: 'error',
                        title: this.$t('management.remotelock.scanSuccess'),
                        timer: 3000,
                        showConfirmButton: false,
                    })
                    this.errors.push(error.response)
                })
                .then(() => {
                    this.isScanning = false
                    this.getDevices()
                })
        },

        /**
         * @name openRegisterModal
         * @description Show device registration form modal
         *
         * @param {Object} device
         * @returns {void}
         */
        openRegisterModal(device) {
            this.selectedDevice = device
            $('#register-device-modal').modal('show')
        },

        /**
         * @name openEditModal
         * @description Show device update form modal
         *
         * @param {Object} device
         * @returns {void}
         */
        openEditModal(device) {
            this.selectedDevice = device
            $('#edit-device-modal').modal('show')
        },

        /**
         * @name closeModal
         * @description Close the modal
         *
         * @returns {void}
         */
        closeModal() {
            $('.modal').modal('hide')
        },

        /**
         * @name removeDevice
         * @description Delete the device
         *
         * @param {Object} device
         * @returns {void}
         */
        removeDevice(device) {
            this.$swal({
                type: 'warning',
                title: this.$t('management.remotelock.deleteConfirm'),
                showCancelButton: true,
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            }).then(result => {
                if (result.value) {
                    axios
                        .delete(`remotelock_devices/${device.DEVICE_ID}`, {
                            validateStatus: status => status === 204,
                        })
                        .then(response => {
                            this.getDevices()
                            this.$swal({
                                type: 'success',
                                title: this.$t('management.remotelock.deleteSuccess'),
                                timer: 3000,
                                showConfirmButton: false,
                            })
                        })
                        .catch(error => {
                            this.errors.push(error.response)
                            this.$swal({
                                type: 'error',
                                title: this.$t('management.remotelock.deleteFailed'),
                                timer: 3000,
                                showConfirmButton: false,
                            })
                        })
                }
            })
        },
    },
}
</script>

<style scoped>
.btn-scan {
    background-color: white;
    color: #000 !important;
    border-color: white;
    font-weight: bold;
}
</style>
