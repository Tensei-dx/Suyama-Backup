<!-- UPDATE   TDN Chris  SPRINT_08 TASK177 09232021 -->
<template>

<div class="d-flex justify-content-between flex-column h-450">
    <div class="position-relative pt-2 mb-32px">
        <div class="input-group col-sm-6 float-right my-2" style="z-index: 1">
            <input type="text" v-model="tableData.filter" class="form-control" :placeholder="$t('natureIr.search')" />
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"/>
                </span>
            </div>
        </div>
        <div class="col-md-12 px-0">
            <!-- <div class="px-0"> -->
            <div class="px-0 pl-3">
                <b-table
                    fixed
                    select-mode="single"
                    selected-variants="true"
                    :items="filteredOperations"
                    :fields="tableFields"
                    :current-page="tableData.currentPage"
                    :per-page="tableData.perPage"
                    :filter="tableData.filter"
                    :sort-by.sync="tableData.sortBy"
                    :sort-desc.sync="tableData.sortDesc"
                    @row-clicked="showDetails"
                    @filtered="onFiltered"
                >
                    <template slot="ACTION" slot-scope="row">
                        <a @click="deleteOperation(row.item)" class="custom-pointer">
                            <span><i class="fa fa-trash-o fa-lg text-danger"/></span>
                        </a>
                    </template>
                </b-table>
            </div>
        </div>
    </div>
    <div class="position-relative">
        <form>
            <div class="form-row px-3">
                <div class="col-4 mr-auto">
                    <select v-model="selectedAppliance" class="custom-select">
                        <option :value="null" selected>{{ $t('natureIr.operations.filters.appliance.all') }}</option>
                        <option v-for="appliance in appliances" :value="appliance.APPLIANCE_ID" :key="appliance.APPLIANCE_ID">{{ appliance.APPLIANCE_NAME }}</option>
                    </select>
                </div>
                <button @click="createOperation()" type="button" class="btn background-orange mr-3" style="transition: all 0.8s">
                    {{ $t('natureIr.operations.create.button') }}
                </button>
            </div>
        </form>
        <!-- Comment out the pagination function -->
        <!-- <div class="mt-3" :class="operations.length > 40 ? '' : 'custom-pagination-orange'">
            <b-pagination
                v-model="tableData.currentPage"
                align="center"
                :total-rows="tableData.totalRows"
                :per-page="tableData.perPage"
            />
        </div> -->
    </div>
</div>

</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.21
 * @version 1.0.0
 */

export default {
    props: {
        operations: {
            type: Array,
            required: true,
            default: []
        },

        appliances: {
            type: Array,
            required: true,
            default: []
        }
    },

    data() {
        return {
            errors: [],
            tableData: {
                currentPage: 1,
                perPage: 8,
                filter: null,
                sortBy: 'SIGNAL_ID',
                sortDesc: false,
                totalRows: 0
            },
            selectedAppliance: null
        }
    },

    mounted() {
        this.tableData.totalRows = this.operations.length
        this.$bus.on('clearFilters', () => this.clearFilters())
    },

    beforeDestroy() {
        this.$bus.off('clearFilters')
    },

    methods: {
        /**
         * @name createOperation
         * @description Request to show OperationCreateModal
         *
         * @returns {void}
         */
        createOperation() {
            this.$bus.emit('openModal', 'OperationCreateModal')
        },

        /**
         * @name deleteOperation
         * @description Delete the selected operation
         *
         * @param {object} item
         * @returns {void}
         */
        deleteOperation(item) {
            const lang = this.$t('natureIr.operations.delete')
            this.$swal({
                title: lang.title,
                text: lang.message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: lang.confirm,
                cancelButtonText: lang.cancel
            }).then(result => {
                if (result.value) {
                    axios.post('deleteNatureRemoSignal', {
                        SIGNAL_ID: item.SIGNAL_ID
                    })
                    .then(response => {
                        if (response.status >= 200 && response.status < 300) {
                            this.$swal({
                                position: 'center',
                                type: 'success',
                                title: lang.success,
                                showConfirmButton: false,
                                timer: 2000
                            })
                        } else {
                            throw new Error(response.data)
                        }
                    })
                    .catch(error => {
                        this.errors.push(error)
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: lang.fail,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    })
                    .then(() => this.$emit('refreshData'))
                }
            })
        },

        /**
         * @name showDetails
         * @description Get the item information selected in the table
         * @since 1.0.0
         *
         * @param {object} item Item information
         * @returns {void}
         */
        showDetails(item) {
            this.$emit('showDetails', {
                element: 'OperationDetails',
                item: item
            })
        },

        /**
         * @name onFiltered
         * @description This method is executed when a search is done
         * @since 1.0.0
         *
         * @param {object[]} filteredItems Filtered items with the search
         * @returns {void}
         */
        onFiltered(filteredItems) {
            this.$emit('hideDetails')
            this.tableData.totalRows = filteredItems.length
            this.tableData.currentPage = 1
        },

        /**
         * @name clearFilters
         * @description Reset the search and filters
         *
         * @returns {void}
         */
        clearFilters() {
            this.tableData.filter = null
            this.selectedAppliance = null
        }
    },

    computed: {
        /**
         * @name filteredOperations
         * @description List of signals filtered by APPLIANCE_ID
         *
         * @returns {object[]}
         */
        filteredOperations: function () {
            return this.selectedAppliance
            ? this.operations.filter(i => i.APPLIANCE_ID === this.selectedAppliance)
            : this.operations
        },

        /**
         * @name tableFields
         * @description Make the table field headers language-dynamic
         *
         * @returns {object[]}
         */
        tableFields: function () {
            let lang = this.$t('natureIr.operations.table')
            return [
                {
                    key: 'nature_remo_appliance.APPLIANCE_NAME',
                    label: lang.appliance,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'nature_remo_appliance.APPLIANCE_TYPE',
                    label: lang.applianceType,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'SIGNAL_NAME',
                    label: lang.name,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'ACTION',
                    label: lang.action,
                    sortable: true,
                    class: 'text-center'
                }
            ]
        }
    }
}
</script>
