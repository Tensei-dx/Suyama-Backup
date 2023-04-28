<template>
    <!-- <div class="row">
        <div class="col" style="background-color:white; color:black; text-align:center;">
            <img src="/img/ManagementDashboard/realdevices/wall_switch_1.png" style="max-width:100%; margin-top:10px;"
                 class="img-fluid"><br>
            <button type="button" class="btn btn-secondary btn-lg" style="margin-top:10px; margin-bottom:10px;"
                    disabled>{{ device.DEVICE_NAME }}</button>
        </div>
        <div class="col"
             style="background-color:white; color:black; text-align:center; margin-right:50px; margin-left:50px; margin-top:10px; margin-bottom:10px;">
            <div class="row" v-for="(item, index) in wall_switch.data" :key="index">
                <div class="col">
                    <button v-if="item.status == true" type="button" class="btn hotel-base-color"
                            :id="'wall_switch_index_' + index"
                            @click="toggleOnOff(wall_switch, index, 0)"><b>ON</b></button>
                    <button v-else type="button" class="btn hotel-theme-color-2" :id="'wall_switch_index_' + index"
                            @click="toggleOnOff(wall_switch, index, 1)">OFF</button>
                    <small class="d-block mt-2">{{ item.device_name }}</small>
                </div>
            </div>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">1st Wall Switch</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/wall_switch_1.png"
                     style="max-width:100%; margin-top:10px;" class="img-fluid">
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
            devices: [],
            selectedDevice: '',
            switchStatus: { 0: false },
            switchStatusAll: false,
            wall_switch: {},
            device_type: 'wall_switch_1',
        }
    },

    created() {
        this.getDataFromDatabase()
        this.$bus.$on('updateDeviceData-' + this.device['DEVICE_ID'], data => {
            if (data['DEVICE_TYPE'] == this.device_type && data['DEVICE_ID'] == this.device['DEVICE_ID']) {
                this.readDeviceData(data)
            }
        })
    },

    watch: {
        device: function () {
            this.readDeviceData(this.device)
        },
    },

    methods: {
        /**
         * @name getDataFromDatabase
         * @desc Get the data from the database
         */
        getDataFromDatabase() {
            axios.post('getDevice/' + this.device['DEVICE_ID']).then(response => {
                this.readDeviceData(response.data)
            })
        },

        /**
         * @name readDeviceData
         * @desc Saves the data of the wall switch
         * @param {Object} data
         */
        readDeviceData(data) {
            this.wall_switch = {
                gateway_id: data['GATEWAY_ID'],
                device_id: data['DEVICE_ID'],
                device_type: data['DEVICE_TYPE'],
                data: data['DATA'],
            }
            //this.updateSwitchAll();
        },

        /**
         * @name toggleOnOff
         * @desc Toggle the on and off of the wall switch
         * @param {Object} device Wall switch information
         * @param {Number} index Index of the each switch in the wall switch
         * @param {Boolean} state The state to be set to the switch
         */
        toggleOnOff(device, index, state) {
            let command = 'status_' + (index + 1)
            let value = state.toString()
            this.wall_switch.data[index].status = value
            this.commandDevice(command, value)
        },

        /**
         * @name commandDevice
         * @desc Send the command structure to ControlPanel.vue
         *
         * @param {Object} device Wall switch information
         * @param {String} command
         * @param {String} value
         */
        commandDevice(command, value) {
            var gateway_id = this.device['GATEWAY_ID']
            var device_id = this.device['DEVICE_ID']
            var device_type = this.device['DEVICE_TYPE']
            this.$emit('commandDevice', {
                GATEWAY_ID: gateway_id,
                DEVICE_ID: device_id,
                DEVICE_TYPE: device_type,
                command: command,
                value: value,
            })
            this.updateSwitchAll()
        },

        /**
         * @name updateSwitchAll
         * @desc Update the status of the all switch depending on the state of the switches
         */
        updateSwitchAll() {
            if (
                this.wall_switch.data[0].status == '1' &&
                this.wall_switch.data[1].status == '1' &&
                this.wall_switch.data[2].status == '1'
            ) {
                this.switch_all_status = true
            } else {
                this.switch_all_status = false
            }
        },
    },
    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getDataFromDatabase()
        })
    },
}
</script>

<style >
.light-on {
    background-color: rgb(0, 176, 80);
}
.light-off {
    background-color: red;
}
</style>
<style scoped>
.device-image {
    border-right: solid 1px #111;
}
.data-label {
    background-color: #add8e6 !important;
    font-weight: bold !important;
}
</style>

