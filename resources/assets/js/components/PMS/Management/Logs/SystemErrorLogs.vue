<!--
    <System Name>  iBMSfH
    <Program Name> SystemErrorLogs.vue
    <Create>       TP Shannie
-->
<template>
    <div class="pb-1">
        <form>
            <div class="form-row align-items-center my-2">

                <!-- searchbox -->
                <div class="col-2 m-3">
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

            </div>
        </form>

        <!-- Table for the System Error Logs -->
        <b-table responsive sticky-header id="logs-table" :fields="fields" :items="system_logs"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('logs.noData')" :empty-text="$t('logs.noData')">

            <!-- <template slot="PROCESSING_OBJECT" slot-scope="data">
                <div v-b-tooltip.click.right.blur tabindex="0" :title="data.item.PROCESSING_OBJECT">
                    {{ checkTextSize(data.item.PROCESSING_OBJECT)}}
                </div>
            </template> -->

            <template slot="CREATED_AT" slot-scope="data">
                {{ transformDate(data.item.CREATED_AT) }}
            </template>

            <template slot="PROCESSING_DETAILS" slot-scope="data">
                <div v-b-tooltip.click.right.blur tabindex="0" :title="data.item.PROCESSING_DETAILS">
                    {{ checkTextSize(data.item.PROCESSING_DETAILS)}}
                </div>
            </template>
        </b-table>

        <!-- pagination for table navigation -->
        <div v-if="table.totalRows > '5'">
            <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                          align="center" aria-controls="#system-error-logs-table" />
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        system_logs: Array,
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
                sortDesc: false,
                sortDirection: 'asc',
            },
        }
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
            if (details && details.length > 15) {
                details = details.substring(0, 15) + '...'
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
                { key: 'CREATED_AT', label: fields.date, isRowHeader: true, class: 'text-nowrap' },
                { key: 'user.USERNAME', label: fields.userInfo, class: 'text-nowrap' },
                { key: 'PROCESSING_OBJECT', label: fields.processingObj, class: 'text-nowrap' },
                { key: 'ERROR_CODE', label: fields.errorCode, class: 'text-nowrap' },
                {
                    key: 'PROCESSING_DETAILS',
                    label: fields.processingDetails,
                    class: 'text-nowrap',
                },
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
}
</script>

