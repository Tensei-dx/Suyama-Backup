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

        <!-- Table for the App Logs -->
        <b-table responsive id="logs-table" :fields="fields" :items="app_logs" :current-page="table.currentPage"
                 :per-page="table.perPage" @filtered="onFilteredBySearch" :filter="table.filter"
                 :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy" :sort-desc.sync="table.sortDesc"
                 :sort-direction="table.sortDirection" show-empty :empty-filtered-text="$t('logs.noData')"
                 :empty-text="$t('logs.noData')">

            <template slot="CREATED_AT" slot-scope="data">
                {{transformDate(data.item.CREATED_AT)}}
            </template>

            <template slot="user.USERNAME" slot-scope="data">
                <div v-b-tooltip.click.right.blur tabindex="0" :title="data.item.user.USERNAME">
                    {{checkTextSize(data.item.user.USERNAME)}}
                </div>
            </template>

            <template slot="CONTENT" slot-scope="data">
                <div v-b-tooltip.click.right.blur tabindex="0" :title="data.item.CONTENT">
                    {{checkTextSize(data.item.CONTENT)}}
                </div>
            </template>
        </b-table>

        <!-- pagination for table navigation -->
        <div v-if="table.totalRows > '5'">
            <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                          align="center" aria-controls="#app-logs-table" />
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        app_logs: Array,
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
            if (details && details.length > 30) {
                details = details.substring(0, 20) + '...'
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
                { key: 'user.USERNAME', label: fields.userInfo, isRowHeader: true, class: 'text-nowrap' },
                { key: 'CREATED_AT', label: fields.date, class: 'text-nowrap' },
                { key: 'EVENT', label: fields.events, class: 'text-nowrap' },
                { key: 'CONTENT', label: fields.content, class: 'text-nowrap' },
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
