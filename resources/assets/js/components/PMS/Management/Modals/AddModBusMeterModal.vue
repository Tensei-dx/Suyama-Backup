<!--
    <System Name> iBMS
    <Program Name> AddModBusMeterModal.vue
    <Create>             TP Robert
             2019.07.11  TP Ivin Insert Comment header
             2019.12.06  TP Ivin disable loading Saving Button
             2020.05.27  TP Uddin Modify axios URL according to the URL list
--> 
<template>
    <!-- cat,show are props of vue contain the data from other component -->
    <!-- gateway display -->
    <!-- modal -->
    <div class="modal" :class="show">
        <!-- call close function  -->
        <div class="modal-background" @click="close"></div>
        <!-- modal dialog -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <!-- modal content -->
            <div class="modal-content">
                <!-- modal header -->
                <div class="modal-header text-dark">
                    <h5 class="modal-title">{{$t('device.addMeter')}}</h5>
                    <!-- call close function on line 259 -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- modal header end-->
                <!-- modal body -->
                <div class="modal-body text-dark">
                    <div class="form-group">
                        <label class="label">{{$t('floor.gateway')}}</label>
                        <div class="">
                            <!-- call getRooms function -->
                            <select class="custom-select" v-model="modalData.GATEWAY_ID" @change="getFloors()">
                                <!-- loop the floors data -->
                                <option v-for="gateway,key in gateways" :key="gateway.GATEWAY_ID" :value="gateway.GATEWAY_ID">{{gateway.GATEWAY_NAME}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="label">{{$t('floor.floor')}}</label>
                        <div class="">
                            <!-- call getRooms function -->
                            <select class="custom-select" v-model="modalData.FLOOR_ID" disabled>
                                <!-- loop the floors data -->
                                <option v-for="floor,key in floors" :key="floor.FLOOR_ID" :value="floor.FLOOR_ID">{{floor.FLOOR_NAME}}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if="showRoom" class="form-group"  id="showMeterDetails">
                        <div class="row">
                            <div class="col-md-4">{{$t('floor.roomName')}}</div>
                            <div class="col-md-4">{{$t('gateway.devSerialNo')}}</div>
                            <div class="col-md-3">{{$t('device.modbusSlave')}}</div>
                        </div>
                        <div class="row room-list-group-scroll">
                            <!-- Loop Room List -->
                            <div v-for="data,key in modalData.meterData" class="col-md-12 room-list-padding">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Room Name -->
                                        <select class="custom-select" v-model="data.roomID" @change="updateRoomSelected()">
                                            <option></option>
                                            <option v-for="room,key in rooms" :key="room.ROOM_ID" :disabled="room.selected == true" :value="room.ROOM_ID">{{room.ROOM_NAME}}</option>
                                        </select>
                                        <small v-if="errors['DATA.' + key + '.roomID']" class="text-danger">{{ errors['DATA.' + key + '.roomID'][0] }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" v-model="data.serialNo" class="form-control" placeholder="Serial No">
                                        <!-- display this when error occured -->
                                        <small v-if="errors['DATA.' + key + '.serialNo']" class="text-danger">{{ errors['DATA.' + key + '.serialNo'][0] }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" v-model="data.slaveID" class="form-control" placeholder="Slave ID">
                                        <small v-if="errors['DATA.' + key + '.slaveID']" class="text-danger">{{ errors['DATA.' + key + '.slaveID'][0] }}</small>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-default" @click="removeSpecificRoomList(key)">
                                            <i class="fa fa-minus fa-lg pointer text-success"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <!-- Add Remove of Rooms + - -->
                            <div class="col-md-12">
                                <div class="float-right">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-default" @click="removeRoomList()">
                                            <i  class="fa fa-minus fa-lg pointer text-success"></i>
                                        </button>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control text-center" v-model="numAdd" placeholder="No">
                                        </div>
                                        <button class="btn btn-default" @click="addRoomList()">
                                            <i class="fa fa-plus fa-lg pointer text-success"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal body end -->
                <!-- modal footer -->
                <div class="modal-footer">
                    <!-- call addData function  -->
                    <!-- enable /disable the save button using gwSaveDisabled function  -->
                    <button class="btn btn-primary" @click="addData()" :disabled="saveDisabled">
                        <!-- display loading animation -->
                        <span class="pull-left" v-if="loading">
                            <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                        </span>
                        <!-- change text value when click -->
                        <span> {{ btn_text }} </span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="close">Close</button>
                </div>
                <!-- modal footer end -->
            </div>
            <!-- modal content end -->
        </div>
        <!-- modal dialog end -->
    </div>
    <!-- modal end-->
</template>
<script>
    export default{
        //get the attributes from other components
        props: ['cat','show', 'currentPage'],
        created (){
            //this.getFloors();
            this.getGateways();
            $("body").addClass("modal-open");
        },
        data(){
            return{
                // where data variables is declare and initialize
                gateways:{},
                floors:{},
                rooms:{},
                modalData:{
                    GATEWAY_ID: null,
                    FLOOR_ID: null,
                    meterData: [],
                },
                errors:{},
                loading:false,
                btn_text: 'Save',
                error_text: 'This field is required',
                numAdd : 1,
            }
        },
        methods: {
            //Funcition Name: getGateways
            //Function Description: get gateways
            //Param: MANUFACTURER_ID
            getGateways(){
                //ajax call
                axios.get('getGatewayAll?filter=MANUFACTURER_ID:2')
                .then(response => {
                    this.gateways = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Funciton Name: getFloors
            //Function Description: get floors
            //Param: GATEWAY_ID
            getFloors(){
                //ajax call
                if(this.modalData.GATEWAY_ID != null){
                    axios.get('getGatewayFloor/'+ this.modalData.GATEWAY_ID)
                    .then(response => {
                        var data =  response.data;
                        this.floors = data;
                        this.modalData.FLOOR_ID = data[0].FLOOR_ID;
                        this.getRooms();
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
                }
            },
            //Function Name: getRooms
            //Function Description: get room function
            //Param: FLOOR_ID
            getRooms(){
                //ajax call
                axios.post('getFloorRooms/'+ this.modalData.FLOOR_ID)
                .then(response => {
                    this.rooms = response.data;
                    for(var key in this.rooms){
                        Vue.set(this.rooms[key], 'selected', false);
                    }
                    if(this.show){
                        $("#showMeterDetails").slideUp(500);
                        $("#showMeterDetails").slideDown(500);
                    }
                    this.modalData.meterData = [];
                    this.numAdd = 1;
                    this.modalData.meterData.push({'serialNo':null,'slaveID':null});
            })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: addRoomList
            //Function Description: add room to list
            addRoomList(){
                var x = 1;
                var available = this.rooms.length - this.modalData.meterData.length;
                if(this.numAdd == "" || this.numAdd == 0 || this.numAdd == null){
                    let message = this.$t('error_message_code.ERR_OPS_078');
                    this.$toast.error(message,"Error",{position:"topCenter"});
                }else{
                    if(this.numAdd > available){
                        let message = this.$t('error_message_code.ERR_OPS_017');
                        this.$toast.error(message + available,"Error",{position:"topCenter"});
                    }else{
                        for(x; x <= this.numAdd; x++){
                            this.modalData.meterData.push({'serialNo':null,'slaveID':null});
                        }
                    }
                }
            },
            //Function Name: removeRoomList
            //Function Description: Decrease room list
            removeRoomList(){
                var x = 1;
                if(this.numAdd == "" || this.numAdd == 0 || this.numAdd == null){
                    let message = this.$t('error_message_code.ERR_OPS_078');
                    this.$toast.error(message,"Error",{position:"topCenter"});
                }else{
                    if(this.modalData.meterData.length > this.numAdd || this.modalData.meterData.length > 1){
                        for(x; x <= this.numAdd; x++){
                            this.modalData.meterData.pop();
                            this.updateRoomSelected();
                        }
                    }else{
                        let message = this.$t('error_message_code.ERR_OPS_079');
                        this.$toast.error(message,"Error",{position:"topCenter"});
                    }
                }
            },
            //Function name: removeSpecificRoomList
            //Function Description: remove a specific room from list
            //Param: key (key in rooms)
            removeSpecificRoomList(key){
                if(this.modalData.meterData.length > 1){
                    this.modalData.meterData.splice(key, 1);
                    this.updateRoomSelected();
                }else{
                    let message = this.$t('error_message_code.ERR_OPS_079');
                    this.$toast.error(message,"Error",{position:"topCenter"});
                }
            },
            //Function name: updateRoomSelected
            //Function Description: update room
            updateRoomSelected(){
                var rooms = this.rooms;
                var meters = this.modalData.meterData;
                for(var x in rooms){
                    var roomID = rooms[x].ROOM_ID;
                    for(var y in meters){
                        if(roomID == meters[y].roomID){
                            rooms[x].selected = true;
                            break;
                        }else{
                            rooms[x].selected = false;
                        }
                    }
                }
            },
            //Function Name: close
            //Function Description: close modal function
            close(){
                this.errors = {},
                this.loading = false,
                this.btn_text = 'Save',
                this.$emit('loaddata', this.currentPage),
                this.$emit('close')
            },
            //Function Name: errs
            //Function Description: error function
            //PARAM: error = response
            errs(error){
                this.errors = error.response.data.errors
                this.loading = false,
                this.btn_text = 'Save'
            },
            //Function Name: addData
            //Function Description: add device or gateway function
            addData(){
                this.loading = true,
                this.btn_text ='Saving',
                //ajax call
                //PARAM: FLOOR_ID,ROOM_ID,GATEWAY_ID,GATEWAY_NAME
                axios({
                  url: 'createMeter',
                  method: 'post',
                  data: {
                    'FLOOR_ID':this.modalData.FLOOR_ID,
                    'GATEWAY_ID':this.modalData.GATEWAY_ID,
                    'DATA':this.modalData.meterData
                    }
                })
                .then((response)=>
                    
                    setTimeout(() => {
                       this.close();
                    }, 1500)
                )
                .catch((error) => this.errs(error));

                this.modalData.meterData=[]
            }
        },
        computed:{
            //disable room select
            roomDisabled(){
                if(this.modalData.FLOOR_ID == null){
                    return true;
                }else{
                    return false;
                }
            },
            //disable serial field
            serialDisabled(){
                if(this.modalData.ROOM_ID == null){
                    return true;
                }else{
                    return false;
                }
            },
            //disable name field
            slaveDisabled(){
                if(this.modalData.SERIAL_NO == null){
                    return true;
                }else{
                    return false;
                }
            },
            //disable gateway button
            saveDisabled(){
                var meters = this.modalData.meterData;
                if(meters.length > 0){
                    if (meters.length == 1) {
                        if (meters[0].roomID &&
                            meters[0].serialNo != null &&
                            meters[0].slaveID != null) {
                            return false;
                        }else{
                            return true;
                        }
                    }
                }else{
                    return true;
                }
            },
            showRoom(){
                if(this.modalData.FLOOR_ID != null){
                    return true;
                }else{
                    return false;
                }
            }
        },
        beforeDestroy() {
            $("body").removeClass("modal-open");
        }
    }
</script>