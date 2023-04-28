<!--
    <System Name> iBMS
    <Program Name> ElectricMeterDeviceManagement.vue
    <Created>            TP Harvey
    <Updated> 2019.01.31 TP Jethro  Changed table format to bootstrap vue table for uniform and removed uneccessary
              2019.06.28 TP Mark    Applying PG Implementation Matrix (Frontend)
              2019.07.11 TP Mark    Applying Horizontal Expansion
              2020.03.10 TP Uddin   Fix column size whenever tab or category is changed
              2020.05.26 TP Uddin   Modify axios URL according to the URL list
-->
<template>
    <!-- card-body -->
    <div class="p-2" :class="tableHeight">
        <div class="input-group col-sm-6 float-right my-2">
            <input  v-model="tableData.search" @input="getData()" type="text" class="form-control" :placeholder="$t('search')">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <!-- table -->
        <div class="table-responsive pt-4">
            <b-table  sortable
                      :items="devices"
                      :fields="table.fields"
                      :current-page="table.currentPage"
                      :per-page="table.perPage"
                      :filter="tableData.search"
                      @row-clicked="showEmeterDetails">
            </b-table>
            <div v-if="devices.length == 0" class="d-flex justify-content-center">
                <strong>{{$t('binding.noDevice')}}</strong>
            </div>
            <div :class="[{ 'position-relative mt-5 pt-3': table.totalRows >= 10 }]">
                <div class="category_position">
                    <div class="d-flex">
                        <div class="mr-2" v-if="tableData.flag == 1">
                            <button class="btn btn-primary" @click="openModal('addModal','modMeter')">
                                {{$t('add')}}
                            </button>
                        </div>
                        <!-- ElectricMeter status condition -->
                        <div :class="[{ 'col-sm-12': tableData.flag === 9 }]">
                            <!-- call getData function on line when category is change -->
                            <select class="custom-select custom-select-gateway" v-model="tableData.flag" @change="changeCategory()">
                                <option value="1">{{$t('gateway.gatewayCat.regist')}}</option>
                                <option value="9">{{$t('gateway.gatewayCat.deleted')}}</option>
                            </select>
                        </div>
                        <!-- ElectricMeter status condition end-->
                    </div>
                </div>
            </div>
        </div>
        <!-- table end -->
        <!-- pagination component -->
        <div class="col-md-12 pagination-class">
            <div class="d-flex justify-content-center" :class="devices.length > 24 ? 'pagination-adjust':'custom-pagination-white'">
                <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" />
            </div>
        </div>
        <!-- add modal component -->
        <div v-if="chooseModal == 'addModal'">
            <AddModal :show="activeModal" :cat="category" :currentPage="table.currentPage" @loaddata="getData($event)" @close="closeModal"></AddModal>
        </div>
    </div>
    <!-- card-body end -->
</template>
<script>
    import AddModal from '../../Modals/AddModBusMeterModal.vue';
    export default {
        //declare components
        components: { AddModal},
        props:{
            locale:''
        },
        created() {
            //call getData function
            this.getData();
            this.$bus.$on('getDeviceData' , data =>{
                this.getData();
            });
            this.$bus.$on('changeDeviceTab' , data =>{
                if (data == 'modDevice') {
                    this.changeCategory();
                }
            });
        },
        data() {
            return {
                table:{
                    fields:[
                        {key:'METER_ID', label:'ID', sortable: true},
                        {key:'FLOOR_NAME', label:'FLOOR NAME', sortable: true},
                        {key:'ROOM_NAME', label:"ROOM NAME", sortable: true},
                        {key:'SERIAL_NO', label:"DEVICE NAME", sortable: true}
                    ],
                    currentPage:1,
                    totalRows:0,
                    perPage:10,
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
            //Function Name: changeTable
            //Function Description: Change table labels based on language settings
            //Param: locale
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
            //Function Name: getData
            //Function Description: Get device data depending on REG_FLAG
            //Param: pages, tableData.flag
            getData(pages) {
                //get device data depends of REG-FLAG
                //REG_FLAG: 1 = REGISTERED, 0 = UNREGISTERED, 4 = BLOCKED, 9 = DELETED
                let page_url;
                let message = this.$t('device.deviceModals');
                if(this.tableData.flag == 1){
                    page_url = 'getRegisteredMeter';
                }else if(this.tableData.flag == 9){
                    page_url = 'getDeletedMeter';
                }
                //ajax call
                //PARAMS: search
                axios.get(page_url, {
                    params:{
                        search: this.tableData.search,
                    }
                })
                .then(response => {
                    if (response.data == 'failed') {
                        this.devices = [];
                        this.table.totalRows = this.devices.length;
                        this.$swal(
                            message.error,
                            message.failedModbus,
                            'error'
                        );
                    }else{
                        let data = response.data;
                        this.devices = data;
                        this.table.totalRows = this.devices.length
                    }
                })
                .catch(error =>{
                    let errormessage = error.response.data.file + " : " +
                                        error.response.data.message;
                    axios.post('createSystemLogs',{ERROR_MESSAGE:errormessage});
                    var title = this.$t('modalText.error');
                    var message = this.$t('logs.error');
                    this.$swal(
                        title,
                        message,
                        'error'
                    );
                });
            },
            //Funciton Name: openModal
            //Function Description: opens specific modal function
            //Param: modal (modal name), item (device)
            openModal(modal,item){
                //dispaly modal
                var details = '';
                this.chooseModal = modal;
                this.activeModal = 'd-block';
                // determine if the modal is modBusMeter
                this.category = 'modBusMeter';
                // set data
                this.modalData = item;
                this.selectedRow = '';
                this.$bus.$emit('selectedDeviceData', details);
            },
            //Function Name: closeModal
            //Function Description: close modal function
            closeModal(){
                this.category = this.modalData = this.activeModal = this.chooseModal = '';
            },
            //Function Name: showEmeterDetails
            //Function Description: shows modbus meter details
            //Param: details (device)
            showEmeterDetails(details){
                this.$set(details, "category", "modMeter");
                this.$bus.$emit('selectedDeviceData', details);
                this.selectedRow = details.METER_ID;
            },
            //Function Name: changeCategory
            //Function Description: changes category displayed
            changeCategory(){
                var details = '';
                if (this.tableData.flag == 1 || this.tableData.flag == 9) {
                    this.isUnregister = false;
                } else {
                    this.isUnregister = true;
                }
                this.$emit('change-size', this.isUnregister);
                this.$bus.$emit('selectedDeviceData', details);
                this.selectedRow = '';
                this.getData();
            },
        },
        computed:{
            //changes table height according to screen height and table length
            tableHeight(){
                var tbHeight = '';
                if(!this.$isMobile.isMobile){
                    tbHeight = 'h-75vh';
                }else{
                    if(this.table.totalRows >= 10){
                        tbHeight = 'h-100';
                    }else{
                        tbHeight = 'h-75vh';
                    }
                }
                return tbHeight;
            }
        },
    };
</script>

 };
</script>
