<!-- UPDATED: TP Ivin SPRINT_04 TASK125 20210831 -->
<!-- UPDATED: TP Ivin SPRINT_09 TASK180 20210928 -->
<template>
    <div>
        <!-- List of All Devices in the Room -->
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <!--  + Sprint05 Task138 -->
                    <!-- A back Button to return in the room list  -->
                    <div class="col-auto align-self-center mt-1">
                        <div class="pointer text-center" @click="backToRoomList()">
                            <i class="fa fa-arrow-left fa-3x"></i>
                        </div>
                    </div>
                    <!--  + Sprint05 Task138 -->
                    <div class="col mt-3">
                        <!-- Title -->
                        <h4 class="d-block text-center font-weight-bold">
                            {{ room.ROOM_NAME }}<br />
                            <!-- {{ $t('management.roomOperation.roomInfo') }} -->
                        </h4>
                    </div>
                    <!-- <div class="col-auto align-self-center mt-1"> -->
                    <!-- Notification -->
                    <!-- <div class="pointer text-center" @click="showNotif()">
                            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
                            <small class="d-block mt-1">{{ $t('mobile.menu.notif') }}</small>
                        </div>
                    </div> -->
                    <div class="w-100" />
                    <!--TASK021-4-->
                    <div class="col-12 pt-3">
                        <EditStatusSelectBox :room="currentRoom" />
                    </div>

                    <!-- <div class="col mt-3 mb-1 text-left">
                        <a style="color:white; text-decoration:none;">
                            <button type="button" class="btn btn-guestcard"
                                    @click="$emit('changePage', 'guestCardCheck')">
                                <i class="fa fa-check-circle btn-guestcard-font" aria-hidden="true"></i>
                                <span class="guestcard-font">
                                    {{ $t('management.roomOperation.checkguestcard') }}
                                </span>
                            </button>
                        </a>
                    </div> -->

                    <div class="col-12 pt-2 pb-3 devices-content">
                        <SmallCategory :smallDevices="smallDevices" :roomAppliances="appliances"
                                       @onSelectItem="selectItem" />
                        <MiddleCategory :middleDevices="middleDevices" @onSelectItem="selectItem" />
                        <LargeCategory :largeDevices="largeDevices" @onSelectItem="selectItem" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 hotel-theme-color-1 device-control-scroll pb-3">
                <div class="text-center h1 my-4 font-weight-bold">
                    {{ $t('management.roomInfo.deviceControl') }}
                </div>
                <div v-if="selectedDevice && selectedDevice !== 'nature_remo' && selectedDevice !== 'appliances'"
                     class="card m-auto">
                    <div class="card-body px-0 py-0">
                        <WallSwitch1 v-if="selectedDevice === 'wall_switch_1'" :deviceId=deviceId :deviceName=deviceName
                                     :device=device @commandDevice="commandDevice" />
                        <WallSwitch2 v-else-if="selectedDevice === 'wall_switch_2'" :deviceId=deviceId
                                     :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <WallSwitch3 v-else-if="selectedDevice === 'wall_switch_3'" :deviceId=deviceId
                                     :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <EmbeddedSwitch1 v-else-if="selectedDevice === 'embedded_switch_1'" :deviceId=deviceId
                                         :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <EmbeddedSwitch2 v-else-if="selectedDevice === 'embedded_switch_2'" :deviceId=deviceId
                                         :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <EmbeddedSwitch3 v-else-if="selectedDevice === 'embedded_switch_3'" :deviceId=deviceId
                                         :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <MotionSensor v-else-if="selectedDevice === 'motion_detector'" :deviceId=deviceId
                                      :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <TempHum v-else-if="selectedDevice === 'temp_hum'" :deviceId=deviceId :deviceName=deviceName
                                 :device=device @commandDevice="commandDevice" />
                        <Camera v-else-if="selectedDevice === 'camera'" :deviceId=deviceId :deviceName=deviceName
                                :device=device @commandDevice="commandDevice" />
                        <WaterLeakage v-else-if="selectedDevice === 'h2o_detector'" :deviceId=deviceId
                                      :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <Co2Detector v-else-if="selectedDevice === 'co2_detector'" :deviceId=deviceId
                                     :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <DustDetector v-else-if="selectedDevice === 'dust_detector'" :deviceId=deviceId
                                      :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <PanicButton v-else-if="selectedDevice === 'panic_button'" :deviceId=deviceId
                                     :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <RemoteLock v-else-if="selectedDevice === 'remote_lock'" :deviceId=deviceId
                                    :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <OccupancyTempLight v-else-if="selectedDevice === 'occupancy_temp_light'" :deviceId=deviceId
                                            :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <WindowDoor v-else-if="selectedDevice === 'window_door_sensor'" :deviceId=deviceId
                                    :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <EmergencyButton v-else-if="selectedDevice === 'emergency_button'" :deviceId=deviceId
                                         :deviceName=deviceName :device=device @commandDevice="commandDevice" />
                        <IRRemoteNR v-else-if="selectedDevice === 'ir_remote'" :deviceId=deviceId :deviceName=deviceName
                                    :device=device @commandDevice="commandDevice" />
                        <Co2TempHumid v-else-if="selectedDevice === 'co2_temp_humid'" :deviceId=deviceId
                                      :deviceName=deviceName :device=device @commandDevice="commandDevice" />

                    </div>
                </div>
                <IRRemoteNR2 v-else-if="selectedDevice === 'nature_remo'" :device="device" />
                <Appliances v-else-if="selectedDevice === 'appliances'" :device="appliances" />
            </div>
        </div>
    </div>
</template>

<script>
import EditStatusSelectBox from '../Modal/EditStatusSelectBox.vue'
import WallSwitch1 from './DeviceList/WallSwitch1.vue'
import WallSwitch2 from './DeviceList/WallSwitch2.vue'
import WallSwitch3 from './DeviceList/WallSwitch3.vue'
import EmbeddedSwitch1 from './DeviceList/EmbeddedSwitch1.vue'
import EmbeddedSwitch2 from './DeviceList/EmbeddedSwitch2.vue'
import EmbeddedSwitch3 from './DeviceList/EmbeddedSwitch3.vue'
import MotionSensor from './DeviceList/MotionSensor.vue'
import TempHum from './DeviceList/TempHum.vue'
import Camera from './DeviceList/Camera.vue'
import WaterLeakage from './DeviceList/WaterLeakage.vue'
import Co2Detector from './DeviceList/Co2Detector.vue'
import DustDetector from './DeviceList/DustDetector.vue'
import PanicButton from './DeviceList/PanicButton.vue'
import RemoteLock from './DeviceList/RemoteLock.vue'
import OccupancyTempLight from './DeviceList/OccupancyTempLight.vue'
import WindowDoor from './DeviceList/WindowDoor.vue'
import EmergencyButton from './DeviceList/EmergencyButton.vue'
import IRRemoteNR from './DeviceList/IRRemoteNR.vue'
import Co2TempHumid from './DeviceList/Co2TempHumid.vue'
import IRRemoteNR2 from './DeviceList/IRRemoteNR2.vue'

import SmallCategory from './DeviceCategory/SmallCategory.vue'
import MiddleCategory from './DeviceCategory/MiddleCategory.vue'
import LargeCategory from './DeviceCategory/LargeCategory.vue'

import Appliances from './Appliances/Appliances.vue'

export default {
    components: {
        EditStatusSelectBox,
        WallSwitch1,
        WallSwitch2,
        WallSwitch3,
        EmbeddedSwitch1,
        EmbeddedSwitch2,
        EmbeddedSwitch3,
        MotionSensor,
        TempHum,
        IRRemoteNR,
        Camera,
        WaterLeakage,
        Co2Detector,
        DustDetector,
        PanicButton,
        RemoteLock,
        OccupancyTempLight,
        WindowDoor,
        EmergencyButton,
        Co2TempHumid,
        IRRemoteNR2,

        SmallCategory,
        MiddleCategory,
        LargeCategory,

        Appliances,
    },

    props: {
        room: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            devices: [],
            selectedDevice: '',
            deviceName: '',
            device: '',
            deviceId: '',
            cameraId: '',
            sensorData: {
                peopleCount: '',
                co2: '',
                temp: '',
                hum: '',
            },
            threshold: {
                temp: {
                    cold: 18,
                    hot: 30,
                },
                hum: {
                    low: 30,
                    high: 50,
                },
                co2: {
                    low: 500,
                    high: 1500,
                },
            },

            // showEditModal: false,
            currentRoom: null,
            errors: [],

            appliances: [],
        }
    },

    created() {
        this.currentRoom = this.room
        this.getSensorData()
        this.getDevices()
        this.getRoomAppliances()
    },

    mounted() {
        // Echo.channel('test')
        // .listen('.deviceCommandEvent', e => this.getSensorData())
        // .listen('.newDeviceDataEvent', e => {
        //     if (e.data.DEVICE_ID === this.cameraId) {
        //         const PEOPLE_COUNT = e.data.DATA
        //         this.sensorData.peopleCount = (PEOPLE_COUNT.yesterdayIn + PEOPLE_COUNT.peopleIn) - (PEOPLE_COUNT.yesterdayOut + PEOPLE_COUNT.peopleOut)
        //     }
        // })

        this.$bus.on('onSelectDeviceItem', device => {
            this.selectItem(device)
        })
        this.$bus.on('onSelectAppliances', appliances => {
            this.selectApplianceItem(appliances)
        })

        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getSensorData()
        })

        Echo.channel('room-message').listen('RoomMessageEvent', value => {
            this.getRoom()
        })

        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            if (value.data.DEVICE_ID === this.cameraId) {
                const PEOPLE_COUNT = value.data.DATA
                this.sensorData.peopleCount =
                    PEOPLE_COUNT.yesterdayIn +
                    PEOPLE_COUNT.peopleIn -
                    (PEOPLE_COUNT.yesterdayOut + PEOPLE_COUNT.peopleOut)
            } else {
                this.getDevices()
                this.getSensorData()
            }
        })
    },

    beforeDestroy() {
        Echo.leave('device-command')
        Echo.leave('newdevice-data')
    },

    computed: {
        /**
         * @name peopleCountStatus
         * @returns {string[]}
         */
        peopleCountStatus() {
            const PEOPLE_COUNT = this.sensorData.peopleCount

            if (!PEOPLE_COUNT && PEOPLE_COUNT !== 0) return ['NO DATA', 'text-danger']

            const MAX = this.room.MAX_OCCUPANCY
            if (PEOPLE_COUNT >= MAX) return ['EXCEEDING', 'text-danger']
            else return ['-', 'text-black']
        },

        /**
         * @name co2Status
         * @returns {string[]}
         */
        co2Status() {
            const CO2_DATA = this.sensorData.co2
            if (!CO2_DATA && CO2_DATA !== 0) return ['NO DATA', 'text-danger']

            const MIN = this.threshold.co2.low
            const MAX = this.threshold.co2.high
            if (CO2_DATA <= MIN) return ['LOW', 'text-danger']
            else if (CO2_DATA >= MAX) return ['HIGH', 'text-danger']
            else return ['NORMAL', 'text-black']
        },

        /**
         * @name temperatureStatus
         * @returns {string[]}
         */
        temperatureStatus() {
            const TEMP_DATA = this.sensorData.temp
            if (!TEMP_DATA && TEMP_DATA !== 0) return ['NO DATA', 'text-danger']

            const MIN = this.threshold.temp.cold
            const MAX = this.threshold.temp.hot
            if (TEMP_DATA <= MIN) return ['COLD', 'text-danger']
            else if (TEMP_DATA >= MAX) return ['HOT', 'text-danger']
            else return ['NORMAL', 'text-black']
        },

        /**
         * @name humidityStatus
         * @returns {string[]}
         */
        humidityStatus() {
            const HUM_DATA = this.sensorData.hum
            if (!HUM_DATA && HUM_DATA !== 0) return ['NO DATA', 'text-danger']

            const MIN = this.threshold.hum.low
            const MAX = this.threshold.hum.high
            if (HUM_DATA <= MIN) return ['LOW', 'text-danger']
            else if (HUM_DATA >= MAX) return ['HIGH', 'text-danger']
            else return ['NORMAL', 'text-black']
        },

        smallDevices: function () {
            return this.devices.filter(device => {
                return ['remote_lock'].includes(device.DEVICE_TYPE)
            })
        },

        middleDevices: function () {
            return this.devices.filter(device => {
                return ['camera', 'window_door_sensor'].includes(device.DEVICE_TYPE)
            })
        },

        largeDevices: function () {
            return this.devices.filter(device => {
                return ['occupancy_temp_light', 'co2_temp_humid'].includes(device.DEVICE_TYPE)
            })
        },
    },

    methods: {
        /**
         * @name selectItem
         * @desc Get the details of the selected device
         * @param {Object} value
         */
        selectItem(device) {
            this.selectedDevice = device.DEVICE_TYPE
            this.device = device
            this.deviceId = device.DEVICE_ID
            this.deviceName = device.DEVICE_NAME
        },

        selectApplianceItem(appliances) {
            this.selectedDevice = 'appliances'
            this.device = appliances
            this.deviceId = null
            this.deviceName = null
        },

        // + SPRINT_05 TASK138
        /**
         * @name backToRoomList
         * @desc Back to previous Display of the screen
         *
         */
        backToRoomList() {
            this.$emit('backToRoomList')
            const newURL = window.location.protocol + '//' + window.location.host + window.location.pathname
            window.history.pushState({ path: newURL }, '', newURL)
        },
        // + SPRINT_05 TASK138

        getDevices() {
            axios
                .get(`getRoomDevices/${this.currentRoom.ROOM_ID}`)
                .then(response => (this.devices = response.data))
                .catch(error => this.errors.push(error))
        },

        /**
         * @name commandDevice
         * @desc Send instruction to the MC to command the device
         * @param {String} value
         */
        commandDevice(value) {
            axios
                .post('sendInstruction', {
                    GATEWAY_ID: value['GATEWAY_ID'],
                    DEVICE_ID: value['DEVICE_ID'],
                    DEVICE_TYPE: value['DEVICE_TYPE'],
                    COMMAND: value['command'],
                    VALUE: value['value'],
                    addlValue: value['addlValue'],
                    TYPE: 'Manual',
                })
                .then(response => console.log(response.data))
        },

        /**
         *
         */
        getCheckTime() {
            switch (this.currentRoom.STATUS_ID) {
                case 201:
                    return ['Check Out Time', this.room.bookings_with_book[0].CHECK_OUT_TIME]
                case 205:
                    return ['Check In Time', this.room.bookings_with_book[0].CHECK_IN_TIME]
                default:
                    return ['', '']
            }
        },

        getBookingDetails() {
            const DETAILS = { timeString: null, timeData: null }
            switch (this.currentRoom.STATUS_ID) {
                case 201:
                    DETAILS.timeString = 'Check Out Time'
                    DETAILS.timeData = this.room.booking_today.CHECK_OUT_TIME
                    break
                case 205:
                    DETAILS.timeString = 'Check In Time'
                    DETAILS.timeData = this.room.booking_today.CHECK_IN_TIME
                    break
            }
            return DETAILS
        },

        /**
         * @name getSensorData
         * @desc
         * @params
         * @returns null
         */
        getSensorData() {
            axios.get(`getRoomDevices/${this.currentRoom.ROOM_ID}`).then(response => {
                const DEVICES = response.data
                // For temperature humidity data
                const TEMP_HUM = DEVICES.find(i => i.DEVICE_TYPE === 'temp_hum')
                if (TEMP_HUM) {
                    this.sensorData.temp = TEMP_HUM.DATA.temp
                    this.sensorData.hum = TEMP_HUM.DATA.hum
                }
                // For carbon dioxide data
                const CO2_DETECTOR = DEVICES.find(i => i.DEVICE_TYPE === 'co2_detector')
                if (CO2_DETECTOR) {
                    this.sensorData.co2 = CO2_DETECTOR.DATA.status
                }
                // // For people counter data
                // const CAMERA = DEVICES.find(i => i.DEVICE_TYPE === 'camera')
                // if (CAMERA) {
                //     this.cameraId = CAMERA.DEVICE_ID
                //     const PEOPLE_COUNT_APP = CAMERA.DATA[0].Applications.find(
                //         i => i.name === 'vmd' && i.status === 'Running'
                //     )
                //     if (PEOPLE_COUNT_APP) {
                //         axios
                //             .post('client/data/latest', {
                //                 DEVICE_ID: CAMERA.DEVICE_ID,
                //             })
                //             .then(response2 => {
                //                 const PEOPLE_COUNT = response2.data.DATA
                //                 this.sensorData.peopleCount =
                //                     PEOPLE_COUNT.peopleIn +
                //                     PEOPLE_COUNT.yesterdayIn -
                //                     (PEOPLE_COUNT.peopleOut + PEOPLE_COUNT.yesterdayOut)
                //             })
                //             .catch(error => console.log(error))
                //     }
                // }
            })
        },

        openEditModal() {
            this.showEditModal = true
        },

        closeModal(event) {
            if (event.modal === 'EditStatusModal') this.showEditModal = false
        },

        getRoomAppliances() {
            axios
                .get(`client/appliances/${this.currentRoom.ROOM_ID}`)
                .then(response => {
                    this.appliances = response.data
                    // this.passApplianceInfo()
                })
                .catch(error => {
                    console.log(error)
                })
        },

        getRoom() {
            axios.get('client/room/' + this.room.ROOM_ID).then(response => {
                this.currentRoom = response.data
            })
        },
    },
}
</script>
<style>
.device-operation-scroll {
    overflow-y: auto;
    height: 260px;
}
.device-control-scroll {
    overflow-y: auto;
    height: 665px;
    max-height: 700px;
}
.device-icon {
    /* max-width: 70px; */
}
.devices-content {
    max-height: 450px !important;
    height: 450px !important;
    overflow: auto !important;
}
@media (max-width: 1280px) {
    /* .device-icon {
        max-width: 45px;
    } */
}
</style>
