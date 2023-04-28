<!--
    <System Name> iBMS
    <Program Name> CreateBindingAlert.vue

    <Created>            TP Yani
    <Updated>
-->
<template>
    <div>
        <div class="row">
            <div class="card-body col-sm-12 text-dark">
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <h4 class="text-dark">TARGET USERS AND ALERT OPTION</h4>
                    </div>
                    <!-- search end-->
                    <div class="divider-line-white"></div>
                    <div class="h-374">
                        <b-table  :items="renderData"
                                  :fields="table.fields"
                                  :current-page="table.currentPage"
                                  :per-page="table.perPage">
                            <!-- USERNAME -->
                            <template slot="USERNAME" slot-scope="row">
                                <div class="d-flex">
                                    {{row.item['USERNAME']}}
                                </div>
                            </template>
                            <!-- EMAIL -->
                            <template slot="EMAIL" slot-scope="row">
                                <div class="d-flex">
                                    <b-form-checkbox v-model="row.item.emailAlert" :disabled="row.item.EMAIL == null" />
                                    {{row.item.EMAIL}}
                                </div>
                            </template>
                            <!-- SMS -->
                            <template slot="SMS" slot-scope="row">
                                <div class="d-flex">
                                    <b-form-checkbox v-model="row.item.smsAlert" :disabled="row.item.CONTACT_NUMBER == null" />
                                    {{row.item.CONTACT_NUMBER}}
                                </div>
                            </template>
                            <!-- TIME INTERVAL -->
                            <template slot="time" slot-scope="row">
                                <select class="custom-select" v-model="row.item.TIME_INTERVAL">
                                    <!-- loop timerValue data -->
                                    <option v-for="timer in timerValue" :value="timer.VAL">{{ timer.VAL }} {{$t('binding.min')}}</option>>
                                </select>
                            </template>
                        </b-table>
                    </div>
                    <!-- pagination component -->
                    <!-- call getData function when prev, next and number is click -->
                    <div v-if="showPaginate" class="d-flex justify-content-between">
                        <div class="ml-auto">
                            <b-pagination :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage" />
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-primary" @click="saveBinding" :disabled="disableSaveButton">
                                <!-- display loading animation -->
                                <span class="pull-left" v-if="loading">
                                    <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                                </span>
                                <span> {{$t('user.save')}} </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props:{
            //Harvey
            locale:'',
            ttt:true,
            propsFloorId:'',
            propsRoomId:'',
            propsGatewayId:'',
            propsDeviceId:'',
            propsDeviceCondition:'',
            propsSelectedRoomTarget:'',
            propsUsers:''
        },
        created (){
            this.getFloors();
            this.getUsername();
            this.$bus.$on('setAlertBindingData' , data =>{
                this.bindedList = data;
            });
            this.inputDefaultId();
        },
        mounted(){
            this.changeTable();
        },
        data(){
            return{
                table:{
                    fields:[
                        {key:'USERNAME', label:'USERNAME'},
                        {key:'EMAIL', label:'EMAIL'},
                        {key:'SMS', label:'SMS'},
                        {key:'time', label:'TIME_INTERVAL'}
                    ],
                    currentPage:1,
                    totalRows:0,
                    perPage:5,
                },
                tableData: {
                    search: '',
                },
                bindedList: null,
                binding:{
                    FLOOR_ID: null,
                    ROOM_ID: null,
                    GATEWAY_ID: null,
                    TARGET_DEVICES:[],
                    TARGET_DEVICE_CATEGORY: 1,
                    SOURCE_DEVICE_ID: null,
                    SOURCE_DEVICE_CONDITION: null,
                    ALERT: []
                },
                bindingCheck:{},
                floors:{},
                rooms:{},
                gateways:{},
                devices:{},
                deviceConditions:{
                    SELECTED: null,
                    DATA: [],
                },
                bindingLists:[],
                timerValue:[{"VAL":1},{"VAL":5},{"VAL":10},{"VAL":15},{"VAL":20},{"VAL":0}],
                userList:[],
                loading: false,
                allDeviceSelected: false,
                required: false,
                disableSaveButton:false
            }
        },
        methods: {
            //Harvey
            //Function Name: inputDefaultId
            //Function Description: sets default data
            inputDefaultId(){
                this.binding.FLOOR_ID = this.propsFloorId;
                this.binding.ROOM_ID = this.propsRoomId;
                this.binding.GATEWAY_ID = this.propsGatewayId;
                this.binding.SOURCE_DEVICE_CONDITION = this.propsDeviceCondition;
                this.binding.SOURCE_DEVICE_ID = this.propsDeviceId;
                this.deviceSourceCondition();
            },
            //Function Name: getFloors
            //Function Description: get floors
            getUsername(pages){
                axios({
                  url: 'getUsers',
                  method: 'post',
                  data: {
                    'USER_TYPE': 1
                  }
                })
                .then(response => {
                    let current = this;
                    this.userList = response.data;
                    this.table.totalRows = this.userList.length;
                    this.userList = this.userList.map(function(item,key){
                        item['USERNAME'] = item.USERNAME;
                        item['TIME_INTERVAL'] = 1;
                        return item;
                    });

                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: getFloors
            //Function Description: get floors
            getFloors(){
                axios.post('getFloorAll')
                .then(response => {
                    this.floors = response.data
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: getRooms
            //Function Description: get room
            //PARAM: FLOOR_ID
            getRooms(){
                axios.post('getFloorRooms/'+this.binding.FLOOR_ID)
                .then(response => {
                    this.rooms = response.data,
                    this.binding.ROOM_ID = null,
                    this.binding.GATEWAY_ID = null,
                    this.binding.SOURCE_DEVICE_ID = null,
                    this.binding.SOURCE_DEVICE_CONDITION = null,
                    this.gateways = {},
                    this.devices = {},
                    this.deviceConditions.SELECTED = null,
                    this.deviceConditions.DATA = [],
                    this.bindingLists = []
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //get gateway
            //PARAM: ROOM_ID
            getGateways(){
                axios.get('getRoomGateways/'+this.binding.ROOM_ID)
                .then(response => {
                    this.gateways = response.data,
                    this.binding.GATEWAY_ID = null,
                    this.binding.SOURCE_DEVICE_ID = null,
                    this.binding.SOURCE_DEVICE_CONDITION = null,
                    this.devices = {},
                    this.deviceConditions.SELECTED = null,
                    this.deviceConditions.DATA = [],
                    this.bindingLists = []
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: getSourceSensors
            //Function Description: get source sensor device for binding
            //PARAM: GATEWAY_ID
            getSourceSensors(){
                var sensors = 'co2_detector,gas_detector,smoke_detector';
                axios.get('getGatewayDevices/'+this.binding.GATEWAY_ID+'/?REG_FLAG=1&filter=DEVICE_TYPE:'+sensors)
                .then(response => {
                    this.devices = response.data,
                    this.binding.SOURCE_DEVICE_ID= null,
                    this.binding.SOURCE_DEVICE_CONDITION = null,
                    this.deviceConditions.SELECTED = null,
                    this.deviceConditions.DATA = []
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: deviceSourceCondition
            //Function Description: get device condition
            //PARAM: SOURCE_DEVICE_ID
            deviceSourceCondition(){
                this.bindingLists = [],
                axios.get('getDeviceBindingListCondition/'+this.binding.SOURCE_DEVICE_ID)
                .then(response => {
                    this.deviceConditions.SELECTED = response.data[0].SELECTED;
                    this.deviceConditions.DATA = response.data[0].DATA;
                    this.binding.SOURCE_DEVICE_CONDITION = response.data[0].SELECTED;
                    this.bindingList();
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: bindingList
            //Function Description: display the device from the binding list
            //PARAM: SOURCE_DEVICE_ID, SOURCEDEVICE_CONDITION,pages
            bindingList(pages){
                pages = pages || '';
                axios.get('getAlertBinding')
                .then(response => {
                    this.bindingLists = response.data;

                    //Add CUSTOM_CONDITION and isActive data to variable
                    self = this;
                    this.bindingLists = this.bindingLists.map(function(item,key){

                        //Check if this is currently registered in binding
                        if(item['binding']!= null){
                            item['emailAlert'] = true;
                            //Seconds convert to minute
                            item['TIME_INTERVAL'] = item['TIME_INTERVAL'] / 60;
                        }else{
                            item['emailAlert'] = false;
                            item['TIME_INTERVAL'] = 1;
                        }
                        return item;
                    });
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: selectAllDevice
            //Function Description: select all device for binding
            selectAllDevice() {
                    let time;
                    //check if the select all device is checked
                    if (!this.allDeviceSelected) {
                        for (var key in this.bindingLists) {
                            //check is the binding list is not null
                            if(this.bindingLists[key].binding === null){
                                //add data to the binding.TARGET_DEVICES array using array push
                                this.binding.TARGET_DEVICES.push({"SOURCE_DEVICE_ID":this.binding.SOURCE_DEVICE_ID,
                                                                  "TARGET_DEVICE_ID":this.bindingLists[key].target_device.DEVICE_ID,
                                                                  "BINDING_LIST_ID":this.bindingLists[key].binding_list.BINDING_LIST_ID,
                                                                  "TIME":0});
                            }
                        }
                    }else{
                        this.binding.TARGET_DEVICES = [];
                    }
            },
            //Function Name: selectDevice
            //Function Description: check all the target devices is check
            //Param: deviceID
            selectDevice() {
                var cnt = 0;
                for(var key in this.bindingLists){
                    if(this.bindingLists[key].binding == null){
                        cnt++;
                    }
                }
                if(this.binding.TARGET_DEVICES.length == cnt){
                    this.allDeviceSelected = true;
                }else{
                    this.allDeviceSelected = false;
                }

            },
            //Function Name: saveBinding
            //Function Description: save binding function
            saveBinding(){

                let finalAddList    = {};       //final list
                let count = 0;       //final remove list

                var sourceDeviceCondition       = this.binding.SOURCE_DEVICE_CONDITION;
                var sourceDeviceCustomCondition = sourceDeviceCondition.split(" ");
                if(sourceDeviceCustomCondition.length == 3){
                    this.binding.SOURCE_DEVICE_CONDITION = {"data":Number(sourceDeviceCustomCondition[1]),
                                                                "operator":sourceDeviceCustomCondition[0]};
                }else {
                    this.binding.SOURCE_DEVICE_CONDITION = {"data":sourceDeviceCustomCondition[0],
                                                                "operator":"Condition"};
                }

                //Segregate Add list to Remove list
                for(var i in this.userList){
                    this.binding['TIME_INTERVAL'] = this.userList[i].TIME_INTERVAL;
                    if(typeof(this.userList[i].emailAlert) != 'undefined'){
                        if(typeof(this.userList[i].smsAlert) != 'undefined'){
                            finalAddList = {"sms": this.userList[i].smsAlert, "email": this.userList[i].emailAlert, "user_id": this.userList[i].USER_ID};
                            this.binding['ALERT'][count] = finalAddList;
                        }else{
                            finalAddList = {"sms": false, "email": this.userList[i].emailAlert, "user_id": this.userList[i].USER_ID};
                            this.binding['ALERT'][count] = finalAddList;
                        }
                        count++;
                    }else if(typeof(this.userList[i].smsAlert) != 'undefined'){
                        if(typeof(this.userList[i].emailAlert) != 'undefined'){
                            finalAddList = {"sms": this.userList[i].smsAlert, "email": this.userList[i].emailAlert, "user_id": this.userList[i].USER_ID};
                            this.binding['ALERT'][count] = finalAddList;
                        }else{
                            finalAddList = {"sms": this.userList[i].smsAlert, "email": false, "user_id": this.userList[i].USER_ID};
                            this.binding['ALERT'][count] = finalAddList;
                        }
                        count++;
                    }
                }
                axios({
                  url: 'createAlertBinding',
                  method: 'post',
                  data: {
                    'BINDING_ALERT':this.binding
                  }
                })
                .then(response=>{
                    if (response.data = 'success'){
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Alert Binding Successfully Save',
                            showConfirmButton: false,
                            timer: 1200
                        });
                    }else if(response.data == 'exist'){
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Alert Binding Successfully Updated'
                        });
                    }else{
                        this.$swal(
                            'Error',
                            "Binding cannot be created.",
                            'error'
                        );
                    }
                })
                .catch((error) => console.log(error));
                this.$bus.$emit('getAlertBindingData');
            },
            //Function name: close
            //Function Description: close modal function
            close(){
                this.binding.FLOOR_ID = null,
                this.binding.ROOM_ID = null,
                this.binding.GATEWAY_ID = null,
                this.binding.TARGET_DEVICES = [],
                this.binding.SOURCE_DEVICE_ID = null,
                this.binding.SOURCE_DEVICE_CONDITION = null,
                this.rooms = {},
                this.gateways = {},
                this.devices = {},
                this.deviceConditions.SELECTED = null,
                this.deviceConditions.DATA = [],
                this.bindingLists = [],
                this.allDeviceSelected = false,
                this.loading = false,
                this.$bus.$emit('getAlertBindingData')
            },
            //Function Name: changeTable
            //Function Description: change table label according to language settings
            //param: $i18n.locale
            changeTable(){
                var labels = this.table.fields;
                var messages = this.$t('binding.bindingDevice');
                for (var i in labels){
                    Object.keys(messages).forEach(function(mess){
                        if (labels[i].key == mess) {
                            labels[i].label = messages[mess];
                        }
                    });
                }
                this.table.fields = labels;
                if (this.$children[0]) {
                    this.$children[0].refresh();
                }
            }
        },
        watch:{
            //Trigger when device outisde component is clicked
            propsDeviceId:function(){
                this.binding.SOURCE_DEVICE_ID = this.propsDeviceId;
                this.deviceSourceCondition();
            },
            //Trigger when device condition outisde component is clicked
            propsDeviceCondition:function(){
                //ON/OFF/ALARM/NORMAL
                this.deviceConditions.SELECTED = this.propsDeviceCondition;
                //ON/OFF/ALARM/NORMAL
                this.binding.SOURCE_DEVICE_CONDITION = this.propsDeviceCondition;
                this.bindingList();
            },
            locale:function(){
                this.changeTable();
            }
        },
        computed: {
            renderData(){
                var data = this.userList;
                var search = this.tableData.search;
                var filteredData = [];

                return data;
            },
            //disbale save button
            saveDisabled () {
                if(this.binding.TARGET_DEVICES.length > 0){
                    return false;
                }else{
                    return true;
                }

            },
            //diabled select all checkbox and time interval all
            selectAllDisabled(){
                if(_.size(this.bindingLists) > 0){
                    var cnt = 0;
                    //count the binding not equal to bull
                    for(var key in this.bindingLists){
                        if(this.bindingLists[key].binding != null){
                            cnt++;
                        }
                    }
                    //compare the length to binding list and binding not equal to null
                    if(this.bindingLists.length == cnt){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return true;
                }
            },
            //show or hide pagination
            showPaginate(){
                return _.size(this.userList) > 0
            },
        }
    }
</script>
