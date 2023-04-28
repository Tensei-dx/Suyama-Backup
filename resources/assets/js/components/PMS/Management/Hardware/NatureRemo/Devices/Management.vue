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
                               :placeholder="$t('management.natureRemo.search')" aria-label="searchbox"
                               aria-describedby="search-icon" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="search-icon">
                                <i class="fa fa-search" aria-hidden="true" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- selectbox for filtering devices with APPLIANCE_ID -->
                <div class="col">
                    <label for="nature-remo-account-select" class="sr-only"
                           v-text="$t('management.natureRemo.selectAccount')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="account-icon"
                                  v-text="$t('management.natureRemo.account')" />
                        </div>
                        <select id="nature-remo-account-select" v-model.number="selectedAccount" class="custom-select">
                            <option :value="null" v-text="$t('management.natureRemo.all')" />
                            <option v-for="account in accounts" :key="account.ACCOUNT_ID" :value="account.ACCOUNT_ID"
                                    v-text="account.ACCOUNT_NAME" />
                        </select>
                    </div>
                </div>

                <!-- selectbox for filtering devices with ROOM_ID -->
                <div class="col">
                    <label for="nature-remo-room-select" class="sr-only"
                           v-text="$t('management.natureRemo.selectRoom')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="room-icon"
                                  v-text="$t('management.natureRemo.room')" />
                        </div>
                        <select id="nature-remo-room-select" v-model.number="selectedRoom" class="custom-select">
                            <option :value="null" v-text="$t('management.natureRemo.all')" />
                            <option v-for="room in rooms" :key="room.ROOM_ID" :value="room.ROOM_ID"
                                    v-text="room.ROOM_NAME" />
                        </select>
                    </div>
                </div>

                <!-- selectbox for filtering devices with REG_FLAG -->
                <div class="col">
                    <label for="nature-remo-status-select" class="sr-only"
                           v-text="$t('management.natureRemo.selectStatus')" />
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1" id="account-icon"
                                  v-text="$t('management.natureRemo.registerStatus')" />
                        </div>
                        <select id="nature-remo-status-select" v-model="selectedStatus" class="custom-select">
                            <option :value="null" v-text="$t('management.natureRemo.all')" />
                            <option v-for="(status, index) in statuses" :key="index" :value="status"
                                    v-text="status.label" />
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

                <!-- button for scanning new device data -->
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-primary" @click="scan" :disabled="isScanning">
                        <span class="sr-only" v-text="$t('management.natureRemo.scanDevices')" />
                        <i class="fa fa-refresh" :class="{ 'fa-spin': isScanning }" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </form>

        <!-- table for displaying the device data -->
        <b-table id="nature-remo-devices-table" class="" :fields="fields" :items="filteredByAccountRoomStatus"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('management.natureRemo.tableNoData')"
                 :empty-text="$t('management.natureRemo.tableNoData')">

            <!-- display serial number in a monospaced font -->
            <template slot="DEVICE_SERIAL_NO" slot-scope="data">
                <span class="text-monospace" v-text="data.item.DEVICE_SERIAL_NO" />
            </template>

            <!-- insert icon for REG_FLAG column -->
            <template slot="REG_FLAG" slot-scope="data">
                <div class="text-center">
                    <i class="fa fa-2x"
                       :class="data.item.REG_FLAG ? 'fa-check-circle text-success' : 'fa-times-circle text-danger'"
                       aria-hidden="true" />
                </div>
            </template>

            <!-- insert button for registering device in the ACTION column  -->
            <template slot="ACTION" slot-scope="data">
                <span class="pointer text-center" @click="showRegisterModal(data)">
                    <span class="sr-only" v-text="$t('management.natureRemo.showRegisterDeviceModal')" />
                    <i class="fa fa-2x" :class="data.item.REG_FLAG ? 'fa-edit' : 'fa-plus'" aria-hidden="true" />
                </span>
            </template>

        </b-table>

        <!-- pagination for table navigation -->
        <b-pagination v-model.number="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                      align="center" aria-controls="#nature-remo-devices-table" v-if="showPagination" />

        <!-- modal for displaying the device registration form -->
        <RegisterDeviceFormModal :device="deviceToRegister" :rooms="rooms" @refresh="refresh" />
    </div>
</template>

<script>
import RegisterDeviceFormModal from './ModalRegisterForm.vue'

export default {
    components: {
        RegisterDeviceFormModal,
    },

    props: {
        accounts: Array,
        devices: Array,
        rooms: Array,
    },

    data() {
        return {
            table: {
                perPage: 5,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'DEVICE_ID',
                sortDesc: false,
                sortDirection: 'asc',
            },
            errors: [],
            isScanning: false,
            selectedAccount: null,
            selectedRoom: null,
            selectedStatus: null,
            deviceToRegister: null,
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
            this.deviceToRegister = null
            this.$emit('refresh')
        },

        /**
         * @name scan
         * @description Request to fetch new devoce data from Nature Remo Cloud
         *
         * @returns {void}
         */
        scan() {
            const message = this.$t('management.natureRemo.alert.scanDevices')
            this.isScanning = true

            axios
                .post('nature_remo_devices/scan')
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
         * @name showRegisterModal
         * @description Displays the register modal
         *
         * @returns {void}
         */
        showRegisterModal(data) {
            this.deviceToRegister = data.item
            $('#register-nature-remo-device-form.modal').modal('show')
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
            this.selectedAccount = null
            this.selectedRoom = null
            this.selectedStatus = null
        },
    },

    computed: {
        /**
         * @name fields
         * @description Fields for the device table
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */
        fields: function () {
            const fields = this.$t('management.natureRemo.tableFields')

            return [
                { key: 'DEVICE_ID', label: fields.id, isRowHeader: true },
                { key: 'nature_remo_account.ACCOUNT_NAME', label: fields.accountName },
                { key: 'DEVICE_SERIAL_NO', label: fields.serialNumber },
                { key: 'DEVICE_NAME', label: fields.deviceName },
                { key: 'room.ROOM_NAME', label: fields.roomName },
                { key: 'REG_FLAG', label: fields.regFlag },
                { key: 'ACTION', label: fields.action, class: 'text-center' },
            ]
        },

        /**
         * @name statuses
         * @description Options for the status select box
         *              Use computed property to allow multilanguage support
         *
         * @returns {object[]}
         */
        statuses: function () {
            const status = this.$t('management.natureRemo.status')
            return [
                { label: status.registered, value: true },
                { label: status.unregistered, value: false },
            ]
        },

        /**
         * @name filteredByAccountRoomStatus
         * @description Filter the devices array with APPLIANCE_ID, ROOM_ID and REG_FLAG property
         *
         * @returns {object[]}
         */
        filteredByAccountRoomStatus: function () {
            let devices = this.devices

            if (!!this.selectedAccount) {
                devices = devices.filter(device => device.ACCOUNT_ID === this.selectedAccount)
            }

            if (!!this.selectedRoom) {
                devices = devices.filter(device => device.ROOM_ID === this.selectedRoom)
            }

            if (!!this.selectedStatus) {
                devices = devices.filter(device => device.REG_FLAG === this.selectedStatus.value)
            }

            this.table.filter = null
            this.table.totalRows = devices.length
            this.table.currentPage = 1

            return devices
        },

        /**
         * @name hasFilters
         * @description Determines if there are filters from searchbox and selectbox
         *
         * @returns {boolean}
         */
        hasFilters: function () {
            return !!this.table.filter || !!this.selectedAccount || !!this.selectedRoom || !!this.selectedStatus
        },

        showPagination: function () {
            return this.devices.length > this.table.perPage
        },
    },
}
</script>

