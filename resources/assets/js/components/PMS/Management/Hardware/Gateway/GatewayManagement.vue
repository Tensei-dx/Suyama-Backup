<!--
    <System Name> iBMS
    <Program Name> GatewayManagement.vue

    <Created>            TP Harvey
    <Updated> 2019.07.03 TP Mark  Applying PG Implementation Matrix (Frontend)
              2019.07.11 TP Mark  Applying Horizontal Expansion
              2020.05.26 TP Uddin Modify axios URL according to the URL list
              2021.09.16 TP Chris  Modify layout for Hotel
-->
<template>
    <!-- card-body -->
    <!-- <div class="py-2" :class="tableHeight"> -->
    <div class="my-2 pb-0 h-450 bg-content">
        <!-- = SPRINT_07 TASK147 -->
        <!-- <div class="input-group col-sm-6 float-right my-2"> -->
        <div class="input-group col-sm-6 pl-2 my-left pt-2" style="width: 300px;">
            <!-- = SPRINT_07 TASK147 -->
            <input v-model="tableData.search" @input="getData()" type="text" class="form-control"
                   :placeholder="$t('search')">
            <div class="input-group-prepend ">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <!-- table -->
        <div class="table-responsive">
            <b-table selectable sortable ref=" gatewayTable" select-mode="single" :items="gateways"
                     :fields="table.fields" :current-page="table.currentPage" :per-page="table.perPage"
                     :filter="tableData.search" @row-clicked="showGatewayDetails">
                <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="ip" slot-scope="row">
                    {{row.item.GATEWAY_IP}}
                </template>
                <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="serial_no" slot-scope="row">
                    {{row.item.GATEWAY_SERIAL_NO}}
                </template>
                <template v-if="tableData.flag == 0 || tableData.flag == 4" slot="action" slot-scope="row">
                    <a v-if="tableData.flag == 0" class="btn" @click="openModal('addModal',row.item)">
                        <span class="">
                            <i class="text-primary fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                        </span>
                    </a>
                    <a v-if="tableData.flag == 0" class="btn" @click="block(row.item.GATEWAY_ID)">
                        <span class="">
                            <i class="text-danger fa fa-ban fa-lg" aria-hidden="true"></i>
                        </span>
                    </a>
                    <a v-if="tableData.flag == 4" class="btn" @click="unblock(row.item.GATEWAY_ID)">
                        <span class="">
                            <i class="text-primary fa fa-check-circle fa-lg" aria-hidden="true"></i>
                        </span>
                    </a>
                    <a class="btn" @click="del(row.item.GATEWAY_ID)">
                        <span class="">
                            <i class="text-danger fa fa-trash-o fa-lg" aria-hidden="true"></i>
                        </span>
                    </a>
                </template>
            </b-table>
            <div v-if="gateways.length == 0" class="d-flex justify-content-center">
                <strong>{{$t('binding.noDevice')}}</strong>
            </div>
            <!-- <div class="position-relative"> -->
            <div class="category_position">
                <div class="d-flex">
                    <!-- scan button -->
                    <!-- check if the flag/category if 0 = unregistered -->
                    <div class="mt-5 mr-2" v-if="tableData.flag == 0">
                        <!-- call scan function on line 381 -->
                        <button class="btn btn-primary" @click="scan" :disabled="scan_loader">
                            <!-- display loading animation -->
                            <!-- <span v-if="scan_loader"> -->
                            <i v-if="scan_loader" class="fa fa-circle-o-notch fa-spin mr-1" aria-hidden="true"></i>
                            {{ $t(scan_loader ? 'gateway.rescanning' : 'gateway.rescan') }}
                            <!-- </span> -->
                            <!-- change the text value of button when click -->
                            <!-- <span> -->

                            <!-- {{$t('gateway.rescan')}} -->

                            <!-- </span> -->
                        </button>
                    </div>
                    <!-- scan button end-->
                    <!-- gateway status condition -->
                    <div :class=" [{ 'col-sm-12' : tableData.flag===1 }]" style="margin-bottom: 0px;">
                        <!-- call getData function on line when category is change -->
                        <select class="custom-select mt-5" v-model="tableData.flag" @change="changeCategory()">
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
        <!-- table end -->
        <!-- pagination component -->
        <!-- call getData function when prev, next and number is click -->
        <!-- comment out the pagination due to limited devices to be installed -->
        <!-- = SPRINT_07 TASK147 -->
        <!-- <div class="col-md-12 pagination-class"> -->
        <!-- <div class="col-md-12 pagination-class-hotel"> -->
        <!-- = SPRINT_07 TASK147 -->
        <!-- <div class="d-flex justify-content-center" v-if="gateways.length > 7" :class="gateways.length > 24 ? '':'custom-pagination-white'" style="margin-top: 90px;">
                <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" />
            </div>
        </div> -->
        <!-- add modal component -->
        <div v-if="chooseModal == 'addModal'">
            <AddModal :show="activeModal" :modalData="modalData" :cat="category" :currentPage="table.currentPage"
                      @loaddata="getData($event)" @close="closeModal"></AddModal>
        </div>
        <!-- <div v-else-if="chooseModal == 'completeScanModal'">
            <CompleteScanModal :show="activeModal" :currentPage="table.currentPage" @loaddata="getData($event)"
                               @close="closeModal" @scanComplete="scan_loader= false"></CompleteScanModal>
        </div> -->
    </div>
    <!-- card-body-end -->

</template>
<script>
// imported components
import AddModal from '../../Modals/AddModal.vue'
//  import CompleteScanModal from '../../Modals/CompleteScanModal.vue'
export default {
    //declare components
    components: {
        AddModal,
        // CompleteScanModal,
    },
    props: ['locale'],
    created() {
        //call getData function
        this.getData()
        this.$bus.$on('getData', data => {
            this.getData()
        })
        this.$bus.$on('changeTab', data => {
            this.changeCategory()
        })
    },
    mounted() {
        this.$i18n.locale = this.$parent.$i18n.locale
        this.changeTableText()
    },
    data() {
        return {
            // where data variables is declare and initialize
            table: {
                fields: [
                    { key: 'GATEWAY_ID', label: 'ID', sortable: true },
                    // Comment out the FLOOR field
                    // {key:'FLOOR_NAME', label:'FLOOR NAME', sortable: true},
                    { key: 'ROOM_NAME', label: 'ROOM NAME', sortable: true },
                    { key: 'GATEWAY_NAME', label: 'GATEWAY NAME', sortable: true },
                ],
                currentPage: 1,
                totalRows: 0,
                perPage: 5,
            },
            tableData: {
                search: '',
                flag: 1,
            },
            gateways: [],
            category: '',
            activeModal: '',
            chooseModal: '',
            modalData: '',
            scan_loader: false,
            progressbar: false,
            progress_value: 0,
            selectedRow: '',
            isUnregister: false,
        }
    },
    methods: {
        //Function Name: getData
        //Function Description: Get gateway data depending on REG_FLAG
        //Param: pages
        getData(pages) {
            //get gateway data depends of REG-FLAG
            //REG_FLAG: 1 = REGISTERED, 0 = UNREGISTERED, 4 = BLOCKED, 9 = DELETED
            let page_url
            if (this.tableData.flag == 1) {
                page_url = 'getRegisteredGateways?' || 'getRegisteredGateways'
                this.table.perPage = 5
                if (this.table.fields.length > 4) {
                    this.table.fields.splice(4, 3)
                }
                // this.$refs.gatewayTable.refresh();
            } else if (this.tableData.flag == 0) {
                this.table.perPage = 8
                page_url = 'getUnregisteredGateways?' || 'getUnregisteredGateways'
                if ((this.table.fields.length = 4)) {
                    this.table.fields.push(
                        { key: 'ip', label: this.$t('gateway.gatewayTable.ip'), sortable: true },
                        { key: 'serial_no', label: this.$t('gateway.gatewayTable.serial_no'), sortable: true },
                        { key: 'action', label: this.$t('gateway.gatewayTable.action'), sortable: true }
                    )
                }
            } else if (this.tableData.flag == 4) {
                this.table.perPage = 8
                page_url = 'getBlockedGateways?' || 'getBlockedGateways'
                if ((this.table.fields.length = 4)) {
                    this.table.fields.push(
                        { key: 'ip', label: this.$t('gateway.gatewayTable.ip'), sortable: true },
                        { key: 'serial_no', label: this.$t('gateway.gatewayTable.serial_no'), sortable: true },
                        { key: 'action', label: this.$t('gateway.gatewayTable.action'), sortable: true }
                    )
                }
            }
            //ajax call
            //PARAM: pageLength, include, sortBy, sortval, search, page
            axios
                .get(page_url, {
                    params: {
                        manufacturerID: 4,
                    },
                })
                .then(response => {
                    let data = response.data
                    for (var i in data) {
                        if (data[i].ROOM_NAME == null) {
                            data[i].ROOM_NAME = '-'
                        }
                        if (data[i].FLOOR_NAME == null) {
                            data[i].FLOOR_NAME = '-'
                        }
                        if (data[i].GATEWAY_NAME == null) {
                            data[i].GATEWAY_NAME = '-'
                        }
                    }
                    this.gateways = data
                    this.table.totalRows = this.gateways.length
                })
                .catch(error => {
                    let errormessage = error.response.data.file + ' : ' + error.response.data.message
                    axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                    var title = this.$t('modalText.error')
                    var message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
            this.reload = false
        },
        //Funciton Name: openModal
        //Function Description: opens specific modal function
        //Param: modal (modal name), item (gateway)
        openModal(modal, item) {
            //dispaly modal
            this.chooseModal = modal
            this.activeModal = 'd-block'
            // determine if the modal is gateway or device
            this.category = 'gateway'
            // set data
            this.modalData = item
        },
        //Function Name: block
        //Function Description: blocks device
        //Param: id (gateway_id)
        block(id) {
            let message = this.$t('gateway.modalText')
            let errorMessage = this.$t('error_message_code')
            this.$swal({
                title: message.blockGateway,
                text: message.sure,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            })
                .then(result => {
                    if (result.value) {
                        axios
                            .post('blockGateway', {
                                GATEWAY_ID: id,
                            })
                            .then(response => {
                                if (response.data == 'success') {
                                    this.$swal({
                                        title: message.block,
                                        text: message.gatewayBlock,
                                        type: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    })
                                } else {
                                    this.$swal('Error', errorMessage.ERR_OPS_012, 'error')
                                }
                                this.getData(this.table.currentPage)
                            })
                    }
                })
                .catch(error => {
                    let errormessage = error.response.data.file + ' : ' + error.response.data.message
                    axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                    var title = this.$t('modalText.error')
                    var message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
        },
        //Function Name: unblock
        //Function Description: unblocks device
        //Param: id (gateway_id)
        unblock(id) {
            let message = this.$t('gateway.modalText')
            let errorMessage = this.$t('error_message_code')
            this.$swal({
                title: message.unblockGateway,
                text: message.sure,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            })
                .then(result => {
                    if (result.value) {
                        axios
                            .post('unblockGateway', {
                                GATEWAY_ID: id,
                            })
                            .then(response => {
                                if (response.data == 'success') {
                                    this.$swal({
                                        title: message.unblock,
                                        text: message.gatewayUnblock,
                                        type: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    })
                                } else {
                                    this.$swal('Error', errorMessage.ERR_OPS_014, 'error')
                                }
                                this.getData(this.table.currentPage)
                            })
                    }
                })
                .catch(error => {
                    let errormessage = error.response.data.file + ' : ' + error.response.data.message
                    axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                    var title = this.$t('modalText.error')
                    var message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
        },
        //Function Name: del
        //Function Description: deletes gateway
        //Param: id (gateway_id)
        del(id) {
            let message = this.$t('gateway.modalText')
            let errorMessage = this.$t('error_message_code')
            this.$swal({
                title: message.delGateway,
                text: message.sure,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                cancelButtonText: this.$t('user.cancel'),
                confirmButtonText: this.$t('device.deviceModals.yes'),
            }).then(result => {
                if (result.value) {
                    axios
                        .post('deleteGateway', {
                            KEY: 'gateway',
                            GATEWAY_ID: id,
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                this.$swal({
                                    title: message.deleted,
                                    text: message.gatewayDel,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else if (response.data == 'gateway') {
                                this.$swal({
                                    title: message.noContact,
                                    text: message.contDelete,
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#aaa',
                                    cancelButtonText: this.$t('user.cancel'),
                                    confirmButtonText: this.$t('device.deviceModals.yes'),
                                })
                                    .then(result => {
                                        if (result.value) {
                                            axios
                                                .post('deleteGateway', {
                                                    FORCE: true,
                                                    KEY: 'gateway',
                                                    GATEWAY_ID: id,
                                                })
                                                .then(response => {
                                                    if (response.data == 'success') {
                                                        this.$swal({
                                                            title: message.deleted,
                                                            text: message.gatewayDel,
                                                            type: 'success',
                                                            timer: 1500,
                                                            showConfirmButton: false,
                                                        })
                                                    } else {
                                                        this.$swal('Error', errorMessage.ERR_OPS_011, 'error')
                                                    }
                                                    this.$bus.$emit('getData', response.data)
                                                    this.show = false
                                                })
                                                .catch(error => {
                                                    console.log(error)
                                                })
                                        }
                                    })
                                    .catch(error => {
                                        let errormessage =
                                            error.response.data.file + ' : ' + error.response.data.message
                                        axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                                        var title = this.$t('modalText.error')
                                        var message = this.$t('logs.error')
                                        this.$swal(title, message, 'error')
                                    })
                            } else {
                                this.$swal('Error', errorMessage.ERR_OPS_011, 'error')
                            }
                            this.getData(this.table.currentPage)
                        })
                        .catch(error => {
                            let errormessage = error.response.data.file + ' : ' + error.response.data.message
                            axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                            var title = this.$t('modalText.error')
                            var message = this.$t('logs.error')
                            this.$swal(title, message, 'error')
                        })
                }
            })
        },
        //Function Name: scan
        //Function Description: opens modal scan function
        scan() {
            this.scan_loader = true
            // this.openModal('completeScanModal', '')
            axios
                .get('scanGatewayAll')
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
                    this.scan_loader = false
                })
        },
        //Function Name: closeModal
        //Function Description: close modal function
        closeModal() {
            //clear the data
            this.category = this.modalData = this.activeModal = this.chooseModal = ''
        },
        //Function Name: showGatewayDetails
        //Function Description: shows gateway details
        //Param: details (gateway)
        showGatewayDetails(details) {
            this.$set(details, 'category', 'systemGateway')
            this.$bus.$emit('selectedData', details)
            this.selectedRow = details.GATEWAY_ID
        },
        //Function Name: changeCategory
        //Function Description: change table size according to category
        //Param: table.flag
        changeCategory() {
            var details = ''
            var column = this.columns
            var style = 'table-cell'
            if (this.tableData.flag == 0 || this.tableData.flag == 4) {
                this.isUnregister = true
                style = 'table-cell'
            } else {
                this.isUnregister = false
                style = 'none'
            }
            for (var i in column) {
                if (i == 4 || i == 5 || i == 6) {
                    column[i].display = style
                }
            }
            this.$emit('change-size', this.isUnregister)
            this.$bus.$emit('selectedData', details)
            this.selectedRow = ''
            this.getData()
        },
        //Function Name: changeTable
        //Function Description: change table label according to language settings
        //param: $i18n.locale
        changeTableText() {
            var labels = this.table.fields
            var messages = this.$t('gateway.gatewayTable')
            for (var i in labels) {
                Object.keys(messages).forEach(function (mess) {
                    if (labels[i].key == mess) {
                        labels[i].label = messages[mess]
                    }
                })
            }
            this.table.fields = labels
            if (this.$children[0]) {
                this.$children[0].refresh()
            }
        },
    },
    computed: {
        //table height change according to screen size
        tableHeight() {
            var tbHeight = ''
            if (!this.$isMobile.isMobile) {
                // = SPRINT_07 TASK147
                //tbHeight = 'h-75vh';
                tbHeight = 'h-50vh'
                // = SPRINT_07 TASK147
            } else {
                if (this.table.totalRows >= 10) {
                    tbHeight = 'h-100'
                } else {
                    // = SPRINT_07 TASK147
                    //tbHeight = 'h-75vh';
                    tbHeight = 'h-50vh'
                    // = SPRINT_07 TASK147
                }
            }
            return tbHeight
        },
    },
    watch: {
        locale: function () {
            this.changeTableText()
        },
    },
}
</script>
<style>
.pagination-class-hotel {
    top: 400px;
    position: absolute;
}
.bg-content {
    background-color: rgb(89, 89, 89);
}
.table th,
.table td {
    padding: 0.5rem !important;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
</style>
