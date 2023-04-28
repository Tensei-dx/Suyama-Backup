<template>
    <div class="card">
        <div class="row no-gutters">
            <!-- ACCENT -->
            <div class="col-2 hotel-base-color d-flex align-items-center justify-content-center">
                <!-- <i class="fa fa-circle fa-3x" aria-hidden="true"></i> -->
                <div class="py-4">
                    <img src="img/guest_dashboard/air-con.png" style="width: 50px;" />
                    <small class="d-block mt-2">{{ $t('mobile.control.aircon.aircon')}}</small>
                </div>
            </div>
            <!-- BODY -->
            <div class="col-10 p-3">
                <div class="row align-items-center justify-content-center">
                    <div class="col-5 ">
                        <div class="row align-items-center">
                            <!-- OFF Button -->
                            <div class="col-12">
                                <button type="button" :disabled="isEmpty" class="button-mode bm-off"
                                        @click="sendSignals(buttons.power)">{{ $t('mobile.control.aircon.off') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="row">
                            <!-- MODE -->
                            <div class="col-12 text-center">
                                <div class="row no-gutters justify-content-around">
                                    <div class="col-6 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-cool"
                                                @click="sendSignals(buttons.cool)">{{ $t('mobile.control.aircon.cool') }}</button>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-dry"
                                                @click="sendSignals(buttons.dry)">{{ $t('mobile.control.aircon.dry') }}</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-warm"
                                                @click="sendSignals(buttons.warm)">{{ $t('mobile.control.aircon.warm') }}</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-auto"
                                                @click="sendSignals(buttons.auto)">{{ $t('mobile.control.aircon.auto') }}</button>
                                    </div>
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
                dry: null,
                warm: null,
                cool: null,
                auto: null,
            },

            device_type: 'ir_remote',
            remote_data: '',
            commandStatus: null,
            aircon: {
                status: false,
                power: true,
                // temp: '',
                // temp_up: false,
                // temp_down: false,
                // fanspeed_up: false,
                // fanspeed_down: false,
            },
            isProcessingData: false,
            status: '',
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
            this.buttons.power = this.signals.find(signal => signal.SIGNAL_LABEL === 'power-off button')
            this.buttons.dry = this.signals.find(signal => signal.SIGNAL_LABEL === 'dry mode')
            this.buttons.cool = this.signals.find(signal => signal.SIGNAL_LABEL === 'cool mode')
            this.buttons.warm = this.signals.find(signal => signal.SIGNAL_LABEL === 'warm mode')
            this.buttons.auto = this.signals.find(signal => signal.SIGNAL_LABEL === 'auto mode')
        },

        // create function when clicking buttons

        sendSignals(signal) {
            axios.post('nature_remo_signals/' + signal.SIGNAL_ID + '/send').catch(error => {
                console.log(error)
            })
        },
    },

    // methods: {
    //     /**
    //      * @name getDataFromDatabase
    //      * @desc Get current device data from database
    //      */
    //     getDataFromDatabase() {
    //         axios
    //             .post('getDevice/' + this.deviceInfo['DEVICE_ID'])
    //             .then(response => {
    //                 this.readDeviceData(response.data)
    //             })
    //             .catch(error => {
    //                 console.log(error)
    //             })
    //     },
    //     /**
    //      * @name readDeviceData
    //      * @desc Modify data and image for latest entry
    //      * @param {Object} data
    //      */
    //     readDeviceData(data) {
    //         if (this.isProcessingData == false) {
    //             this.isProcessingData = true
    //             if (data['DATA'].length > 0) {
    //                 this.remote_data = data['DATA']
    //             }
    //             this.isProcessingData = false
    //         }
    //     },
    //     /**
    //      * @name clickRemote
    //      * @desc Command IR remote
    //      * @param {string} data
    //      */
    //     clickRemote(data) {
    //         // Power button process
    //         if (data == 'power_on') {
    //             // this.aircon.status = !this.aircon.status
    //             this.commandStatus = 'AC_POWER_ON'
    //             // Temp+ button process
    //         } else if (data == 'power_off') {
    //             // this.aircon.status = !this.aircon.status
    //             this.commandStatus = 'AC_POWER_OFF'
    //         } else if (data == 'temp_up') {
    //             var temp = new Number(this.remote_data['temp_value'])
    //             if (temp >= 16 && temp < 30) {
    //                 this.remote_data['temp_value'] = temp + 1
    //             }
    //             this.commandStatus = 'TEMP_' + this.remote_data['temp_value']
    //             // Temp- button process
    //         } else if (data == 'temp_down') {
    //             var temp = new Number(this.remote_data['temp_value'])
    //             if (temp > 16 && temp <= 30) {
    //                 this.remote_data['temp_value'] = temp - 1
    //             }
    //             this.commandStatus = 'TEMP_' + this.remote_data['temp_value']
    //             // Fanspeed+ button process
    //         } else if (data == 'fanspeed_up') {
    //             ;('You clicked the fanspeed_up button')
    //             console.log('Please add process to fanspeed_up button')
    //             this.commandStatus = ''
    //             // Fanspeed- button process
    //         } else if (data == 'fanspeed_down') {
    //             console.log('You clicked the fanspeed_down button')
    //             console.log('Please add process to fanspeed_down button')
    //             this.commandStatus = ''
    //             // Error case
    //         } else {
    //             console.log('Error message')
    //             this.commandStatus = ''
    //         }
    //         var timer = ''
    //         clearTimeout(timer)
    //         timer = setTimeout(() => {
    //             this.getLearningList(this.commandStatus, this.deviceInfo['DEVICE_ID'])
    //         }, 1000)
    //     },
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
    //     /**
    //      * @name isEmpty
    //      * @desc Check if the deviceinfo is empty or not
    //      * @param {string} obj
    //      */
    //     isEmpty(obj) {
    //         return Object.keys(obj).length === 0
    //     },
    // },

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
.custom-ac-btn-width {
    width: 60px;
}
.temp-card {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0.5rem 0.5rem !important;
}
.col-pad {
    padding: 0 3px !important;
    text-align: center !important;
}
.col-pad-l {
    padding-right: 1px !important;
    padding-left: 15px !important;
    text-align: center !important;
}
h4 {
    margin-top: 10px !important;
}
.bm-off {
    color: crimson;
}
</style>
