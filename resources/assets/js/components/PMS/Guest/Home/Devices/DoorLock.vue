<template>
    <div class="card">
        <div class="row no-gutters">
            <div class="col-xl-5 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="img/guest_dashboard/padlock.png" style="width: 50px;" />
                    <small class="d-block mt-2">{{ $t('mobile.control.doorlock.doorlock') }}</small>
                </div>
            </div>
            <div class="col p-2 align-self-center">
                <div v-if="Object.keys(deviceInfo).length === 0">
                    <button type="button" disabled class="btn"
                            :class="isLocked ? 'hotel-theme-color-2' : 'hotel-accent-color'"
                            @click="unlockRemoteLock()">
                        <span><i class="fa fa-3x"
                               :class="isLocked ? 'fa-lock hotel-white-color-text' : 'fa-unlock-alt hotel-black-color-text'"
                               aria-hidden="true"></i></span>
                    </button>
                </div>
                <div v-else>
                    <button type="button" class="btn" :class="isLocked ? 'hotel-theme-color-2' : 'hotel-accent-color'"
                            @click="unlockRemoteLock()" :disabled="isDisabled">
                        <span><i class="fa fa-3x"
                               :class="isLocked ? 'fa-lock hotel-white-color-text' : 'fa-unlock-alt hotel-black-color-text'"
                               aria-hidden="true"></i></span>
                    </button>
                </div>
                <small class="d-block mt-1">{{ $t('mobile.control.doorlock.entrancedoor') }}</small>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceInfo: Object,
    },
    data() {
        return {
            isDisabled: false,
            isLocked: true,
            rl_id: '',
        }
    },
    methods: {
        /**
         * @name getDataFromDatabase
         * @desc Get complete information of the device
         */
        getDataFromDatabase() {
            axios
                .post('getDevice/' + this.deviceInfo.DEVICE_ID)
                .then(response => {
                    this.rl_id = response.data.DATA.remote_lock_id
                })
                .catch(error => {
                    console.error(error)
                })
        },

        /**
         * @name unlockRemoteLock
         *
         */
        unlockRemoteLock() {
            this.isDisabled = true
            this.isLocked = false
            axios
                .post('unlockRemoteLockState', {
                    REMOTE_LOCK_ID: this.rl_id,
                })
                .then(response => {
                    setTimeout(() => {
                        this.isDisabled = false
                        this.isLocked = true
                    }, 1000)
                })
                .catch(error => {
                    console.error(error)
                })
        },

        /**
         * @name isEmpty
         * @desc Check if the deviceinfo is empty or not
         * @param {string} obj
         */
        isEmpty(obj) {
            return Object.keys(obj).length === 0
        },
    },
}
</script>
