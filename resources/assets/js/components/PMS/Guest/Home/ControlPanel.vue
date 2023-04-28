<template>
    <div class="scrollDevices row d-flex align-items-center h-100 pb-4">

        <div class="col-12 pb-4 pt-2" v-if="!!passAppliance.ac">
            <AirCon :deviceInfo="passAppliance.ac" @refreshAppliance="refreshAppliance" />
        </div>

        <div class="col-12 pb-4" v-if="!!passAppliance.light">
            <LightSwitch :deviceInfo="passAppliance.light" @refreshAppliance="refreshAppliance" />
        </div>

        <div class="col-12 pb-4" v-if="!!passAppliance.tv">
            <Television :deviceInfo="passAppliance.tv" @refreshAppliance="refreshAppliance" />
        </div>

        <div class="col-6 pr-1 pb-4" v-if="!!passDevice.occupancy_temp_light">
            <ShowerStatus :room_id="room_id" @commandDevice="commandDevice"></ShowerStatus>
        </div>

        <div class="col-6 pb-4" v-if="!!passDevice.window_door_sensor">
            <RestRoomStatus :room_id="room_id" @commandDevice="commandDevice"></RestRoomStatus>
        </div>

        <div class="col-6 pb-1 pr-1">
            <EmergencyStatus v-show="hasEmergencyButton" :room_id="room_id" />
        </div>

    </div>
</template>

<script>
import AirCon from './Devices/AirCon.vue'
import LightSwitch from './Devices/LightSwitch.vue'
import Television from './Devices/Television.vue'
import DoorLock from './Devices/DoorLock.vue'
import ShowerStatus from './Devices/ShowerStatus.vue'
import RestRoomStatus from './Devices/RestroomStatus.vue'
import EmergencyStatus from './Devices/EmergencyStatus.vue'

export default {
    components: {
        AirCon,
        LightSwitch,
        Television,
        DoorLock,
        ShowerStatus,
        RestRoomStatus,
        EmergencyStatus,
    },

    props: ['room_id'],

    data() {
        return {
            devices: [],
            appliances: [],
            passAppliance: {
                ac: {},
                light: {},
                tv: {},
            },
            passDevice: [
                { ir_remote: {} },
                { light_switch: [] },
                { embedded_switch: [] },
                { door_lock: {} },
                { occupancy_temp_light: {} },
                { window_door_sensor: {} },
            ],
        }
    },

    computed: {
        hasEmergencyButton: function () {
            return this.devices.map(device => device.DEVICE_TYPE).includes('emergency_button')
        },
    },

    created() {
        this.getRoomDevices()
        this.getRoomAppliances()
    },

    mounted() {
        Echo.channel('nature_remo_appliances').listen('NatureRemoApplianceStateUpdated', e => {
            const applianceIDs = this.appliances.map(appliance => appliance.APPLIANCE_ID)
            if (applianceIDs.includes(e.appliance.APPLIANCE_ID)) {
                this.getRoomAppliances()
            }
        })
    },

    methods: {
        /**
         * @name getRoomDevices
         * @desc Get all devices in the specified room
         *
         * @returns null
         */
        getRoomDevices() {
            axios
                .get(`client/devices/${this.room_id}`)
                .then(response => {
                    this.devices = response.data
                    this.passDeviceInfo()
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         *
         */
        getRoomAppliances() {
            axios
                .get(`client/appliances/${this.room_id}`)
                .then(response => {
                    this.appliances = response.data
                    this.passApplianceInfo()
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * @name commandDevice
         * @desc Commands selected device
         *
         * @param {Object} value
         * @returns null
         */
        commandDevice(value) {
            axios
                .post('client/instruct', {
                    GATEWAY_ID: value['GATEWAY_ID'],
                    DEVICE_ID: value['DEVICE_ID'],
                    DEVICE_TYPE: value['DEVICE_TYPE'],
                    COMMAND: value['command'],
                    VALUE: value['value'],
                    addlValue: value['addlValue'],
                    TYPE: 'Manual',
                })
                .then(response => {
                    console.log(value['DEVICE_TYPE'] + ' ' + value['command'] + ' ' + value['value'])
                })
        },

        /**
         * @name passDeviceInfo
         * @desc Identify device to be controlled
         *
         * @returns null
         */
        passDeviceInfo() {
            for (let i in this.devices) {
                switch (this.devices[i].DEVICE_TYPE) {
                    case 'ir_remote':
                        this.passDevice['ir_remote'] = this.devices[i]
                        break
                    case 'wall_switch_2':
                    case 'wall_switch_3':
                        this.passDevice['light_switch'].push(this.devices[i])
                        break
                    case 'embedded_switch_1':
                    case 'embedded_switch_2':
                    case 'embedded_switch_3':
                        this.passDevice['embedded_switch'].push(this.devices[i])
                        break
                    case 'remote_lock':
                        this.passDevice['door_lock'] = this.devices[i]
                        break
                    case 'occupancy_temp_light':
                        this.passDevice['occupancy_temp_light'] = this.devices[i]
                        break
                    case 'window_door_sensor':
                        this.passDevice['window_door_sensor'] = this.devices[i]
                        break
                    default:
                        break
                }
            }
        },

        /**
         *
         */
        passApplianceInfo() {
            this.passAppliance.ac = this.appliances.find(appliance => appliance.APPLIANCE_TYPE === 'AC')
            this.passAppliance.light = this.appliances.find(
                appliance => appliance.APPLIANCE_TYPE === 'IR' && appliance.APPLIANCE_NAME === 'Light'
            )
            this.passAppliance.tv = this.appliances.find(appliance => appliance.APPLIANCE_TYPE === 'TV')
        },

        /**
         *
         */
        refreshAppliance(appliance) {
            console.debug('RefreshAppliance triggered in the Control Panel component!')
        },
    },
}
</script>

<style scoped>
.scrollDevices {
    overflow-y: scroll;
}
</style>
