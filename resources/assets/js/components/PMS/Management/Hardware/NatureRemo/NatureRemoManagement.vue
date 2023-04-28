<template>
    <div>
        <div class="row my-3">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3 text-uppercase text-white">
                    {{ $t('management.natureRemo.title') }}
                </span>
            </div>
        </div>

        <div>
            <!-- tab list -->
            <ul id="nature-remo-management-tab" class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="nature-remo-accounts-tab" class="nav-link active" href="#nature-remo-accounts" role="tab"
                       data-toggle="tab" aria-controls="nature-remo-accounts" aria-selected="true"
                       v-text="$t('management.natureRemo.tabTitleAccounts')" />
                </li>
                <li class="nav-item">
                    <a id="nature-remo-devices-tab" class="nav-link" href="#nature-remo-devices" role="tab"
                       data-toggle="tab" aria-controls="nature-remo-devices" aria-selected="false"
                       v-text="$t('management.natureRemo.tabTitleDevices')" />
                </li>
                <li class="nav-item">
                    <a id="nature-remo-appliances-tab" class="nav-link" href="#nature-remo-appliances" role="tab"
                       data-toggle="tab" aria-controls="nature-remo-appliances" aria-selected="false"
                       v-text="$t('management.natureRemo.tabTitleAppliances')" />
                </li>
                <li class="nav-item">
                    <a id="nature-remo-signals-tab" class="nav-link" href="#nature-remo-signals" role="tab"
                       data-toggle="tab" aria-controls="nature-remo-signals" aria-selected="false"
                       v-text="$t('management.natureRemo.tabTitleSignals')" />
                </li>
            </ul>

            <!-- tab contents -->
            <div class="tab-content nature-tab mt-2" id="nature-remo-management-tab-content">
                <div class="tab-pane fade show active py-1" id="nature-remo-accounts" role="tabpanel">
                    <AccountManagement :accounts="accounts" @refresh="refresh" />
                </div>
                <div class="tab-pane fade py-1" id="nature-remo-devices" role="tabpanel">
                    <DeviceManagement :accounts="accounts" :devices="devices" :rooms="rooms" @refresh="refresh" />
                </div>
                <div class="tab-pane fade py-1" id="nature-remo-appliances" role="tabpanel">
                    <ApplianceManagement :devices="devices" :appliances="appliances" @refresh="refresh" />
                </div>
                <div class="tab-pane fade py-1" id="nature-remo-signals" role="tabpanel">
                    <SignalManagement :appliances="appliances" :signals="signals" @refresh="refresh" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AccountManagement from './Accounts/Management.vue'
import DeviceManagement from './Devices/Management.vue'
import ApplianceManagement from './Appliances/Management.vue'
import SignalManagement from './Signals/Management.vue'

export default {
    components: {
        AccountManagement,
        DeviceManagement,
        ApplianceManagement,
        SignalManagement,
    },

    data() {
        return {
            accounts: [],
            devices: [],
            appliances: [],
            signals: [],
            rooms: [],
        }
    },

    created() {
        this.refresh()
    },

    mounted() {
        this.resetAllFilters()

        Echo.channel('nature_remo_appliances').listen('NatureRemoApplianceStateUpdated', e => {
            console.log(e.appliance)
        })
    },
    beforeDestroy() {
        Echo.leaveChannel('nature_remo_appliances')
    },

    methods: {
        /**
         * @name getAccounts
         * @description Retrieve the list of accounts
         *
         * @returns {void}
         */
        async getAccounts() {
            const response = await axios.get('nature_remo_accounts', {
                params: {
                    show_devices_count: true,
                    show_devices: false,
                    show_appliances_count: true,
                    show_appliances: false,
                },
            })
            this.accounts = response.data
        },

        /**
         * @name getDevices
         * @description Retrieve the list of nature remo devices
         *
         * @returns {void}
         */
        async getDevices() {
            const response = await axios.get('nature_remo_devices', {
                params: {
                    show_account: true,
                    show_room: true,
                    show_appliances_count: false,
                    show_appliances: false,
                    show_signals_count: false,
                    show_signals: false,
                },
            })
            this.devices = response.data
        },

        /**
         * @name getAppliances
         * @description Retrieve the list of nature remo appliances
         *
         * @returns {void}
         */
        async getAppliances() {
            const response = await axios.get('nature_remo_appliances', {
                params: {
                    show_device: false,
                    show_device_room: true,
                    show_signals_count: true,
                    show_signals: false,
                },
            })
            this.appliances = response.data
        },

        /**
         * @name getSignals
         * @description Retrive the list of nature remo signals
         *
         * @returns {void}
         */
        async getSignals() {
            const response = await axios.get('nature_remo_signals', {
                params: {
                    show_appliance: true,
                },
            })
            this.signals = response.data
        },

        /**
         * @name getRooms
         * @description Retrieve the list of rooms
         *
         * @returns {void}
         */
        async getRooms() {
            const response = await axios.get('getRoomAll')
            this.rooms = response.data
        },

        /**
         * @name refresh
         * @description Refresh all data
         *
         * @returns {void}
         */
        refresh() {
            this.getAccounts()
            this.getDevices()
            this.getAppliances()
            this.getSignals()
            this.getRooms()
        },

        /**
         * @name resetFilters
         * @description Reset filters in children components when switching tabs
         *
         * @returns {void}
         */
        resetAllFilters() {
            $('a.nav-link').on('hidden.bs.tab', event => {
                this.$children.forEach(child => child.resetFilters())
            })
        },
    },
}
</script>

<style scoped>
.mt-10 {
    margin-top: 10px;
}
.-mb-15 {
    margin-bottom: -15px;
}
.mb-15 {
    margin-bottom: 15px;
}
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.nature-tab {
    background-color: #595959;
    color: white;
}
.tab-content {
    font-size: 16px;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    font-size: 18px;
}
</style>
