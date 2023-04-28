<template>
    <div class="card">
        <div class="row no-gutters">
            <!-- ACCENT -->
            <div class="col-2 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="img/guest_dashboard/monitor.png" style="width: 50px;" />
                    <small class="d-block mt-2">{{ $t('mobile.control.tv.tv') }}</small>
                </div>
            </div>
            <!-- BODY -->
            <div class="col-10 p-3">
                <div class="row">
                    <!-- POWER -->
                    <div class="col-7">
                        <div class="row">
                            <div class="col-4">
                                <button type="button" :disabled="isEmpty" class="fa fa-power-off btn-push mt-3"
                                        @click="sendSignals(buttons.power)">
                                </button>
                                <small class="d-block">{{ $t('mobile.control.tv.power') }}</small>
                            </div>
                            <!-- SOURCE -->
                            <div class="col-4">
                                <button type="button" :disabled="isEmpty" class="fa fa-sign-in button-source mt-3"
                                        @click="sendSignals(buttons.source)">
                                </button>
                                <small class="d-block">{{ $t('mobile.control.tv.source') }}</small>
                            </div>
                            <!-- MUTE -->
                            <div class="col-4">
                                <button type="button" :disabled="isEmpty" class="fa fa-volume-off button-mute mt-3"
                                        @click="sendSignals(buttons.mute)">
                                </button>
                                <small class="d-block">{{ $t('mobile.control.tv.mute') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="row">
                            <!-- VOLUME -->
                            <div class="col-5 col-pad">
                                <div class="col-pad-l">
                                    <button class="fa fa-plus button-tv-vol" :disabled="isEmpty"
                                            @click="sendSignals(buttons.volume_up)"></button>
                                    <small class="d-block text-center">{{ $t('mobile.control.tv.volume') }}</small>
                                    <button class="fa fa-minus button-tv-vol" :disabled="isEmpty"
                                            @click="sendSignals(buttons.volume_down)"></button>
                                </div>
                            </div>
                            <!-- CHANNEL -->
                            <div class="col-7">
                                <div class="btn-group-vertical text-center">

                                    <button class="button-tv-cha fa fa-chevron-up hotel-white-color-text"
                                            :disabled="isEmpty" @click="sendSignals(buttons.channel_up)"></button>
                                    <small class="d-block">{{ $t('mobile.control.tv.channel') }}</small>
                                    <button class="button-tv-cha fa fa-chevron-down hotel-white-color-text"
                                            :disabled="isEmpty" @click="sendSignals(buttons.channel_down)">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            signals: [],
            buttons: {
                power: null,
                source: null,
                mute: null,
                volume_up: null,
                volume_down: null,
                channel_up: null,
                channel_down: null,
            },

            device_type: 'ir_remote',
            remote_data: '',
            commandStatus: null,
            hd: '',
            tv: {
                status: false,
                power: false,
            },
            isProcessingData: false,
        }
    },

    methods: {
        getSignals() {
            axios
                .get('nature_remo_appliances/' + this.deviceInfo.APPLIANCE_ID + '/signals')
                .then(response => {
                    this.signals = response.data
                    this.assignSignals()
                })
                .catch(error => {
                    console.log(error)
                })
        },

        assignSignals() {
            this.buttons.power = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_power')
            this.buttons.source = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_source')
            this.buttons.mute = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_mute')
            this.buttons.volume_up = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_volume_up')
            this.buttons.volume_down = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_volume_down')
            this.buttons.channel_up = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_next_channel')
            this.buttons.channel_down = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_previous_channel')
        },

        // create function when clicking buttons

        sendSignals(signal) {
            axios.post('nature_remo_signals/' + signal.SIGNAL_ID + '/send').catch(error => {
                console.log(error)
            })
        },

        // /**
        //  * @name getDataFromDatabase
        //  * @desc Get current device data from database
        //  */
        // getDataFromDatabase() {
        //     axios
        //         .post('getDevice/' + this.deviceInfo['DEVICE_ID'])
        //         .then(response => {
        //             this.readDeviceData(response.data)
        //         })
        //         .catch(error => {
        //             console.log(error)
        //         })
        // },
        // /**
        //  * @name readDeviceData
        //  * @desc Modify data and image for latest entry
        //  * @param {Object} data
        //  */
        // readDeviceData(data) {
        //     if (this.isProcessingData == false) {
        //         this.isProcessingData = true
        //         if (data['DATA'].length > 0) {
        //             this.remote_data = data['DATA']
        //         }
        //         this.isProcessingData = false
        //     }
        // },
        // clickRemote(data) {
        //     // Power button process
        //     if (data == 'power') {
        //         this.tv.power = !this.tv.power
        //         this.commandStatus = this.tv.power ? 'TV_ON' : 'TV_OFF'
        //         // Error
        //     } else {
        //         console.log('Error message')
        //         this.commandStatus = ''
        //     }
        //     var timer = ''
        //     clearTimeout(timer)
        //     timer = setTimeout(() => {
        //         this.getLearningList(this.commandStatus, this.deviceInfo['DEVICE_ID'])
        //     }, 1000)
        // },
        // /**
        //  * @name getLearningList
        //  * @desc Get IR Learning List
        //  * @param {string} command
        //  * @param {number} device_id
        //  * @returns null
        //  */
        // getLearningList(command, device_id) {
        //     axios
        //         .get('getAllIrLearningList')
        //         .then(response => {
        //             var irLists = response.data
        //             // Loop irLists
        //             irLists.map(item => {
        //                 if (item.DEVICE_ID == device_id && item.operation['OPERATION_NAME'] == command) {
        //                     this.remote_data.map(remoteData => {
        //                         if (item.appliances['BRAND_NAME'] == remoteData['brand']) {
        //                             //Learning Value TV_ON  = 100
        //                             //Learning Value TV_OFF = 99
        //                             this.commandDevice('status', 2, item.LEARNING_VALUE)
        //                         }
        //                     })
        //                 }
        //             })
        //         })
        //         .catch(errors => {
        //             console.log(errors)
        //         })
        // },
        // /**
        //  * @name commandDevice
        //  * @desc Pass command information to dashboard
        //  * @param {string} command
        //  * @param {number} value
        //  * @param {mixed} irValue
        //  */
        // commandDevice(command, value, irValue) {
        //     this.$emit('commandDevice', {
        //         GATEWAY_ID: this.deviceInfo['GATEWAY_ID'],
        //         DEVICE_ID: this.deviceInfo['DEVICE_ID'],
        //         DEVICE_TYPE: this.deviceInfo['DEVICE_TYPE'],
        //         command: command,
        //         value: value,
        //         addlValue: irValue,
        //     })
        // },
    },

    computed: {
        isEmpty: function () {
            return Object.keys(this.deviceInfo).length === 0
        },
    },

    watch: {
        deviceInfo: {
            deep: true,
            handler: function () {
                this.getSignals()
            },
        },
    },
}
</script>
<style scoped>
.col-mute {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 33.33333333%;
    flex: 0 0 33.33333333%;
    max-width: 25%;
}
.custom-ac-btn-width {
    width: 60px;
}
.temp-card {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.75rem 0.5rem !important;
}

.col-pad {
    padding: 0 3px !important;
    text-align: center !important;
}
.text-center {
    align-items: center !important;
}
.col-4 {
    padding: 5px !important;
}
</style>
