<template>
    <!-- <div class="row">
        <div class="col-md-3" style="background-color:white; color:black; text-align:center;">
            <img src="/img/ManagementDashboard/realdevices/ir_transmitter.png" style="max-width:130%; margin-top:10px;"
                 class="img-fluid"><br>
            <button type="button" class="btn btn-secondary btn-lg" style="margin-top:5px; margin-bottom:5px;"
                    disabled>{{ device.DEVICE_NAME }}</button>
        </div>
        <div class="col p-3" style="color:black; text-align:center; margin-top:30px;">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-auto col-12">
                    <button type="button" class="btn power-on btn-lg" @click="clickRemote('power')">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                    </button>
                    <small class="d-block">POWER</small>
                </div>
                <div class="col-auto">
                    <div class="btn-group-vertical">
                        <button type="button" class="btn btn-secondary d-block" @click="clickRemote('temp_up')">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-secondary d-block" @click="clickRemote('temp_down')">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small class="d-block text-center">TEMPERATURE</small>
                </div>
                <div class="col-auto">
                    <div class="btn-group-vertical">
                        <button type="button" class="btn btn-secondary d-block justify-center"
                                @click="clickRemote('fanspeed_up')">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-secondary d-block" @click="clickRemote('fanspeed_down')">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small class="d-block text-center">FAN SPEED</small>
                </div>
            </div>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">IR Remote</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/ir_transmitter.png"
                     style="max-width:130%; margin-top:10px;" class="img-fluid">
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
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
            device_type: 'ir_remote',
            remote_data: '',
            commandStatus: null,
            aircon: {
                status: false,
                power: false,
                temp: '',
                temp_up: false,
                temp_down: false,
                fanspeed_up: false,
                fanspeed_down: false,
            },
            isProcessingData: false,
        }
    },

    created() {
        this.getDataFromDatabase()
    },

    methods: {
        /**
         * @name getDataFromDatabase
         * @desc Get the data from the database
         */
        getDataFromDatabase() {
            axios
                .post('getDevice/' + this.device['DEVICE_ID'])
                .then(response => {
                    this.readDeviceData(response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * @name readDeviceData
         * @desc Saves the data of the wall switch
         * @param {Object} data
         */
        readDeviceData(data) {
            if (this.isProcessingData == false) {
                this.isProcessingData = true
                if (data['DATA'].length > 0) {
                    this.remote_data = data['DATA'][0]
                }
                this.isProcessingData = false
            }
        },

        /**
         * @name clickRemote
         * @desc Command IR remote
         * @param {string} data
         */
        clickRemote(data) {
            // Power button process
            if (data == 'power') {
                this.aircon.status = !this.aircon.status
                this.commandStatus = this.aircon.status ? 'AC_POWER_ON' : 'AC_POWER_OFF'
                // Temp+ button process
            } else if (data == 'temp_up') {
                var temp = new Number(this.remote_data['temp_value'])
                if (temp >= 16 && temp < 30) {
                    this.remote_data['temp_value'] = temp + 1
                }
                this.commandStatus = 'TEMP_' + this.remote_data['temp_value']
                // Temp- button process
            } else if (data == 'temp_down') {
                var temp = new Number(this.remote_data['temp_value'])
                if (temp > 16 && temp <= 30) {
                    this.remote_data['temp_value'] = temp - 1
                }
                this.commandStatus = 'TEMP_' + this.remote_data['temp_value']
                // Fanspeed+ button process
            } else if (data == 'fanspeed_up') {
                console.log('You clicked the fanspeed_up button')
                console.log('Please add process to fanspeed_up button')
                this.commandStatus = ''
                // Fanspeed- button process
            } else if (data == 'fanspeed_down') {
                console.log('You clicked the fanspeed_down button')
                console.log('Please add process to fanspeed_down button')
                this.commandStatus = ''
                // Error case
            } else {
                console.log('Error message')
                this.commandStatus = ''
            }
            var timer = ''
            clearTimeout(timer)
            timer = setTimeout(() => {
                this.getLearningList(this.commandStatus, this.device['DEVICE_ID'])
            }, 1000)
        },

        /**
         * @name getLearningList
         * @desc Get IR Learning List
         * @param {string} command
         * @param {number} device_id
         */
        getLearningList(command, device_id) {
            axios
                .get('getAllIrLearningList')
                .then(response => {
                    var irLists = response.data
                    // Loop irLists
                    for (var j in irLists) {
                        if (
                            irLists[j].DEVICE_ID == device_id &&
                            irLists[j].operation['OPERATION_NAME'] == command &&
                            irLists[j].appliances['BRAND_NAME'] == this.remote_data['brand']
                        ) {
                            this.commandDevice('status', 2, irLists[j].LEARNING_VALUE)
                        }
                    }
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name commandDevice
         * @desc Pass command information to dashboard
         * @param {string} command
         * @param {number} value
         * @param {mixed} irValue
         */
        commandDevice(command, value, irValue) {
            this.$emit('commandDevice', {
                GATEWAY_ID: this.device['GATEWAY_ID'],
                DEVICE_ID: this.device['DEVICE_ID'],
                DEVICE_TYPE: this.device['DEVICE_TYPE'],
                command: command,
                value: value,
                addlValue: irValue,
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
.power-on {
    background-color: rgb(0, 176, 80);
}
</style>
