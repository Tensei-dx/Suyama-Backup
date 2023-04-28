<template>
    <div>

        <!-- form for interacting with the table -->
        <form>
            <div class="form-row align-items-center my-2 px-2">

                <!-- searchbox -->
                <div class="col-3">
                    <label for="signal-search" class="sr-only" v-text="$t('management.natureRemo.search')" />
                    <div class="input-group">
                        <input type="text" id="signals-search" v-model.trim="table.filter" class="form-control"
                               :placeholder="$t('management.natureRemo.search')" aria-label="searchbox"
                               aria-describedby="search-icon" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="search-icon">
                                <span class="sr-only" v-text="$t('management.natureRemo.search')" />
                                <i class="fa fa-search" aria-hidden="true" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- selectbox for filtering signals with APPLIANCE_ID -->
                <div class="col-3">
                    <label for="appliance-select-box" class="sr-only"
                           v-text="$t('management.natureRemo.selectAppliance')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="appliance-icon"
                                  v-text="$t('management.natureRemo.appliance')" />
                        </div>
                        <select id="appliance-select-box" class="custom-select" v-model="selectedAppliance">
                            <option :value="null" selected v-text="$t('management.natureRemo.all')" />
                            <option v-for="(appliance, index) in appliances" :key="index"
                                    :value="appliance.APPLIANCE_ID" v-text="appliance.APPLIANCE_NAME" />
                        </select>
                    </div>
                </div>

                <!-- button for resetting the filters, shows only if filters exists -->
                <div class="col-auto">
                    <button v-show="hasFilters" type="button" class="btn btn-danger" @click="resetFilters">
                        <span class="sr-only" v-text="$t('management.natureRemo.resetFilters')" />
                        <i class="fa fa-times" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </form>

        <!-- table for displaying the signal data -->
        <b-table id="nature-remo-signals-table" class="" :fields="fields" :items="filteredByAppliance"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('management.natureRemo.tableNoData')"
                 :empty-text="$t('management.natureRemo.tableNoData')">

            <!-- insert button for sending the signal in the ACTION column -->
            <template slot="ACTION" slot-scope="data">
                <button class="btn btn-sm btn-primary" @click="send(data)" :disabled="isSending">
                    <span class="text-uppercase" v-text="$t('management.natureRemo.send')" />
                </button>
            </template>
        </b-table>

        <!-- pagination for table navigation -->
        <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows" align="center"
                      aria-controls="#nature-remo-signals-table" v-if="showPagination" />

    </div>
</template>

<script>
export default {
    props: {
        appliances: Array,
        signals: Array,
    },

    data() {
        return {
            table: {
                perPage: 5,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'SIGNAL_ID',
                sortDesc: false,
                sortDirection: 'asc',
            },
            errors: [],
            isSending: false,
            selectedAppliance: null,
        }
    },

    methods: {
        /**
         * @name send
         * @description Request to send the signal via Nature Remo Cloud
         *
         * @returns {void}
         */
        send(data) {
            const message = this.$t('management.natureRemo.alert.sendSignals')
            this.isSending = true

            axios
                .post(`nature_remo_signals/${data.item.SIGNAL_ID}/send`)
                .then(response => {
                    this.$swal({
                        title: message.onSuccess,
                        type: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                })
                .catch(error => {
                    this.errors.push(error)
                    this.$swal({
                        title: message.onFail,
                        type: 'error',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                })
                .then(() => {
                    this.isSending = false
                })
        },

        /**
         * @name onFilteredBySearch
         * @description Event handler for filtering through searchbox
         *
         * @param {object[]} filteredItems = list of filtered items
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
            this.selectedAppliance = null
        },
    },

    computed: {
        /**
         * @name fields
         * @description Fields for the signals table
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        fields: function () {
            const fields = this.$t('management.natureRemo.tableFields')

            return [
                { key: 'SIGNAL_ID', label: fields.id, isRowHeader: true },
                { key: 'nature_remo_appliance.APPLIANCE_NAME', label: fields.applianceName },
                { key: 'SIGNAL_LABEL', label: fields.label },
                { key: 'ACTION', label: fields.action, class: 'text-center' },
            ]
        },

        /**
         * @name filteredByAppliance
         * @description Filter the signals array with APPLIANCE_ID property
         *
         * @returns {object[]}
         */
        filteredByAppliance: function () {
            let signals = this.signals

            if (!!this.selectedAppliance) {
                signals = signals.filter(signal => signal.APPLIANCE_ID === this.selectedAppliance)
            }

            this.table.filter = null
            this.table.totalRows = signals.length
            this.table.currentPage = 1
            return signals
        },

        /**
         * @name hasFilters
         * @description Determines if there are filters in searchbox and selectbox
         *
         * @returns {boolean}
         */
        hasFilters: function () {
            return this.table.filter || this.selectedAppliance
        },

        showPagination: function () {
            return this.signals.length > this.table.perPage
        },
    },
}
</script>
