<!--Created by Shannie 2021/06/09 -->
<template>
    <div class="modal fade" id="error-response-modal" tabindex="-1" role="dialog" aria-labelledby="Notification Modal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-width" role="document">
            <div class="modal-content">

                <!-- form for registering device -->
                <div class="modal-header">
                    <h4 class="modal-title text-dark font-weight-bold">
                        {{  $t('management.systemLogs.responseModal.title') + " [" + (!!error_data.MESSAGE_ID ? error_data.MESSAGE_ID.slice(0, 4): '') + "]" }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3 py-3 px-0 text-dark content-body">
                    <div class="w-100 content-font" v-if="!!error_data.room">
                        {{$t('management.systemLogs.responseModal.date') + ":  " + transformDate(error_data.CREATED_AT)}}
                    </div>
                    <div class="w-100 content-font mt-2" v-if="!!error_data.room">
                        {{$t('management.systemLogs.responseModal.room') + ":  " + error_data.room.ROOM_NAME}}
                    </div>
                    <div class="w-100 content-font mt-2">
                        {{$t('management.systemLogs.responseModal.message') + ":  "}}
                    </div>
                    <div class="content-font mt-1 message-box p-3">
                        {{!!error_data.MESSAGE_ID ? (error_data.MESSAGE_ID.slice(5, 15).length > 0 ? transformDateLogs(error_data.MESSAGE_ID.slice(5, 15)): '')+ $t('errorList' + (!!error_data.MESSAGE_ID ? '.' + error_data.MESSAGE_ID.slice(0, 4) : '')  + '.message') : ''}}
                    </div>
                    <div class="w-100 content-font mt-2 text-danger">
                        {{$t('management.systemLogs.responseModal.response') + ":  "}}
                    </div>
                    <div class="content-font mt-1 corrMessage-box p-3">
                        {{$t('errorList' + (!!error_data.MESSAGE_ID ? '.' + error_data.MESSAGE_ID.slice(0, 4) : '')  + '.response')}}
                    </div>
                    <div class="w-100 content-font mt-3" v-if="!error_data">
                        {{$t('management.systemLogs.responseModal.responder') + ":  "+ "-"}}
                    </div>
                    <div class=" w-100 content-font mt-3"
                         v-else-if="!!error_data && error_data.correspondence && error_data.correspondence.CORRESPONDING_PERSON !== '' && error_data.correspondence.CORRESPONDING_PERSON !== null">
                        {{$t('management.systemLogs.responseModal.responder') + ":  " + error_data.correspondence.CORRESPONDING_PERSON}}
                    </div>
                    <div class="w-100 content-font mt-3" v-else>
                        {{$t('management.systemLogs.responseModal.responder') + ":  "+ "-"}}
                    </div>
                    <div class="w-100 content-font mt-3" v-if="!error_data">
                        {{$t('management.systemLogs.responseModal.responseTime') + ":  "+ "-"}}
                    </div>
                    <div class="w-100 content-font mt-3"
                         v-else-if="!!error_data && error_data.correspondence && error_data.correspondence.RESPONSE_TIME !== '' && error_data.correspondence.RESPONSE_TIME !== null">
                        {{$t('management.systemLogs.responseModal.responseTime') + ":  " + (transformDate(error_data.correspondence.RESPONSE_TIME))}}
                    </div>
                    <div class="w-100 content-font mt-3" v-else>
                        {{$t('management.systemLogs.responseModal.responseTime') + ":  "+ "-"}}
                    </div>
                    <button type="button" class="btn btn-danger mt-3" @click="createResponse(error_data.LOGS_NOTIF_ID)"
                            v-if="error_data.EVENT_STATUS === 0">
                        {{  $t('management.systemLogs.responseModal.done')  }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    data() {
        return {
            error_data: [],
        }
    },

    created() {
        this.transformMessage()
    },

    methods: {
        /**
         * @name
         * @desc
         */
        createResponse(logsID) {
            axios
                .post('/logs-notification/create/response', {
                    LOGS_NOTIF_ID: logsID,
                })
                .then(response => {
                    this.$swal({
                        title: this.transformMessage(),
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                    $('#error-response-modal').modal('hide')
                })
                .catch(error => {
                    console.log(error)
                })
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
        transformMessage() {
            let message
            if (this.$i18n.locale === 'ja') {
                message = 'エラーを解決されました'
            } else if (this.$i18n.locale === 'en') {
                message = 'Error has been resolved'
            }
            return message
        },
    },

    mounted() {
        this.$bus.on('error-data', item => {
            this.error_data = item
        })
    },
}
</script>
<style scoped>
.modal-width {
    max-width: 700px !important;
    height: auto !important;
}
.content-body {
    max-height: 350px !important;
    overflow: auto;
}
.content-font {
    font-size: 18px;
    font-weight: bold;
}
.message-box {
    border: solid 2px #000;
    width: 95% !important;
}
.corrMessage-box {
    border: solid 2px red;
    color: red;
    width: 95% !important;
}
</style>
