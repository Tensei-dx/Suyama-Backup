<!--
    <System Name>  iBMSfH
    <Program Name> Error.vue
    <Create>       TP Shannie
-->
<template>
    <div class="pb-1">
        <form>
            <div class="form-row align-items-center my-2">

                <!-- searchbox -->
                <div class="col-2 m-2">
                    <label for="date-search" class="sr-only" v-text="$t('management.search')" />
                    <div class="input-group">
                        <input type="text" v-model.trim="table.filter" class="form-control"
                               :placeholder="$t('management.search')" aria-label="searcbox"
                               aria-describedby="search-icon" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="search-icon">
                                <i class="fa fa-search" aria-hidden="true" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- button for resetting the filters, shows only if filters exists -->
                <div class="col-auto">
                    <button v-show="hasFilters" type="button" class="btn btn-danger" @click="resetFilters">
                        <span class="sr-only" v-text="$t('management.resetFilters')" />
                        <i class="fa fa-times" aria-hidden="true" />
                    </button>
                </div>

                <div class="col-8 m-2">
                    <div class="row px-0 w-100">
                        <div class="col-4 pt-2 pb-2 text-center h-date">
                            <span class="my-3">{{ formatStartDate(startDate) }}</span>
                        </div>
                        <div class="text-center h-date col-auto">
                            <h1 class="mb-1">-</h1>
                        </div>
                        <div class="col-5 text-center h-date py-1">
                            <input class="form-control" type="datetime-local" v-model="endDate" id="date" />
                        </div>
                        <div class="col-2 py-1">
                            <button type="button" class=" btn btn-gray" @click="showDefault">
                                {{ $t('management.clear') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table for the Error Logs -->
        <b-table responsive sticky-header id="logs-table" :fields="fields" :items="items"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('logs.noData')" :empty-text="$t('logs.noData')">

            <template slot="CREATED_AT" slot-scope="data">
                {{ formatCreatedAtDate(data.item.CREATED_AT) }}
            </template>

            <template slot="MESSAGE_ID" slot-scope="data">
                <div class="text-nowrap w-50" v-b-tooltip.click.right.blur tabindex="0" :title="messageContent(data)">
                    {{ messageContent(data) }}
                </div>
            </template>

            <!-- insert button for RESPONSE column  -->
            <template slot="RESPONSE" slot-scope="data">
                <!-- {{$t('management.systemLogs.responseModal.responseBtn')}} -->
                <i v-if="data.item.EVENT_STATUS === 0" class="fa fa-exclamation-circle fa-size text-danger"
                   aria-hidden="true" @click="showResponseModal(data.item)" />
                <i v-else-if="data.item.EVENT_STATUS === 1" class="fa fa-check-circle fa-size text-success"
                   aria-hidden="true" @click="showResponseModal(data.item)" />
            </template>
        </b-table>

        <!-- pagination for table navigation -->
        <div v-if="table.totalRows > '5'">
            <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                          align="center" aria-controls="#error-logs-table" />
        </div>
        <!-- modal for displaying the device registration form -->
        <ResponseModal id="error-response-modal" />
    </div>
</template>

<script>
import ResponseModal from './ResponseModal.vue'
import moment from 'moment'
export default {
    components: {
        ResponseModal,
    },

    data() {
        return {
            table: {
                perPage: 5,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'CREATED_AT',
                sortDesc: true,
                sortDirection: 'desc',
                done: false,
            },
            items: [],
            endDate: '',
            startDate: '',
        }
    },

    created() {
        this.dateTime()
    },

    methods: {
        /**
         * @name onFilteredBySearch
         * @description Event handler for filtering through searchbox
         *
         * @param {object[]} filteredItems - list of filtered items
         * @returns {void}
         */
        onFilteredBySearch(filteredItems) {
            this.table.totalRows = filteredItems.length
            this.table.currentPage = 1
        },

        /**
         * @name resetFilters
         * @description Reset the filters in searchbox and selectbox
         *
         * @returns {void}
         */
        resetFilters() {
            this.table.filter = null
        },

        /**
         * @name showResponseModal
         * @description Reset the filters in searchbox and selectbox
         *
         * @param {mixed} item
         * @returns {void}
         */
        showResponseModal(item) {
            this.$bus.emit('error-data', item)
            $('#error-response-modal').modal('show')
        },

        /**
         * @name trimText
         * @description Truncate the text
         *
         * @param {string} text
         * @return {string}
         */
        trimText(text) {
            const LIMIT = this.$i18n.locale === 'ja' ? 30 : 50

            return text && text.length > LIMIT ? text.substring(0, LIMIT) + '...' : text
        },

        /**
         * @name formatMessageDate
         * @description Show formatted check in date if it exists
         *
         * @param {object} data
         * @return {string}
         */
        formatMessageDate(data) {
            const MESSAGE_WITH_DATE = ['E002', 'E003', 'E004', 'E005']

            // guard clause
            if (!MESSAGE_WITH_DATE.includes(data.item.MESSAGE_ID)) return ''

            // check if the reservation is not null
            if (!data.item.reservation || data.item.reservation.booking_rooms.length == 0) return ''

            const CHECK_IN_TIME = data.item.reservation.booking_rooms[0].CHECK_IN_TIME

            // use different format for ja
            const FORMAT = this.$i18n.locale === 'en' ? 'MM/DD/YYYY' : 'YYYY/MM/DD'

            // use created_at timestamp for testing
            return moment(CHECK_IN_TIME).locale(this.$i18n.locale).format(FORMAT)
        },

        /**
         * @name messageContent
         * @description Shows the proper message
         *
         * @param {object} data
         * @return {string}
         */
        messageContent(data) {
            return this.trimText(
                this.formatMessageDate(data) + ' ' + this.$t('errorList.' + data.item.MESSAGE_ID + '.message')
            )
        },

        /**
         * @name formatCreatedAtDate
         * @description Formats the input date
         *
         * @param {string} date
         * @return {string}
         */
        formatCreatedAtDate(date) {
            // guard clause
            if (date === null) return null

            const FORMAT = this.$i18n.locale === 'en' ? 'MM/DD/YYYY hh:mm:ss A' : 'YYYY/MM/DD HH:mm:ss'

            return moment(date).locale(this.$i18n.locale).format(FORMAT)
        },

        /**
         * @name formatStartDate
         * @description Format the Start Date input box
         *
         * @param {string} date
         * @return {string}
         */
        formatStartDate(date) {
            // guard clause
            if (date === null) return null

            const FORMAT = this.$i18n.locale === 'en' ? 'MM/DD/YYYY HH:mm A' : 'YYYY/MM/DD HH:mm'

            return moment(date).locale(this.$i18n.locale).format(FORMAT)
        },

        /**
         *
         */
        dateTime() {
            this.endDate = moment().locale(this.$i18n.locale).format('YYYY-MM-DDTHH:mm')
        },

        /**
         *
         */
        getLogsNotifications() {
            axios
                .get('logs-notification/get/all', {
                    params: {
                        EVENT_STATUS: [0, 1],
                        START_TIME: this.startDate,
                        END_TIME: this.endDate,
                    },
                })
                .then(response => {
                    let temp_items = []
                    temp_items = response.data
                    this.items = temp_items.slice(0, 100)
                })
        },

        /**
         *
         */
        showDefault() {
            this.endDate = moment().locale(this.$i18n.locale).format('YYYY-MM-DDTHH:mm')
        },
    },

    computed: {
        /**
         * @name fields
         * @description Fields for the
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        fields: function () {
            const fields = this.$t('management.systemLogs')

            return [
                // Tentative column name. Column name must be changed according to DB
                { key: 'CREATED_AT', label: fields.date, isRowHeader: true, class: 'text-nowrap w-25' },
                { key: 'room.ROOM_NAME', label: fields.roomName, class: 'text-nowrap w-25' },
                { key: 'MESSAGE_ID', label: fields.message },
                { key: 'RESPONSE', label: '', class: 'text-nowrap w-auto' },
            ]
        },

        /**
         * @name hasFilters
         * @description Determines if there are filters from searchbox
         *
         * @returns {boolean}
         */
        hasFilters: function () {
            return !!this.table.filter
        },
    },

    mounted() {
        Echo.channel('updateErrorLogs-event').listen('UpdateErrorLogsEvent', value => {
            this.getLogsNotifications()
        })

        Echo.channel('logsNotification-event').listen('LogsNotificationEvent', value => {
            this.getLogsNotifications()
        })
    },

    beforeDestroy() {
        Echo.leave('updateErrorLogs-event')
        Echo.leave('logsNotification-event')
    },

    watch: {
        endDate: function (value) {
            this.startDate = moment(value).subtract(7, 'days').format('YYYY-MM-DDTHH:mm')
            this.getLogsNotifications()
        },
    },
}
</script>

<style scoped>
.fa-size {
    font-size: 1.7em !important;
}
.form-control {
    font-size: 16px !important;
}
.btn-gray {
    font-size: 16px !important;
}
.my-3 {
    font-size: 1.2rem !important;
}
</style>
