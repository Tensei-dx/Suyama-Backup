<!--Created by Shannie 2021/06/09 -->
<template>
    <div class="modal" id="notifModalDashboard" tabindex="-1" role="dialog" aria-labelledby="Notification Modal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h3 class="modal-title text-dark font-weight-bold">{{  $t('notificationCode.notification')  }}</h3>
                    <div class="excess-notifs" v-if="this.logs_notif_count > 100">
                        {{ $t('modalText.notificationsExcess')}}
                    </div>
                    <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close"
                            @click="updateModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black text-left addScrollNotif pt-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="error-type">{{$t('notificationCode.event')}}</th>
                                <th scope="col" style="max-width: 20rem">{{$t('notificationCode.message')}}
                                </th>
                                <th scope="col">{{$t('notificationCode.roomName')}}</th>
                                <th scope="col">{{$t('notificationCode.date')}}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in notifications" :key="item.LOGS_NOTIF_ID">
                                <td :class="tableColor(item)">{{ item.EVENT_TYPE }}</td>
                                <td :class="tableColor(item)" v-b-tooltip.click.right.blur tabindex="0"
                                    :title="(!!item.MESSAGE_ID ? (item.MESSAGE_ID.slice(5, 15).length > 0 ? transformDateLogs(item.MESSAGE_ID.slice(5, 15)): '') + $t('errorList.'+ item.MESSAGE_ID.slice(0, 4) + '.message'): '')"
                                    v-if="item.EVENT_TYPE === 'ERROR'">
                                    {{ checkTextSize((item.MESSAGE_ID.slice(5, 15).length > 0 ? transformDateLogs(item.MESSAGE_ID.slice(5, 15)): '') + $t('errorList' + (!!item.MESSAGE_ID ? '.' + item.MESSAGE_ID.slice(0, 4) : '') + '.message'))}}
                                </td>
                                <td :class="tableColor(item)" v-b-tooltip.click.right.blur tabindex="0"
                                    :title="$t('errorList.'+ item.MESSAGE_ID)" v-if="item.EVENT_TYPE !== 'ERROR'">
                                    {{ checkTextSize($t('errorList.'+ item.MESSAGE_ID))}}</td>
                                <td :class="tableColor(item)">{{ item.ROOM_NAME }}</td>
                                <td :class="tableColor(item)">{{ transformDate(item.DATE) }}</td>
                                <td :class="tableColor(item)">
                                    <i v-if="item.EVENT_STATUS === 0" class="fa fa-exclamation-circle fa-2x text-danger"
                                       aria-hidden="true" @click="showResponseModal(item)" />
                                    <i v-if="item.EVENT_STATUS===1" class="fa fa-check-circle fa-2x text-success"
                                       aria-hidden="true" @click="showResponseModal(item)" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        logs_notif_count: '',
    },
    data() {
        return {
            notifications: [],
        }
    },
    methods: {
        /**
         * @name getNotifications
         * @desc Get notification from the database
         */
        getLogsNotifications() {
            axios
                .get('logs-notification/get/notification', {
                    validateStatus: status => status >= 200 && status < 300,
                })
                .then(response => {
                    let tempnotif = []
                    tempnotif = response.data
                    this.notifications = tempnotif.slice(0, 100)
                })
                .catch(error => {
                    console.log(error)
                })
        },

        updateModal() {
            this.getLogsNotifications()
        },

        transformDate(date) {
            if (this.$i18n.locale === 'en') {
                return moment(date).locale(this.$i18n.locale).format('MM/DD/YYYY hh:mm A')
            } else {
                return moment(date).locale(this.$i18n.locale).format('YYYY/MM/DD HH:mm')
            }
        },

        tableColor(data) {
            if (data.EVENT_TYPE == 'ERROR') {
                return 'table-color-error'
            } else {
                return 'table-color;'
            }
        },
        showResponseModal(item) {
            // this.$bus.emit('error-data', item)
            this.$bus.emit('responseModal', item)
            $('#notifModalDashboard').modal('hide')
        },
        checkTextSize(details) {
            if (details && details.length > 30) {
                details = details.substring(0, 30) + '...'
            }
            return details
        },
        transformDateLogs(date) {
            let format
            if (date === null) {
                return null
            }

            if (this.$i18n.locale === 'en') {
                format = 'MM/DD/YYYY'
            } else {
                format = 'YYYY/MM/DD'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },
        transformDate(date) {
            let format
            if (date === null) {
                return null
            }

            if (this.$i18n.locale === 'en') {
                format = 'MM/DD/YYYY hh:mm:ss A'
            } else {
                format = 'YYYY/MM/DD HH:mm:ss'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },
    },
    created() {
        this.getLogsNotifications()
    },
    mounted() {
        Echo.channel('logsNotification-event').listen('LogsNotificationEvent', value => {
            this.getLogsNotifications()
        })

        Echo.channel('updateErrorLogs-event').listen('UpdateErrorLogsEvent', value => {
            this.getLogsNotifications()
        })
    },

    beforeDestroy() {
        Echo.leave('logsNotification-event')
        Echo.leave('updateErrorLogs-event')
    },
}
</script>

<style>
.table-color-error {
    background-color: pink;
}
.table-color {
    background-color: white;
}
.excess-notifs {
    background-color: transparent;
    padding-top: 12px;
    color: red;
    margin-left: auto;
    font-weight: 700;
}
.error-type {
    width: 7rem !important;
    max-width: 7rem !important;
}
</style>
