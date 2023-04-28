<!--  <System Name> iBMS
      <Program Name> GuestList.vue
      <Created>            TP Russell
      <Updated>            TP Chris 15/10/2021
-->
<template>
    <div>
        <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3">
                    {{$t("management.keyManagementPage.userAndGuestAccount")}}
                </span>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div id="account-management-tabs" role="tablist" class="nav nav-tabs w-100">
                    <!-- guest tab -->
                    <a id="guest-account-tab" @click="currentPage('guest')" data-toggle="tab" href="#guest-account-list"
                       role="tab" aria-controls="guest-account-list" aria-selected="false"
                       class="nav-item nav-link active font_header">
                        {{ $t("management.keyManagementPage.guests") }}
                    </a>
                    <!-- user tab -->
                    <a id="user-account-tab" @click="currentPage('user')" data-toggle="tab" href="#user-account-list"
                       role="tab" aria-controls="user-account-list" aria-selected="true"
                       class="nav-item nav-link font_header">
                        {{ $t("management.keyManagementPage.users") }}
                    </a>
                    <!-- spare key tab -->
                    <a id="spare-key-tab" @click="currentPage('spare_keys')" data-toggle="tab" href="#spare-key-list"
                       role="tab" aria-controls="spare-key-list" aria-selected="false"
                       class="nav-item nav-link font_header text-uppercase">
                        {{ $t('spareKeys.tabName') }}
                    </a>

                </div>
            </div>
            <div id="account-management-content" class="tab-content tab-bg-gray" style="height: 100%">
                <div id="user-account-list" role="tabpanel" aria-labelledby="user-account-list" class="tab-pane fade">
                    <UsersInfo />
                    <div v-if="this.no_users_accounts == true"
                         class="h3 border-bottom border-top-0 border-left-0 border-right-0 text-center py-2">
                        {{$t("management.keyManagementPage.noUserAccountsRegister")}}
                    </div>
                </div>
                <div id="guest-account-list" role="tabpanel" aria-labelledby="guest-account-list"
                     class="tab-pane fade show active">
                    <GuestInfo :access_guests="returnAccessGuests" />
                    <!-- <div v-if="this.no_guests_accounts == true"
                         class="h3 border-bottom border-top-0 border-left-0 border-right-0 text-center py-2">
                        {{$t("management.keyManagementPage.noGuestAccountsRegister")}}
                    </div> -->
                </div>
                <div id="spare-key-list" role="tabpanel" aria-labelledby="spare-key-list" class="tab-pane fade">
                    <SpareKey />
                </div>
            </div>
            <div v-if="isLoading" class="loader">
            </div>
            <div class="row">
                <!-- <div v-if="page == 'guest'" class="col mt-2 text-right">
                    <a style="color:white; text-decoration:none;" href="/remotelockGuest">
                        <button type="button"
                                class="btn font_button button-create-base-color ml-2 font-weight-bold pl-2"
                                style="margin-top:0.3rem;">
                            {{$t("management.keyManagementPage.createGuestAccount")}}
                        </button>
                    </a>
                </div> -->
                <!-- <div v-else-if="page == 'user'" class="col mt-2 text-right">
                    <a style="color:white; text-decoration:none;" href="/remotelock">
                        <button type="button"
                                class="btn font_button button-create-base-color ml-2 font-weight-bold pl-2"
                                style="margin-top:0.3rem;">
                            {{$t("management.keyManagementPage.createAdminAccount")}}
                        </button>
                    </a>
                </div> -->
                <!-- <div v-if="page == 'user'" class="col mt-2 text-right">
                    <button type="button" class="btn font_button button-create-base-color ml-2 font-weight-bold pl-2"
                            style="margin-top:0.3rem;" @click="$emit('changePage', 'addAdminAccount')">
                        {{$t("management.keyManagementPage.createAdminAccount")}}
                    </button>
                </div> -->
                <div class="w-100 mb-5" />
            </div>
        </div>
    </div>
</template>

<script>
import UsersInfo from './Users/UsersInfo.vue'
import GuestInfo from './Guests/GuestInfo.vue'
import SpareKey from './SpareKeys/SpareKey.vue'

export default {
    components: {
        UsersInfo,
        GuestInfo,
        SpareKey,
    },

    data() {
        return {
            users: '',
            page: 'guest',
            isLoading: true,
            access_users: [],
            access_guests: [],
            no_users_accounts: false,
            no_guests_accounts: false,
            errors: [],
            test: '',
            page: 'guest',
        }
    },

    created() {
        // this.getRedirectUrl();
        this.getRemoteLockAccessAccounts()
    },
    mounted() {
        this.$bus.on('updateAccountList', e => {
            this.getRemoteLockAccessAccounts()
        })
    },
    beforeDestroy() {},
    methods: {
        /**
         * @name getRemoteLockAccessAccounts
         * @desc Get all Remote Lock accounts
         *
         * @returns {void}
         */
        getRemoteLockAccessAccounts() {
            //Get Guest List Information
            axios
                .get('getBookingAccountInfo')
                .then(response => {
                    //Get Guest List Information
                    let guestList = response.data.guest_list
                    for (let i in guestList) {
                        if (guestList[i].bookingsWithRoom.room !== null) {
                            let status_id = guestList[i].bookingsWithRoom.room.STATUS_ID
                            let status_code = ''
                            switch (status_id) {
                                case 201:
                                    status_code = 'Checked In'
                                    break
                                case 202:
                                    status_code = 'Checked Out'
                                    break
                                case 203:
                                    status_code = 'Available'
                                    break
                                case 204:
                                    status_code = 'Unavailable'
                                    break
                                case 205:
                                    status_code = 'Reserved'
                                    break
                                default:
                                    break
                            }
                            guestList[i].STATUS_CODE = status_code
                        } else {
                            guestList[i].STATUS_CODE = '-'
                            guestList[i].bookingsWithRoom.room = new Object()
                            guestList[i].bookingsWithRoom.room = { ROOM_NAME: 'NO VALID ROOM' }
                        }
                    }
                    this.access_guests = guestList

                    //Get Admin List Information
                    this.access_users = response.data.admin_list
                })
                .then(() => {
                    this.isLoading = false
                    this.no_users_accounts = this.access_users.length === 0
                    this.no_guests_accounts = this.access_guests.length === 0
                })
                .catch(error => this.errors.push(error))
        },

        /**
         * @name currentPage
         * @desc Get current page
         * @returns {void}
         */
        currentPage(page) {
            this.page = page
        },
    },

    computed: {
        /**
         * @name returnAccessGuests
         * @description Returns the access_guests variable
         *
         * @returns {Object[]}
         */
        returnAccessGuests: function () {
            return this.access_guests
        },

        /**
         * @name returnAccessUsers
         * @description Returns the access_users variable
         *
         * @returns {Object[]}
         */
        returnAccessUsers: function () {
            return this.access_users
        },
    },
}
</script>

<style scoped>
.nav-tabs .nav-header-dark-gray.active,
.nav-tabs .nav-header-dark-gray.show {
    position: relative;
    background-color: #595959;
    color: #fff;
    border-color: transparent;
    border-radius: inherit;
}
.nav-tabs .nav-header-dark-gray {
    position: relative;
    background-color: #bfbfbf;
    color: white;
    border-color: transparent;
    border-radius: inherit;
}
.nav-tabs .nav-header-dark-gray:hover,
.nav-tabs .nav-header-dark-gray:focus {
    border-color: transparent;
}
.nav-tabs .nav-header-dark-gray:after {
    content: ' ';
    position: absolute;
    display: block;
    width: 12%;
    height: 106%;
    top: -1px;
    left: 100%;
    z-index: -1;
    background: #bfbfbf;
    transform-origin: bottom left;
    -ms-transform: skew(-30deg, 0deg);
    -webkit-transform: skew(-30deg, 0deg);
    transform: skew(18deg, 180deg);
    z-index: 10;
    color: rgb(0, 0, 0);
}
.nav-tabs .nav-header-dark-gray.active:after {
    content: ' ';
    position: absolute;
    display: block;
    width: 12%;
    height: 106%;
    top: -1px;
    left: 100%;
    z-index: -1;
    background: #595959;
    transform-origin: bottom left;
    -ms-transform: skew(-30deg, 0deg);
    -webkit-transform: skew(-30deg, 0deg);
    transform: skew(18deg, 180deg);
    z-index: 10;
}
.tab-bg-gray {
    background-color: #595959 !important;
    background-image: linear-gradient(180deg, #595959, #595959);
    color: #fff;
    box-shadow: 4px 2px 12px 0px #999;
}
.button-create-base-color {
    background-color: white;
}
.loader {
    height: 50px;
    width: 50px;
    border: 5px solid #45474b;
    border-top-color: white;
    position: relative;
    margin-top: inherit;
    margin-bottom: auto;
    margin-left: auto;
    margin-right: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: 50%;
    animation: spin 1.5s infinite linear;
}
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.font_header {
    font-size: 1rem;
}
.font_button {
    font-size: 1rem;
}
.nav-tab {
    border-bottom: 1px solid #dee2e6;
}
@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
.nav-item {
    font-size: 18px;
}
</style>

