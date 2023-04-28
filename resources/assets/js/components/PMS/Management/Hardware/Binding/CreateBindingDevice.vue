<!--
    <System Name> iBMS
    <Program Name> CreateBindingDevice.vue

    <Created>            TP Harvey
    <Updated> 2019.07.16 TP Mark  Applying Horizontal Expansion
              2020.02.14 TP Harvey  Applying Specific command for Wall Switch
              2020.05.26 TP Uddin Modify axios URL according to the URL list
              2020.09.22 TP Russell Added Create Binding with Threshold Setting
-->
<template>
    <div>
        <div class="row">
            <div class="card-body col-sm-12 text-dark h-526">
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <h5 class="text-dark">{{$t('binding.targetDevice')}}</h5>
                        <!-- search -->
                        <div class="input-group col-sm-3 pl-0 h-75 float-right">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                            </div>
                            <!-- call bindingList function-->
                            <!-- enable /disable the room select using selectAllDisabled function-->
                            <input type="text" class="form-control" v-model="tableData.search" @input="bindingList()" :placeholder="$t('search')">
                        </div>
                        <!-- search end-->
                    </div>
                    <div class="divider-line"></div>
                    <!-- table component -->
                    <div class="table-responsive h-374">

                        <b-table  :items="renderData"
                                  :fields="table.fields"
                                  :current-page="table.currentPage"
                                  :per-page="table.perPage"
                                  :filter="tableData.search">
                            <!-- DEVICE TYPE -->
                            <template slot="target_device.DEVICE_TYPE" slot-scope="row">
                                <div class="d-flex">
                                    <b-form-checkbox v-model="row.item.isActive" @input="clickCheckBox()"/>
                                    {{row.item['target_device']['DEVICE_TYPE']}}
                                </div>
                            </template>

                            <!-- DEVICE CONDITION -->
                            <template slot="binding_list.TARGET_DEVICE_CONDITION" slot-scope="row">
                                <div v-if="checkCustomCondition(row.item.target_device.DEVICE_TYPE)">
                                    <button class="btn btn-primary btn-sm" @click="clickCustomCondition(row.item.target_device,row.index)">Custom Binding</button>
                                </div>
                                <div v-else>
                                    {{row.item.binding_list.TARGET_DEVICE_CONDITION}}
                                </div>
                            </template>

                            <!-- TIME INTERVAL -->
                            <template slot="time" slot-scope="row">
                                <select class="custom-select" v-model="row.item.target_device.TIME_INTERVAL">
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
        <!-- Modal -->
        <CustomConditionBindingModal v-if="modal.showCustomConditionBindingModal==true"
                                    :device="currentCustomDevice"
                                    @saveDeviceCondition="saveDeviceCondition"
                                    @closeModal="modal.showCustomConditionBindingModal=false">
        </CustomConditionBindingModal>
    </div>
</template>

<script>
    import CustomConditionBindingModal from './Modal/CustomConditionBindingModal.vue';
    export default{
        components:{
            CustomConditionBindingModal
        },
        props:{
            locale:'',
            propsFloorId:'',
            propsRoomId:'',
            propsGatewayId:'',
            propsDeviceId:'',
            propsDeviceCondition:'',
            propsSelectedRoomTarget:'',
        },
        created (){
            this.inputDefaultId();
            this.getFloors();
            this.$bus.$on('setDeviceBindingData' , data =>{
                this.bindedList = data;
            });
        },
        mounted(){
            this.changeTable();
        },
        data(){
            return{
                table:{
                    fields:[
                        {key:'target_device.DEVICE_TYPE', label:'DEVICE TYPE'},
                        {key:'target_device.DEVICE_NAME', label:'DEVICE NAME'},
                        {key:'binding_list.TARGET_DEVICE_CONDITION', label:"DEVICE CONDITION"},
                        {key:'time', label:'TIME_INTERVAL'},
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
                    TARGET_DEVICE_CATEGORY: 0,
                    SOURCE_DEVICE_ID: null,
                    SOURCE_DEVICE_CONDITION: null,
                },
                floors:{},
                rooms:{},
                gateways:{},
                devices:{},
                deviceConditions:{
                    SELECTED: null,
                    DATA: [],
                },
                sourceDevice:'',
                bindingLists:[],

                // 10/20/2020 Deleted
                // tempValues:[{"tempDown":'TEMP_16'}, {"tempDown":'TEMP_17'}, {"tempDown":'TEMP_18'},
                //             {"tempDown":'TEMP_19'}, {"tempDown":'TEMP_20'}, {"tempDown":'TEMP_21'},
                //             {"tempDown":'TEMP_22'}, {"tempDown":'TEMP_23'}, {"tempDown":'TEMP_24'},
                //             {"tempDown":'TEMP_25'}, {"tempUp":'TEMP_26'}, {"tempUp":'TEMP_27'},
                //             {"tempUp":'TEMP_28'}, {"tempUp":'TEMP_29'}, {"tempUp":'TEMP_30'}],

                timerValue:[{"VAL":1},{"VAL":5},{"VAL":10},{"VAL":15},{"VAL":20},{"VAL":0}],
                loading: false,
                required: false,
                disableSaveButton:false,

                //Harvey 20200214
                //Custom Condition Binding
                modal:{
                    showCustomConditionBindingModal:false
                },
                customConditionList:["wall_switch_1",                           //Supported Custom Condition
                                    "wall_switch_2",
                                    "wall_switch_3",
                                    "embedded_switch_1",
                                    "embedded_switch_2",
                                    "embedded_switch_3",
                                    // 9/22/2020 Added "ir_remote"
                                    "ir_remote"],
                currentCustomDevice:"",                                         //Device information for Custom Device
                currentCustomDeviceIndex:"",                                    //Use to determine device Custom Condition
                deviceBindingCondition:[]                                             //List of Device That will be trigger
            }
        },
        methods: {
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
            //Function Name: getSourceDevices
            //Function Description: get source device for binding
            //PARAM: GATEWAY_ID
            getSourceDevices(){
                axios.get('getGatewayDevices/' + this.binding.GATEWAY_ID + '/?REG_FLAG=1&filter=DEVICE_CATEGORY:1')
                .then(response => {
                    this.devices = response.data,
                    this.binding.SOURCE_DEVICE_ID = null,
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
                axios.get('getDeviceBindingListCondition/' + this.binding.SOURCE_DEVICE_ID)
                .then(response => {
                    this.deviceConditions.SELECTED = response.data[0].SELECTED;
                    this.deviceConditions.DATA = response.data[0].DATA;
                    this.binding.SOURCE_DEVICE_CONDITION = response.data[0].SELECTED;
                    this.bindingList();
                }).catch(errors => {
                    console.log(errors);
                });

                axios.post('getDevice/'+this.binding.SOURCE_DEVICE_ID)
                .then(response => {
                    this.sourceDevice = response.data;
                }).catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: bindingList
            //Function Description: display the device from the binding list
            //PARAM: SOURCE_DEVICE_ID, SOURCEDEVICE_CONDITION,pages
            bindingList(pages){
                pages = pages || '';
                axios.get('getDeviceBindingListDevices/' + this.binding.SOURCE_DEVICE_ID + '/' 
                    +this.binding.SOURCE_DEVICE_CONDITION + '/' + this.binding.TARGET_DEVICE_CATEGORY, {
                    params: {
                        targetRoomId: this.propsSelectedRoomTarget.ROOM_ID
                    }
                })
                .then(response => {
                    this.bindingLists = response.data;
                    this.table.totalRows = this.bindingLists.length;

                    //Add CUSTOM_CONDITION and isActive data to variable
                    self = this;
                    this.bindingLists = this.bindingLists.map(function(item,key){

                        // 9/22/2020 Added for Binding with Treshold
                        //Check if source condition is a Threshold
                        var sourceDeviceCondition = self.propsDeviceCondition; 
                        item['target_device']["SOURCE_DEVICE_CONDITION"]   = {};
                        var sourceDeviceCustomCondition = sourceDeviceCondition.split(" ");   //MAX 23 °C
                        //if SOURCE_DEVICE_CONDITION has DATA, BINDING_LIST_ID=0
                        if(sourceDeviceCustomCondition.length == 3){
                            item['target_device']["SOURCE_DEVICE_CONDITION"] = {"data":Number(sourceDeviceCustomCondition[1]),
                                                                                "operator":sourceDeviceCustomCondition[0]}
                            item['target_device']['BINDING_LIST_ID']    = 0;
                        //if SOURCE_DEVICE_CONDITION has no DATA, input default BINDING_LIST_ID
                        }else if(sourceDeviceCustomCondition.length == 1){
                            item['target_device']['BINDING_LIST_ID']    = item.binding_list.BINDING_LIST_ID;

                        }

                        item['target_device']["CUSTOM_CONDITION"]   = [];
                        item['target_device']['SOURCE_DEVICE_ID']   = self.binding.SOURCE_DEVICE_ID;

                        //Check if this is currently registered in binding
                        if(item['binding']!= null){
                            item['isActive'] = true;
                            item['target_device']['CUSTOM_CONDITION'] = item['binding']['CUSTOM_CONDITION'];
                            //Seconds convert to minute
                            item['target_device']['TIME_INTERVAL'] = item['binding']['TIME_INTERVAL'] / 60;
                        }else{
                            item['isActive'] = false;
                            item['target_device']['CUSTOM_CONDITION'] = [];
                            item['target_device']['TIME_INTERVAL'] = 1;
                        }
                        return item;
                    });
                    //***************************************************
                    for(var key in this.bindingLists){
                        if(this.bindingLists[key].binding == null){
                            //set a default value of time to each devices
                            var source_device_type = this.bindingLists[key].binding_list.SOURCE_DEVICE_TYPE;

                            //Harvey 20200218
                            if(source_device_type == 'light_detector'){
                                this.allDeviceTime = 0;
                                Vue.set(this.bindingLists[key], 'TIME_INTERVAL',this.allDeviceTime);
                            }else if(this.binding.SOURCE_DEVICE_CONDITION.includes("NORMAL") ||
                                this.binding.SOURCE_DEVICE_CONDITION.includes("COMFORT") ||
                                this.binding.SOURCE_DEVICE_CONDITION.includes("GOOD") ||
                                this.binding.SOURCE_DEVICE_CONDITION.includes("DAY")){
                                this.allDeviceTime = 1;
                                Vue.set(this.bindingLists[key], 'TIME_INTERVAL', this.allDeviceTime);
                            }else{
                                this.allDeviceTime = 1;
                                Vue.set(this.bindingLists[key], 'TIME_INTERVAL', this.allDeviceTime);
                            }

                        }else{
                            //convert the time to minute if its already binded
                            // let time = this.bindingLists[key].binding.TIME_INTERVAL;
                            // time = time / 60;
                            // Vue.set(this.bindingLists[key].binding, 'TIME_INTERVAL', time);
                        }
                    }
                });
                // .catch(errors => {
                //     console.log(errors);
                // });
            },
            //Function Name: selectDevice
            //Function Description: check all the target devices is check
            //Param: deviceID
            selectDevice(deviceID) {
                var totalList = this.table.totalRows;
                let data = this.binding.TARGET_DEVICES;
                if(this.bindingLists.binding == null){
                    var exist = data.filter(function(i){
                        if(i.TARGET_DEVICE_ID == deviceID){
                            return true;
                        }
                    });
                    if(exist.length > 0){
                        this.bindCount++;
                    }else{
                        if(this.bindCount != 0){
                            this.bindCount--;
                        }
                    }
                }

            },
            //Funciton Name: validateData
            //Function Description: validate data being sent
            //Param: binding.SOURCE_DEVICE_ID,bindingList.target_device.DEVICE_ID,
            //       bindingList.binding_list.BINDING_LIST_ID,
            //       bindingList.binding_list.TARGET_DEVICE_TYPE,$event
            validateData(sourceID,targetID,bindingListID,targetType,event){
                let data = this.binding.TARGET_DEVICES;
                let data2 = this.bindedList;
                if(targetType == 'ir_remote'){
                    var exist = [];
                    var exist2 = [];
                    exist = data.filter(function(i){
                        if(i.TARGET_DEVICE_ID == targetID){
                            return i.TARGET_DEVICE_ID;
                        }
                    });
                    if(exist.length > 0){
                        for(var i in data){
                            if(data[i].BINDING_LIST_ID != bindingListID){
                                event.preventDefault();
                            }
                        }
                    }
                    if(data2 == null){
                        data2 = [];
                    }
                    if(data2.length > 0){
                        for(var i in data2){
                            if (data2[i].DEVICE_ID == sourceID) {
                                exist2 = data2[i].bindings.filter(function(i){
                                    if(i.SOURCE_DEVICE_ID == sourceID &&
                                       i.TARGET_DEVICE_ID == targetID){
                                        return i.TARGET_DEVICE_ID;
                                    }
                                });
                                break;
                            }
                        }
                        if(exist2.length > 0){
                            event.preventDefault();
                        }
                    }


                }
            },
            //Function Name: saveBinding
            //Function Description: save binding function
            saveBinding(){

                let finalAddList    = [];       //final list
                let finalRemoveList = [];       //final remove list

                //Segregate Add list to Remove list
                for(var i in this.bindingLists){
                    if(this.bindingLists[i].isActive == true){
                        finalAddList.push(this.bindingLists[i].target_device);
                    }else{
                        finalRemoveList.push(this.bindingLists[i].target_device);
                    }
                }


                //Check if you already chose a custom condition for supported device
                if(this.isCustomConditionFilled(finalAddList) == false){
                    this.swal('error','Please fill out all Custom Binding');
                    return;
                }
                axios({
                    url: 'createBinding',
                    method: 'post',
                    data: {
                        'TARGET_DEVICES':finalAddList,
                        'REMOVE_LIST':finalRemoveList
                    }
                }).then(response => {
                    if(response.data == 'success'){
                        this.swal('success','Device Binding Successfully Save');
                    }else{
                        this.swal('error','Device Binding Error');
                    }
                }).catch(error => {
                        var title = this.$t('error_message_code.ERR_OPS_020');
                        var message = this.$t('logs.error');
                        let errormessage = title + " : " + error.response.data.message;
                        axios.post('createSystemLogs',{ERROR_MESSAGE:errormessage});
                        this.swal('error',title,message);
                    });
                this.$bus.$emit('getDeviceBindingData');
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
            },
            //Function Name: checkCustomCondition
            //Function Description: Check if the target device has a custom condition
            //param: Device Type
            checkCustomCondition(device_type){
                if(this.customConditionList.includes(device_type)){
                    return true;
                }else{
                    return false;
                }
            },
            //Function Name: clickCustomCondition
            //Function Description: Choose device that has custom condition
            //param: device_information, index
            clickCustomCondition(device_information,index){
                this.currentCustomDevice        = device_information;
                this.currentCustomDeviceIndex   = index
                this.modal.showCustomConditionBindingModal = true;
            },

            //Function Name: clickCustomCondition
            //Function Description: Choose device that has custom condition
            //param: device_information, index
            saveDeviceCondition(customDeviceCondition){
                let index = this.currentCustomDeviceIndex;
                this.bindingLists[index]['target_device']['CUSTOM_CONDITION'] = customDeviceCondition;

            },
            //Function Name: clickCheckBox
            //Function Description: if each checkbox is clicked
            //param:
            clickCheckBox(){
                //Length of selected Binding List
                // let bindingLength = this.bindingLists.filter(item => item.isActive == false).length;
                // if(bindingLength == this.bindingLists.length){
                //     this.disableSaveButton = true;
                // }else{
                //     this.disableSaveButton = false;
                // }
            },
            //Function Name: isCustomConditionFilled
            //Function Description: Check if this Custom Condition still needs to fill up
            //param: finalBindingList
            isCustomConditionFilled(finalBindingList){
                let deviceWithCondition = finalBindingList.filter((item)=>{
                    return this.customConditionList.includes(item.DEVICE_TYPE);
                });
                let customConditionExist = deviceWithCondition.filter((item)=>{
                    return item.CUSTOM_CONDITION.length == 0;
                });

                if(customConditionExist.length == 0){return true;
                }else{return false;}
            },
            //Function Name: swal``
            //Function Description: Show message
            //param: type, title, message
            swal(type,title,message){
                this.$swal({
                            position: 'center',
                            type: type,
                            title: title,
                            message:message,
                            showConfirmButton: false,
                            timer: 1200
                        });
            },

        },
        watch:{
            //Trigger when device outisde component is clicked
            propsDeviceId:function(){
                this.binding.SOURCE_DEVICE_ID = this.propsDeviceId;

                this.deviceSourceCondition();
                //this.getSourceDevices();
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
        computed:{
            //disbale save button
            renderData(){
                var data = this.bindingLists;
                var search = this.tableData.search;
                var filteredData = [];

                return data;

            },
            totalBinded(){
                var cnt = 0;
                for (var key in this.bindingLists) {
                    //check is the binding list is not null
                    if(this.bindingLists[key].binding == null){
                        cnt++;
                    }
                }
                return cnt;
            },
            //show or hide pagination
            showPaginate(){
                return _.size(this.bindingLists) > 0
            },
            disableTime(){
                if (this.sourceDevice.DEVICE_TYPE == 'light_detector') {
                    return true;
                }else{
                    return false;
                }
            }
        },
    };
</script>

