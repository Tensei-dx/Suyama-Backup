<template>
    <div class="pb-1">
        <form>
            <div class="form-row align-items-center my-2">

                <!-- searchbox -->
                <div class="col-2 m-2 text-center">
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
                            <span class="my-3">{{transformStartDate(startDate)}}</span>
                        </div>
                        <div class="text-center h-date col-auto">
                            <h1 class="mb-1">-</h1>
                        </div>
                        <div class="col-5 text-center h-date py-1">
                            <input class="form-control" type="datetime-local" v-model="endDate" id="date" />
                        </div>
                        <div class="col-2 py-1">
                            <button type="button" class="btn btn-gray" @click="showDefault">
                                {{$t('management.clear')}}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <b-table responsive sticky-header id="logs-table" :fields="fields" :items="items"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('logs.noData')" :empty-text="$t('logs.noData')">

            <template slot="CREATED_AT" slot-scope="data">
                {{ transformDate(data.item.CREATED_AT) }}
            </template>

            <template slot="MESSAGE_ID" slot-scope="data">
                <div v-b-tooltip.click.right.blur tabindex="0" :title="$t('errorList.' + data.item.MESSAGE_ID)">
                    {{ checkTextSize($t('errorList.' + data.item.MESSAGE_ID))}}
                </div>
            </template>
        </b-table>

        <!-- pagination for table navigation -->
        <div v-if="table.totalRows > '5'">
            <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                          align="center" aria-controls="#info-logs-table" />
        </div>
    </div>
</template>

<script>
import moment from 'moment'

export default {
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
         * @name checkTextSize
         * @description Truncate the text
         *
         * @returns {void}
         */
        checkTextSize(details) {
            if (details && details.length > 50) {
                details = details.substring(0, 50) + '...'
            }
            return details
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
        transformStartDate(date) {
            let format
            if (date === null) {
                return null
            }

            if (this.$i18n.locale === 'en') {
                format = 'MM/DD/YYYY HH:mm A'
            } else {
                format = 'YYYY/MM/DD HH:mm'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },
        dateTime() {
            this.endDate = moment().locale(this.$i18n.locale).format('YYYY-MM-DDTHH:mm')
        },

        getLogsNotifications() {
            axios
                .get('logs-notification/get/all', {
                    params: {
                        EVENT_STATUS: [4, 5],
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
                { key: 'MESSAGE_ID', label: fields.message, class: 'text-nowrap w-50' },
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

    watch: {
        endDate: function (value) {
            this.startDate = moment(value).subtract(7, 'days').format('YYYY-MM-DDTHH:mm')
            this.getLogsNotifications()
        },
    },

    mounted() {
        Echo.channel('logsNotification-event').listen('LogsNotificationEvent', value => {
            this.getLogsNotifications()
        })
    },

    beforeDestroy() {
        Echo.leave('logsNotification-event')
    },
}
</script>

<style>
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
