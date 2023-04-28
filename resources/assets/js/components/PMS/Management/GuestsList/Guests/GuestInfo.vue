<template>
    <div class="pb-1">
        <div class="form form-inline pt-2 pb-2" style="background-color:#595959;">
            <div class="cleardate-btn">
                <input class="form-control font_date" type="date" v-model="date" id="date" />
                <!-- <input class="form-control" data-provide="datepicker" /> -->
                <button @click="date=null" class="btn btn-gray font_data">
                    {{$t("management.keyManagementPage.clear")}}
                </button>
            </div>
        </div>
        <b-table responsive sticky-header id="user-table" :fields="fields" :items="filteredGuestAccount"
                 :current-page="table.currentPage" :per-page="table.perPage" @filtered="onFilteredBySearch"
                 :filter="table.filter" :filter-included-fields="table.filterOn" :sort-by.sync="table.sortBy"
                 :sort-desc.sync="table.sortDesc" :sort-direction="table.sortDirection" show-empty
                 :empty-filtered-text="$t('user.noData')" :empty-text="$t('user.noData')">

            <template slot="bookingsWithRoom.ARRIVAL_TIME" slot-scope="data">
                <ul class="mb-0 p-0">
                    {{transformDate(data.item.bookingsWithRoom.ARRIVAL_TIME).slice(0, 10)}}
                </ul>
                <ul class="mb-0  p-0">
                    {{transformDate(data.item.bookingsWithRoom.ARRIVAL_TIME).slice(11, 19)}}
                </ul>
            </template>
            <template slot="bookingsWithRoom.DEPARTURE_TIME" slot-scope="data">
                <ul class="mb-0  p-0">
                    {{transformDate(data.item.bookingsWithRoom.DEPARTURE_TIME).slice(0, 10)}}
                </ul>
                <ul class="mb-0  p-0">
                    {{transformDate(data.item.bookingsWithRoom.DEPARTURE_TIME).slice(11, 19)}}
                </ul>
            </template>
            <template slot="guestInfoNewWindow" slot-scope="data">
                <span @click="guestInfo(data.item.bookingsWithRoom.USER_ID)" class="pointer text-center">
                    <i class="fa fa-print fa-2x" aria-hidden="true" />
                </span>
            </template>

            <!-- <template slot="remoteLock" slot-scope="data">
                <a v-bind:href="`remotelock/updateGuest/${data.item.bookingsWithRoom.USER_ID}`">
                    <span class="pointer text-center">
                        <i class="fa fa-edit fa-lg" aria-hidden="true" />
                    </span>
                </a>
            </template>
            <template slot="deleteModal" slot-scope="data">
                <span @click="showDeleteGuestAccountModal(data)" class="pointer text-center">
                    <i class="fa fa-trash fa-lg" aria-hidden="true" />
                </span>
            </template> -->
        </b-table>
        <!-- <div class="table-scroll">
                <table class="px-2 py-6 w-100" style="background-color:#595959;">
                    <thead>
                        <tr class="px-2 font_header border-2 border-bottom text-center">
                            <th>{{$t("management.keyManagementPage.number")}}</th>
                            <th>{{$t("management.keyManagementPage.serial")}}</th>
                            <th class="pl-2">{{$t("management.keyManagementPage.accessCode")}}</th>
                            <th>{{$t("management.keyManagementPage.name")}}</th>
                            <th>{{$t("management.keyManagementPage.room")}}</th>
                            <th>{{$t("management.keyManagementPage.checkIn")}}</th>
                            <th>{{$t("management.keyManagementPage.checkOut")}}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font_data border-bottom border-top-0 border-left-0 border-right-0 text-center"
                            v-for="(item, index) in filteredGuestAccount" :key="index">
                            <td class="py-2">{{index + 1}}</td>
                            <td class="py-2 pl-2">{{item.bookingsWithRoom.user.USERNAME}}</td>
                            <td class="py-2 pl-2">{{item.bookingsWithRoom.PIN}}</td>
                            <td class="py-2">{{item.FIRST_NAME}}</td>
                            <td class="py-2">{{item.bookingsWithRoom.room.ROOM_NAME}}</td>
                            <td class="py-2">
                                <ul class="mb-0 p-0">
                                    {{transformDate(item.bookingsWithRoom.CHECK_IN_TIME).slice(0, 10)}}
                                </ul>
                                <ul class="mb-0  p-0">
                                    {{transformDate(item.bookingsWithRoom.CHECK_IN_TIME).slice(11, 19)}}
                                </ul>
                            </td>
                            <td class="py-2">
                                <ul class="mb-0  p-0">
                                    {{transformDate(item.bookingsWithRoom.CHECK_OUT_TIME).slice(0, 10)}}
                                </ul>
                                <ul class="mb-0  p-0">
                                    {{transformDate(item.bookingsWithRoom.CHECK_OUT_TIME).slice(11, 19)}}
                                </ul>
                            </td>
                            <td class="py-2">
                                <a v-bind:href="`remotelock/updateGuest/${item.bookingsWithRoom.USER_ID}`">
                                    <span class="pointer text-center">
                                        <i class="fa fa-edit fa-lg" aria-hidden="true" />
                                    </span>
                                </a>
                            </td>
                            <td class="py-2">
                                <span @click="showDeleteGuestAccountModal(item)" class="pointer text-center">
                                    <i class="fa fa-trash fa-lg" aria-hidden="true" />
                                </span>
                            </td>
                        </tr>
                        <div v-if="table.totalRows > '5'">
                            <b-pagination v-model="table.currentPage" :per-page="table.perPage"
                                            :total-rows="table.totalRows" align="center" />
                        </div>
                    </tbody>
                </table> -->
        <div v-if="table.totalRows > '4'">
            <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                          align="center" aria-controls="#user-table" />
        </div>
        <DeleteGuestAccountModal @closeModal="closeModal" v-if="bShowDeleteGuestAccountModal"
                                 :booking_data="selected_booking_data" />
    </div>
</template>

    <script>
// - SPRINT_05 [Task131]
// import UpdateGuestAccountModal from '../Guests/Modals/UpdateGuestAccountModal.vue'
// - SPRINT_05 [Task131]
import DeleteGuestAccountModal from '../Guests/Modals/DeleteGuestAccountModal.vue'
import moment from 'moment'

export default {
    components: {
        DeleteGuestAccountModal,
        // - SPRINT_05 [Task131]
        // UpdateGuestAccountModal
        // - SPRINT_05 [Task131]
    },

    props: {
        access_guests: Array,
    },

    data() {
        return {
            table: {
                perPage: 4,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                done: false,
            },
            selected_booking_data: '',
            filteredGuestAccount: [],
            guests: [],
            guest_id: '',
            guest_first_name: '',
            guest_last_name: '',
            guest_email: '',
            guest_pin: '',
            guest_check_in: '',
            guest_check_out: '',
            delete_guest_id: '',
            delete_guest_username: '',
            delete_guest_name: '',
            delete_guest_check_in: '',
            delete_guest_check_out: '',
            delete_guest_room_name: '',
            date: null,

            pin: '',
            username: '',
            room_name: '',
            check_in: '',
            check_out: '',
            url: '',
            newUrl: '',

            // - SPRINT_05 [Task131]
            // bShowUpdateGuestAccountModal:false,
            // - SPRINT_05 [Task131]
            bShowDeleteGuestAccountModal: false,
        }
    },

    created() {
        //Set date today
        var d = new Date()
        this.date = d.toISOString().split('T')[0]

        //Filter Guests by Today's date
        this.filterGuestByDate()
    },

    methods: {
        // - SPRINT_05 [Task131]
        // /**
        //  * @name showUpdateGuestAccountModal
        //  * @desc Show guest account update modal
        //  *
        //  * @params {String} id,first_name,last_name,email,check_in,check_out,pin
        //  * @returns null
        //  */
        // showUpdateGuestAccountModal(booking_data) {

        //     this.selected_booking_data = booking_data
        //     this.bShowUpdateGuestAccountModal = true;

        // },
        // - SPRINT_05 [Task131]
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
         * @name fields
         * @description Fields for the
         *              Use computed property to allow multi-language support
         *
         * @returns {object[]}
         */

        /**
         * @name showDeleteGuestAccountModal
         * @desc Show guest account delete modal
         *
         * @params {String} id,username,name,check_in,check_out,room_name
         * @returns null
         */
        showDeleteGuestAccountModal(booking_data) {
            this.selected_booking_data = booking_data
            this.bShowDeleteGuestAccountModal = true
        },
        /**
         * @name closeModal
         * @desc Close all modal
         *
         * @params {String} id,username,name,check_in,check_out,room_name
         * @returns null
         */
        closeModal() {
            this.bShowDeleteGuestAccountModal = false
            this.bShowUpdateGuestAccountModal = false
        },
        /**
         * @name filterGuestByDate
         * @desc Filter all Guests by date
         *
         * @params {String} id,username,name,check_in,check_out,room_name
         * @returns null
         */
        filterGuestByDate() {
            if (this.date != null) {
                this.filteredGuestAccount = this.access_guests.filter(guest => {
                    var checkIn = new Date(guest['bookingsWithRoom'].ARRIVAL_TIME)
                    var dd = String(checkIn.getDate()).padStart(2, '0')
                    var mm = String(checkIn.getMonth() + 1).padStart(2, '0') //January is 0!
                    var yyyy = checkIn.getFullYear()
                    checkIn = yyyy + '-' + mm + '-' + dd

                    if (checkIn === this.date) {
                        return guest
                    }
                })
            } else {
                this.filteredGuestAccount = this.access_guests
            }
        },

        /**
         *
         */
        transformDate(date) {
            let format
            if (date === null) {
                return null
            }

            if (this.$i18n.locale === 'en') {
                format = 'MM/DD/YYYY hh:mm A'
            } else {
                format = 'YYYY/MM/DD HH:mm'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },

        guestInfo(user_id) {
            // this.$emit('guestinfo')
            // var url = '/guestinfo/' + this.USER_
            window.open('/guestinfo/' + user_id)
        },
    },
    computed: {
        // filteredGuestAccount: function () {
        //     if (this.date) {
        //         return this.access_guests.filter(i => i['bookings_with_room'][0]['CHECK_IN_TIME'].split("T")[0] === this.date || i['bookings_with_room'][0]['CHECK_IN_TIME'].split("T")[0] === this.date)
        //     } else {
        //         this.access_guests.filter(g=>{
        //             var dd = new Date(g['bookings_with_room'][0]['CHECK_IN_TIME']).toISOString().split('T')[0];
        //             console.log(dd);
        //             console.log(dd === this.date);
        //         });
        //         return this.access_guests
        //     }
        // }
        fields: function () {
            const fields = this.$t('management.keyManagementPage')

            return [
                // Tentative column name. Column name must be changed according to DB
                { key: 'bookingsWithRoom.user.USERNAME', label: fields.serial, class: 'py-2 pl-2' },
                { key: 'bookingsWithRoom.PIN', label: fields.accessCode, class: 'py-2 pl-2' },
                { key: 'FIRST_NAME', label: fields.name, class: 'py-2' },
                { key: 'bookingsWithRoom.room.ROOM_NAME', label: fields.room, class: 'py-2' },
                { key: 'bookingsWithRoom.ARRIVAL_TIME', label: fields.arrivalTime, class: 'py-2' },
                { key: 'bookingsWithRoom.DEPARTURE_TIME', label: fields.departureTime, class: 'py-2' },
                { key: 'guestInfoNewWindow', label: '', class: 'py-2' },
                { key: 'remoteLock', label: '', class: 'py-2' },
                { key: 'deleteModal', label: '', class: 'py-2' },
            ]
        },
    },
    watch: {
        date: function () {
            this.filterGuestByDate()
        },
        access_guests: function () {
            this.filterGuestByDate()
        },
    },
}
</script>

<style scoped>
.edit-icon-size {
    width: 20px;
    height: 20px;
}
.table-scroll {
    max-height: 320px !important;
    overflow: auto;
    position: relative;
}
table thead {
    position: sticky !important;
    top: 0;
    z-index: 999;
    overflow: hidden !important;
    background-color: #595959;
}
table tbody {
    top: 24px !important;
    position: sticky !important;
    overflow: hidden !important;
    height: 100% !important;
}
.font_date {
    font-size: 1.0625rem;
}
.font_header {
    font-size: 1rem;
}
.font_data {
    font-size: 1rem;
}
.fa-edit {
    color: white;
}
.fa-trash {
    color: white;
}
</style>

