<template>
    <div>

        <!-- form for interacting with the table -->
        <form>
            <div class="form-row align-items-center my-2 px-2">

                <!-- searchbox -->
                <div class="col-3">
                    <label for="appliance-search" class="sr-only" v-text="$t('management.natureRemo.search')" />
                    <div class="input-group">
                        <input id="appliances-search" type="text" v-model.trim="table.filter" class="form-control"
                               :placeholder="$t('management.natureRemo.search')" aria-label="searchbox"
                               aria-describedby="search-icon" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="search-icon">
                                <i class="fa fa-search" aria-hidden="true" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- selectbox for filtering appliances with DEVICE_ID -->
                <div class="col-3">
                    <label for="device-select" class="sr-only" v-text="$t('management.natureRemo.selectDevice')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="device-icon"
                                  v-text="$t('management.natureRemo.device')" />
                        </div>
                        <select id="device-select" v-model.number="selectedDevice" class="custom-select">
                            <option :value="null" selected v-text="$t('management.natureRemo.all')" />
                            <option v-for="(device, index) in devices" :key="index" :value="device.DEVICE_ID"
                                    v-text="device.DEVICE_NAME" />
                        </select>
                    </div>
                </div>

                <!-- selectbox for filtering appliances with APPLIANCE_TYPE -->
                <div class="col-3">
                    <label for="type-select" class="sr-only" v-text="$t('management.natureRemo.selectType')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="type-icon"
                                  v-text="$t('management.natureRemo.applianceType')" />
                        </div>
                        <select id="type-select" v-model.trim="selectedType" class="custom-select">
                            <option :value="null" v-text="$t('management.natureRemo.all')" />
                            <option :value="type.value" v-for="(type, index) in types" :key="index"
                                    v-text="type.label" />
                        </select>
                    </div>
                </div>

                <!-- button for resetting the filters, shows only if filters exist -->
                <div class="col-auto">
                    <button v-show="hasFilters" type="button" class="btn btn-danger" @click="resetFilters">
                        <span class="sr-only" v-text="$t('management.natureRemo.resetFilters')" />
                        <i class="fa fa-times" aria-hidden="true" />
                    </button>
                </div>

                <!-- button for scanning new appliances data -->
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-primary" @click="scan" :disabled="isScanning">
                        <span class="sr-only" v-text="$t('management.natureRemo.scanAppliances')" />
                        <i class="fa fa-refresh" :class="{ 'fa-spin': isScanning }" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </form>

        <!-- table for displaying the appliance data -->
        <b-table id="nature-remo-appliances-table" class="" :fields="fields" :items="filteredByDeviceAndType"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('management.natureRemo.tableNoData')"
                 :empty-text="$t('management.natureRemo.tableNoData')">

            <!-- display detailed appliance type -->
            <template slot="APPLIANCE_TYPE" slot-scope="data">
                <span>{{ types.find(type => type.value === data.item.APPLIANCE_TYPE).label }}</span>
            </template>

        </b-table>

        <!-- pagination for table navigation -->
        <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows" align="center"
                      aria-controls="#nature-remo-appliances-table" v-if="showPagination" />

    </div>
</template>

<script>
export default {
    props: {
        devices: Array,
        appliances: Array,
    },

    data() {
        return {
            table: {
                perPage: 5,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'APPLIANCE_ID',
                sortDesc: false,
                sortDirection: 'asc',
            },
            errors: [],
            isScanning: false,
            selectedType: null,
            selectedDevice: null,
        }
    },

    methods: {
        /**
         * @name scan
         * @description Request to fetch new appliance data from Nature Remo Cloud
         *
         * @returns {void}
         */
        scan() {
            const message = this.$t('management.natureRemo.alert.scanAppliances')
            this.isScanning = true

            axios
                .post('nature_remo_appliances/scan')
                .then(response => {
                    this.$emit('refresh')
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
                    this.isScanning = false
                })
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
            this.selectedType = null
            this.selectedDevice = null
        },
    },

    computed: {
        /**
         * @name fields
         * @description Fields for the appliance table
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        fields: function () {
            const fields = this.$t('management.natureRemo.tableFields')

            return [
                { key: 'APPLIANCE_ID', label: fields.id, isRowHeader: true },
                { key: 'nature_remo_device.room.ROOM_NAME', label: fields.roomName },
                { key: 'nature_remo_device.DEVICE_NAME', label: fields.deviceName },
                { key: 'APPLIANCE_TYPE', label: fields.type },
                { key: 'APPLIANCE_NAME', label: fields.applianceName },
                { key: 'nature_remo_signals_count', label: fields.signalCount },
            ]
        },

        /**
         * @name types
         * @description Options for appliance types
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        types: function () {
            const types = this.$t('management.natureRemo.type')

            return [
                { value: 'AC', label: types.ac },
                { value: 'TV', label: types.tv },
                { value: 'LIGHT', label: types.light },
                { value: 'IR', label: types.ir },
            ]
        },

        /**
         * @name filteredByDevice
         * @description Filter the appliances array with DEVICE_ID and APPLIANCE_TYPE property
         *
         * @returns {object[]}
         */
        filteredByDeviceAndType: function () {
            let appliances = this.appliances

            if (!!this.selectedDevice) {
                appliances = this.appliances.filter(appliance => appliance.DEVICE_ID === this.selectedDevice)
            }

            if (!!this.selectedType) {
                appliances = appliances.filter(appliance => appliance.APPLIANCE_TYPE === this.selectedType)
            }

            this.table.filter = null
            this.table.totalRows = appliances.length
            this.table.currentPage = 1

            return appliances
        },

        /**
         * @name hasFilters
         * @description Determines if there are filters from searchbox and selectbox
         *
         * @returns {boolean}
         */
        hasFilters: function () {
            return !!this.table.filter || !!this.selectedType || !!this.selectedDevice
        },

        showPagination: function () {
            return this.appliances.length > this.table.perPage
        },
    },
}
</script>
