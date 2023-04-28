<template>
    <div>
        <div class="row mx-0" style="height: 665px">
            <div class="col-xl-4 pt-1 pb-2 hotel-theme-color-1" style="color:white;">
                <Menu @changePage="changePage" :locale="locale" :currentPage="currentPage" />
            </div>
            <!-- ROOM STATUS -->
            <div v-if="this.currentPage === 'dashboard'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <RoomStatus2 @roomSelect="updateRoomSelected" :rooms="rooms" @refreshRooms="getRooms" />
            </div>
            <!-- ROOM DEVICE OPERATION -->
            <div v-if="currentPage === 'roomDeviceOperation'" class="col-xl-8 hotel-theme-color-2"
                 style="color: white;">
                <RoomDeviceOperation2 @backToRoomList="backToPrevious" :room="selectedRoom"
                                      v-on:changePage="changePage" />
            </div>

            <!-- GUEST CARD CHECK -->
            <!-- <div class="col-xl-8" style="background-color: white;" v-if="currentPage === 'guestCardCheck'">
                <GuestCardCheck @backToRoomMenu="backToRoomDeviceOperation" v-on:changePage="changePage" />
            </div> -->

            <!-- KEY MANAGEMENT -->
            <div v-if="currentPage === 'user'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <GuestList @changePage="changePage" />
            </div>
            <!-- Logs Screen -->
            <div v-if="currentPage === 'logs'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <Logs v-on:changePage="changePage" :userid="userId" />
            </div>
            <!-- CO2 Monitor Screen -->
            <div v-if="currentPage === 'co2'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <EnvironmentalMonitor v-on:changePage="changePage" />
            </div>
            <div v-if="currentPage === 'device_slide_show'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <DeviceSlideShow v-on:changePage="changePage" />
            </div>
            <!-- Hardware Management Screen -->
            <div v-if="currentPage === 'hardware_management'" class="col-xl-8 hotel-theme-color-2"
                 style="color: white;">
                <HardwareManagement v-on:changePage="changePage" />
            </div>
            <div v-if="currentPage === 'gateway'" class="col-xl-8 hotel-theme-color-2 h-100" style="color: white;">
                <Gateway v-on:changePage="changePage" />
            </div>
            <!-- + SPRINT_07 TASK148 -->
            <div v-if="currentPage === 'device'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <Device v-on:changePage="changePage" />
            </div>
            <div v-if="currentPage === 'remotelock_management'" class="col-xl-8 hotel-theme-color-2">
                <RemoteLockManagement @changePage="changePage" />
            </div>
            <!-- + SPRINT_07 TASK148 -->
            <div v-if="currentPage === 'nature_IR'" class="col-xl-8 hotel-theme-color-2">
                <NatureRemoManagement @changePage="changePage" />
            </div>
            <!-- Manual Screen -->
            <div v-if="currentPage === 'manual'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <Manual v-on:changePage="changePage" />
            </div>
            <!-- Param Screen -->
            <div v-if="currentPage === 'param'" class="col-xl-8 hotel-theme-color-2" style="color: white;">
                <ParamSetting v-on:changePage="changePage" />
            </div>

            <!-- ADD ADMIN ACCOUNT -->
            <div v-if="currentPage === 'addAdminAccount'" class="col-xl-8" style="background-color: white;">
                <AddAdminAccount @backToGuestList="backToGuestList" />
            </div>
        </div>

        <EmergencyModal />

        <!-- Footer -->
        <Footer @changeLang="changeLang" />
    </div>
</template>

<script>
import Menu from './Menu/Menu.vue'
import RoomStatus from './Room/RoomStatus.vue'
import RoomDeviceOperation from './Room/RoomDeviceOperation.vue'
import KeyManagement from './Key/KeyManagement.vue'
import GuestList from './GuestsList/GuestsList.vue'
import Footer from '../Guest/Footer/Footer.vue'

import RoomStatus2 from './Room/RoomStatus2.vue'
import RoomDeviceOperation2 from './Room/RoomDeviceOperation2.vue'
import EnvironmentalMonitor from './EnvironmentalMonitor/EnvironmentalMonitor.vue'
import DeviceSlideShow from './EnvironmentalMonitor/DeviceSlideShow/DeviceSlideShow.vue'
import HardwareManagement from './Hardware/HardwareManagement.vue'
import Gateway from './Hardware/Gateway/Gateway.vue'
import Device from './Hardware/Devices/Device.vue'
import Logs from './Logs/Logs.vue'
import RemoteLockManagement from './Hardware/RemoteLock/Management.vue'
import ParamSetting from './Hardware/Param/ParamSetting.vue'

import NatureRemoManagement from './Hardware/NatureRemo/NatureRemoManagement.vue'
import Manual from './Manual/Manual.vue'

// import GuestCardCheck from './GuestsList/Guests/GuestCardCheck.vue'
import AddAdminAccount from './GuestsList/AddAdminAccount.vue'

import EmergencyModal from './EmergencyModal.vue'

export default {
    components: {
        Menu,
        RoomStatus,
        RoomStatus2,
        RoomDeviceOperation,
        RoomDeviceOperation2,
        KeyManagement,
        GuestList,
        Footer,
        EnvironmentalMonitor,
        DeviceSlideShow,
        HardwareManagement,
        Gateway,
        Device,
        Logs,
        RemoteLockManagement,
        NatureRemoManagement,
        // GuestCardCheck,
        Manual,
        AddAdminAccount,
        ParamSetting,
        EmergencyModal,
    },
    props: ['userId'],

    data() {
        return {
            currentPage: 'dashboard',
            rooms: [],
            selectedRoom: null,
            locale: '',
            errors: [],
            url: '',
            newUrl: '',
        }
    },

    created() {
        this.getRooms()
        this.refreshLang()
    },

    mounted() {
        Echo.channel('room-message').listen('RoomMessageEvent', value => {
            this.getRooms()
        })

        Echo.channel('reservation-synced').listen('ReservationSyncedEvent', value => {
            console.log('synced managerment dashboard')
            this.getRooms()
        })

        Echo.channel('room-status').listen('RoomStatusUpdateEvent', value => {
            value.data.forEach(updatedRoom => {
                this.rooms.find(room => room.ROOM_ID === updatedRoom.ROOM_ID).STATUS_ID = updatedRoom.STATUS_ID
            })
        })
    },

    beforeDestroy() {
        Echo.leave('room-message')
        Echo.leave('room-status')
        Echo.leave('reservation-synced')
    },

    methods: {
        /**
         * @name changePage
         * @desc Change the page depending on the icon that has beem selected
         *
         * @param {string} page
         * @returns {void}
         */
        changePage(page) {
            this.currentPage = page
            // + SPRINT_02 TASK017
            this.url = new URL(window.location.origin)
            this.newUrl = this.url + 'management'
            window.history.pushState('', '', this.newUrl)
            // + SPRINT_02 TASK017
        },

        // + SPRINT_05 TASK138
        /**
         * @name backToPrevious
         * @desc Return to the previous page
         *
         */
        backToPrevious() {
            this.currentPage = 'dashboard'
        },

        /**
         * @name backToRoomDeviceOperation
         * @desc Return to the previous page
         *
         */
        backToRoomDeviceOperation() {
            this.currentPage = 'roomDeviceOperation'
        },

        backToGuestList() {
            this.currentPage = 'user'
        },

        /**
         * @name changeLang
         * @desc Change the language
         *
         * @param {string} lang
         * @returns {void}
         */
        changeLang(lang) {
            this.locale = lang
        },

        /**
         * @listens roomSelected
         */
        updateRoomSelected(selectedRoom) {
            // + SPRINT_02 TASK017
            this.url = new URL(window.location.origin)
            this.newUrl = this.url + 'management?room_id=' + selectedRoom.ROOM_ID
            window.history.pushState('', '', this.newUrl)
            // + SPRINT_02 TASK017
            this.currentPage = 'roomDeviceOperation'
            this.selectedRoom = selectedRoom
        },

        // /**
        //  * @name getRooms
        //  */
        // getRooms() {
        //     axios
        //         .get('getAllRooms', {
        //             validateStatus: status => status >= 200 && status < 300,
        //         })
        //         // = SPRINT_02 TASK017
        //         .then(response => {
        //             this.rooms = response.data
        //             this.newUrl = window.location.href
        //             this.checkUrl(this.newUrl)
        //         })
        //         .catch(error => this.errors.push(error))
        //     // = SPRINT_02 TASK017
        // },

        /**
         *
         */
        getRooms() {
            axios
                .get('rooms', {
                    params: {
                        children: ['booking_today', 'booking_now', 'check_out_today', 'check_in_today'],
                    },
                })
                .then(response => {
                    this.rooms = response.data
                })
                .catch(error => console.error(error))
        },

        // + SPRINT_02 TASK017
        /**
         * @name checkUrl
         * @desc check the url of the current page
         *
         * @param {string} newUrl
         * @returns {void}
         */
        checkUrl(newUrl) {
            var roomID = parseInt(new URL(this.newUrl).searchParams.get('room_id'))
            if (!Number.isNaN(roomID)) {
                for (let i = 0; i < this.rooms.length; i++) {
                    if (roomID == this.rooms[i].ROOM_ID) {
                        this.updateRoomSelected(this.rooms[i])
                    }
                }
            } else {
                this.changePage(page)
            }
        },
        refreshLang() {
            axios.get('/getSession').then(response => {
                if (response.data.locale) {
                    this.locale = response.data.locale
                }
            })
        },
    },
}
</script>

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
}
.room {
    margin-bottom: 15px;
}
.indicator {
    margin: auto;
    margin-top: 1px;
    margin-bottom: 8px;
    font-size: 18px;
}
div .gfg {
    margin-left: 17px;
    padding: 15px;
    background-color: transparent;
    width: 740px;
    height: 520px;
    overflow-x: hidden;
    overflow-y: auto;
}
.room-status-border {
    border-radius: 35px;
}
.room-status-footer {
    border-radius: 0px 0px 35px 35px !important;
    height: 30px !important;
    padding-top: 0.15rem !important;
}
</style>
