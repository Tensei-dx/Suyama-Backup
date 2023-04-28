<!-- UPDATED: TP Ivin SPRINT_04 TASK125 20210825 -->

<template>
    <div>
        <!-- Title -->
        <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3">
                    {{ $t('management.roomStats.roomStatus') }}
                </span>
            </div>
            <!-- Notification -->
            <div class="col-auto pointer text-center" @click="showNotif()">
                <i class="fa fa-bell-o fa-2x" aria-hidden="true" />
                <span class="fa-stack nav-notifmanagement">
                    <span class="fa fa-circle fa-stack-2x text-orange"></span>
                    <strong class="fa-stack-1x" style="font-size:0.7em">
                        {{ notifCount }}
                    </strong>
                </span>
                <small class="d-block mt-1">{{ $t('mobile.menu.notif') }}</small>
            </div>
        </div>
        <!-- Legend/Indicator for Rooms -->
        <div class="row indicator font-weight-bold pl-2">
            <div class="col-xl-2 text-center d-flex">
                <i class="fa fa-circle fa-2x icon-color-available" aria-hidden="true"></i>
                <span class="mt-1 ml-2">{{ $t('management.roomStats.status.available') }}</span>
            </div>
            <div class="col-xl-2 text-center d-flex">
                <i class="fa fa-circle fa-2x icon-color-reserved" aria-hidden="true"></i>
                <span class="mt-1 ml-2">{{ $t('management.roomStats.status.reserved') }}</span>
            </div>
            <div class="col text-center d-flex">
                <i class="fa fa-circle fa-2x icon-color-checkedin" aria-hidden="true"></i>
                <span class="mt-1 ml-2">{{ $t('management.roomStats.status.checkedIn') }}</span>
            </div>
            <div class="col text-center d-flex">
                <i class="fa fa-circle fa-2x icon-color-checkedout" aria-hidden="true"></i>
                <span class="mt-1 ml-2">{{ $t('management.roomStats.status.checkedOut') }}</span>
            </div>
            <div class="col text-center d-flex">
                <i class="fa fa-circle fa-2x icon-color-unavailable" aria-hidden="true"></i>
                <span class="mt-1 ml-2">{{ $t('management.roomStats.status.unavailable') }}</span>
            </div>
        </div>
        <!-- List of All Rooms  -->
        <div class="row ml-2 mt-4 px-0">
            <div v-for="room in this.rooms" :key="room.ROOM_ID" class="col-xl-2 col-2 room mr-4 px-1 text-center">
                <div class="pointer card room-status-btn-size" :class="checkRoomStatus(room)[0]">
                    <div class="card-body pt-1 pb-1 "
                         @click="$emit('selectedRoom', 'roomDeviceOperation', room.ROOM_NAME, room.ROOM_ID, room)">
                        <b class="btn-room">{{ room.ROOM_NAME }}</b>
                        <div :class="checkRoomMessage(room)[0]">
                            <div>{{ checkRoomMessage(room)[1] }}</div>
                        </div>
                    </div>
                </div>
                <div class="room-checkin-checkout" :class="checkRoomStatus(room)[1]">
                    <div>{{ $t(checkRoomStatusTime(room)[0]) }}</div>
                    <div>{{ checkRoomStatusTime(room)[1] }}</div>
                </div>
            </div>
            <NotifModalDashboard id="notifModal" :room_id="room_id"></NotifModalDashboard>
        </div>
    </div>
</template>

<script>
import NotifModalDashboard from '../Modal/NotifModalDashboard.vue'

export default {
    components: {
        NotifModalDashboard,
    },

    data() {
        return {
            rooms: [],
            room_id: 1,
            notifLists: [],
            notifCount: 0,
            blinkStatus: '',
            notifFirstLoadFlag: true,
            errors: [],
        }
    },

    created() {
        this.getAllRooms()
        this.getNotifications()
    },

    methods: {
        /**
         * @name getAllRooms
         * @desc Get all rooms from the database
         *
         * @returns {void}
         */
        getAllRooms() {
            axios
                .get('getAllRooms')
                .then(res => {
                    if (res.status >= 200 && res.status < 300) {
                        this.rooms = res.data
                    } else {
                        throw new Error(res)
                    }
                })
                .catch(error => this.errors.push(error))
        },

        /**
         * @name getNotifications
         * @desc Get notification from the database
         *
         * @returns {void}
         */
        getNotifications() {
            axios
                .get('client/notifications/' + this.room_id)
                .then(res => {
                    if (res.status >= 200 && res.status < 300) {
                        this.notifLists = []
                        res.data.forEach(item => {
                            if (item.SEEN_FLAG === 0) {
                                this.notifCount += 1
                            }
                            this.notifLists.push(item)
                        })
                    }
                })
                .catch(error => this.errors.push(error))
        },

        /**
         * @name showNotif
         * @description Display the notification modal
         */
        showNotif() {
            $('#notifModal').modal('show')
            // reset count to 0
            this.notifCount = 0
            this.updateNotification()
        },

        /**
         * @name checkRoomStatus
         * @description Update the class of the room
         *
         * @param {Object} room
         * @return {string[]}
         */
        checkRoomStatus(room) {
            switch (room.STATUS_ID) {
                case 201:
                    return ['hotel-black-color-text bg-color-checkedin', 'hotel-white-color-text font-weight-bold']
                case 202:
                    return ['hotel-white-color-text bg-color-checkedout', 'hotel-white-color-text font-weight-bold']
                case 203:
                    return ['hotel-black-color-text bg-color-available', '']
                case 204:
                    return ['hotel-white-color-text bg-color-unavailable', '']
                case 205:
                    return ['hotel-black-color-text bg-color-reserved', 'hotel-white-color-text font-weight-bold']
                default:
                    return ['', '']
            }
        },

        /**
         * @name checkRoomMessage
         * @description Check if there is message
         *
         * @param {Object} room
         * @returns {string[]}
         */
        checkRoomMessage(room) {
            return room.ROOM_MESSAGE === 'NO MESSAGE' ? ['', ''] : ['room-message', room.ROOM_MESSAGE]
        },

        /**
         * @name checkRoomStatusTime
         * @description Check the room status checkin and checkout
         *
         * @param {Object} room
         * @returns {string[]}
         */
        checkRoomStatusTime(room) {
            if (room.bookings_with_book.length === 0) {
                room.STATUS_ID = 203
            }
            switch (room.STATUS_ID) {
                case 201: //check-in
                    return ['management.roomStats.checkOutTime', room.bookings_with_book[0].CHECK_OUT_TIME.slice(0, 16)]
                case 202: //check-out
                    return ['', '']
                case 203: //available
                    return ['', '']
                case 204: //unavailable
                    return ['', '']
                case 205: //reserved
                    return ['management.roomStats.checkInTime', room.bookings_with_book[0].CHECK_IN_TIME.slice(0, 16)]
                default:
                    return ['', '']
            }
        },

        /**
         * @name updateNotifications
         * @description Update notification seen flag on click
         *
         * @returns {void}
         */
        updateNotification() {
            this.notifLists.forEach(item => {
                if (item.SEEN_FLAG === 0) {
                    axios
                        .post('client/updateNotification', {
                            NOTIFICATION_ID: item.NOTIFICATION_ID,
                        })
                        .then(res => {
                            if (res.status >= 200 && res.status < 300 && res.data === 'success') {
                                item.SEEN_FLAG = 1
                            } else {
                                throw new Error(res)
                            }
                        })
                        .catch(error => this.errors.push(error))
                }
            })
        },
    },

    computed: {
        /**
         * @name updateNotifCount
         * @description Update notification count
         *
         * @returns {number}
         */
        updateNotifCount: function () {
            return this.notifLists.length
        },
    },

    mounted() {
        Echo.channel('notification-event').listen('NotificationEvent', value => {
            var data = e.data.NOTIFICATION
            var notifData = ''
            var checkedData = ''
            var recheckData = ''

            if (e.data.NOTIFICATION.ERROR_FLAG == 5) {
                // insert new notification
                this.notifLists.unshift(data)
                // counter for notifications
                this.notifCount += 1

                //[Task 003] 2021/07/12
                //Notification for Low Battery of Remote Lock
                if (e.data.NOTIFICATION.STATUS_ID == null) {
                    return
                }

                // Check in
                if (e.data.NOTIFICATION.STATUS_ID == 201) {
                    // lang = ja
                    if (this.$i18n.locale == 'ja') {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME
                        checkedData = notifData.search('Checked In')
                        // + Sprint04 Task125
                        recheckedData = notifData.search('Late Check Out')
                        // + Sprint04 Task125

                        if (checkedData >= 0) {
                            notifData = notifData.replace('Checked In', 'チェックイン')
                        }
                        // + Sprint04 Task125
                        else if (recheckedData >= 0) {
                            notifData = notifData.replace('Late Check Out', '遅刻 チェックアウト')
                        }
                        // + Sprint04 Task125
                        this.$toast.success(notifData, '通知:', { position: 'topCenter', timeout: 5000 })
                    }

                    // lang = en
                    else {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME
                        checkedData = notifData.search('チェックイン')
                        // + Sprint04 Task125
                        recheckedData = notifData.search('遅刻 チェックアウト')
                        // + Sprint04 Task125

                        if (checkedData >= 0) {
                            notifData = notifData.replace('チェックイン', 'Checked In')
                        }
                        // + Sprint04 Task125
                        else if (recheckedData >= 0) {
                            notifData = notifData.replace('遅刻 チェックアウト', 'Late Check Out')
                        }
                        // + Sprint04 Task125
                        this.$toast.success(notifData, 'Notification:', { position: 'topCenter', timeout: 5000 })
                    }
                }

                //Check out
                if (e.data.NOTIFICATION.STATUS_ID == 202) {
                    //lang = ja
                    if (this.$i18n.locale == 'ja') {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME
                        // search "Checked Out" word
                        checkedData = notifData.search('Checked Out')
                        // search "Trying to recheck-in" word
                        recheckData = notifData.search('Trying to recheck-in')

                        // If checkedData >= 0
                        if (checkedData >= 0) {
                            notifData = notifData.replace('Checked Out', 'チェックアウト')
                        }

                        // If recheckData >= 0
                        else if (recheckData >= 0) {
                            notifData = notifData.replace('Trying to recheck-in', '再チェックインしようとしている')
                        }

                        // create pop-up notification
                        this.$toast.warning(notifData, '通知:', { position: 'topCenter', timeout: 5000 })
                    }
                    // lang = en
                    else {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME
                        // search "Checked Out" word
                        checkedData = notifData.search('チェックアウト')
                        // search "Trying to recheck-in" word
                        recheckData = notifData.search('再チェックインしようとしている')

                        // If checkedData >= 0
                        if (checkedData >= 0) {
                            notifData = notifData.replace('チェックアウト', 'Checked Out')
                        }

                        // If recheckData >= 0
                        else if (recheckData >= 0) {
                            notifData = notifData.replace('Trying to recheck-in', '再チェックインしようとしている')
                        }

                        // create pop-up notification
                        this.$toast.warning(notifData, 'Notification:', { position: 'topCenter', timeout: 5000 })
                    }
                }
                if (e.data.NOTIFICATION.STATUS_ID == 205) {
                    // lang = ja
                    if (this.$i18n.locale == 'ja') {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME

                        checkedData = notifData.search('Late Check In')

                        if (checkedData >= 0) {
                            notifData = notifData.replace('Late Check In', '遅刻　チェックイン')
                        }
                        this.$toast.info(notifData, '通知:', { position: 'topCenter', timeout: 5000 })
                    }

                    //lang = en
                    else {
                        notifData = e.data.NOTIFICATION.OBJECT_NAME
                        checkedData = notifData.search('遅刻　チェックイン')

                        if (checkedData >= 0) {
                            notifData = notifData.replace('遅刻　チェックイン', 'Late Check In')
                        }
                        this.$toast.info(notifData, 'Notification:', { position: 'topCenter', timeout: 5000 })
                    }
                }
                // loop for rooms
                this.rooms.forEach(room => {
                    if (room.ROOM_ID == data.ROOM_ID) {
                        // Update room status
                        room.STATUS_ID = data.STATUS_ID
                    }
                })
            }
        })
    },

    beforeDestroy() {
        Echo.leave('notification-event')
    },
}
</script>
