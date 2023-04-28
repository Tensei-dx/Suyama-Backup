<template>
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0" v-text="$t('management.roomInfo.remoteLock')" />
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/remotelock8j.png"
                     style="max-width: 100%; margin-top: 10px;" class="img-fluid" />
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded" v-text="$t('management.roomInfo.deviceName')" />
                    <div class="col-7 px-0 py-2 text-white font-weight-bold" v-text="device.DEVICE_NAME" />
                </div>
                <div class="text-center mt-4 row">
                    <!-- Unlock Button -->
                    <div class="px-0 pt-2" :class="columnClass">
                        <button type="button" class="button-mode button-custom" @click="lock" :disabled="isLocking">
                            <i v-show="isLocking" class="fa fa-circle-o-notch fa-spin mr-2" aria-hidden="true" />
                            <span class="text-uppercase">
                                {{ $t(isLocking ? 'management.roomOperation.locking' : 'management.roomOperation.lock') }}
                            </span>
                        </button>
                    </div>
                    <div class="px-0 pt-2" :class="columnClass">
                        <button type="button" class="button-mode button-custom" @click="unlock" :disabled="isUnlocking">
                            <i v-show="isUnlocking" class="fa fa-circle-o-notch fa-spin mr-2" aria-hidden="true" />
                            <span class="text-uppercase">
                                {{ $t(isUnlocking ? 'management.roomOperation.unlocking' : 'management.roomOperation.unlock') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceId: '',
        deviceName: '',
        device: '',
    },

    data() {
        return {
            isLocking: false,
            isUnlocking: false,
            columnClass: 'col-6',
        }
    },

    methods: {
        /**
         * @name unlock
         * @description Unlocks the remote lock device
         *
         * @returns {void}
         */
        unlock() {
            this.isUnlocking = true
            this.columnClass = 'col-12'
            const message = this.$t('management.roomOperation')
            axios
                .post('remotelock_devices/' + this.deviceId + '/unlock')
                .then(response => {
                    this.$swal({
                        type: 'success',
                        title: message.unlockSuccess,
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .catch(error => {
                    this.$swal({
                        type: 'error',
                        title: message.unlockFailed,
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .then(() => {
                    this.isUnlocking = false
                    this.columnClass = 'col-6'
                })
        },
        lock() {
            this.isLocking = true
            this.columnClass = 'col-12'
            const message = this.$t('management.roomOperation')
            axios
                .post('remotelock_devices/' + this.deviceId + '/lock')
                .then(response => {
                    this.$swal({
                        type: 'success',
                        title: message.lockSuccess,
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .catch(error => {
                    this.$swal({
                        type: 'error',
                        title: message.lockFailed,
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .then(() => {
                    this.isLocking = false
                    this.columnClass = 'col-6'
                })
        },
    },
}
</script>
<style scoped>
.device-image {
    border-right: solid 1px #111;
}
.data-label {
    background-color: #add8e6 !important;
    font-weight: bold !important;
}
.button-custom {
    color: crimson;
    height: initial;
    width: initial;
    padding: 0.5rem 1rem;
}
</style>
