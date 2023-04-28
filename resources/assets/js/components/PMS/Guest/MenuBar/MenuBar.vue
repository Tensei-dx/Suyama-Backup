<template>
    <div>
        <div class="row align-items-center">
            <div class="col text-left" style="font-size: 2.5em;">
                <span class="custom-clock-time font-weight-bold" style="font-size:33px;">
                    <Clock class="hotel-white-color-text" :format="$i18n.locale=== 'ja'? 'HH:mm': 'hh:mm A' "></Clock>
                </span>
            </div>
            <!-- <div class="reeditButton">
             <i class="fa fa-pencil-square red-color fa-2x" aria-hidden="true"></i>
             <small class="d-block mt-1">{{ $t('mobile.menu.editguest') }}</small>
             <button type="button"
                        class="editGuestCard btn-sm d-block mt-1">{{ $t('mobile.menu.editguest') }}</button>
             </div> -->
            <div class="col-auto pointer" @click="goTo('alarm')">
                <i class="fa fa-clock-o fa-2x text-red" aria-hidden="true"></i>
                <small class="d-block mt-1">{{ $t('mobile.menu.alarm') }}</small>
            </div>
        </div>
        <div class="row no-gutters mt-1 justify-content-between">
            <div class="col">
                <span class="d-block text-left">
                    <Clock :format="$i18n.locale === 'en' ? 'DD/MM/YYYY' : 'YYYY/MM/DD'"></Clock>
                </span>
                <span class="d-block text-left text-uppercase">
                    <Clock format="dddd" locale="ja"></Clock>
                </span>
            </div>
            <div class="col-auto text-left pr-1">
                <span class="d-block hotel-white-color-text">{{ $t('mobile.menu.checkintime') }}:</span>
                <span class="d-block hotel-white-color-text">{{ $t('mobile.menu.checkouttime') }}:</span>
            </div>
            <div class="col-auto text-left">
                <span class="d-block hotel-accent-color-text font-weight-bold">{{ transformDate(checkInTime) }}</span>
                <span class="d-block hotel-accent-color-text font-weight-bold">{{ transformDate(checkOutTime) }}</span>
            </div>
        </div>
        <div class="row mt-3 align-items-center">
            <div class="col text-left">
                <span class="h4">{{ $t('mobile.menu.greeting') }} {{ this.firstname }}
                    {{ $t('mobile.menu.greetingextension') }}</span>
            </div>
            <div class="w-100 pb-2"></div>
            <div class="col col-md-9 mx-auto">
                <form>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text hotel-accent-color hotel-black-color-text font-weight-bold">
                                {{ $t('mobile.menu.message') }}</div>
                        </div>
                        <select name="" id="message"
                                class="form-control hotel-accent-color hotel-black-color-text font-weight-bold"
                                v-model="selected" @change="updateMessage" :disabled='isLoading'>
                            <option v-for="roomMessage in roomMessages" :key="roomMessage.MESSAGE_ID"
                                    :value="roomMessage.MESSAGE_ID">
                                {{ $t(roomMessage.MESSAGE) }}
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <!-- Navigation Icons -->
        <div class=" container">
            <div class="row h-100 align-items-start justify-content-around m-3">
                <!-- Home -->
                <div class="col-xl-6 col-auto order-1 mt-2 mb-2 custom-i pointer"
                     @click="$emit('changePage', 'dashboard')">
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'dashboard' ? 'ct-bg-icon-orange' : 'hotel-base-color'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <i class="fa fa-home fa-4x custom-i-fg" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-1">{{ $t('mobile.menu.home') }}</span>
                </div>
                <!-- Room Service -->
                <div class="col-xl-6 col-auto order-2 mt-2 mb-2 custom-i pointer"
                     @click="$emit('changePage', 'room-service')">
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'room-service' ? 'ct-bg-icon-orange' : 'hotel-base-color'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <i class="fa fa-bed fa-4x custom-i-fg" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-1">{{ $t('mobile.menu.roomservice') }}</span>
                </div>
                <!-- Room Manual -->
                <div class="col-xl-6 col-auto order-3 mt-2 mb-2 pointer" @click="$emit('changePage', 'room-manual')">
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'room-manual' ? 'ct-bg-icon-orange' : 'hotel-base-color'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <i class="fa fa-book fa-4x custom-i-fg" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-1">{{ $t('mobile.menu.roommanual')}}</span>
                </div>
                <!-- Call Front Desk -->
                <div class="col-xl-6 col-auto order-4 mt-2 mb-2 custom-i pointer" @click="goTo('front-desk')">
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'front-desk' ? 'ct-bg-icon-orange' : 'hotel-base-color'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <i class="fa fa-phone fa-4x custom-i-fg" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-1">{{ $t('mobile.menu.frontdesk') }}</span>
                </div>
            </div>
        </div>
        <!-- Room number and Check Out button -->
        <div class="row align-items-center">
            <!-- Room number -->
            <div class="col text-left">
                <span>{{ $t('mobile.menu.roomnumber') }}: {{ this.roomName }}</span>
            </div>
            <!-- Check out button -->
            <!-- <div class="col-auto m-1">
                <button type="button" class="btn hotel-accent-color hotel-black-color-text font-weight-bold" @click="showLogout()">{{ $t('mobile.menu.checkout')}}</button>
            </div> -->
        </div>
        <div class="row">
            <div class="col-8 mt-3">
                <span class="d-block text-left">
                    {{ $t('hotel.address') }}
                </span>
            </div>
            <!-- <div class="col-12 mt-2">
                <span class="d-block text-left">{{ $t('hotel.address') }}</span>
            </div> -->
            <!-- Check out button -->
            <div class="col-4 pl-0 pr-2" style="text-align:end inline-block vertical-align: middle">
                <button type="button" class="btn checkout-btn hotel-black-color-text font-weight-bold"
                        @click="showLogout()">{{ $t('mobile.menu.checkout')}}</button>
            </div>
        </div>
        <!-- <NotifModal id="notifModal" :room_id="this.room_id"></NotifModal> -->
        <LogoutModal id="logoutModal"></LogoutModal>
    </div>
</template>

<script>
import moment from 'moment'
import Clock from '../../Widgets/Clock2.vue'
import NotifModal from '../Modal/NotifModal.vue'
import LogoutModal from '../Modal/LogoutModal.vue'
export default {
    components: {
        Clock,
        NotifModal,
        LogoutModal,
    },
    props: ['room_id', 'locale', 'currentPage'],
    //     room_id: Number,
    //     locale: '',
    // },

    data() {
        return {
            userAgent: '',
            firstname: '',
            roomName: '',
            selected: null,
            checkInTime: '',
            checkOutTime: '',
            bookRoomID: null,
            isLoading: false,
            roomMessages: [],
        }
    },
    methods: {
        /**
         *
         */
        getRoomMessages() {
            axios.get('room_messages').then(response => {
                this.roomMessages = response.data
            })
        },

        /**
         *
         */
        getUsername() {
            axios.get('getUserID').then(response => {
                this.firstname = response.data.FIRST_NAME
            })
        },
        /**
         *
         */
        getCurrentCheckInDetails() {
            axios
                .get('getCurrentCheckInDetails')
                .then(response => {
                    this.roomName = response.data.room.ROOM_NAME
                    this.selected = response.data.MESSAGE_ID
                    this.bookRoomID = response.data.BOOK_ROOM_ID
                    this.checkInTime = moment(response.data.CHECK_IN_TIME, 'YYYY-MM-DD HH:mm:ss').format('MM/DD HH:mm')
                    this.checkOutTime = moment(response.data.CHECK_OUT_TIME, 'YYYY-MM-DD HH:mm:ss').format(
                        'MM/DD HH:mm'
                    )
                })
                .catch(error => {
                    console.log(error)
                })
        },
        /**
         *
         */
        updateMessage() {
            this.isLoading = true
            axios
                .post('client/room/message/update', {
                    BOOK_ROOM_ID: this.bookRoomID,
                    MESSAGE_ID: this.selected,
                })
                .then(response => {})
                .catch(error => {
                    console.log(error)
                })
                .then(() => {
                    this.isLoading = false
                })
        },
        /**
         *
         */
        showNotif() {
            $('#notifModal').modal('show')
        },
        getDeviceType() {
            const ua = navigator.userAgent
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                this.userAgent = 'tablet'
            } else if (
                /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(
                    ua
                )
            ) {
                this.userAgent = 'mobile'
            } else if (/Kioware/i.test(ua)) {
                this.userAgent = 'kiosk'
            } else {
                this.userAgent = 'desktop'
            }
        },
        goTo(data) {
            switch (data) {
                case 'front-desk':
                    if (this.userAgent !== 'desktop') {
                        location = 'ibmscontroller://callfront'
                    } else {
                        window.open('https://www.skype.com/en/')
                    }
                    break
                case 'alarm':
                    if (this.userAgent !== 'desktop') {
                        window.location = 'ibmscontroller://setalarm'
                    } else {
                        window.open('https://vclock.com/')
                    }
                    break
                default:
                    console.log('No location for ' + data)
                    break
            }
        },
        showLogout() {
            $('#logoutModal')
                .modal({
                    backdrop: 'static',
                })
                .modal('show')
        },
        transformDate(date) {
            if (this.$i18n.locale === 'en') {
                return moment(date).locale(this.$i18n.locale).format('MM/DD hh:mm A')
            } else {
                return moment(date).locale(this.$i18n.locale).format('MM/DD HH:mm')
            }
        },
    },
    created() {
        this.getUsername()
        // this.getRoomMessage();
        this.getCurrentCheckInDetails()
        this.getDeviceType()
        this.getRoomMessages()
    },
}
</script>

<style>
.ct-bg-icon {
    height: 100px;
    width: 100px;
}
.checkout-btn {
    background-color: #add8e6 !important;
}
.ct-bg-icon-orange {
    background-color: #ffa500 !important;
    height: 100px;
    width: 100px;
}
.ct-fg-icon {
    color: black;
    margin: 0;
    position: absolute;
    top: 50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
.editGuestCard {
    background-color: #ffa500 !important;
    font-weight: bold;
    outline: white;
}
/* .col-auto {
    padding-left: 5px;
} */
.red-color {
    color: red;
}
</style>
