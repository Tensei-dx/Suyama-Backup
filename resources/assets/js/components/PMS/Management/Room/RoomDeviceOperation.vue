<template>
    <div class="">
        <!-- List of All Devices in the Room -->
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col mt-1">
                        <!-- Title -->
                        <h3 class="d-block text-center font-weight-bold">
                            {{ roomName }}<br>{{ $t('management.roomOperation.deviceOperation') }}</h3>
                        <!-- <h3 class="d-block text-center font-weight-bold"></h3> -->
                    </div>
                    <div class="col-auto align-self-center mt-1">
                        <!-- Notification -->
                        <div class="pointer text-center" @click="showNotif()">
                            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
                            <small class="d-block mt-1">{{ $t('mobile.menu.notif') }}</small>
                        </div>
                    </div>
                    <div class="w-100"></div>
                    <div class="col-12">
                        <span class="d-block">{{ $t('management.roomOperation.status') }}:
                            <b>{{getRoomStatus()}}</b></span>
                        <span class="d-block">{{ getCheckTime()[0] }}&nbsp;<b>{{ getCheckTime()[1] }}</b></span>
                        <span class="d-block">{{ $t('management.roomOperation.message') }}:
                            <b>{{ room.ROOM_MESSAGE }}</b></span>
                    </div>
                    <div class="col-12 py-3">
                        <div class="row no-gutters justify-content-between align-items-center">
                            <div class="col-6 p-1">
                                <div class="card">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3 h-100 py-1 text-center hotel-base-color rounded-left">
                                            <span class="d-block">
                                                <i class="fa fa-user fa-lg text-black" aria-hidden="true"></i>
                                            </span>
                                            <small
                                                   class="d-block text-black custom-font-size-xs">{{ $t('management.roomOperation.peopleCount') }}</small>
                                        </div>
                                        <div class="col-4 h-100 text-center border-right border-black">
                                            <span class="d-block font-weight-bold">{{ sensorData.peopleCount }}</span>
                                            <span class="d-block custom-font-size-xs">People</span>
                                        </div>
                                        <div class="col-5 h-100 text-center">
                                            <span class="d-block custom-font-size-xs"
                                                  :class="updatePeopleStatus[1]">{{ updatePeopleStatus[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-1">
                                <div class="card">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3 py-1 text-center hotel-base-color rounded-left">
                                            <span class="d-block">
                                                <i class="fa fa-cloud fa-lg text-black" aria-hidden="true"></i>
                                            </span>
                                            <small
                                                   class="d-block text-black custom-font-size-xs">{{ $t('management.roomOperation.co2') }}</small>
                                        </div>
                                        <div class="col-4 text-center border-right border-black">
                                            <span class="d-block font-weight-bold">{{ sensorData.co2 }}</span>
                                            <span class="d-block custom-font-size-xs">PPM</span>
                                        </div>
                                        <div class="col-5 text-center align-self-center">
                                            <span class="d-block custom-font-size-xs"
                                                  :class="updateCO2Status[1]">{{ updateCO2Status[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-1">
                                <div class="card">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3 py-1 text-center hotel-base-color rounded-left">
                                            <span class="d-block">
                                                <i class="fa fa-thermometer-full fa-lg text-black"
                                                   aria-hidden="true"></i>
                                            </span>
                                            <small
                                                   class="d-block text-black custom-font-size-xs">{{ $t('management.roomOperation.temp') }}</small>
                                        </div>
                                        <div class="col-4 text-center border-right border-black">
                                            <span class="d-block font-weight-bold">{{ sensorData.temp }}</span>
                                            <span class="d-block custom-font-size-xs">ºC</span>
                                        </div>
                                        <div class="col-5 text-center align-self-center">
                                            <span class="d-block custom-font-size-xs"
                                                  :class="updateTempStatus[1]">{{ updateTempStatus[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-1">
                                <div class="card">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3 py-1 text-center hotel-base-color rounded-left">
                                            <span class="d-block">
                                                <i class="fa fa-thermometer-full fa-lg text-black"
                                                   aria-hidden="true"></i>
                                            </span>
                                            <small
                                                   class="d-block text-black custom-font-size-xs">{{ $t('management.roomOperation.hum') }}</small>
                                        </div>
                                        <div class="col-4 text-center border-right border-black">
                                            <span class="d-block font-weight-bold">{{ sensorData.hum }}</span>
                                            <span class="d-block custom-font-size-xs">%</span>
                                        </div>
                                        <div class="col-5 text-center align-self-center">
                                            <span class="d-block custom-font-size-xs"
                                                  :class="updateHumStatus[1]">{{ updateHumStatus[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 h2 text-center font-weight-bold">{{ $t('management.roomOperation.deviceList') }}
                    </div>
                    <div class="col-12">
                        <div class="row device-operation-scroll border border-black" style="height:18rem;">
                            <div v-for="device in devices" :key="device.DEVICE_ID" class="col-xl-3 px-0"
                                 style="text-align:center">
                                <div class="card border border-black bg-transparent rounded-0 text-white">
                                    <img :src="'/img/ManagementDashboard/icon/Devices/'+device.DEVICE_TYPE+'.png'"
                                         style="max-width:100%;" class="img-fluid pl-4 pt-2 pr-4 pb-0"
                                         @click="selectDevice([device.DEVICE_TYPE,device.DEVICE_ID,device.DEVICE_NAME,device])">
                                    <div class="card-device pt-1" style="text-align:center;height:50px">
                                        {{ device.DEVICE_NAME}}</div>
                                </div>
                            </div>
                            <!-- No Device Available -->
                            <div v-if="devices.length == 0">
                                <h1>{{ $t('management.noDevice') }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 hotel-theme-color-1" style="height:39rem;">
                <div class="text-center h1 my-4"><b>Device Control</b></div>
                <div class="card m-3" style="margin:auto;">
                    <div class="card-body" v-if="selectedDevice == 'wall_switch_1'">
                        <WallSwitch1 :deviceId=deviceId :deviceName=deviceName :device=device
                                     v-on:commandDevice="commandDevice"></WallSwitch1>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'wall_switch_2'">
                        <WallSwitch2 :deviceId=deviceId :deviceName=deviceName :device=device
                                     v-on:commandDevice="commandDevice"></WallSwitch2>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'wall_switch_3'">
                        <WallSwitch3 :deviceId=deviceId :deviceName=deviceName :device=device
                                     v-on:commandDevice="commandDevice"></WallSwitch3>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'embedded_switch_1'">
                        <EmbeddedSwitch1 :deviceId=deviceId :deviceName=deviceName :device=device
                                         v-on:commandDevice="commandDevice"></EmbeddedSwitch1>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'embedded_switch_2'">
                        <EmbeddedSwitch2 :deviceId=deviceId :deviceName=deviceName :device=device
                                         v-on:commandDevice="commandDevice"></EmbeddedSwitch2>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'embedded_switch_3'">
                        <EmbeddedSwitch3 :deviceId=deviceId :deviceName=deviceName :device=device
                                         v-on:commandDevice="commandDevice"></EmbeddedSwitch3>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'motion_detector'">
                        <MotionSensor :deviceId=deviceId :deviceName=deviceName :device=device
                                      v-on:commandDevice="commandDevice"></MotionSensor>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'temp_hum'">
                        <TempHum :deviceId=deviceId :deviceName=deviceName :device=device
                                 v-on:commandDevice="commandDevice"></TempHum>
                    </div>
                    <!--    <div class="card-body" v-else-if="selectedDevice == 'ir_remote'">
                        <IRRemote :deviceId=deviceId :deviceName=deviceName :device=device v-on:commandDevice="commandDevice"></IRRemote>
                    </div> -->
                    <div class="card-body" v-else-if="selectedDevice == 'camera'">
                        <Camera :deviceId=deviceId :deviceName=deviceName :device=device
                                v-on:commandDevice="commandDevice"></Camera>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'h2o_detector'">
                        <WaterLeakage :deviceId=deviceId :deviceName=deviceName :device=device
                                      v-on:commandDevice="commandDevice"></WaterLeakage>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'co2_detector'">
                        <Co2Detector :deviceId=deviceId :deviceName=deviceName :device=device
                                     v-on:commandDevice="commandDevice"></Co2Detector>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'dust_detector'">
                        <DustDetector :deviceId=deviceId :deviceName=deviceName :device=device
                                      v-on:commandDevice="commandDevice"></DustDetector>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'panic_button'">
                        <PanicButton :deviceId=deviceId :deviceName=deviceName :device=device
                                     v-on:commandDevice="commandDevice"></PanicButton>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'remote_lock'">
                        <RemoteLock :deviceId=deviceId :deviceName=deviceName :device=device
                                    v-on:commandDevice="commandDevice"></RemoteLock>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'occupancy_temp_light'">
                        <OccupancyTempLight :deviceId=deviceId :deviceName=deviceName :device=device
                                            v-on:commandDevice="commandDevice"></OccupancyTempLight>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'window_door_sensor'">
                        <WindowDoor :deviceId=deviceId :deviceName=deviceName :device=device
                                    v-on:commandDevice="commandDevice"></WindowDoor>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'emergency_button'">
                        <EmergencyButton :deviceId=deviceId :deviceName=deviceName :device=device
                                         v-on:commandDevice="commandDevice"></EmergencyButton>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'ir_remote'">
                        <IRRemoteNR :deviceId=deviceId :deviceName=deviceName :device=device
                                    v-on:commandDevice="commandDevice"></IRRemoteNR>
                    </div>
                    <div class="card-body" v-else-if="selectedDevice == 'co2_temp_humid'">
                        <Co2TempHumid :deviceId=deviceId :deviceName=deviceName :device=device
                                      v-on:commandDevice="commandDevice"></Co2TempHumid>
                    </div>
                </div>
            </div>
        </div>
        <NotifModal id="notifModal" :room_id="this.roomId"></NotifModal>
    </div>
</template>

<script>
import NotifModal from '../Modal/NotifModal.vue'
import WallSwitch1 from './DeviceList/WallSwitch1.vue'
import WallSwitch2 from './DeviceList/WallSwitch2.vue'
import WallSwitch3 from './DeviceList/WallSwitch3.vue'
import EmbeddedSwitch1 from './DeviceList/EmbeddedSwitch1.vue'
import EmbeddedSwitch2 from './DeviceList/EmbeddedSwitch2.vue'
import EmbeddedSwitch3 from './DeviceList/EmbeddedSwitch3.vue'
import MotionSensor from './DeviceList/MotionSensor.vue'
import TempHum from './DeviceList/TempHum.vue'
//   import IRRemote          from "./DeviceList/IRRemote.vue";
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

export default {
    components: {
        NotifModal,
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
    },

    props: {
        roomName: String,
        roomId: Number,
        devices: '',
        room: '',
    },

    data() {
        return {
            selectedDevice: '',
            deviceName: '',
            device: '',
            deviceId: '',
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
        }
    },

    created() {
        this.getSensorData()
    },
    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getSensorData()
        })

        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            const pc = e2.data.DATA
            this.sensorData.peopleCount = pc.yesterdayIn + pc.peopleIn - (pc.yesterdayOut + pc.peopleOut)
        })
    },
    methods: {
        /**
         * @name selectDevice
         * @desc Get the details of the selected device
         * @param {Object} value
         */
        selectDevice(value) {
            this.selectedDevice = value[0]
            this.deviceId = value[1]
            this.deviceName = value[2]
            this.device = value[3]
        },

        /**
         *
         */
        showNotif() {
            $('#notifModal').modal('show')
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
                .then(response => {})
        },

        /**
         * @name updateRoomStatus
         * @desc Update the room status to availabe when pressing the cleaned up button
         * @param {String} value
         */
        updateRoomStatus(value) {
            axios.post('updateRoomStatus', {
                ROOM_ID: value,
            })
        },

        /**
         *
         */
        getRoomStatus() {
            let status = this.room.ROOM_STATUS
            if (status === 0) {
                status = 'Available'
            } else if (status === 1) {
                status = 'Reserved'
            } else if (status === 2) {
                status = 'Checked In'
            } else if (status === 3) {
                status = 'Unavailable'
            } else if (status === 4) {
                status = 'Checked Out'
            }
            return status
        },

        /**
         *
         */
        getCheckTime() {
            let status = this.room.ROOM_STATUS
            if (status === 1) {
                return ['Check In Time:', this.room.book.CHECK_IN_TIME]
            } else if (status === 2) {
                return ['Check Out Time:', this.room.book.CHECK_OUT_TIME]
            } else {
                return ['', '']
            }
        },

        /**
         * @name getSensorData
         * @desc
         * @params
         * @returns null
         */
        getSensorData() {
            axios
                .get('getRoomDevices/' + this.roomId)
                .then(response => {
                    let devices = response.data
                    for (let i in devices) {
                        switch (devices[i].DEVICE_TYPE) {
                            case 'temp_hum':
                                this.sensorData.temp = devices[i].DATA.temp
                                this.sensorData.hum = devices[i].DATA.hum
                                continue
                            case 'co2_detector':
                                this.sensorData.co2 = devices[i].DATA.status
                                continue
                            case 'camera':
                                let apps = devices[i].DATA[0].Applications
                                for (let j in apps) {
                                    if (apps[j].name === 'vmd' && apps[j].status === 'Running') {
                                        axios
                                            .post('client/data/latest', {
                                                DEVICE_ID: devices[i].DEVICE_ID,
                                            })
                                            .then(response => {
                                                let pc = response.data.DATA
                                                this.sensorData.peopleCount =
                                                    pc.peopleIn + pc.yesterdayIn - (pc.peopleOut + pc.yesterdayOut)
                                            })
                                            .catch(error => {
                                                console.log(error)
                                            })
                                    }
                                }
                                continue
                            default:
                                continue
                        }
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    computed: {
        /**
         * @name updatePeopleStatus
         * @desc
         * @returns {String[]}
         */
        updatePeopleStatus: function () {
            let pc = this.sensorData.peopleCount
            let th = this.room.ROOM_TOTAL_PEOPLE
            if (!pc) {
                return ['NO DATA', 'text-danger']
            } else if (pc <= th) {
                return ['-', 'text-black']
            } else if (pc > th) {
                return ['EXCEEDING', 'text-danger']
            }
        },

        /**
         * @name updateCO2Status
         * @desc
         * @returns {String[]}
         */
        updateCO2Status: function () {
            let co2 = this.sensorData.co2
            let th = this.threshold.co2
            if (!co2) {
                return ['NO DATA', 'text-danger']
            } else if (co2 <= th.low) {
                return ['LOW', 'text-danger']
            } else if (co2 > th.low && co2 < th.high) {
                return ['NORMAL', 'text-black']
            } else if (co2 >= th.high) {
                return ['HIGH', 'text-danger']
            }
        },

        /**
         * @name updateTempStatus
         * @desc
         * @returns {String[]}
         */
        updateTempStatus: function () {
            let temp = this.sensorData.temp
            let th = this.threshold.temp
            if (!temp) {
                return ['NO DATA', 'text-danger']
            } else if (temp <= th.cold) {
                return ['COLD', 'text-danger']
            } else if (temp > th.cold && temp < th.hot) {
                return ['NORMAL', 'text-black']
            } else if (temp >= th.hot) {
                return ['HIGH', 'text-danger']
            }
        },

        /**
         * @name updateHumStatus
         * @desc
         * @returns {String[]}
         */
        updateHumStatus: function () {
            let hum = this.sensorData.hum
            let th = this.threshold.hum
            if (!hum) {
                return ['NO DATA', 'text-danger']
            } else if (hum <= th.low) {
                return ['LOW', 'text-danger']
            } else if (hum > th.low && hum < th.high) {
                return ['NORMAL', 'text-black']
            } else if (hum >= th.high) {
                return ['HIGH', 'text-danger']
            }
        },
    },
}
</script>
<style>
.device-operation-scroll {
    overflow-y: auto;
    height: 260px;
}
</style>
