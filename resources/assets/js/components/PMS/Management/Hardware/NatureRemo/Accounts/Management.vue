<template>
    <div>

        <!-- form for interacting with the table -->
        <form>
            <div class="form-row align-items-center my-2 px-2">

                <!-- searchbox -->
                <div class="col-3">
                    <label for="nature-remo-account-search" class="sr-only"
                           v-text="$t('management.natureRemo.search')" />
                    <div class="input-group">
                        <input type="text" v-model.trim="table.filter" class="form-control"
                               :placeholder="$t('management.natureRemo.search')" aria-label="searcbox"
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
                        <span class="sr-only" v-text="$t('management.natureRemo.resetFilters')" />
                        <i class="fa fa-times" aria-hidden="true" />
                    </button>
                </div>

                <!-- button to show the account registration form modal  -->
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#create-nature-remo-account-form">
                        <span class="sr-only" v-text="$t('management.natureRemo.showCreateAccountFormModal')" />
                        <i class="fa fa-plus" aria-hidden="true" />
                    </button>
                </div>

            </div>
        </form>

        <!-- table for displaying the account data -->
        <b-table id="nature-remo-accounts-table" class="" :fields="fields" :items="accounts"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('management.natureRemo.tableNoData')"
                 :empty-text="$t('management.natureRemo.tableNoData')">

            <!-- add class in ACCESS TOKEN column -->
            <template slot="ACCESS_TOKEN" slot-scope="data">
                <span class="d-inline-block text-truncate text-monospace" style="max-width: 35ch"
                      v-text="data.item.ACCESS_TOKEN" />
            </template>

            <!-- add date formatting for CREATED_AT column -->
            <template slot="CREATED_AT" slot-scope="data">
                {{ transformDate(data.item.CREATED_AT) }}
            </template>

            <!-- insert button for deleting account in the ACTION column -->
            <template slot="ACTION" slot-scope="data">
                <span @click="destroy(data)" class="pointer text-center">
                    <span class="sr-only" v-text="$t('management.natureRemo.deleteAccount')" />
                    <i class="fa fa-trash fa-2x" aria-hidden="true" />
                </span>
            </template>

        </b-table>

        <!-- pagination for table navigation -->
        <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows" align="center"
                      aria-controls="#nature-remo-accounts-table" v-if="showPagination" />

        <!-- modal for displaying the account registration form -->
        <CreateAccountFormModal @refresh="refresh" />
    </div>
</template>

<script>
import CreateAccountFormModal from './ModalCreateForm.vue'
import moment from 'moment'

export default {
    components: {
        CreateAccountFormModal,
    },

    props: {
        accounts: Array,
    },

    data() {
        return {
            table: {
                perPage: 5,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'ACCOUNT_ID',
                sortDesc: false,
                sortDirection: 'asc',
            },
            errors: [],
        }
    },

    methods: {
        /**
         * @name refresh
         * @description Emit event to refresh data in the parent component
         *
         * @returns {void}
         */
        refresh() {
            this.$emit('refresh')
        },

        /**
         * @name destroy
         * @description Delete the account
         *
         * @param {object} data
         * @returns {void}
         */
        destroy(data) {
            const message = this.$t('management.natureRemo.alert.deleteAccount')
            this.isLoading = true

            // display confirmation alert for deleting account
            this.$swal({
                title: message.title,
                text: message.text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: message.confirm,
                cancelButtonText: message.cancel,
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: () => axios.delete(`nature_remo_accounts/${data.item.ACCOUNT_ID}`),
            })
                .then(response => {
                    if (response.value) {
                        this.refresh()
                        this.$swal({
                            title: message.onSuccess,
                            type: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                        })
                    }
                })
                .catch(error => {
                    this.$swal({
                        title: message.onFail,
                        type: 'error',
                        timer: 1500,
                        showConfirmButton: false,
                    })
                })
                .then(() => {
                    this.isLoading = false
                })
        },

        /**
         * @name transformDate
         * @description Customize the format of a date parameter
         *
         * @param {Date} date
         * @returns {Date}
         */
        transformDate(date) {
            let format = ''
            
            if(this.$i18n.locale === 'en'){
                format = 'LLL'
            } else {
                format = 'LL HH:mm'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },

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
    },

    computed: {
        /**
         * @name fields
         * @description Fields for the accounts table
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        fields: function () {
            const fields = this.$t('management.natureRemo.tableFields')

            return [
                { key: 'ACCOUNT_ID', label: fields.id, isRowHeader: true },
                { key: 'ACCOUNT_NAME', label: fields.accountName },
                { key: 'CREATED_AT', label: fields.dateAdded },
                { key: 'nature_remo_devices_count', label: fields.deviceCount },
                { key: 'nature_remo_appliances_count', label: fields.applianceCount },
                { key: 'ACTION', label: fields.action, class: 'text-center' },
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

        showPagination: function () {
            return this.accounts.length > this.table.perPage
        },
    },
}
</script>



