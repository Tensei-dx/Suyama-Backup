<template>
    <div class="container text-center">

        <!-- HEADER -->
        <div class="d-flex align-items-center mt-2 mb-1">
            <!-- Title -->
            <span class="h1 mr-auto font-weight-bold pl-3" v-text="$t('management.roomStats.roomStatus')" />

            <!-- Skype button -->
            <div class="mx-2">
                <button class="btn btn-refresh" @click="goTo(skype.link,skype.fallback)">
                    <i class="fa fa-phone fa-2x" aria-hidden="true" />
                    <span class="d-block mt-1 text-center" v-text="$t('mobile.menu.phone')" />
                </button>
            </div>

            <!-- Sync button -->
            <div class="mx-2">
                <button class="btn btn-refresh" @click="sync" :disabled="isSyncing">
                    <i class="fa fa-refresh fa-2x" :class="{ 'fa-spin': isSyncing }" aria-hidden="true" />
                    <span class="d-block mt-1 text-center" v-text="$t('mobile.menu.synch')" />
                </button>
            </div>

            <!-- Notification -->
            <span @click="showNotif" class="pointer text-center">
                <i class="fa fa-bell fa-2x" aria-hidden="true" />
                <i v-if="this.logs_notif_count >= 1" class="fa fa-circle notif-circle fa-1x text-red">
                    <span class="num text-center" :style="notifCounter" v-text="notif_count" />
                </i>
                <span class="d-block mt-1 w-78px" v-text="$t('mobile.menu.notif')" />
            </span>
        </div>

        <!-- LEGENDS -->
        <div class="row indicator font-weight-bold pl-2">
            <div class="col-4 d-flex text-center" v-for="(legend, index) in legends" :key="index">
                <i class="fa fa-circle fa-2x" :class="legend.color" aria-hidden="true" />
                <span class="mt-1 ml-2" v-text="$t(legend.text)" />
            </div>
        </div>

        <!-- ROOM GRID -->
        <div class="row ml-2 mt-4 px-0 room-grid">
            <template v-for="room in rooms">
                <div :key="room.ROOM_ID" :class="heightClass(room.STATUS_ID)">

                    <!-- ROOM CARD -->
                    <div class="card pointer room-status-btn-size" :class="getRoomClass(room.STATUS_ID)">
                        <div class="card-body py-1 room-card" @click="selectRoom(room)">
                            <b class="btn-room" v-text="room.ROOM_NAME" />
                        </div>
                    </div>

                    <!-- ROOM MESSAGE -->
                    <div class="room-checkin-checkout hotel-white-color-text font-weight-bold">
                        <div v-text="$t(getRoomStatus(room.STATUS_ID))" />
                        <div v-text="getBookingSchedule(room)" />
                        <div v-if="room.STATUS_ID === 201 && !!room.booking_now && room.booking_now.MESSAGE_ID !== 1"
                             class="mb-5">
                            <span class="message-icon text-center exclamation-style">
                                <div>
                                    <i class="fa fa-exclamation-circle" aria-hidden="true" />
                                    {{ $t(room.booking_now.room_message.MESSAGE) }}
                                </div>
                            </span>
                        </div>
                    </div>

                </div>
            </template>

            <NotifModalDashboard id="notifModalDashboard" :logs_notif_count="logs_notif_count" />
            <NotifResponseModal id="responseModal" class="text-left" />
        </div>

        <!-- ERROR toast -->
        <div v-if="errorLogs.length > 0" :class="errorNotifBox()">
            <div class="alert alert-danger content-ja text-center d-flex" role="alert">
                <i class="fa fa-exclamation-circle fa-2x text-danger"></i>
                <span class="mx-2 py-1 text-black font-weight-bold text-left">{{$t('errorMessage')}}</span>
            </div>
        </div>
    </div>
</template>

<script>
import NotifModalDashboard from '../Modal/NotifModalDashboard.vue'
import NotifResponseModal from '../Modal/NotifResponseModal.vue'
import moment from 'moment'

export default {
    components: {
        NotifModalDashboard,
        NotifResponseModal,
    },

    props: {
        rooms: Array,
        locale: '',
    },

    data() {
        return {
            legends: [
                { color: 'icon-color-available-rs', text: 'management.roomStats.status.available' },
                { color: 'icon-color-reserved-rs', text: 'management.roomStats.status.reserved' },
                { color: 'icon-color-checkedin-rs', text: 'management.roomStats.status.checkedIn' },
                { color: 'icon-color-checkedout-rs', text: 'management.roomStats.status.checkedOut' },
                { color: 'icon-color-unavailable', text: 'management.roomStats.status.unavailable' },
            ],
            cards: [
                { status: 201, class: 'hotel-black-color-text bg-color-checkedin-rs' },
                { status: 202, class: 'hotel-black-color-text bg-color-checkedout-rs' },
                { status: 203, class: 'hotel-black-color-text bg-color-available-rs' },
                { status: 204, class: 'hotel-white-color-text bg-color-unavailable' },
                { status: 205, class: 'hotel-black-color-text bg-color-reserved-rs' },
            ],
            room_id: 1,
            errors: [],
            logs_notifs: [],
            logs_notif_count: 0,
            isSyncing: false,
            logs: [],
            item: [],
            userAgent: '',
            skype: {
                link: 'ibmscontroller://skypeadmin',
                fallback: 'https://www.skype.com/',
            },
        }
    },

    created() {
        this.getLogsNotifications()
        this.getDeviceType()
    },

    methods: {
        /**
         * @name sync
         * @description Fetch and pull the latest room and reservation data from Staysee API
         *
         * @returns {void}
         */
        sync() {
            this.isSyncing = true
            axios
                .post('staysee_rooms/sync')
                .then(response => {
                    console.log('sync room success')
                    this.$emit('refreshRooms')
                })
                .catch(error => console.log('sync room failed'))
                .then(() => axios.post('syncReservations'))
                .then(response => console.log('sync reservation success'))
                .catch(error => {
                    console.log('sync reservation failed')
                    console.log(error)
                })
                .then(() => (this.isSyncing = false))
        },

        /**
         * @name selectRoom
         * @fires roomSelect When a room is selected
         */
        selectRoom(room) {
            this.$emit('roomSelect', room)
        },

        /**
         * @name showNotif
         * @description Display the notification modal
         *
         * @returns {void}
         */
        showNotif() {
            $('#notifModalDashboard').modal({
                backdrop: 'static',
                keyboard: false,
            })
            this.updateNotification()
        },

        /**
         * @name updateNotifications
         * @description Update notification seen flag on click
         *
         * @returns {void}
         */
        updateNotification() {
            const STATUS = [2, 4]
            this.logs_notifs.forEach(item => {
                if (STATUS.includes(item.EVENT_STATUS)) {
                    axios
                        .post('/logs-notification/update/event-status', {
                            LOGS_NOTIF_ID: item.LOGS_NOTIF_ID,
                        })
                        .then(response => this.getLogsNotifications())
                        .catch(error => this.errors.push(error))
                }
            })
        },

        /**
         * @name getRoomClass
         * @description Get the class for the room status
         *
         * @param {int} status
         * @returns {string}
         */
        getRoomClass(status) {
            return this.cards.find(card => card.status === status).class
        },

        /**
         * @name getBookingSchedule
         * @description Get the schedule that will be displayed in the room status
         *
         * @param {object} room
         * @returns {string}
         */
        getBookingSchedule(room) {
            if (room.STATUS_ID === 202) return ''
            if (room.STATUS_ID === 203) return ''
            if (room.STATUS_ID === 204) return ''

            let schedule = ''
            let book = this.selectBooking(room)

            switch (room.STATUS_ID) {
                case 201:
                    schedule = book.DEPARTURE_TIME
                    break
                case 205:
                    schedule = book.ARRIVAL_TIME
                    break
                default:
                    return ''
            }

            const FORMAT = this.$i18n.locale === 'en' ? 'MM/DD/YYYY hh:mm A' : 'YYYY/MM/DD HH:mm'
            return moment(schedule).locale(this.$i18n.locale).format(FORMAT)
        },

        /**
         * @name selectBooking
         * @description Return which booking should be displayed
         *
         * @param {object} room
         * @returns {string}
         */
        selectBooking(room) {
            const CURRENTLY_BOOKED = room.booking_now
            const HAS_CHECK_OUT_TODAY = room.check_out_today
            const HAS_CHECK_IN_TODAY = room.check_in_today

            if (!HAS_CHECK_OUT_TODAY && !!HAS_CHECK_IN_TODAY) {
                return room.check_in_today
            } else if (!!HAS_CHECK_OUT_TODAY && !HAS_CHECK_IN_TODAY) {
                return !!CURRENTLY_BOOKED ? room.booking_now : ''
            } else if (!!HAS_CHECK_OUT_TODAY && !!HAS_CHECK_IN_TODAY) {
                return !!CURRENTLY_BOOKED ? room.booking_now : room.check_in_today
            } else {
                return !!CURRENTLY_BOOKED ? room.booking_now : ''
            }
        },

        /**
         * @name getRoomStatus
         * @description Get the message that will be displayed in the room status
         *
         * @param {status} status
         * @returns {string}
         */
        getRoomStatus(status) {
            let statusName = ''
            switch (status) {
                case 201:
                    statusName = 'management.roomStats.departureTime'
                    break
                case 205:
                    statusName = 'management.roomStats.arrivalTime'
                    break
            }
            return statusName
        },

        getLogsNotifications() {
            axios
                .get('logs-notification/get/notification', {
                    validateStatus: status => status >= 200 && status < 300,
                })
                .then(response => {
                    this.logs_notifs = response.data
                    this.logs_notif_count = this.logs_notifs.length
                })
                .catch(error => {
                    this.errors.push(error)
                })
        },

        errorNotifBox() {
            if (this.$i18n.locale === 'ja') {
                return 'error-notif-ja text-center'
            } else if (this.$i18n.locale === 'en') {
                return 'error-notif-en text-center'
            }
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

        goTo(link, fallback) {
            if (this.userAgent !== 'desktop') {
                window.location = link
            } else {
                window.open(fallback)
            }
        },

        heightClass(data) {
            if (data === 201 || data === 202 || data === 205) {
                return 'room col-2 ml-3 mr-5 px-1 py-0 text-center card-room'
            } else {
                return 'room col-2 ml-3 mr-5 px-1 py-0 text-center card-room-without-text'
            }
        },
    },

    computed: {
        notifCounter: function () {
            if (this.logs_notif_count < 10) {
                return 'right:7px !important;'
            } else if (this.logs_notif_count > 9 && this.logs_notif_count < 100) {
                return 'right:2px !important; font-size:15px; top:6px;'
            } else if (this.logs_notif_count > 99) {
                return 'font-size:11px !important; top:8px !important; right:-1px !important;'
            }
        },

        notif_count: function () {
            if (this.logs_notif_count < 100) {
                return this.logs_notif_count
            } else return '99+'
        },

        errorLogs: function () {
            return this.logs_notifs.filter(log => log.EVENT_STATUS === 0)
        },
    },

    mounted() {
        this.$bus.on('responseModal', item => {
            // this.item = item
            this.$bus.emit('error-data', item)
            $('#responseModal').modal('show')
        })

        Echo.channel('updateErrorLogs-event').listen('UpdateErrorLogsEvent', value => {
            this.getLogsNotifications()
        })

        Echo.channel('logsNotification-event').listen('LogsNotificationEvent', value => {
            this.getLogsNotifications()
        })
    },

    beforeDestroy() {
        Echo.leave('logsNotification-event')
        Echo.leave('updateErrorLogs-event')
    },
}
</script>

<style scoped>
.room-grid {
    height: 365px;
    max-height: 365px;
    overflow: auto;
    margin-bottom: 10px;
}
.btn-refresh {
    background-color: transparent !important;
    color: #fff !important;
}
.message-icon {
    border-bottom: 1px solid #cc0000;
}
.exclamation-style {
    color: #cc0000 !important ;
}

.notif-circle {
    position: absolute;
    font-size: 2em;
    top: 7px;
    color: red;
    right: 29px;
}
span.num {
    position: absolute;
    font-size: 16px;
    top: 5px;
    color: #fff;
    font-weight: 700;
}
.room-card {
    overflow: hidden;
}
.error-notif-ja {
    max-height: 10px !important;
    margin: 0 auto !important;
    max-width: 55% !important;
}
.error-notif-en {
    max-height: 10px !important;
    margin: 0 auto !important;
    max-width: 65% !important;
}
.container {
    position: relative;
}
.content {
    border-radius: 10px;
    color: #cc0000;
    width: auto;
    margin: 0 auto !important;
    border: 1px solid gainsboro;
    position: relative;
}
.card-room {
    height: 130px !important;
}
.card-room-without-text {
    height: 60px !important;
}
.text-red {
    color: red;
}
.w-78px {
    width: 78px;
}
</style>
