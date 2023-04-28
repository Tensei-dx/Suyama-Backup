<!-- Updated TP Leo Sprint_03 Task025 20210816 -->
<!-- UPDATED: TP Chris   SPRINT_04 TASK113 08232021 -->
<!-- UPDATED: TP Leo   SPRINT_04 TASK117 09022021 -->
<!-- UPDATED: TP Leo   SPRINT_06 TASK150 09072021 -->
<!-- UPDATED: TP Ivin SPRINT_10 TASK208 10052021 -->
<!-- UPDATED: TP Rhea SPRINT_12 TASK236 10212021 -->
<template>
    <div>
        <!-- TIME AND DATE -->
        <div class="row" style="margin-bottom:0px;">
            <div class="col-6" style="margin-top:10px;">
                <span class="custom-clock-time font-weight-bold" style="font-size:33px;">
                    <Clock format="hh:mm A" v-if="this.locale === 'en'" :locale="this.locale"></Clock>
                    <Clock format="HH:mm" v-else :locale="this.locale"></Clock>
                </span>
            </div>
            <div class="col-6" style="text-align:end; margin-top:10px;">
                <span class="text-uppercase date font-weight-bold" style="font-size:20px;">
                    <Clock format="MM/DD/YYYY" v-if="this.locale === 'en'" :locale="this.locale"></Clock>
                    <Clock format="YYYY/MM/DD" v-else :locale="this.locale"></Clock>
                </span>
                <span class="text-uppercase" style="font-size:16px;">
                    <Clock format="dddd" :locale="this.locale"></Clock>
                </span>
            </div>
            <div class="col text-left">
                <span class="h4">{{this.userName}}</span>
            </div>
        </div>
        <!-- 【Task150】 -->
        <!-- <div class="container font-weight-bold" style="height:29rem; font-size:15px;"> -->
        <div class="container font-weight-bold" style="height:29rem; font-size:15px;">
            <!-- 【Task150】 -->
            <div class="row align-items-start justify-content-around mx-3 my-1">
                <!-- DASHBOARD -->
                <div class="col-xl-6 col-auto order-1 mt-1 mb-1 custom-i pointer"
                     @click="$emit('changePage', 'dashboard')">
                    <!-- + SPRINT_12 TASK236 -->
                    <div class="container rounded-circle ct-bg-icon  text-center"
                         :class="roomDeviceOperation.includes(currentPage) ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/Dashboard.png" class="menu-icon-size">
                            </div>
                        </div>
                    </div>
                    <!-- 【Task021】 -->
                    <!-- <span class="d-block mt-0 text-center">{{ $t('management.dashboard') }}</span> -->
                    <!-- 【Task021】 -->
                    <span class="d-block mt-0 text-center">{{ $t('management.roomStatus') }}</span>
                </div>
                <!-- USER MANAGEMENT -->
                <div class="col-xl-6 col-auto order-2 mt-1 mb-1 pointer" @click="$emit('changePage', 'user')">
                    <!-- + SPRINT_12 TASK236 -->
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="userManagement.includes(currentPage) ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/guest_management.png"
                                     class="menu-icon-custom-size">
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.users')}}</span>
                </div>
                <!-- 【TASK015】 -->
                <!-- CLEANING MANAGEMENT -->
                <!-- <div class="col-xl-6 col-auto order-3 mt-1 mb-1 custom-i pointer" @click="$emit('changePage', 'cleaning')">
                    <div class="container rounded-circle ct-bg-icon hotel-base-color-rs text-center">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/Cleaning.png" class="menu-icon-size">
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.cleaning') }}</span>
                </div> -->
                <!-- 【TASK015】 -->
                <!-- - SPRINT_08 TASK173 -->
                <!-- USERS MANAGEMENT -->
                <!-- <div class="col-xl-6 col-auto order-4 mt-1 mb-1 custom-i pointer">
                    <div class="container rounded-circle ct-bg-icon hotel-base-color-rs text-center">
                        <a style="color:white; text-decoration:none;" href="/user-management" target="_blank" rel="noopener noreferrer">
                            <div class="row h-100 align-items-center">
                                    <div class="col">
                                        <img src="/img/ManagementDashboard/icon/User.png" class="menu-icon-size">
                                    </div>
                            </div>
                        </a>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.users') }}</span>
                </div> -->
                <!-- - SPRINT_08 TASK173 -->
                <!-- - SPRINT_04 Task117 -->
                <!-- FLOOR MANAGEMENT -->
                <!-- <div class="col-xl-6 col-auto order-5 mt-1 mb-1 custom-i pointer">
                    <div class="container rounded-circle ct-bg-icon hotel-base-color-rs text-center">
                        <a style="color:white; text-decoration:none;" href="/floor-management" target="_blank" rel="noopener noreferrer">
                            <div class="row h-100 align-items-center">
                                <div class="col">
                                    <img src="/img/ManagementDashboard/icon/Floor.png" class="menu-icon-size">
                                </div>
                            </div>
                        </a>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.floors') }}</span>
                </div> -->
                <!-- - SPRINT_04 Task117 -->
                <!-- LOGS -->
                <!-- = SPRINT_08 Task149 -->
                <!-- <div class="col-xl-6 col-auto order-6 mt-1 mb-1 custom-i pointer"> -->
                <div class="col-xl-6 col-auto order-6 mt-1 mb-1 custom-i pointer " @click="$emit('changePage', 'logs')">
                    <!-- = SPRINT_08 Task149 -->
                    <!-- + SPRINT_12 TASK236 -->
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'logs' ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <!-- = SPRINT_08 Task149 -->
                        <!-- <a style="color:white; text-decoration:none;" href="/logs" target="_blank" rel="noopener noreferrer"> -->
                        <a style="color:white; text-decoration:none;">
                            <!-- = SPRINT_08 Task149 -->
                            <div class="row h-100 align-items-center">
                                <div class="col">
                                    <img src="/img/ManagementDashboard/icon/Logs.png" class="menu-icon-size">
                                </div>
                            </div>
                        </a>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.logs') }}</span>
                </div>
                <!-- + SPRINT_04 TASK1113 -->
                <!-- CO2 Monitor Screen-->
                <div class="col-xl-6 col-auto order-6 mt-1 mb-1 custom-i pointer" @click="$emit('changePage', 'co2')">
                    <!-- + SPRINT_12 TASK236 -->
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'co2' ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/co2.png" class="menu-icon-size">
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.co2') }}</span>
                </div>
                <!-- + SPRINT_04 TASK1113 -->

                <!-- + SPRINT_07 TASK147 -->
                <!-- Hardware Management Screen-->
                <div class="col-xl-6 col-auto order-6 mt-1 mb-1 custom-i pointer"
                     @click="$emit('changePage', 'hardware_management')">
                    <!-- + SPRINT_12 TASK236 -->
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="hardwareManagement.includes(currentPage) ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/hardware_management.png" class="menu-icon-size">
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.hardware') }}</span>
                </div>
                <!-- + SPRINT_07 TASK147 -->

                <!-- Manual Screen-->
                <div class="col-xl-6 col-auto order-6 mt-1 mb-1 custom-i pointer"
                     @click="$emit('changePage', 'manual')">
                    <div class="container rounded-circle ct-bg-icon text-center"
                         :class="currentPage === 'manual' ? 'ct-bg-icon-orange' : 'hotel-base-color-rs'">
                        <div class="row h-100 align-items-center">
                            <div class="col">
                                <img src="/img/ManagementDashboard/icon/manual.png" class="menu-icon-size">
                            </div>
                        </div>
                    </div>
                    <span class="d-block mt-0 text-center">{{ $t('management.manual') }}</span>
                </div>

            </div>
        </div>
        <div class="row">
            <!-- iBMS LOGO -->
            <div class="col-8 mt-1-313">
                <!-- = 【Task150】 -->
                <!--<img src="/img/ManagementDashboard/icon/iBMSLogo.png" style="max-width:100%" class="img-fluid"> -->
                <span class="d-block text-left">{{ $t('hotel.address') }}</span>
                <!-- = 【Task150】 -->
            </div>
            <!-- LOGOUT  -->
            <div class="col-4" style="text-align:end">
                <button type="button" class="btn btn-logout-bg-color-mng logout-btn" @click="showLogout()">
                    <span class="font-weight-bold hotel-black-color-text">{{ $t('management.logout') }}</span>
                </button>
            </div>
        </div>
        <LogoutModal id="logoutModal" />
    </div>
</template>

<script>
/**
 * <System> iBMS
 *
 * @author TP Russell <r-russell@tenseiph.com>
 *
 * Updated : 2021.06.30 TP Uddin Changed the image for Key Management
 *                               Changed the title of Key Management to Register Guest
 *         : 2021.08.10 TP Ivin  Comment out Cleaning Management Function
 */

import Clock from '../Event/Clock.vue'
import LogoutModal from '../Modal/LogoutModal.vue'

export default {
    props: {
        locale: {
            type: String,
            required: true,
        },

        currentPage: {
            type: String,
        },
    },
    data() {
        return {
            userName: '',
            hardwareManagement: [
                'hardware_management',
                'nature_IR',
                'gateway',
                'remotelock_management',
                'device',
                'param',
                'tablet_registration',
            ],
            roomDeviceOperation: ['dashboard', 'roomDeviceOperation', 'guestCardCheck'],
            userManagement: ['user', 'addAdminAccount'],
        }
    },
    components: {
        Clock,
        LogoutModal,
    },
    methods: {
        /**
         * @name showLogout
         * @description Show the Logout modal
         *
         * @returns {void}
         */
        showLogout() {
            $('#logoutModal')
                .modal({
                    backdrop: 'static',
                })
                .modal('show')
        },
        getUsername() {
            axios.get('getUserID').then(response => {
                this.userName = response.data.USERNAME
            })
        },
    },
    created() {
        this.getUsername()
    },
}
</script>

<style scoped>
.ct-bg-icon {
    height: 120px;
    width: 120px;
}
.ct-bg-icon-orange {
    background-color: #ffa500 !important;
    height: 120px;
    width: 120px;
}
.menu-icon-custom-size {
    display: block;
    max-width: 90px;
    max-height: 90px;
    width: auto;
    height: auto;
    margin: auto;
}
.logout-btn {
    background-color: #e2e2e2 !important;
    /* background-color: #add8e6 !important; */
}
.mt-1-313 {
    margin-top: 1.313rem !important;
}
</style>
