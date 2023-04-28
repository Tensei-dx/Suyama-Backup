<!--
    <Create> 2020.10.16 TP Uddin
    <Updated> 2021.09.21 TDN Okada  Modify layout for Hotel
-->
<template>
    <!-- card-body -->
    <!-- = SPRINT_08 TASK148 -->
    <!-- <div class="py-2" :class="tableHeight"> -->
    <div class="my-2 h-450 bg-content">
        <div class="input-group col-sm-6 float-left pl-2 pt-2" style="width: 300px;">
            <!-- = SPRINT_08 TASK148 -->
            <input v-model="tableData.search" @input="getData()" type="text" class="form-control"
                   :placeholder="$t('search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <div class="hardware-scroll-bar">
            <div class="py-2 h-470">
                <!-- <div class="input-group col-sm-6 float-right my-2"> -->

                <!-- table -->
                <div class="table-responsive">
                    <b-table selectables ref="deviceTable" select-mode="single" :items="devices" :fields="table.fields"
                             :current-page="table.currentPage" :per-page="table.perPage" :filter="tableData.search"
                             @row-clicked="showGatewayDetails">
                        <template v-if="tableData.flag == 0" slot="gateway" slot-scope="row">
                            {{row.item.GATEWAY_NAME}}
                        </template>
                        <template v-if="tableData.flag == 0" slot="devtype" slot-scope="row">
                            {{row.item.DEVICE_TYPE}}
                        </template>
                        <template v-if="tableData.flag == 0" slot="serialno" slot-scope="row">
                            {{row.item.DEVICE_SERIAL_NO}}
                        </template>
                        <template v-if="tableData.flag == 0" slot="action" slot-scope="row">
                            <a class="btn" @click="openModal('addModal',row.item)">
                                <span class="">
                                    <i class="text-primary fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>
                            <a class="btn" @click="del(row.item.DEVICE_ID)">
                                <span class="">
                                    <i class="text-danger fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                </span>
                            </a>
                        </template>
                    </b-table>
                    <div v-if="devices.length == 0" class="d-flex justify-content-center">
                        <strong>{{$t('binding.noDevice')}}</strong>
                    </div>
                </div>
                <!-- table end -->
                <!-- - SPRINT_08 TASK148 -->
                <!-- <div class="col-md-12 pagination-class"> -->
                <!-- <div class="d-flex justify-content-center" :class="devices.length > 24 ? '':'custom-pagination-white'"> -->
                <!-- <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" /> -->
                <!-- </div> -->
                <!-- </div> -->
                <!-- - SPRINT_08 TASK148 -->
                <!-- add modal component -->
                <div v-if="chooseModal == 'addModal'">
                    <AddModal :show="activeModal" :modalData="modalData" :cat="category"
                              :currentPage="table.currentPage" @loaddata="getData($event)" @close="closeModal">
                    </AddModal>
                </div>
                <!-- complete scan modal component -->
                <!-- <div v-else-if="chooseModal == 'completeScanModal'">
                    <CompleteScanModal :show="activeModal" :category="category" :currentPage="table.currentPage"
                                       @loaddata="getData($event)" @close="closeModal"
                                       @scanComplete="cameraLoader= false"></CompleteScanModal>
                </div> -->
            </div>
            <!-- = SPRINT_09 TASK148 -->
        </div>
        <!-- <div class="position-relative mt-5 pt-3 bg-content"> -->
        <div class="category_position pt-2">
            <div class="d-flex">
                <!-- scan button -->
                <!-- check if the flag/category if 0 = unregistered -->
                <div class="mr-2" v-if="tableData.flag == 0">
                    <!-- call scan function on line 381 -->
                    <button class="btn btn-primary" @click="scan" :disabled="cameraLoader">
                        <!-- change the text value of button when click -->
                        <i v-if="cameraLoader" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true"></i>
                        {{ $t(cameraLoader ? 'gateway.rescanning' : 'gateway.rescan') }}

                    </button>
                </div>
                <!-- scan button end -->

                <!-- gateway status condition -->
                <div :class="[{ 'col-sm-12': tableData.flag === 1 }]">
                    <!-- call getData function on line when category is change -->
                    <select class="custom-select custom-select-gateway" v-model="tableData.flag"
                            @change="changeCategory()">
                        <option value="1">{{$t('gateway.gatewayCat.regist')}}</option>
                        <option value="0">{{$t('gateway.gatewayCat.unregist')}}</option>
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
import AddModal from '../../Modals/AddCameraModal.vue'
import CompleteScanModal from '../../Modals/CompleteScanModal.vue'
export default {
    //declare components
    components: { AddModal, CompleteScanModal },
    created() {
        //call getData function
        this.getData()
        this.$bus.$on('getDeviceData', data => {
            this.getData()
        })
        this.$bus.$on('changeDeviceTab', data => {
            if (data == 'cameraDevice') {
                this.changeCategory()
            }
        })
    },
    // + SPRINT_09 TASK148
    props: ['locale'],
    // + SPRINT_09 TASK148
    data() {
        return {
            table: {
                fields: [
                    { key: 'DEVICE_ID', label: 'ID', sortable: true },
                    //- SPRINT_09 TASK148
                    // {key: "FLOOR_NAME",  label: "FLOOR NAME",  sortable: true},
                    // - SPRINT_09 TASK148
                    { key: 'ROOM_NAME', label: 'ROOM NAME', sortable: true },
                    { key: 'DEVICE_NAME', label: 'DEVICE NAME', sortable: true },
                ],
                currentPage: 1,
                totalRows: 0,
                perPage: 10,
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
            cameraLoader: false,
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
        //Function Name: getData
        //Function Description: Get device data depending on REG_FLAG
        //Param: pages
        getData(pages) {
            let message = this.$t('device.deviceModals')
            let url
            if (this.tableData.flag == 1) {
                url = 'getRegisteredCameras'
                this.table.perPage = 10
                if (this.table.fields.length > 4) {
                    this.table.fields.splice(3, 5, {
                        key: 'DEVICE_NAME',
                        label: 'DEVICE NAME',
                        sortable: true,
                    })
                }
            } else if (this.tableData.flag == 0) {
                url = 'getUnregisteredCameras'
                this.tableData.perPage = 8
                if (this.table.fields.length == 4) {
                    this.table.fields.splice(3, 1, {
                        key: 'GATEWAY_NAME',
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
            }
            axios
                .get(url, {
                    params: {
                        include: 'floor>room>gateway',
                        search: this.tableData.search,
                    },
                })
                .then(response => {
                    let data = response.data
                    // = SPRINT_09 TASK148
                    // if (data == 'failed') {
                    //     this.devices = [];
                    //     this.table.totalRows = this.devices.length;
                    //     this.$swal(message.error, message.failedDevice, 'error');
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
                    axios.post('createSystemLogs', {
                        ERROR_MESSAGE: error.message,
                    })
                    var title = this.$t('modalText.error')
                    var message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
        },
        //Function Name: openModal
        //Function Description: opens specific modal function
        //Param: modal (modal name), item (device)
        openModal(modal, item) {
            //display modal
            this.chooseModal = modal

            this.activeModal = 'd-block'
            // determine if the modal is gateway or device
            this.category = 'camera'
            // set data
            this.modalData = item
        },
        //Function Name: del
        //Function Description: deletes device
        //Param: id (device_id)
        del(id) {
            this.$swal({
                title: 'Delete Device',
                text: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then(result => {
                if (result.value) {
                    axios
                        .post('deleteCamera', {
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
        //Function Name: scan
        //Function Description: opens modal scan function
        scan() {
            this.cameraLoader = true
            // this.openModal('completeScanModal', 'camera')
            axios
                .post('scanAllCameras')
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
                    this.cameraLoader = false
                })
        },
        //Function Name: closeModal
        //Function Description: close modal function
        closeModal() {
            this.category = this.modalData = this.activeModal = this.chooseModal = ''
        },
        //Function Name: showGatewayDetails
        //Function Description: shows device details
        //Param: details (device)
        showGatewayDetails(details) {
            this.$set(details, 'category', 'camera')
            this.$bus.$emit('selectedDeviceData', details)
            this.selectedRow = details.DEVICE_ID
        },
        //Function Name: changeCategory
        //Function Description: change table size according to category
        //Param: table.flag
        changeCategory() {
            var details = ''
            var column = this.columns
            var style = 'table-cell'

            if (this.tableData.flag == 0) {
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
        //changes table height according to screen height and table length
        tableHeight() {
            var tbHeight = ''
            if (this.$screenHeight > 900) {
                if (!this.$isMobile.isMobile) {
                    // = SPRINT_08 TASK148
                    //tbHeight = 'h-75vh';
                    tbHeight = 'h-50vh'
                }
                // } else {
                //     if (this.table.totalRows >= 10) {
                //         tbHeight = 'h-100';
                //     } else {
                //         tbHeight = 'h-75vh';
                //     }
                // }
                // = SPRINT_08 TASK148
            } else {
                tbHeight = 'h-100'
            }
            return tbHeight
        },
    },
    beforeDestroy() {
        this.$bus.$off('changeDeviceTab')
    },
}
</script>

<style scoped>
.bg-content {
    background-color: rgb(89, 89, 89);
}
</style>
