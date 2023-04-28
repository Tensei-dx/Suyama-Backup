<!-- UPDATE   TDN Chris  SPRINT_08 TASK177 09232021 -->
<template>

<div class="d-flex justify-content-between flex-column h-450">
    <div class="position-relative pt-2 mb-32px">
        <div class="input-group col-sm-6 float-right my-2" style="z-index: 1">
            <input type="text" v-model="tableData.filter" class="form-control" :placeholder="$t('natureIr.search')">
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
                    :items="appliances"
                    :fields="tableFields"
                    :current-page="tableData.currentPage"
                    :per-page="tableData.perPage"
                    :filter="tableData.filter"
                    :sort-by.sync="tableData.sortBy"
                    :sort-desc.sync="tableData.sortDesc"
                    @row-clicked="showDetails"
                    @filtered="onFiltered"
                    >
                    <template slot="NAME" slot-scope="row">
                        {{ row.item.NAME }}
                    </template>
                    <template slot="ACTION" slot-scope="row">
                        <a @click="deleteAppliance(row.item)" class="custom-pointer">
                            <span><i class="text-danger fa fa-trash-o fa-lg"/></span>
                        </a>
                    </template>
                </b-table>
            </div>
        </div>
    </div>
    <div class="position-relative">
        <form>
            <div class="form-row px-3">
                <button @click="createAppliance()" type="button" class="btn background-orange ml-auto mr-3" style="transition: all 0.8s">
                    {{ $t('natureIr.appliances.create.button') }}
                </button>
            </div>
        </form>
        <!-- Comment out the pagination function -->
        <!-- <div class="mt-3" :class="appliances.length > 40 ? '' : 'custom-pagination-orange'">
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
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.09
 * @version 1.0.0
 */

export default {
    props: {
        appliances: {
            required: true,
            type: Array,
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
                sortBy: 'APPLIANCE_NAME',
                sortDesc: false,
                totalRows: 0
            }
        }
    },

    mounted() {
        this.tableData.totalRows = this.appliances.length
        this.$bus.on('clearFilters', () => this.clearFilters())
    },

    beforeDestroy() {
        this.$bus.off('clearFilters')
    },

    methods: {
        /**
         * @name createAppliance
         * @description Request to show ApplianceCreateModal
         *
         * @returns {void}
         */
        createAppliance() {
            this.$bus.emit('openModal', 'ApplianceCreateModal')
        },

        /**
         * @name deleteAppliance
         * @description Delete the selected appliances
         *
         * @param {object} item Appliance information
         * @returns {void}
         */
        deleteAppliance(item) {
            this.$swal({
                title: this.$t('natureIr.appliances.delete.title'),
                text: this.$t('natureIr.appliances.delete.message'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: this.$t('natureIr.appliances.delete.confirm'),
                cancelButtonText: this.$t('natureIr.appliances.delete.cancel')
            }).then(result => {
                if (result.value) {
                    axios.post('deleteNatureRemoAppliance', {
                        APPLIANCE_ID: item.APPLIANCE_ID
                    })
                    .then(response => {
                        if (response.status >= 200 && response.status < 300) {
                            this.$swal({
                                position: 'center',
                                type: 'success',
                                title: this.$t('natureIr.appliances.delete.success'),
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
                            title: this.$t('natureIr.appliances.delete.fail'),
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
                element: 'ApplianceDetails',
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
        }
    },

    computed: {
        /**
         * @name tableFields
         * @description Make the table field headers language-dynamic
         *
         * @returns {object[]}
         */
        tableFields: function () {
            let lang = this.$t('natureIr.appliances.table')
            return [
                {
                    key: 'APPLIANCE_NAME',
                    label: lang.name,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'APPLIANCE_TYPE',
                    label: lang.type,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'BRAND_NAME',
                    label: lang.brand,
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
