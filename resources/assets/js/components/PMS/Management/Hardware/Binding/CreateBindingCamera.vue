<!--
    <System Name> iBMS
    <Program Name> CreateBindingDevice.vue

    <Created>      20201027      TP Harvey
    <Updated>
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
                            <input type="text" class="form-control" v-model="tableData.search" @input="bindingList()"
                                   :placeholder="$t('search')">
                        </div>
                        <!-- search end-->
                    </div>
                    <div class="divider-line"></div>
                    <!-- table component -->
                    <div class="table-responsive h-374">

                        <b-table :items="renderData" :fields="table.fields" :current-page="table.currentPage"
                                 :per-page="table.perPage" :filter="tableData.search">
                            <!-- DEVICE TYPE -->
                            <template slot="device_info.DEVICE_TYPE" slot-scope="row">
                                <div class="d-flex">
                                    <b-form-checkbox v-model="row.item.isActive" />
                                    {{row.item.device_info.DEVICE_TYPE}}
                                </div>
                            </template>

                            <!-- DEVICE CONDITION -->
                            <template slot="target_device.TARGET_DEVICE_CONDITION" slot-scope="row">
                                <div v-if="checkCustomCondition(row.item.device_info.DEVICE_TYPE)">
                                    <button class="btn btn-primary btn-sm"
                                            @click="clickCustomCondition(row.item.device_info,row.index)">Custom
                                        Binding</button>
                                </div>
                                <div v-else>
                                    {{row.item.target_device.TARGET_DEVICE_CONDITION}}
                                </div>
                            </template>

                            <!-- TIME INTERVAL -->
                            <template slot="time" slot-scope="row">
                                <select class="custom-select" v-model="row.item.target_device.TIME_INTERVAL">
                                    <!-- loop timerValue data -->
                                    <option v-for="timer in timerValue" :value="timer.VAL">{{ timer.VAL }}
                                        {{$t('binding.min')}}</option>>
                                </select>
                            </template>
                        </b-table>

                    </div>
                    <!-- pagination component -->
                    <!-- call getData function when prev, next and number is click -->
                    <div v-if="showPaginate" class="d-flex justify-content-between">
                        <div class="ml-auto">
                            <b-pagination :total-rows="table.totalRows" :per-page="table.perPage"
                                          v-model="table.currentPage" />
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
        <CustomConditionBindingModal v-if="modal.showCustomConditionBindingModal==true" :device="currentCustomDevice"
                                     @saveDeviceCondition="saveDeviceCondition"
                                     @closeModal="modal.showCustomConditionBindingModal=false">
        </CustomConditionBindingModal>
    </div>
</template>

<script>
import CustomConditionBindingModal from './Modal/CustomConditionBindingModal.vue'
export default {
    components: {
        CustomConditionBindingModal,
    },
    props: {
        locale: '',
        propsFloorId: '',
        propsRoomId: '',
        propsGatewayId: '',
        propsDeviceId: '',
        propsDeviceCondition: '',
        propsSelectedRoomTarget: '',
    },
    created() {
        this.inputDefaultId()
        this.getFloors()
        this.$bus.$on('setDeviceBindingData', data => {
            this.bindedList = data
        })
    },
    mounted() {
        this.changeTable()
    },
    data() {
        return {
            table: {
                fields: [
                    { key: 'device_info.DEVICE_TYPE', label: 'DEVICE TYPE' },
                    { key: 'device_info.DEVICE_NAME', label: 'DEVICE NAME' },
                    { key: 'target_device.TARGET_DEVICE_CONDITION', label: 'DEVICE CONDITION' },
                    { key: 'time', label: 'TIME_INTERVAL' },
                ],
                currentPage: 1,
                totalRows: 0,
                perPage: 5,
            },
            tableData: {
                search: '',
            },
            bindedList: null,
            binding: {
                FLOOR_ID: null,
                ROOM_ID: null,
                GATEWAY_ID: null,
                TARGET_DEVICES: [],
                TARGET_DEVICE_CATEGORY: 0,
                SOURCE_DEVICE_ID: null,
                SOURCE_DEVICE_CONDITION: null,
            },
            floors: {},
            rooms: {},
            gateways: {},
            devices: {},
            deviceConditions: {
                SELECTED: null,
                DATA: [],
            },
            bindingLists: [],

            // 10/20/2020 Deleted
            // tempValues:[{"tempDown":'TEMP_16'}, {"tempDown":'TEMP_17'}, {"tempDown":'TEMP_18'},
            //             {"tempDown":'TEMP_19'}, {"tempDown":'TEMP_20'}, {"tempDown":'TEMP_21'},
            //             {"tempDown":'TEMP_22'}, {"tempDown":'TEMP_23'}, {"tempDown":'TEMP_24'},
            //             {"tempDown":'TEMP_25'}, {"tempUp":'TEMP_26'}, {"tempUp":'TEMP_27'},
            //             {"tempUp":'TEMP_28'}, {"tempUp":'TEMP_29'}, {"tempUp":'TEMP_30'}],

            timerValue: [{ VAL: 1 }, { VAL: 5 }, { VAL: 10 }, { VAL: 15 }, { VAL: 20 }, { VAL: 0 }],
            loading: false,
            required: false,
            disableSaveButton: false,

            //Harvey 20200214
            //Custom Condition Binding
            modal: {
                showCustomConditionBindingModal: false,
            },
            customConditionList: [
                'wall_switch_1', //Supported Custom Condition
                'wall_switch_2',
                'wall_switch_3',
                'embedded_switch_1',
                'embedded_switch_2',
                'embedded_switch_3',
                // 9/22/2020 Added "ir_remote"
                'ir_remote',
                'gas_valve',
            ],
            currentCustomDevice: '', //Device information for Custom Device
            currentCustomDeviceIndex: '', //Use to determine device Custom Condition
            deviceBindingCondition: [], //List of Device That will be trigger
        }
    },
    methods: {
        //Function Name: inputDefaultId
        //Function Description: sets default data
        inputDefaultId() {
            this.binding.FLOOR_ID = this.propsFloorId
            this.binding.ROOM_ID = this.propsRoomId
            this.binding.GATEWAY_ID = this.propsGatewayId
            this.binding.SOURCE_DEVICE_CONDITION = this.propsDeviceCondition
            this.binding.SOURCE_DEVICE_ID = this.propsDeviceId
            //this.deviceSourceCondition();  20201027 delete
            this.bindingList()
        },
        //Function Name: getFloors
        //Function Description: get floors
        getFloors() {
            axios
                .post('getFloorAll')
                .then(response => {
                    this.floors = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        //Function Name: deviceSourceCondition
        //Function Description: get device condition
        //PARAM: SOURCE_DEVICE_ID
        deviceSourceCondition() {
            ;(this.bindingLists = []),
                axios
                    .get('getDeviceBindingListCondition/' + this.binding.SOURCE_DEVICE_ID)
                    .then(response => {
                        this.deviceConditions.SELECTED = response.data[0].SELECTED
                        this.deviceConditions.DATA = response.data[0].DATA
                        this.binding.SOURCE_DEVICE_CONDITION = response.data[0].SELECTED
                        this.bindingList()
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
        },
        //Function Name: bindingList
        //Function Description: display the device from the binding list
        //PARAM: SOURCE_DEVICE_ID, SOURCEDEVICE_CONDITION,pages
        bindingList(pages) {
            let floor_id = this.propsFloorId
            let room_id = this.propsSelectedRoomTarget.ROOM_ID
            let filter = '?filter=DEVICE_CATEGORY:0|FLOOR_ID:' + floor_id + '|ROOM_ID:' + room_id

            axios.get('getCameraBindingDevices' + filter).then(response => {
                if (response.data.length > 0) {
                    //this.bindingLists = response.data;
                    var responseData = response.data
                    var binding = {}
                    var customCondition = []
                    var isActive = false
                    var timeInterval = 1
                    self = this
                    self.bindingLists = []

                    responseData.map(function (item, key) {
                        //Check if binding Existed
                        for (var i in item['CAMERA_BINDING']) {
                            let bindingCamera = item['CAMERA_BINDING'][i]
                            timeInterval = 1
                            if (
                                bindingCamera['SOURCE_DEVICE_ID'] == self.propsDeviceId &&
                                bindingCamera['SOURCE_DEVICE_CONDITION'] == self.propsDeviceCondition
                            ) {
                                //Input to binding selection
                                customCondition = item['CAMERA_BINDING'][i]['CUSTOM_CONDITION']
                                item['CUSTOM_CONDITION'] = item['CAMERA_BINDING'][i]['CUSTOM_CONDITION']
                                isActive = true
                                timeInterval = item['CAMERA_BINDING'][i]['TIME_INTERVAL'] / 60
                            }
                        }

                        //Create a CUSTOM_CONDITION Manually
                        if (item['CUSTOM_CONDITION'] == undefined) {
                            item['CUSTOM_CONDITION'] = []
                        }

                        binding = {
                            SOURCE_DEVICE_CONDITION: self.propsDeviceCondition,
                            SOURCE_DEVICE_ID: self.propsDeviceId,
                            CUSTOM_CONDITION: customCondition,
                            TARGET_DEVICE_ID: item['DEVICE_ID'],
                            TIME_INTERVAL: timeInterval,
                            DEVICE_TYPE: item['DEVICE_TYPE'],
                        }

                        self.bindingLists.push({ device_info: item, isActive: isActive, target_device: binding })
                        //Reset Variable
                        isActive = false
                        timeInterval = 1
                    })
                    // for(var i in response.data){
                    //     sss[i]["BINDING"] = {};
                    //     sss[i]['IS_ACTIVR'] = {};
                    //     sss[i]['DEVICE_INFO'] = response.data[i];
                    // }
                }
            })
        },
        //Function Name: saveBinding
        //Function Description: save camera binding function
        saveBinding() {
            let finalAddList = [] //final list
            let finalRemoveList = [] //final remove list

            //Segregate Add list to Remove list
            for (var i in this.bindingLists) {
                if (this.bindingLists[i].isActive == true) {
                    finalAddList.push(this.bindingLists[i].target_device)
                } else {
                    finalRemoveList.push(this.bindingLists[i].target_device)
                }
            }

            //Check if you already chose a custom condition for supported device
            if (this.isCustomConditionFilled(finalAddList) == false) {
                this.swal('error', 'Please fill out all Custom Binding')
                return
            }

            axios
                .post('createCameraBinding', { TARGET_DEVICES: finalAddList, REMOVE_LIST: finalRemoveList })
                .then(response => {
                    response.data = response.data.replace(/\s/g, '')
                    if (response.data == 'success') {
                        this.swal('success', 'Camera Binding Added!')
                    } else {
                        this.swal('error', 'Device Binding Error')
                    }
                })

            return
            axios({
                url: 'createBinding',
                method: 'post',
                data: {
                    TARGET_DEVICES: finalAddList,
                    REMOVE_LIST: finalRemoveList,
                },
            })
                .then(response => {
                    if (response.data == 'success') {
                        this.swal('success', 'Device Binding Successfully Save')
                    } else {
                        this.swal('error', 'Device Binding Error')
                    }
                })
                .catch(error => {
                    var title = this.$t('error_message_code.ERR_OPS_020')
                    var message = this.$t('logs.error')
                    let errormessage = title + ' : ' + error.response.data.message
                    axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                    this.swal('error', title, message)
                })
            this.$bus.$emit('getDeviceBindingData')
        },
        //Function Name: changeTable
        //Function Description: change table label according to language settings
        //param: $i18n.locale
        changeTable() {
            var labels = this.table.fields
            var messages = this.$t('binding.bindingDevice')
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
        //Function Name: checkCustomCondition
        //Function Description: Check if the target device has a custom condition
        //param: Device Type
        checkCustomCondition(device_type) {
            if (this.customConditionList.includes(device_type)) {
                return true
            } else {
                return false
            }
        },
        //Function Name: clickCustomCondition
        //Function Description: Choose device that has custom condition
        //param: device_information, index
        clickCustomCondition(device_information, index) {
            this.currentCustomDevice = device_information
            this.currentCustomDeviceIndex = index
            this.modal.showCustomConditionBindingModal = true
        },

        //Function Name: clickCustomCondition
        //Function Description: Choose device that has custom condition
        //param: device_information, index
        saveDeviceCondition(customDeviceCondition) {
            let index = this.currentCustomDeviceIndex
            this.bindingLists[index]['target_device']['CUSTOM_CONDITION'] = customDeviceCondition
            this.bindingLists[index]['device_info']['CUSTOM_CONDITION'] = customDeviceCondition
        },
        //Function Name: isCustomConditionFilled
        //Function Description: Check if this Custom Condition still needs to fill up
        //param: finalBindingList
        isCustomConditionFilled(finalBindingList) {
            let deviceWithCondition = finalBindingList.filter(item => {
                return this.customConditionList.includes(item.DEVICE_TYPE)
            })
            let customConditionExist = deviceWithCondition.filter(item => {
                return item.CUSTOM_CONDITION.length == 0
            })

            if (customConditionExist.length == 0) {
                return true
            } else {
                return false
            }
        },
        //Function Name: swal``
        //Function Description: Show message
        //param: type, title, message
        swal(type, title, message) {
            this.$swal({
                position: 'center',
                type: type,
                title: title,
                message: message,
                showConfirmButton: false,
                timer: 1200,
            })
        },
    },
    watch: {
        //Trigger when device outisde component is clicked
        propsDeviceId: function () {
            this.binding.SOURCE_DEVICE_ID = this.propsDeviceId

            this.deviceSourceCondition()
            //this.getSourceDevices();
        },
        //Trigger when device condition outisde component is clicked
        propsDeviceCondition: function () {
            //ON/OFF/ALARM/NORMAL
            this.deviceConditions.SELECTED = this.propsDeviceCondition
            //ON/OFF/ALARM/NORMAL
            this.binding.SOURCE_DEVICE_CONDITION = this.propsDeviceCondition
            this.bindingList()
        },
        locale: function () {
            this.changeTable()
        },
    },
    computed: {
        //disbale save button
        renderData() {
            var data = this.bindingLists
            var search = this.tableData.search
            var filteredData = []

            return data
        },
        totalBinded() {
            var cnt = 0
            for (var key in this.bindingLists) {
                //check is the binding list is not null
                if (this.bindingLists[key].binding == null) {
                    cnt++
                }
            }
            return cnt
        },
        //show or hide pagination
        showPaginate() {
            return _.size(this.bindingLists) > 0
        },
    },
}
</script>

