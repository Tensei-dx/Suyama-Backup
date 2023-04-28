<!--
    <Update> 2019.01.31 Jethro      Changed table format to bootstrap vue table for uniform and removed uneccessary
             2020.03.10 TP Uddin    Fix column size whenever tab or category is changed
             2020.05.26 TP Uddin    Modify axios URL according to the URL list
             2021.05.12 TP Uddin    Add scan function for Nature Remo devices
             2021.09.02 TP Harvey   SPRINT_05 TASK021 Fixing bug in change language
             2021.09.16 TDN Okada   SPRINT_07 TASK148 Modify layout for Hotel
-->
<template>
    <!-- card-body -->
    <!-- = SPRINT_07 TASK148 -->
    <!-- <div class="py-2" :class="tableHeight"> -->
    <div class="my-2 h-450 bg-content">
        <!-- <div class="input-group col-sm-6 float-right my-2"> -->
        <div class="input-group col-sm-6 pl-2 pt-2 my-left" style="width: 300px;">
            <!-- = SPRINT_07 TASK148 -->
            <input v-model="tableData.search" @input="getData()" type="text" class="form-control"
                   :placeholder="$t('search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <div class="hardware-scroll-bar">
            <div class="py-2 h-450">
                <!-- table -->
                <div class="table-responsive">
                    <b-table selectable ref="deviceTable" select-mode="single" :items="devices" :fields="table.fields"
                             :current-page="table.currentPage" :per-page="table.perPage" :filter="tableData.search"
                             @row-clicked="showGatewayDetails">

                        <!-- GATEWAY NAME COLUMN -->
                        <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="gateway" slot-scope="row">
                            {{row.item.GATEWAY_NAME}}
                        </template>

                        <!-- DEVICE TYPE COLUMN -->
                        <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="devtype" slot-scope="row">
                            {{row.item.DEVICE_TYPE}}
                        </template>

                        <!-- DEVICE SERIAL NO COLUMN -->
                        <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="serialno" slot-scope="row">
                            {{row.item.DEVICE_SERIAL_NO}}
                        </template>

                        <!-- ACTION COLUMN -->
                        <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="action" slot-scope="row">

                            <!-- REGISTER DEVICE BUTTON -->
                            <a v-if="tableData.flag == 0" class="btn" @click="openModal('addModal',row.item)">
                                <span class="">
                                    <i class="text-primary fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>

                            <!-- BLOCK DEVICE BUTTON -->
                            <a v-if="tableData.flag == 0" class="btn" @click="block(row.item.DEVICE_ID)">
                                <span class="">
                                    <i class="text-danger fa fa-ban fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>

                            <!-- UNBLOCK DEVICE BUTTON -->
                            <a v-if="tableData.flag == 4" class="btn" @click="unblock(row.item.DEVICE_ID)">
                                <span class="">
                                    <i class="text-primary fa fa-check-circle fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>

                            <!-- DELETE DEVICE BUTTON -->
                            <a class="btn" @click="del(row.item.DEVICE_ID)">
                                <span class="">
                                    <i class="text-danger fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>
                        </template>
                    </b-table>

                    <!-- DISPLAYS IF THE TABLE IS EMPTY -->
                    <div v-if="devices.length == 0" class="d-flex justify-content-center">
                        <strong>{{$t('binding.noDevice')}}</strong>
                    </div>

                </div>
                <!-- table end -->
                <!-- = SPRINT_07 TASK148 -->
                <!-- <div class="col-md-12 pagination-class"> -->
                <!-- <div class="col-md-12 pagination-class"> -->
                <!-- = SPRINT_07 TASK148 -->
                <!-- <div class="d-flex justify-content-center" :class="devices.length > 24 ? '':'custom-pagination-white'"> -->
                <!-- <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" /> -->
                <!-- </div> -->
                <!-- </div> -->
                <!-- add modal component -->
                <div v-if="chooseModal == 'addModal'">
                    <AddModal :show="activeModal" :modalData="modalData" :cat="category"
                              :currentPage="table.currentPage" @loaddata="getData($event)" @close="closeModal" />
                </div>
                <!-- complete scan modal component -->
                <!-- <div v-else-if="chooseModal == 'completeScanModal'">
                    <CompleteScanModal :show="activeModal" :category="category" :currentPage="table.currentPage"
                                       @loaddata="getData($event)" @close="closeModal"
                                       @scanComplete="deviceLoader= false" />
                </div> -->
            </div>
            <!-- = SPRINT_09 TASK148 -->
        </div>
        <!-- <div class="position-relative mt-5 pt-3"> -->
        <div class="category_position pt-2">
            <div class="d-flex">
                <!-- scan button -->
                <!-- check if the flag/category if 0 = unregistered -->
                <div class="mr-2" v-if="tableData.flag == 0">
                    <!-- call scan function on line 381 -->
                    <button class="btn btn-primary" @click="scan" :disabled="deviceLoader">
                        <!-- change the text value of button when click -->
                        <i v-if="deviceLoader" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true"></i>
                        {{ $t(deviceLoader ? 'gateway.rescanning' : 'gateway.rescan') }}
                    </button>
                </div>
                <!-- scan button end-->

                <!-- gateway status condition -->
                <div :class="[{ 'col-sm-12': tableData.flag === 1 }]">
                    <!-- call getData function on line when category is change -->
                    <select class="custom-select custom-select-gateway" v-model="tableData.flag"
                            @change="changeCategory()">
                        <option value="1">{{$t('gateway.gatewayCat.regist')}}</option>
                        <option value="0">{{$t('gateway.gatewayCat.unregist')}}</option>
                        <option value="4">{{$t('gateway.gatewayCat.blocked')}}</option>
                    </select>
                </div>
                <!-- gateway status condition end-->
            </div>
        </div>
        <!-- </div> -->
    </div>
    <!-- = SPRINT_09 TASK148 -->
    <!-- card-body end -->
</template>
<script>
import AddModal from '../../Modals/AddModal.vue'
import CompleteScanModal from '../../Modals/CompleteScanModal.vue'

export default {
    // declare components
    components: {
        AddModal,
        CompleteScanModal,
    },

    created() {
        // call getData function
        this.getData()
        this.$bus.$on('getDeviceData', data => {
            this.getData()
        })
        this.$bus.$on('changeDeviceTab', data => {
            if (data == 'sysDevice') {
                this.changeCategory()
            }
        })
    },
    // + SPRINT_05 TASK021
    props: ['locale'],
    // + SPRINT_05 TASK021
    data() {
        return {
            table: {
                fields: [
                    {
                        key: 'DEVICE_ID',
                        label: 'ID',
                        sortable: true,
                    },
                    // - SPRINT_09 TASK148
                    // {
                    //     key: "FLOOR_NAME",
                    //     label: 'FLOOR NAME',
                    //     sortable: true
                    // },
                    // - SPRINT_09 TASK148
                    {
                        key: 'ROOM_NAME',
                        label: 'ROOM NAME',
                        sortable: true,
                    },
                    {
                        key: 'DEVICE_NAME',
                        label: 'DEVICE NAME',
                        sortable: true,
                    },
                ],
                currentPage: 1,
                totalRows: 0,
                // = SPRINT_08 TASK148
                // perPage: 10,
                // = SPRINT_08 TASK148
            },
            devices: [],
            tableData: {
                search: '',
                flag: '1',
            },
            category: '',
            activeModal: '',
            chooseModal: '',
            modalData: '',
            selectedRow: '',
            deviceLoader: false,
            isUnregister: false,
        }
    },

    mounted() {
        this.changeTable()
    },

    watch: {
        locale: function () {
            this.changeTable()
        },
    },

    methods: {
        // Function Name: catchError
        // Function Description: Store errors to variable "errors"
        // Param: error
        changeTable() {
            ///for table header multilingual support
            let fields = this.table.fields
            let head = this.$t('device.deviceTable')
            for (var i in fields) {
                Object.keys(head).forEach(function (mess) {
                    if (fields[i].key == mess) {
                        fields[i].label = head[mess]
                    }
                })
            }
            this.table.fields = fields
            if (this.$children[0]) {
                this.$children[0].refresh()
            }
        },

        catchError(error) {
            this.errors = error.response.data.errors
        },

        // Function Name: getData
        // Function Description: Get device data depending on REG_FLAG
        // Param: pages
        getData(pages) {
            // get device data depends of REG-FLAG
            // REG_FLAG: 1 = REGISTERED, 0 = UNREGISTERED, 4 = BLOCKED, 9 = DELETED
            let page_url
            let message = this.$t('device.deviceModals')
            if (this.tableData.flag == 1) {
                page_url = 'getRegisteredDevices'
                // this.table.perPage = 10;
                if (this.table.fields.length > 4) {
                    this.table.fields.splice(3, 5, {
                        key: 'DEVICE_NAME',
                        label: 'DEVICE NAME',
                        sortable: true,
                    })
                }
            } else if (this.tableData.flag == 0) {
                page_url = 'getUnregisteredDevices'
                this.table.perPage = 8
                if (this.table.fields.length == 4) {
                    this.table.fields.splice(3, 1, {
                        key: 'gateway',
                        label: this.$t('device.gatewayName'),
                        sortable: true,
                    })
                    this.table.fields.push(
                        {
                            key: 'DEVICE_NAME',
                            label: this.$t('device.deviceTable.DEVICE_NAME'),
                            sortable: true,
                        },
                        {
                            key: 'devtype',
                            label: this.$t('device.deviceTable.DEVICE_TYPE'),
                            sortable: true,
                        },
                        {
                            key: 'serialno',
                            label: this.$t('device.deviceTable.SERIAL_NO'),
                            sortable: true,
                        },
                        {
                            key: 'action',
                            label: this.$t('device.deviceTable.action'),
                            sortable: true,
                        }
                    )
                }
            } else if (this.tableData.flag == 4) {
                page_url = 'getBlockedDevices'
                this.table.perPage = 8
                if ((this.table.fields.length = 4)) {
                    this.table.fields.splice(3, 1, {
                        key: 'gateway',
                        label: this.$t('device.gatewayName'),
                        sortable: true,
                    })
                    this.table.fields.push(
                        {
                            key: 'DEVICE_NAME',
                            label: this.$t('device.deviceTable.DEVICE_NAME'),
                            sortable: true,
                        },
                        {
                            key: 'devtype',
                            label: this.$t('device.deviceTable.DEVICE_TYPE'),
                            sortable: true,
                        },
                        {
                            key: 'serialno',
                            label: this.$t('device.deviceTable.SERIAL_NO'),
                            sortable: true,
                        },
                        {
                            key: 'action',
                            label: this.$t('device.deviceTable.action'),
                            // + SPRINT_10 TASK148
                            sortable: true,
                            // + SPRINT_10 TASK148
                        }
                    )
                }
            }
            // ajax call
            // PARAMS: pageLength,include,sortBy,sortVal,search,page
            axios
                .get(page_url, {
                    params: {
                        manufacturerID: 4,
                    },
                })
                .then(response => {
                    let data = response.data
                    // = SPRINT_09 TASK148
                    // console.log('checking here');
                    // console.log(data);
                    // if (data == 'failed') {
                    //     this.devices= [];
                    //     this.table.totalRows = this.devices.length;
                    //     console.log('aaaa');
                    //     this.$swal(
                    //         message.error,
                    //         message.failedDevice,
                    //         'error'
                    //     );
                    // } else {
                    for (var i in data) {
                        if (data[i].ROOM_NAME == null) {
                            data[i].ROOM_NAME = '-'
                        }
                        // - SPRINT_09 TASK148
                        // if (data[i].FLOOR_NAME == null) {
                        //     data[i].FLOOR_NAME = '-';
                        // }
                        // - SPRINT_09 TASK148
                        if (data[i].DEVICE_NAME == null) {
                            data[i].DEVICE_NAME = '-'
                        }
                    }
                    this.devices = data
                    this.table.totalRows = this.devices.length
                    // }
                    // = SPRINT_09 TASK148
                })
                .catch(error => {
                    let errormessage = error.response.data.file + ' : ' + error.response.data.message
                    // axios.post('createSystemLogs', {
                    //     ERROR_MESSAGE: errormessage
                    // });
                    var title = this.$t('modalText.error')
                    var message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
        },

        // Funciton Name: openModal
        // Function Description: opens specific modal function
        // Param: modal (modal name), item (device)
        openModal(modal, item) {
            // dispaly modal
            this.chooseModal = modal
            this.activeModal = 'd-block'
            // determine if the modal is gateway or device
            this.category = 'device'
            // set data
            this.modalData = item
        },

        // Function Name: block
        // Function Description: blocks device
        // Param: id (device_id)
        block(id) {
            this.$swal({
                title: 'Block Device',
                text: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            }).then(result => {
                if (result.value) {
                    axios
                        .post('blockDevice', {
                            DEVICE_ID: id,
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                this.$swal({
                                    title: 'Block',
                                    text: 'Device has been Block.',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                this.$swal('Error', "Device can't Block.", 'error')
                            }
                            this.getData(this.table.currentPage)
                        })
                }
            })
        },

        // Function Name: unblock
        // Function Description: unblocks device
        // Param: id (device_id)
        unblock(id) {
            this.$swal({
                title: 'Unblock Device',
                text: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            }).then(result => {
                if (result.value) {
                    axios
                        .post('unblockDevice', {
                            DEVICE_ID: id,
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                this.$swal({
                                    title: 'Unblock',
                                    text: 'Device has been Unblock.',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                this.$swal('Error', "Device can't Unblock.", 'error')
                            }
                            this.getData(this.table.currentPage)
                        })
                }
            })
        },

        // Function Name: del
        // Function Description: deletes device
        // Param: id (device_id)
        del(id) {
            this.$swal({
                title: 'Delete Device',
                text: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            }).then(result => {
                if (result.value) {
                    axios
                        .post('deleteDevice', {
                            KEY: 'device',
                            DEVICE_ID: id,
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                this.$swal({
                                    title: 'Deleted',
                                    text: 'Device has been deleted.',
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                this.$swal('Error', "Device can't delete.", 'error')
                            }
                            this.getData(this.table.currentPage)
                        })
                }
            })
        },

        // Function Name: scan
        // Function Description: opens modal scan function
        scan() {
            this.deviceLoader = true
            // this.openModal('completeScanModal', 'device')
            axios
                .get('scanDeviceAll')
                .then(response => {
                    this.$swal({
                        type: 'success',
                        title: this.$t('gateway.scanSuccessful'),
                        timer: 3000,
                        showConfirmButton: false,
                    })
                })
                .catch(error => {
                    this.$swal({
                        type: 'error',
                        title: this.$t('gateway.scanFailed'),
                        timer: 3000,
                        showConfirmButton: false,
                    })
                    this.errors.push(error.response)
                })
                .then(() => {
                    this.deviceLoader = false
                })
        },

        // Function Name: closeModal
        // Function Description: close modal function
        closeModal() {
            this.category = this.modalData = this.activeModal = this.chooseModal = ''
        },

        // Function Name: showGatewayDetails
        // Function Description: shows device details
        // Param: details (device)
        showGatewayDetails(details) {
            this.$set(details, 'category', 'device')
            this.$bus.$emit('selectedDeviceData', details)
            this.selectedRow = details.DEVICE_ID
        },

        // Function Name: changeCategory
        // Function Description: change table size according to category
        // Param: table.flag
        changeCategory() {
            var details = ''
            var column = this.columns
            var style = 'table-cell'

            if (this.tableData.flag == 0 || this.tableData.flag == 4) {
                this.isUnregister = true
                style = 'table-cell'
            } else if (this.tableData.flag == 1) {
                this.isUnregister = false
                style = 'none'
            }
            for (var i in column) {
                if (i == 3 || i == 5 || i == 6 || i == 7) {
                    column[i].display = style
                }
            }
            this.$emit('change-size', this.isUnregister)
            this.$bus.$emit('selectedDeviceData', details)
            this.selectedRow = ''
            this.getData()
        },
    },

    computed: {
        // changes table height according to screen height and table length
        tableHeight() {
            var tbHeight = ''
            if (this.$screenHeight > 900) {
                if (!this.$isMobile.isMobile) {
                    // = SPRINT_07 TASK148
                    //tbHeight = 'h-75vh';
                    tbHeight = 'h-50vh'
                }
                // if (this.table.totalRows >= 10) {
                //     tbHeight = 'h-100';
                // } else {
                //
                //     //tbHeight = 'h-75vh';
                //     tbHeight = 'h-50vh';
                //
                // }
                // }
                // = SPRINT_07 TASK148
            } else {
                tbHeight = 'h-100'
            }
            return tbHeight
        },
    },
}
</script>

<style scoped>
.bg-content {
    background-color: rgb(89, 89, 89);
}
</style>
