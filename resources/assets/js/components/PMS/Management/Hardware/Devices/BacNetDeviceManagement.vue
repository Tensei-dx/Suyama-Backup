<!--
<Create>    2020.01.15 TP Uddin
<Update>    2020.03.10 TP Uddin   Fix column size whenever tab or category is changed
            2020.05.22 TP Uddin   Modify URLs according to the URL list
 -->

<template>
	<!-- card-body -->
    <div class="py-2" :class="tableHeight">
        <div class="input-group col-sm-6 float-right my-2">
            <input
                v-model="tableData.search"
                @input="getData()"
                type="text"
                class="form-control"
               :placeholder="$t('search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <!-- table -->
        <div class="table-responsive">
            <b-table  selectable
                      ref="deviceTable"
                      select-mode="single"
                      :items="devices"
                      :fields="table.fields"
                      :current-page="table.currentPage"
                      :per-page="table.perPage"
                      :filter="tableData.search"
                      @row-clicked="showDeviceDetails">
                <template
                    v-if="tableData.flag == 0"
                    slot="gateway"
                    slot-scope="row">
                    {{row.item.gateway.GATEWAY_NAME}}
                </template>
                <template
                    v-if="tableData.flag == 0"
                    slot="serialno"
                    slot-scope="row">
                    {{row.item.DEVICE_SERIAL_NO}}
                </template>
                <template
                    v-if="tableData.flag == 0"
                    slot="action"
                    slot-scope="row">
                    <a  class="btn"
                        @click="openModal('AddBacnetModal',row.item)">
                        <span class="">
                            <i  class="text-primary fa fa-plus-circle fa-lg"
                                aria-hidden="true"></i>
                        </span>
                    </a>
                    <a  class="btn"
                        @click="deleteDevice(row.item.BACNETDEVICE_ID)">
                        <span class="">
                            <i  class="text-danger fa fa-trash-o fa-lg"
                                aria-hidden="true"></i>
                        </span>
                    </a>
                </template>
            </b-table>
            <div v-if="devices.length == 0"
                class="d-flex justify-content-center">
                <strong>{{$t('binding.noDevice')}}</strong>
            </div>
            <div class="position-relative mt-5 pt-3">
            	<div class="category_position">
	                <div class="d-flex">
	                    <!-- scan button -->
	                    <!-- check if the flag/category if 0 = unregistered -->
	                    <div class="mr-2" v-if="tableData.flag == 0">
	                        <!-- call scan function on line 381 -->
                            <button
                                class="btn btn-primary"
                                @click="scanDevices">
	                            <!-- change the text value of button when click -->
	                            <span>
	                                {{$t('gateway.rescan')}}
	                            </span>
	                        </button>
	                    </div>
	                    <!-- scan button end-->

	                    <!-- gateway status condition -->
	                    <div :class="[{ 'col-sm-12': tableData.flag === 1 }]">
	                        <!-- call getData function on line when category is change -->
	                        <select
                                class="custom-select custom-select-gateway"
                                v-model="tableData.flag"
                                @change="changeCategory()">
	                            <option value="1">
                                    {{$t('gateway.gatewayCat.regist')}}
                                </option>
	                            <option value="0">
                                    {{$t('gateway.gatewayCat.unregist')}}
                                </option>
	                        </select>
	                    </div>
	                    <!-- gateway status condition end-->

	                </div>
	            </div>
            </div>
        </div>
        <!-- table end -->
        <div class="col-md-12 pagination-class">
            <div class="d-flex justify-content-center"
                :class="devices.length > 24 ? '':'custom-pagination-white'">
                <b-pagination
                    :total-rows="table.totalRows"
                    :per-page="table.perPage"
                    v-model="table.currentPage" />
            </div>
        </div>
        <!-- add bacnet modal component -->
        <div v-if="chooseModal == 'AddBacnetModal'">
            <AddBacnetModal
                :show="activeModal"
                :modalData="modalData"
                :cat="category"
                :currentPage="table.currentPage"
                @loaddata="getData($event)"
                @close="closeModal">
            </AddBacnetModal>
        </div>
        <!-- scan bacnet modal component -->
        <div v-else-if="chooseModal == 'ScanBacnetModal'">
            <ScanBacnetModal
                :show="activeModal"
                :category="category"
                :currentPage="table.currentPage"
                @loaddata="getData($event)"
                @close="closeModal">
            </ScanBacnetModal>
        </div>
    </div>
	<!-- card-body end -->
</template>
<script>
	import AddBacnetModal from '../../Modals/AddBacnetModal.vue';
	import ScanBacnetModal from '../../Modals/ScanBacnetModal.vue';

	export default {
        //declare components
	    components: { AddBacnetModal, ScanBacnetModal },
	    created() {
            //call getData function
	        this.getData();
            this.$bus.$on('getDeviceData', data =>{
                this.getData();
            });
            this.$bus.$on('changeDeviceTab', data =>{
                if (data == 'bacnetDevice'){
                    this.changeCategory();
                }
            });
	    },
	    data() {
	        return {
                table:{
                    fields:[
                        {
                            key: "BACNETDEVICE_ID",
                            label: "ID",
                            sortable: true
                        },
                        {
                            //key:"floor.FLOOR_NAME"⇨this parameter can't translate
                            key: "FLOOR_NAME",
                            label: "FLOOR NAME",
                            sortable: true
                        },
                        {
                            //key:"room.ROOM_NAME"⇨this parameter can't translate
                            key: "ROOM_NAME",
                            label: "ROOM NAME",
                            sortable: true
                        },
                        {
                            key: "DEVICE_NAME",
                            label: "DEVICE NAME",
                            sortable: true
                        },
                    ],
                    currentPage: 1,
                    totalRows: 0,
                    perPage: 10,
                },
	            devices: [],
	            tableData: {
	                search: '',
	                flag: '1'
	            },
	            category: '',
                activeModal: '',
                chooseModal: '',
                modalData: '',
                selectedRow: '',
                isUnregister: false,
                errors: {},
	        }
	    },
        mounted(){
            this.changeTable();
        },
        watch:{
            locale:function(){
                this.changeTable();
            }
        },
	    methods: {
            // Function Name: catchError
            // Function Description: Store errors to variable "errors"
            // Param: error
            changeTable(){
                ///for table header multilingual support
                let fields = this.table.fields;
                let head = this.$t('device.deviceTable');
                for(var i in fields){
                    Object.keys(head).forEach(function(mess){
                        if (fields[i].key == mess) {
                            fields[i].label = head[mess];
                        }
                    });
                }
                this.table.fields = fields;
                if (this.$children[0]) {
                    this.$children[0].refresh();
                }
            },
            catchError(error) {
                this.errors = error.response.data.errors;
            },
            // Function Name: getData
            // Function Description: Get device data depending on REG_FLAG
            // Param: pages
	        getData(pages) {
                //get device data depends of REG-FLAG
                //REG_FLAG: 1 = REGISTERED, 0 = UNREGISTERED
	            let page_url;
	            if(this.tableData.flag == 1){
	                page_url = 'getRegisteredBacnetDevices';
                    this.table.perPage = 10;
                    if (this.table.fields.length > 4) {
                        this.table.fields.splice(3, 5, {key:'DEVICE_NAME',
                            label: this.$t('device.deviceTable.DEVICE_NAME'),
                            sortable: true});
                    }
	            }else if(this.tableData.flag == 0){
	                page_url = 'getUnregisteredBacnetDevices';
                    this.table.perPage = 8;
                    if (this.table.fields.length == 4) {
                        this.table.fields.splice(3, 1, {key:'gateway',
                            label: this.$t('device.deviceTable.gateway'),
                            sortable: true});
                        this.table.fields.push(
                            {key: 'DEVICE_NAME',
                            label: this.$t('device.deviceTable.DEVICE_NAME'),
                            sortable: true},
                            {key: 'serialno',
                            label: this.$t('device.deviceTable.SERIAL_NO'),
                            sortable: true},
                            {key: 'action',
                            label: this.$t('device.deviceTable.action')});
                    }
	            }
                axios.get(page_url, {
                    params: {
                        include: "floor>room>gateway",
                        search: this.tableData.search
                    }
                }).then(response => {
                    let data = response.data;
                    if (data == 'failed') {
                        this.devices = [];
                        this.table.totalRows = this.devices.length;
                        this.$swal(message.error, message.failedDevice, 'error');
                    } else {
                        for (var i in data) {
                            if (data[i].room.ROOM_NAME == null) {
                                data[i].room.ROOM_NAME = '-';
                            }
                            if (data[i].floor.FLOOR_NAME == null) {
                                data[i].floor.FLOOR_NAME = '-';
                            }
                            if (data[i].DEVICE_NAME == null || data[i].DEVICE_NAME == "") {
                                data[i].DEVICE_NAME = '-';
                            }
                        }
                        this.devices = data;
                        this.table.totalRows = this.devices.length;
                    }
                }).catch(error => {
                    let errormessage = error.response.data.file + " : " + error.response.data.message;
                    axios.post('createSystemLogs', {ERROR_MESSAGE:errormessage});
                    var title = this.$t('modalText.error');
                    var message = this.$t('logs.error');
                    this.$swal(title, message, 'error');
                });
	        },
            //Function Name: openModal
            //Function Description: opens specific modal function
            //Param: modal (modal name), item (device)
            openModal(modal,item){
                //display modal
                this.chooseModal = modal;
                this.activeModal = 'd-block';
                // determine if the modal is gateway or device
                this.category = 'bacnet';
                // set data
                this.modalData = item;
            },
            //Function Name: deleteDevice
            //Function Description: deletes selected device
            //Param: id (BACNET_DEVICE_ID)
            deleteDevice(id){
                this.$swal({
                    title: "Delete Device",
                    text: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.value){
                        axios.post('deleteBacnetDevice',{
                            'DEVICE_ID': id,
                        }).then(response => {
                            if(response.data == 'success'){
                                this.$swal({
                                    title:'Deleted',
                                    text:"Device has been deleted.",
                                    type:'success',
                                    timer:1500,
                                    showConfirmButton:false
                                });
                            }else{
                                this.$swal('Error', "Device can't delete.", 'error');
                            }
                            this.getData(this.table.currentPage);
                        }).catch(error => {
                            this.catchError(error)
                        });
                    }
                });
            },
            //Function Name: scanDevices
	        //Function Description: opens modal scan function
	        scanDevices(){
                this.openModal('ScanBacnetModal', 'bacnet');
	        },
            //Function Name: closeModal
	        //Function Description: close modal function
	        closeModal(){
                this.category = this.modalData = this.activeModal = this.chooseModal = '';
	        },
            //Function Name: showDeviceDetails
            //Function Description: shows device details
            //Param: details (device)
            showDeviceDetails(details){
                this.$set(details, "category", "bacnet");
                this.$bus.$emit('selectedDeviceData', details);
                this.selectedRow = details.BACNETDEVICE_ID;
            },
            //Function Name: changeCategory
            //Function Description: change table size according to category
            changeCategory(){
                var details = '';
                var column = this.columns;
                var style = 'table-cell';

                if(this.tableData.flag == 0){
                    this.isUnregister = true;
                    style = 'table-cell';
                }else if(this.tableData.flag == 1){
                    this.isUnregister = false;
                    style = 'none';
                }
                for(var i in column){
                    if(i == 3 || i == 5 || i == 6 || i == 7){
                        column[i].display = style;
                    }
                }
                this.$emit('change-size', this.isUnregister);
                this.$bus.$emit('selectedDeviceData', details);
                this.selectedRow = '';
                this.getData();
            },
        },
        computed: {
            //changes table height according to screen height and table length
            tableHeight(){
                var tbHeight = '';
                if(this.$screenHeight > 900){
                    if(!this.$isMobile.isMobile){
                        tbHeight = 'h-75vh';
                    }else{
                        if(this.table.totalRows >= 10){
                            tbHeight = 'h-100';
                        }else{
                            tbHeight = 'h-75vh';
                        }
                    }
                }else{
                    tbHeight = 'h-100';
                }
                return tbHeight;
            }
        },
	};
</script>
