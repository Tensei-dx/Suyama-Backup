<!--
    <System Name> iBMS
    <Program Name> CameraGatewayManagement.vue

    <Created> 2020.10.15 TP Uddin
    <Updated> 2021.09.16 TP Chris  Modify layout for Hotel
-->
<template>
    <!-- card-body -->
    <!-- = SPRINT_07 TASK147 -->
    <!-- <div class="py-2 h-700"> -->
    <div class="my-2 h-450 bg-content">
        <!-- <div class="input-group col-sm-6 float-right my-2"> -->
        <div class="input-group col-sm-6 pl-2 pt-2" style="width: 300px;">
            <!-- = SPRINT_07 TASK147 -->
            <input v-model="tableData.search" @input="getData()" type="text" class="form-control"
                   :placeholder="$t('search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <!-- table -->
        <div class="table-responsive" style="padding-left: 0px;">
            <!-- datatable component -->
            <b-table sortable :items="gateways" :fields="table.fields" :current-page="table.currentPage"
                     :per-page="table.perPage" :filter="tableData.search" @row-clicked="showGatewayDetails">
            </b-table>
            <div v-if="gateways.length == 0" class="d-flex justify-content-center">
                <strong>{{$t('binding.noDevice')}}</strong>
            </div>
            <!-- data table component end -->
            <div class="category_position mt-5">
                <div class="d-flex">
                    <div class="mr-2 mb-0" v-if="tableData.flag == 1">
                        <!-- call scan function -->
                        <button class="btn btn-primary" @click="openModal('addModal','')">
                            {{$t('add')}}
                        </button>
                    </div>

                    <!-- gateway status condition -->
                    <div :class="[{ 'col-sm-12': tableData.flag === 9 }]">
                        <!-- call getData function on line when category is change -->
                        <select class="custom-select custom-select-gateway" v-model="tableData.flag"
                                @change="changeCategory()">
                            <option value="1">{{$t('gateway.gatewayCat.regist')}}</option>
                            <!--                             <option value="9">{{$t('gateway.gatewayCat.deleted')}}</option> -->
                        </select>
                    </div>
                    <!-- gateway status condition end-->
                </div>
            </div>
        </div>
        <!-- table end -->
        <!-- pagination component -->
        <!-- call getData function when prev, next and number is click -->
        <!-- comment out the pagination due to limited devices to be installed -->
        <!-- = SPRINT_07 TASK147 -->
        <!-- <div class="col-md-12 pagination-class"> -->
        <!-- <div class="col-md-12 pagination-class-hotel"> -->
        <!-- = SPRINT_07 TASK147 -->
        <!-- <div class="d-flex justify-content-center" :class="gateways.length > 24 ? '':'custom-pagination-white'" style="margin-top: 90px;">
                <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" />
            </div>
        </div> -->
        <!-- add modal component -->
        <div v-if="chooseModal == 'addModal'">
            <AddModal :show="activeModal" :cat="category" :currentPage="table.currentPage" @loaddata="getData($event)"
                      @close="closeModal"></AddModal>
        </div>
    </div>
    <!-- card-body-end -->
</template>
<script>
// imported components
import AddModal from '../../Modals/AddCameraGatewayModal.vue'
export default {
    //declare components
    components: { AddModal },
    props: {
        locale: '',
    },
    created() {
        //call getData function
        this.getData()
        this.$bus.$on('getData', data => {
            this.getData()
        })
        this.$bus.$on('changeTab', data => {
            this.selectedRow = ''
        })
    },
    data() {
        return {
            // where data variables is declare and initialize
            table: {
                fields: [
                    { key: 'GATEWAY_ID', label: 'ID', sortable: true },
                    { key: 'MANUFACTURER_NAME', label: 'MANUFACTURER', sortable: true },
                    { key: 'GATEWAY_NAME', label: 'ROOM NAME', sortable: true },
                    { key: 'GATEWAY_IP', label: 'GATEWAY IP', sortable: true },
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
            chooseModal: '',
            selectedRow: '',
        }
    },
    mounted() {
        this.changeTable()
    },
    methods: {
        //Function Name: getData
        //Function Description: Get gateway data depending on REG_FLAG
        //Param: pages, tableData.flag
        getData(pages) {
            //get gateway data depends of REG-FLAG
            let page_url
            if (this.tableData.flag == 1) {
                page_url = 'getRegisteredGateways?' || 'getRegisteredGateways'
            }
            //ajax call
            //PARAM: search
            axios
                .get(page_url, {
                    params: {
                        manufacturerID: 4,
                    },
                })
                .then(response => {
                    let data = response.data
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
        },
        //Function Name: showGatewayDetails
        //Function Description: shows modbus gateway details
        //Param: details (gateway)
        showGatewayDetails(details) {
            this.$set(details, 'category', 'cameraGateway')
            this.$bus.$emit('selectedData', details)
            this.selectedRow = details.GATEWAY_ID
        },
        //Function Name: changeCategory
        //Function Description: changes category displayed
        changeCategory() {
            var details = ''
            this.$bus.$emit('selectedData', details)
            this.selectedRow = ''
            this.getData()
        },
        //Funciton Name: openModal
        //Function Description: opens specific modal function
        //Param: modal (modal name), item (device)
        openModal(modal, item) {
            //dispaly modal
            this.chooseModal = modal
            this.activeModal = 'd-block'
            // determine if the modal is gateway or device
            this.category = 'cameraGateway'
            // set data
            this.modalData = item
        },
        //Function Name: closeModal
        //Function Description: close modal function
        closeModal() {
            //clear the data
            this.category = this.modalData = this.activeModal = ''
            this.chooseModal = ''
        },
        //Function Name: changeTable
        //Function Description: change table label according to language settings
        //param: $i18n.locale
        changeTable() {
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
        //change table height according to data length
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
            this.changeTable()
        },
    },
}
</script>
<style scoped>
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
