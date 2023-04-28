<!--
    <System Name> iBMS for Hotel
    <Program Name> TabletManagement.vue

    <Created>   TP Chris  SPRINT_10 TASK188 10/05/2021
-->
<template>
    <div>
        <div class="row" style="margin-top: 10px;" @click="$emit('changePage', 'hardware_management')">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3" style="margin-bottom: -15px;">
                    {{$t("navbar.hardwarem")}}
                </span>
            </div>
        </div>
        <div class="d-flex justify-content-between font-size-hotel py-1 pl-3" style="margin-bottom: 15px;">
            <div>
                <div>{{$t('management.tablet')}} </div>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <div id="account-management-content" class="tab-content tab-bg-gray" style="height: 100%">
                <div>
                    <table class="px-2 py-6 custom-table">
                        <tr class="px-2 h3 border-2 border-bottom text-center">
                            <th>{{$t("tablet.tabletTable.uuid")}}</th>
                            <th>{{$t("tablet.tabletTable.room")}}</th>
                            <th>{{$t("tablet.tabletTable.admin")}}</th>
                            <th></th>
                        </tr>
                        <tr class="h6 border-bottom border-top-0 border-left-0 border-right-0 text-center" v-for="(item, index) in tablet_data" :key="index" :value="item.id">
                            <td class="py-2">{{item.uuid}}</td>
                            <td class="py-2">{{item.ROOM_NAME}}</td>
                            <td class="py-2">{{item.admin ? "YES" : "NO"}}</td>
                            <td class="py-2">
                                <img src="/img/ManagementDashboard/icon/delete.png" class="edit-icon-size pointer"
                                @click="showDeleteUuidModal(item)">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <TabletDevice
                @refreshData="refreshData"
            />
            <DeleteUuidModal
                @refreshData="refreshData"
                @closeModal = "closeModal"
                v-if = "bShowDeleteUuidModal"
                :uuid_data = "selected_uuid_data"
            />
            <div class="row">
                <div class="col mt-2 text-right">
                   <button @click="registerTablet()" type="button" class="btn btn btn-primary ml-auto mr-3" style="transition: all 0.8s">
                        {{ $t('tablet.tabletBtn') }}
                    </button>
                </div>
                <div class="w-100 mb-5"/>
            </div>
        </div>
    </div>
</template>

<script>

import TabletDevice from './Modals/TabletRegisterModal.vue'
import DeleteUuidModal from './Modals/DeleteUuidModal.vue'

export default {
    components: {
        TabletDevice,
        DeleteUuidModal
    },

    data() {
        return {
            tablet_data: [],
            errors: [],
            selected_uuid_data:'',
            bShowDeleteUuidModal:false,
        }
    },

    created() {
        this.refreshData()
        this.registerTablet();
        this.getRegisteredTablet();
    },

    mounted(){
        //set child locale to parent locale
        this.$i18n.locale = this.$parent.locale;
    },

    methods: {
        /**
         * @name registerTablet
         * @description Request to show TabletRegisterModal
         *
         * @returns {void}
         */
        registerTablet() {
            this.$bus.emit('openModal', 'TabletRegisterModal');
        },

        /**
         * @name showDeleteUuidModal
         * @desc Show uuid delete modal
         *
         * @params {String} value1,value2
         * @returns null
         */
        showDeleteUuidModal(uuidInfo) {
            this.selected_uuid_data = uuidInfo;
            this.bShowDeleteUuidModal = true;
        },

        /**
         * @name getRegisteredTablet
         * @description Get all registered tablet
         * @since 1.0.0
         *
         * @returns {void}
         */
        getRegisteredTablet() {
            axios.get('/clientDevice/getAllDevices', {
            })
            .then(response => {
                if (response.status >= 200 && response.status < 300) {
                    this.tablet_data = response.data
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },

        /**
         * @name refreshData
         * @description Initializes or refreshes the data
         *
         * @returns {void}
         */
        refreshData() {
            this.getRegisteredTablet()
        },

        /**
         * @name closeModal
         * @desc Close all modal
         *
         * @params {String} id,uuid,room_name,admin
         * @returns null
         */
        closeModal(){
            this.bShowDeleteUuidModal = false;
        }
    },

    computed: {
        /**
         * @name returnTabletData
         * @description Returns the tablet_data variable
         *
         * @returns {Object[]}
         */
        returnTabletData: function () {
            return this.tablet_data
        }
    }
}
</script>

<style>
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
    content: " ";
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
    content: " ";
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
.font-size-hotel{
    font-size: 20px;
}
.edit-icon-size {
    width: 30px;
    height: 30px;
}

.custom-table {
    width: 100%;
    background-color: #595959;
}
@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
</style>

