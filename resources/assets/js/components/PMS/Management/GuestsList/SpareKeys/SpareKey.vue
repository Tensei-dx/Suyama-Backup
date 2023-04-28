<template>
    <div class="table-scroll">
        <form>
            <div class="form-row m-3">
                <div class="ml-auto col-auto">
                    <button class="btn btn-gray font_data" @click="refreshSpareKeys" :disabled="isRefreshing">
                        <i v-show="isRefreshing" class="fa fa-circle-o-notch" :class="{ 'fa-spin': isRefreshing }" />
                        <span v-text="$t(isRefreshing ? 'spareKeys.refreshing': 'spareKeys.refresh')" />
                    </button>
                </div>
            </div>
        </form>
        <!-- <table class="px-2 py-6 w-100 table-bg-color">
            <thead>
                <tr class="px-2 border-2 border-bottom text-center table-row-height">
                    <th v-for="(header, key) in table.headers" :key="key" v-text="$t(header)" />
                </tr>
            </thead>
            <tbody>
                <tr v-if="!spareKeys.length && !isLoading">
                    <td colspan="5" align="center" class="font-weight-bold h-25px text-uppercase"
                        v-text="$t('spareKeys.error.tableEmpty')" />
                </tr>
                <template v-else>
                    <tr v-for="spareKey in spareKeys" :key="spareKey.SPARE_KEY_ID"
                        class="border-bottom text-center table-row-height">
                        <td v-text="spareKey.SPARE_KEY_ID" />
                        <td v-text="spareKey.PIN_CODE" />
                        <td v-text="spareKey.room.ROOM_NAME" />
                        <td v-text="spareKey.device.DEVICE_NAME" />
                        <td v-text="formatDateTime(spareKey.STARTS_AT) + ' - ' + formatDateTime(spareKey.ENDS_AT)" />
                    </tr>
                </template>
            </tbody>
        </table> -->
        <b-table :fields="fields" :items="spareKeys" :current-page="table.currentPage" :per-page="table.perPage"
                 @filtered="onFilteredItems" :filter="table.filter" :filter-included-fields="table.filterOn"
                 :sort-by.sync="table.sortBy" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('spareKeys.error.tableEmpty')" :empty-text="$t('spareKeys.error.tableEmpty')">

            <template slot="STARTS_AT" slot-scope="data">
                {{ formatDateTime(data.item.STARTS_AT) + ' - ' + formatDateTime(data.item.ENDS_AT) }}
            </template>

        </b-table>

        <!-- pagination for table navigation -->
        <div v-if="table.totalRows > '6'">
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
            isLoading: false,
            isRefreshing: false,
            spareKeys: [],
            // table: {
            //     headers: [
            //         'spareKeys.tableHeaders.id',
            //         'spareKeys.tableHeaders.pinCode',
            //         'spareKeys.tableHeaders.roomName',
            //         'spareKeys.tableHeaders.deviceName',
            //         'spareKeys.tableHeaders.period',
            //     ],
            // },
            table: {
                perPage: 6,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'spareKey.SPARE_KEY_ID',
                sortDesc: true,
                sortDirection: 'desc',
            },
        }
    },

    mounted() {
        // initially retrieve the spare keys entities
        this.getSpareKeys()

        // retrieve the spare keys entities when the event has been emitted
        Echo.channel('spare_keys').listen('SpareKeysUpdated', e => {
            this.getSpareKeys()
        })
    },

    beforeDestroy() {
        // stop listening to event before destroying the component
        Echo.leaveChannel('spare_keys')
    },

    methods: {
        /**
         * @name getSpareKeys
         * @description Retrieve the latest Spare Keys resource
         *
         * @return {void}
         */
        getSpareKeys() {
            this.isLoading = true
            axios
                .get('spare_keys', {
                    params: {
                        show_room: true,
                        show_device: true,
                    },
                })
                .then(response => (this.spareKeys = response.data))
                .catch(error => console.log(error))
                .then(() => (this.isLoading = false))
        },

        /**
         * @name refreshSpareKeys
         * @description Create new spare keys
         *
         * @return {void}
         */
        refreshSpareKeys() {
            this.isRefreshing = true
            axios
                .post('spare_keys/refresh')
                .catch(error => console.log(error))
                .then(() => (this.isRefreshing = false))
        },

        /**
         * @name formatDateTime
         * @description Add custom format for timestamps
         *
         * @return {string}
         */
        formatDateTime(datetime) {
            // default format

            let format = ''

            if(this.$i18n.locale === 'en') {
           
                format = 'LLL'
            } else {
                format = 'LL HH:mm'
            }

            // // if the locale is `ja`, set different format
            // if (this.$18n.locale === 'ja') format = 'LLL'

            return moment(datetime).locale(this.$i18n.locale).format(format)
        },

        onFilteredItems(filteredItems) {
            this.table.totalRows = filteredItems.length
            this.table.currentPage = 1
        },
    },

    computed: {
        fields: function () {
            const fields = this.$t('spareKeys.tableHeaders')

            return [
                // Tentative column name. Column name must be changed according to DB
                {
                    key: 'SPARE_KEY_ID',
                    label: fields.id,
                    isRowHeader: true,
                    class: 'text-center text-nowrap title-border',
                },
                { key: 'PIN_CODE', label: fields.pinCode, class: 'text-center text-nowrap title-border' },
                {
                    key: 'room.ROOM_NAME',
                    label: fields.roomName,
                    sortable: true,
                    class: 'text-center text-nowrap title-border',
                },
                { key: 'device.DEVICE_NAME', label: fields.deviceName, class: 'text-center text-nowrap title-border' },
                {
                    key: 'STARTS_AT',
                    label: fields.period,
                    sortable: true,
                    class: 'text-center text-nowrap title-border',
                },
            ]
        },
    },
}
</script>

<style scoped>
.table-scroll {
    max-height: 470px !important;
    position: relative;
}
.table-bg-color {
    background-color: #595959;
}
.table-row-height {
    height: 2.5rem;
}
.h-25px {
    height: 3.5rem;
}
.table {
    border-top: 1px solid #fff !important;
}
.font_data {
    font-size: 1rem;
}
</style>
