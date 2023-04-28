<!-- UPDATE   TDN Chris  SPRINT_08 TASK177 09232021 -->
<template>
<div class="d-flex justify-content-between flex-column h-450">
    <div class="position-relative pt-2 mb-32px">
        <div class="input-group col-sm-6 float-right my-2" style="z-index: 1">
            <input type="text" v-model="tableData.filter" class="form-control" :placeholder="$t('natureIr.search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
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
                    :items="filteredIrDevices"
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
                </b-table>
            </div>
        </div>
    </div>
    <div class="position-relative">
        <form>
            <div class="form-row px-3">
                <!-- Comment out the floor field -->
                <!-- <div class="col-4">
                    <select v-model="selectedFloor" class="custom-select" @change="clearSelectedRoom">
                        <option :value="null" selected>{{ $t('natureIr.irDevices.filters.floor.all') }}</option>
                        <option v-for="floor in floors" :value="floor.FLOOR_ID" :key="floor.FLOOR_ID">{{ floor.FLOOR_NAME }}</option>
                    </select>
                </div> -->
                <div class="col-4">
                    <select v-model="selectedRoom" class="custom-select">
                        <option :value="null" selected>{{ $t('natureIr.irDevices.filters.room.all') }}</option>
                        <option v-for="room in filteredRooms" :value="room.ROOM_ID" :key="room.ROOM_ID">{{ room.ROOM_NAME }}</option>
                    </select>
                </div>
            </div>
        </form>
        <!-- Comment out the pagination function -->
        <!-- <div class="mt-3" :class="irDevices.length > 40 ? '' : 'custom-pagination-orange'">
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
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.16
 * @version 1.0.0
 */

export default {
    props: {
        irDevices: {
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
                sortBy: 'DEVICE_NAME',
                sortDesc: false,
                totalRows: 0
            },
            floors: [],
            rooms: [],
            selectedFloor: null,
            selectedRoom: null
        }
    },

    created() {
        this.getFloors()
        this.getRooms()
    },

    mounted() {
        this.tableData.totalRows = this.irDevices.length
        this.$bus.on('clearFilters', () => this.clearFilters())
    },

    beforeDestroy() {
        this.$bus.off('clearFilters')
    },

    methods: {
        /**
         * @name showDetails
         * @description Get the item information selected in the table
         * @since 1.0.0
         *
         * @param {object} item Item information
         * @param {number} [index] Optional. Index of the item in the table
         * @param {object} [event] Optional. Information about the event
         * @returns {void}
         */
        showDetails(item) {
            this.$emit('showDetails', {
                element: 'IrDeviceDetails',
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
         * @name getFloors
         * @description Get all floors
         * @since 1.0.0
         *
         * @returns {void}
         */
        getFloors() {
            axios.post('getFloorAll')
            .then(response => {
                if (response.status == 200) {
                    this.floors = response.data
                } else {
                    this.errors.push(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },

        /**
         * @name getRooms
         * @description Get all rooms
         * @since 1.0.0
         *
         * @returns {void}
         */
        getRooms() {
            axios.get('getRoomAll')
            .then(response => {
                if (response.status == 200) {
                    this.rooms = response.data
                } else {
                    this.errors.push(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },

        /**
         * @name clearSelectedRoom
         * @description Set selectedRoom to null when the selectedFloor is changed
         *
         * @returns {void}
         */
        clearSelectedRoom() {
            this.selectedRoom = null
        },

        /**
         * @name clearFilters
         * @description Reset the search and filters
         *
         * @returns {void}
         */
        clearFilters() {
            this.tableData.filter = null
            this.selectedFloor = null
            this.selectedRoom = null
        }
    },

    computed: {
        /**
         * @name filteredRooms
         * @description List of rooms filtered by FLOOR_ID
         *
         * @returns {object[]}
         */
        filteredRooms: function () {
            return this.selectedFloor
            ? this.rooms.filter(i => i.FLOOR_ID === this.selectedFloor)
            : this.rooms
        },

        /**
         * @name filteredIrDevices
         * @description List of nature remo devices filtered by FLOOR_ID and ROOM_ID
         *
         * @returns {object[]}
         */
        filteredIrDevices: function () {
            let irDevices = this.irDevices
            if (this.selectedFloor) {
                irDevices = irDevices.filter(i => i.FLOOR_ID === this.selectedFloor)
            }
            if (this.selectedRoom) {
                irDevices = irDevices.filter(i => i.ROOM_ID === this.selectedRoom)
            }
            return irDevices
        },

        /**
         * @name tableFields
         * @description Make the table field headers language-dynamic
         *
         * @returns {object[]}
         */
        tableFields: function () {
            let lang = this.$t('natureIr.irDevices.table')
            return [
                {
                    key: 'floor.FLOOR_NAME',
                    label: lang.floor,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'room.ROOM_NAME',
                    label: lang.room,
                    sortable: true,
                    class: 'text-truncate'
                },
                {
                    key: 'DEVICE_NAME',
                    label: lang.name,
                    sortable: true,
                    class: 'text-truncate'
                }
            ]
        }
    }
}
</script>
