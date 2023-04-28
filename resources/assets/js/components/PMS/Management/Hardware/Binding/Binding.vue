<template>
    <div>
        <!-- content-wrapper -->
        <div class="wrapper hardware-img-bg">
            <!-- content-fluid -->
            <div class="container-fluid h-880">
                <div class="d-flex justify-content-between py-3">
                    <div>
                        <div>{{$t('binding.header')}}</div>
                    </div>
                    <clock :locale="$i18n.locale"></clock>
                </div>
                <!-- View Binding List Mode -->
                <div v-if="bindingMode=='viewBindingList'" class="row h-826">
                    <div class="col-md-4 mb-3 mt-2">
                        <div class="background-orange">
                            <div class="pt-2 pb-1 pl-3">
                                <h5><b>{{$t('binding.floor')}}</b></h5>
                            </div>
                        </div>
                        <div class="tab-bg-2 custom-scroll-bar">
                            <!-- Looping Floor -->
                            <h1 v-if="floorList.length > 0" v-for="floor in limitedFloors"
                                @click="chooseListBindingFloor(floor)" class="pl-2 text-dark pointer binding-lists"
                                :class="floor['FLOOR_ID']==selectedBindingListFLoor['FLOOR_ID'] ? 'binding-selected':''">
                                <b>{{floor['FLOOR_NAME']}}</b>
                            </h1>
                            <div v-if="floorList.length > 4"
                                 class="d-flex justify-content-center custom-pagination-orange">
                                <b-pagination :total-rows="floorList.length" :per-page="listPagination.paginationLimit"
                                              v-model="listPagination.floorCurrentPage">
                                </b-pagination>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3 mt-2">
                        <div class="binding-map-bar card-body border border-dark h-271 tab-bg-2">
                            <div class="row mt-3" v-if="selectedBindingListFLoor!=[]">
                                <BindingMapView v-if="floorList.length > 0" :currentFloor="selectedBindingListFLoor"
                                                :currentRoom="selectedRoom" @selectRoom="mapViewSelect">
                                </BindingMapView>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 mt-2">
                        <!-- tab-navigation -->
                        <div class="d-flex justify-content-between align-items-center nav-line">
                            <!-- div-tab-list -->
                            <!-- Nav Bar -->
                            <div class="nav nav-tabs nav-line w-100" role="tablist">
                                <a class="nav-item nav-link nav-header-blue"
                                   :class="selectedCategory=='Device Binding' ? 'active':''" data-toggle="tab"
                                   href="#binding-device-list" role="tab" aria-controls="nav-binding"
                                   aria-selected="true"
                                   @click="tabShow('Device Binding')">{{$t('binding.sensorDevice')}}</a>
                                <a class="nav-item nav-link nav-header-blue"
                                   :class="selectedCategory=='Alert Binding' ? 'active':''" data-toggle="tab"
                                   href="#binding-sensor-list" role="tab" aria-controls="nav-binding"
                                   aria-selected="false"
                                   @click="tabShow('Alert Binding')">{{$t('binding.sensorAlert')}}</a>
                                <!-- 20201026 Camera Binding -->
                                <a class="nav-item nav-link nav-header-blue"
                                   :class="selectedCategory=='Camera Binding' ? 'active':''" data-toggle="tab"
                                   href="#binding-camera-list" role="tab" aria-controls="nav-binding"
                                   aria-selected="false"
                                   @click="tabShow('Camera Binding')">{{$t('binding.cameraDevice')}}</a>
                            </div>
                            <!-- div-tablist-end -->
                            <button v-if="selectedCategory=='Device Binding'" @click="clickAddBinding('device')"
                                    class="btn btn-sm btn-light">{{$t('binding.addDevice')}}</button>
                            <button v-if="selectedCategory=='Alert Binding'" @click="clickAddBinding('alert')"
                                    class="btn btn-sm btn-light">{{$t('binding.addAlert')}}</button>
                            <button v-if="selectedCategory=='Camera Binding'" @click="clickAddBinding('camera')"
                                    class="btn btn-sm btn-light">{{$t('binding.addCamera')}}</button>
                        </div>
                        <div class="tab-content tab-bg-2" id="nav-tabContent">
                            <div v-if="selectedCategory=='Device Binding'" class="tab-pane fade show active"
                                 :class="selectedCategory=='Device Binding' ? 'show active':''" id="binding-device-list"
                                 role="tabpanel">
                                <DeviceBindingManagement :selectedRoom="selectedRoom"
                                                         :selectedBindingListFLoor="selectedBindingListFLoor"
                                                         @change-size="changeSize($event)"></DeviceBindingManagement>
                            </div>
                            <div v-else-if="selectedCategory=='Alert Binding'" class="tab-pane fade"
                                 :class="selectedCategory=='Alert Binding' ? 'show active':''" id="binding-sensor-list"
                                 role="tabpanel">
                                <AlertBindingManagement :selectedRoom="selectedRoom"
                                                        :selectedBindingListFLoor="selectedBindingListFLoor"
                                                        @change-size="changeSize($event)"></AlertBindingManagement>
                            </div>
                            <!--20201026 Camera Binding -->
                            <div v-if="selectedCategory=='Camera Binding'" class="tab-pane fade show active"
                                 :class="selectedCategory=='Device Binding' ? 'show active':''" id="binding-camera-list"
                                 role="tabpanel">
                                <CameraBindingManagement :selectedRoom="selectedRoom"
                                                         :selectedBindingListFLoor="selectedBindingListFLoor"
                                                         @change-size="changeSize($event)"></CameraBindingManagement>
                            </div>
                        </div>
                        <!-- divtab-content-end -->
                    </div>
                </div>
                <!-- Add Binding Mode -->
                <div v-if="bindingMode=='addBinding'">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="nav nav-tabs w-100" role="tablist">
                            <a v-if="selectedCategory == 'Device Binding'" class="nav-item nav-link px-4 active"
                               data-toggle="tab" role="tab" aria-controls="nav-binding"
                               aria-selected="true">{{$t('binding.createDevice')}}</a>
                            <a v-else-if="selectedCategory == 'Alert Binding'" class="nav-item nav-link px-4 active"
                               data-toggle="tab" role="tab" aria-controls="nav-binding"
                               aria-selected="true">{{$t('binding.createAlert')}}</a>
                        </div>
                        <!-- Breadcrumb -->
                        <div v-if="selectedCategory!=''" class="text-dark text-nowrap"><span class="breadcrumb-custom"
                                  @click="clickBreadcrumb('category')">{{selectedCategory}}</span></div>
                        <!--Floors-->
                        <div v-if="selectedFloor!=''" class="text-dark text-nowrap">/<span class="breadcrumb-custom"
                                  @click="clickBreadcrumb('floor')">{{selectedFloor['FLOOR_NAME']}}</span></div>
                        <!--Floors-->
                        <div v-if="selectedRoom!=''" class="text-dark text-nowrap">/<span class="breadcrumb-custom"
                                  @click="clickBreadcrumb('room')">{{selectedRoom['ROOM_NAME']}}</span></div>
                        <!--Room-->
                        <div v-if="selectedGateway!=''" class="text-dark text-nowrap">/<span class="breadcrumb-custom"
                                  @click="clickBreadcrumb('gateway')">{{selectedGateway['GATEWAY_NAME']}}</span></div>
                        <!--Gateway-->
                        <div v-if="selectedDevice!=''" class="text-dark text-nowrap">/<span class="breadcrumb-custom"
                                  @click="clickBreadcrumb('device')">{{selectedDevice['DEVICE_NAME']}}</span></div>
                        <!--Gateway-->
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <!-- Floor List -->
                            <div v-if="viewList=='floors'">
                                <div class="background-orange">
                                    <div class="pt-2 pb-1 pl-3">
                                        <h5><b>{{$t('binding.floor')}}</b></h5>
                                    </div>
                                </div>
                                <div class="binding-bg custom-scroll-bar">
                                    <!-- Looping Floor -->
                                    <h2 v-if="floorList.length > 0" v-for="floor in limitedFloors"
                                        @click="chooseFloor(floor)" class="pl-2 text-dark pointer binding-lists"
                                        :class="floor['FLOOR_ID']==selectedFloor['FLOOR_ID'] ? 'binding-selected':''">
                                        <b>{{floor['FLOOR_NAME']}}</b>
                                    </h2>
                                    <div v-if="floorList.length > 4"
                                         class="d-flex justify-content-center custom-pagination-orange">
                                        <b-pagination :total-rows="floorList.length"
                                                      :per-page="listPagination.paginationLimit"
                                                      v-model="listPagination.floorCurrentPage"></b-pagination>
                                    </div>
                                </div>
                            </div>
                            <!-- Room List -->
                            <div v-else-if="viewList=='rooms'">
                                <div class="background-orange">
                                    <div class="pt-2 pb-1 pl-3">
                                        <h5><b>{{$t('binding.room')}}</b></h5>
                                    </div>
                                </div>
                                <div class="binding-bg custom-scroll-bar">
                                    <!-- Looping Floor -->
                                    <h2 v-if="roomList.length > 0" v-for="room in limitedRooms"
                                        @click="chooseRoom(room)" class="pl-2 text-dark pointer binding-lists"
                                        :class="room['ROOM_ID']==selectedRoom['ROOM_ID'] ? 'binding-selected':''">
                                        <b>{{room['ROOM_NAME']}}</b>
                                    </h2>
                                    <div v-if="roomList.length > 4"
                                         class="d-flex justify-content-center custom-pagination-orange">
                                        <b-pagination :total-rows="roomList.length"
                                                      :per-page="listPagination.paginationLimit"
                                                      v-model="listPagination.roomCurrentPage"></b-pagination>
                                    </div>
                                </div>
                                <div></div>
                                <!--For Scroll Bar Bug-->
                            </div>
                        </div>
                        <div class="col-md-8 mt-2">
                            <div class="binding-bg-map">
                                <div class="row" v-if="selectedFloor!=''">
                                    <CreateBindingMap v-if="floorList.length > 0" :currentFloor="selectedFloor"
                                                      :currentRoom="selectedRoom"></CreateBindingMap>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="showGatewayList == true" class="col-md-4">
                            <!-- Gateway Header -->
                            <div class="background-orange">
                                <div class="pt-2 pb-1 pl-3">
                                    <h5><b>GATEWAY</b></h5>
                                </div>
                            </div>
                            <div class="binding-bg-gateway custom-scroll-bar">
                                <div v-for="gateway in limitedGateways" @click="chooseGateway(gateway)"
                                     class="text-dark pointer pl-2 pt-1 binding-lists"
                                     :class="gateway['GATEWAY_ID']==selectedGateway['GATEWAY_ID'] ? 'binding-selected':''">
                                    <h2><b>{{gateway['GATEWAY_NAME']}}</b></h2>
                                </div>
                                <div v-if="gatewayList.length > 4"
                                     class="d-flex justify-content-center custom-pagination-orange">
                                    <b-pagination :total-rows="gatewayList.length"
                                                  :per-page="listPagination.paginationLimit"
                                                  v-model="listPagination.gatewayCurrentPage"></b-pagination>
                                </div>
                            </div>
                        </div>
                        <div v-if="showDeviceList == true" class="col-md-4">
                            <!-- Device Header -->
                            <div class="background-orange">
                                <div class="pt-2 pb-1 pl-3">
                                    <h5><b>{{$t('binding.sensors')}}</b></h5>
                                </div>
                            </div>
                            <!-- Device List -->
                            <div class="binding-bg-gateway h-350 custom-scroll-bar">
                                <div v-if="deviceList.length > 0" v-for="device in limitedDevices"
                                     @click="chooseDevice(device)" class="text-dark pointer pl-2 pt-1 binding-lists"
                                     :class="device['DEVICE_ID']==selectedDevice['DEVICE_ID'] ? 'binding-selected':''">
                                    <h2><b>{{device['DEVICE_NAME']}}</b></h2>
                                </div>
                                <div v-if="deviceList.length > 4"
                                     class="d-flex justify-content-center custom-pagination-orange">
                                    <b-pagination :total-rows="deviceList.length"
                                                  :per-page="listPagination.paginationLimit"
                                                  v-model="listPagination.deviceCurrentPage">
                                    </b-pagination>
                                </div>
                                <h2 v-if="deviceList.length == 0">{{$t('binding.noDevice')}}</h2>
                            </div>
                            <!-- Device Condition List -->
                            <div v-if="deviceList.length > 0 && selectedRoomTarget.length != 0 && selectedCategory != 'Alert Binding'"
                                 class="background-orange">
                                <div class="pt-2 pb-1 pl-3">
                                    <h5><b>{{$t('binding.devCondition')}}</b>
                                        <a class="pointer" @click="clickCustomCondition(selectedDevice['DEVICE_TYPE'])">
                                            <span>
                                                <i aria-hidden="true" class="text-white fa fa-edit"></i>
                                            </span>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                            <div v-if="deviceList.length > 0 && selectedRoomTarget.length != 0 && selectedCategory == 'Device Binding'"
                                 class="binding-bg-device custom-scroll-bar">
                                <div v-for="deviceCondition in deviceConditionList"
                                     @click="chooseDeviceCondition(deviceCondition['SOURCE_DEVICE_CONDITION'])"
                                     class="text-dark pointer pl-2 pt-1 binding-lists"
                                     :class="deviceCondition['SOURCE_DEVICE_CONDITION']==selectedDeviceCondition ? 'binding-selected':''">
                                    <h2><b>{{deviceCondition['SOURCE_DEVICE_CONDITION']}}</b></h2>
                                </div>
                            </div>
                            <div v-if="deviceList.length > 0 && selectedRoomTarget.length != 0 && selectedCategory == 'Camera Binding'"
                                 class="binding-bg-device custom-scroll-bar">
                                <div v-for="deviceCondition in deviceConditionList"
                                     @click="chooseDeviceCondition(deviceCondition)"
                                     class="text-dark pointer pl-2 pt-1 binding-lists"
                                     :class="deviceCondition==selectedDeviceCondition ? 'binding-selected':''">
                                    <h2><b>{{deviceCondition}}</b></h2>
                                </div>
                            </div>
                            <div v-if="selectedCategory == 'Alert Binding' && showTargetInterface == 'floor'"
                                 class="background-orange">
                                <div class="pt-2 pb-1 pl-3">
                                    <h5><b>{{$t('binding.devCondition')}}</b>
                                        <a class="pointer" @click="clickCustomCondition(selectedDevice['DEVICE_TYPE'])">
                                            <span>
                                                <i aria-hidden="true" class="text-white fa fa-edit"></i>
                                            </span>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                            <div v-if="selectedCategory == 'Alert Binding' && showTargetInterface == 'floor'"
                                 class="binding-bg-device custom-scroll-bar">
                                <div v-for="deviceCondition in deviceConditionList"
                                     @click="chooseDeviceCondition(deviceCondition['SOURCE_DEVICE_CONDITION'])"
                                     class="text-dark pointer pl-2 pt-1 binding-lists"
                                     :class="deviceCondition['SOURCE_DEVICE_CONDITION']==selectedDeviceCondition ? 'binding-selected':''">
                                    <h2><b>{{deviceCondition['SOURCE_DEVICE_CONDITION']}}</b></h2>
                                </div>
                            </div>
                        </div>
                        <!-- Binding Creation Form aside from Alert Binding-->
                        <div v-if="showCreateForm == true && selectedCategory != 'Alert Binding'" class="col-sm-8">
                            <div class="row">
                                <div v-if="showTargetInterface == 'floor'" class="col-md-12">
                                    <div class="pt-2 pb-1 pl-3 background-orange text-white">
                                        <h5><b>{{$t('binding.targetFloor')}}</b></h5>
                                    </div>
                                    <div class="tab-bg-2 custom-scroll-bar">
                                        <!-- Looping Room -->
                                        <h1 v-if="floorList.length > 0" v-for="floor in limitedFloors"
                                            @click="chooseTargetFloor(floor)"
                                            class="pl-2 text-dark pointer binding-lists"
                                            :class="floor['FLOOR_ID']==selectedFloorTarget['FLOOR_ID'] ? 'binding-selected':''">
                                            <b>{{floor['FLOOR_NAME']}}</b>
                                        </h1>
                                        <div v-if="roomList.length > 4"
                                             class="d-flex justify-content-center custom-pagination-orange">
                                            <b-pagination :total-rows="roomTargetList.length"
                                                          :per-page="listPagination.paginationLimit"
                                                          v-model="listPagination.roomTargetCurrentPage">
                                            </b-pagination>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="showTargetInterface == 'room'" class="col-md-12">
                                    <div class="pt-2 pb-1 pl-3 background-orange text-white">
                                        <h5><b>{{$t('binding.targetRoom')}}</b></h5>
                                    </div>
                                    <div class="tab-bg-2 custom-scroll-bar">
                                        <!-- Looping Room -->
                                        <h1 v-if="roomTargetList.length > 0" v-for="room in limitedRoomsTarget"
                                            @click="chooseRoomTarget(room)" class="pl-2 text-dark pointer binding-lists"
                                            :class="room['ROOM_ID']==selectedRoomTarget['ROOM_ID'] ? 'binding-selected':''">
                                            <b>{{room['ROOM_NAME']}}</b>
                                        </h1>
                                        <div v-if="roomList.length > 4"
                                             class="d-flex justify-content-center custom-pagination-orange">
                                            <b-pagination :total-rows="roomTargetList.length"
                                                          :per-page="listPagination.paginationLimit"
                                                          v-model="listPagination.roomTargetCurrentPage">
                                            </b-pagination>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="showTargetInterface == 'devices'" class="col-md-12">
                                    <div class="tab-bg-2" v-if="selectedCategory == 'Device Binding'">
                                        <CreateBindingDevice :locale="$i18n.locale"
                                                             :propsFloorId="selectedFloorTarget['FLOOR_ID']"
                                                             :propsRoomId="selectedRoom['ROOM_ID']"
                                                             :propsGatewayId="selectedGateway['GATEWAY_ID']"
                                                             :propsDeviceId="selectedDevice['DEVICE_ID']"
                                                             :propsDeviceCondition="selectedDeviceCondition"
                                                             :propsSelectedRoomTarget="selectedRoomTarget">
                                        </CreateBindingDevice>
                                    </div>
                                    <div class="tab-bg-2" v-else-if="selectedCategory == 'Camera Binding'">
                                        <CreateBindingCamera :locale="$i18n.locale"
                                                             :propsFloorId="selectedFloorTarget['FLOOR_ID']"
                                                             :propsRoomId="selectedRoom['ROOM_ID']"
                                                             :propsGatewayId="selectedGateway['GATEWAY_ID']"
                                                             :propsDeviceId="selectedDevice['DEVICE_ID']"
                                                             :propsDeviceCondition="selectedDeviceCondition"
                                                             :propsSelectedRoomTarget="selectedRoomTarget">
                                        </CreateBindingCamera>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Binding Creation Form for Alert Binding-->
                        <div v-if="showCreateForm == true && selectedCategory == 'Alert Binding'" class="col-sm-8">
                            <div class="row">
                                <div v-if="showTargetInterface == 'floor'" class="col-md-12">
                                    <div class="tab-bg-2" v-if="selectedCategory == 'Alert Binding'">
                                        <CreateBindingAlert :locale="$i18n.locale"
                                                            :propsFloorId="selectedFloorTarget['FLOOR_ID']"
                                                            :propsRoomId="selectedRoom['ROOM_ID']"
                                                            :propsGatewayId="selectedGateway['GATEWAY_ID']"
                                                            :propsDeviceId="selectedDevice['DEVICE_ID']"
                                                            :propsDeviceCondition="selectedDeviceCondition"
                                                            :propsSelectedRoomTarget="selectedRoomTarget">
                                        </CreateBindingAlert>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 9/22/2020 Added Device Condition for Threshold -->
                <SourceDeviceConditionModal v-if="modal.showCustomConditionBindingModal==true"
                                            :device="currentCustomDevice" @saveDeviceCondition="saveDeviceCondition"
                                            @closeModal="modal.showCustomConditionBindingModal=false">
                </SourceDeviceConditionModal>
            </div>
            <!-- content-fluid-end -->
        </div>
        <!-- content-warpper-end -->
        <Footer></Footer>
    </div>

</template>
<script>
//import components
import BindingMapView from './BindingMapView/BindingMapView.vue'
import CreateBindingMap from './BindingMapView/CreateBindingMap.vue'
import DeviceBindingManagement from './DeviceBindingManagement.vue'
import CameraBindingManagement from './CameraBindingManagement.vue'
//import SensorBindingManagement from './SensorBindingManagement.vue';
import AlertBindingManagement from './AlertBindingManagement.vue'
import CreateBindingDevice from './CreateBindingDevice.vue'
import CreateBindingCamera from './CreateBindingCamera.vue'
//import CreateBindingSensor from './CreateBindingSensor.vue';
import CreateBindingAlert from './CreateBindingAlert.vue'
import SourceDeviceConditionModal from './Modal/SourceDeviceConditionModal.vue'
export default {
    //initialize component
    components: {
        DeviceBindingManagement,
        AlertBindingManagement,
        CreateBindingDevice,
        CreateBindingAlert,
        CreateBindingCamera,
        BindingMapView,
        CreateBindingMap,
        CameraBindingManagement,
        SourceDeviceConditionModal,
    },
    data() {
        return {
            isBinded: false,
            selectedCategory: 'Device Binding',
            floorList: [],
            roomList: [],
            roomTargetList: [],
            gatewayList: [],
            deviceList: [],
            deviceConditionList: [],

            selectedFloor: [],
            selectedRoom: [],
            selectedFloorTarget: [],
            selectedRoomTarget: [],
            selectedGateway: [],
            selectedDevice: [],
            selectedDeviceCondition: [],
            selectedSourceDevice: [],

            selectedBindingListFLoor: [],

            bindingImagePreview: '',

            listPagination: {
                floorCurrentPage: 1,
                roomCurrentPage: 1,
                roomTargetCurrentPage: 1,
                gatewayCurrentPage: 1,
                deviceCurrentPage: 1,
                paginationLimit: 4,
            },

            //Harvey 20200214
            //Custom Condition Binding
            modal: {
                showCustomConditionBindingModal: false,
            },
            showTargetInterface: 'room',
            showCreateForm: false,
            showGatewayList: false,
            showDeviceList: false,
            viewList: 'floors', // floors / rooms
            bindingMode: 'viewBindingList', //viewBindingList / addBinding
            user: '',
        }
    },
    created() {
        this.getFloors()
    },
    methods: {
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
            // 9/22/2020 Added for Threshold
            if (this.currentCustomDevice == 'temp_hum') {
                for (var i in customDeviceCondition) {
                    var temp = i + ' ' + customDeviceCondition[i]
                    this.deviceConditionList.push({ SOURCE_DEVICE_CONDITION: temp + ' °C' })
                }
            } else if (this.currentCustomDevice == 'dust_detector') {
                for (var i in customDeviceCondition) {
                    var temp = i + ' ' + customDeviceCondition[i]
                    this.deviceConditionList.push({ SOURCE_DEVICE_CONDITION: temp + ' ' })
                }
            } else if (this.currentCustomDevice == 'co2_detector') {
                for (var i in customDeviceCondition) {
                    var temp = i + ' ' + customDeviceCondition[i]
                    this.deviceConditionList.push({ SOURCE_DEVICE_CONDITION: temp + ' ppm' })
                }
            }
        },
        //Function Name: chooseListBindingFloor
        //Function Description: When Floor List is clicked in binding List
        //Param: floor (floor)
        chooseListBindingFloor(floor) {
            this.selectedRoom = []
            this.selectedBindingListFLoor = floor
            this.listPagination.floorCurrentPage = 1
            this.getRooms(floor)
            this.viewList = 'floors'
        },
        //Function Name: chooseFloor
        //Function Description: choose floor
        //Param: data (floor)
        chooseFloor(data) {
            this.selectedFloor = data
            this.getRooms(data)
            this.listPagination.roomCurrentPage = 1
        },
        //Function Name: clickAddBinding
        //Function Description: Display add binding
        clickAddBinding(data) {
            this.bindingMode = 'addBinding'
            this.viewList = 'floors'
        },
        //Function Name: chooseRoom
        //Function Description: choose room
        //Param: roomData (room)
        chooseRoom(roomData) {
            if (this.selectedFloor == '') {
                this.$toast.error('Please select a floor.', 'Error', { position: 'topCenter' })
            } else {
                this.selectedRoom = roomData
                this.listPagination.deviceCurrentPage = 1
                this.deviceConditionLis = []
                this.showGatewayList = false
                this.showDeviceList = true
                this.selectedGateway = ''
                this.selectedDevice = ''
                this.selectedDeviceCondition = ''
                this.showCreateForm = false
                this.showTargetInterface = 'room'
                this.getDevices()
            }
        },
        //Function Name: chooseRoomTarget
        //Function Description: Set targetted room
        //Param: roomData (room)
        chooseRoomTarget(roomData) {
            this.selectedRoomTarget = roomData
            this.showTargetInterface = 'devices'
        },
        //Function Name: chooseDevice
        //Function Description: set chosen device
        //Param: deviceData (device)
        chooseDevice(deviceData) {
            this.listPagination.roomTargetCurrentPage = 1
            this.selectedDevice = deviceData
            this.showCreateForm = true
            this.showTargetInterface = 'room'
            this.bindingImagePreview = ''
            this.getDeviceSourceCondition()
        },
        //Function Name: chooseDeviceCondition
        //Function Description: set chosen device condition
        //Param: deviceConditionData (device condition)
        chooseDeviceCondition(deviceConditionData) {
            this.selectedDeviceCondition = deviceConditionData
            this.bindingImagePreview = ''
        },
        //Function Name: getFloors
        //Function Description: get floors
        getFloors() {
            axios.post('getFloorAll').then(response => {
                if (response.data.length > 0) {
                    this.floorList = response.data
                    this.selectedFloor = response.data[0]
                    this.selectedBindingListFLoor = response.data[0]
                    this.getRooms(this.selectedBindingListFLoor)
                }
            })
        },
        //Function Name: getRooms
        //Function Description: get rooms
        //Param: data (floor)
        getRooms(data) {
            axios.post('getFloorRooms/' + data['FLOOR_ID']).then(response => {
                if (response.data.length > 0) {
                    this.roomList = response.data
                    this.selectedRoom = response.data[0]
                    this.viewList = 'rooms'
                }
            })
        },
        //Function Name: getRoomsTarget
        //Function Description: get rooms to select for target
        //Param: seletedFloorTarget
        getRoomsTarget() {
            axios.post('getFloorRooms/' + this.selectedFloorTarget['FLOOR_ID']).then(response => {
                if (response.data.length > 0) {
                    this.roomTargetList = response.data
                    this.showTargetInterface = 'room'
                }
            })
        },
        //Function name: getDevices
        //Function Description: get devices depending on category
        //Param: selectedCategory
        getDevices() {
            if (this.selectedCategory == 'Device Binding') {
                // 9/22/2020 Added "dust_detector" and "co2_detector"
                var sensors =
                    'light_detector,motion_detector,panic_button,temp_hum,dust_detector,co2_detector,gas_detector,smoke_detector'
                axios
                    .get('getRoomDevices/' + this.selectedRoom.ROOM_ID + '/?REG_FLAG=1&filter=DEVICE_TYPE:' + sensors)
                    .then(response => {
                        if (response.data.length > 0) {
                            this.deviceList = response.data
                        } else {
                            this.deviceList = []
                        }
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            } else if (this.selectedCategory == 'Alert Binding') {
                var sensors = 'co2_detector,gas_detector,smoke_detector,dust_detector,panic_button,temp_hum'
                axios
                    .get('getRoomDevices/' + this.selectedRoom.ROOM_ID + '/?REG_FLAG=1&filter=DEVICE_TYPE:' + sensors)
                    .then(response => {
                        if (response.data.length > 0) {
                            this.deviceList = response.data
                        } else {
                            this.deviceList = []
                        }
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            } else if (this.selectedCategory == 'Camera Binding') {
                let floor_id = this.selectedFloor.FLOOR_ID
                let room_id = this.selectedRoom.ROOM_ID
                let filter = '/?filter=FLOOR_ID:' + floor_id + '|ROOM_ID:' + room_id
                axios.get('getRegisteredCameras' + filter).then(response => {
                    if (response.data.length > 0) {
                        this.deviceList = response.data
                    } else {
                        this.deviceList = []
                    }
                })
            }
        },
        //Function Name: getDeviceSourceCondition
        //Function Description: get device condition
        //PARAM: SOURCE_DEVICE_ID
        getDeviceSourceCondition() {
            this.bindingLists = {}
            //Added 20201027
            if (this.selectedCategory == 'Device Binding' || this.selectedCategory == 'Alert Binding') {
                axios
                    .get('getDeviceBindingListCondition/' + this.selectedDevice.DEVICE_ID)
                    .then(response => {
                        if (response.data.length > 0) {
                            this.deviceConditionList = response.data[0].DATA
                            // 9/22/2020 Added "['SOURCE_DEVICE_CONDITION']"
                            this.selectedDeviceCondition = response.data[0].DATA[0]['SOURCE_DEVICE_CONDITION']
                        }
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            } else if (this.selectedCategory == 'Camera Binding') {
                //Add based on available condition in Camera Axis
                this.deviceConditionList = ['Motion Detection', 'Another Detection', 'videoMotionDetection']

                this.selectedDeviceCondition = this.deviceConditionList[0]
            }
            this.showTargetInterface = 'floor'
        },
        //Function Name: chooseTargetFloor
        //Function Description: set chosen target floor
        //Param: data (floor)
        chooseTargetFloor(data) {
            this.selectedFloorTarget = data
            this.getRoomsTarget()
        },
        //Function Name: mapViewSelect
        //Function Description: select room from
        mapViewSelect(data) {
            this.selectedRoom = data
        },
        //Fucntion Name: clickBreadCrumb
        //Function Description: for breadcrumbs function
        //Param: data(category, floor, room, gateway, device)
        clickBreadcrumb(data) {
            switch (data) {
                case 'category':
                    this.bindingMode = 'viewBindingList'
                    this.gatewayList = []
                    this.roomList = []
                    this.deviceList = []
                    this.deviceConditionList = []
                    this.selectedFloor = this.floorList[0]
                    this.selectedRoom = ''
                    this.selectedGateway = ''
                    this.selectedDevice = ''
                    this.selectedCategory = 'Device Binding'
                    this.showGatewayList = false
                    this.showDeviceList = false
                    this.showCreateForm = false
                    this.showTargetInterface = 'room'
                    this.viewList = 'floors'
                    this.$forceUpdate()
                    break

                case 'floor':
                    this.gatewayList = []
                    this.deviceList = []
                    this.deviceConditionList = []
                    this.selectedFloor = this.floorList[0]
                    this.selectedRoom = ''
                    this.selectedGateway = ''
                    this.selectedDevice = ''
                    this.showGatewayList = false
                    this.showDeviceList = false
                    this.showCreateForm = false
                    this.showTargetInterface = 'room'
                    this.viewList = 'floors'
                    this.$forceUpdate()
                    break

                case 'room':
                    this.gatewayList = []
                    this.deviceList = []
                    this.deviceConditionList = []
                    this.selectedRoom = ''
                    this.selectedGateway = ''
                    this.selectedDevice = ''
                    this.selectedRoomTarget = ''
                    this.showGatewayList = false
                    this.showDeviceList = false
                    this.showCreateForm = false
                    this.showTargetInterface = 'room'
                    this.viewList = 'rooms'
                    this.$forceUpdate()
                    break

                case 'gateway':
                    this.deviceList = []
                    this.deviceConditionList = []
                    this.selectedGateway = ''
                    this.selectedDevice = ''
                    this.showGatewayList = true
                    this.showDeviceList = false
                    this.showCreateForm = false
                    this.showTargetInterface = 'room'
                    this.$forceUpdate()
                    break

                case 'device':
                    break
            }
        },
        //function Name: changeSize
        //function Description: change size if isBinded = true
        //Param: data
        changeSize(data) {
            this.isBinded = data
        },
        //Function Name: tabShow
        //Function Description: change tab
        //Param: data
        tabShow(data) {
            var details = ''
            this.$bus.$emit('selectedDeviceBindingData', details)
            if (data == 'Device Binding') {
                this.$bus.$emit('changeBindingTab', data)

                // 10/20/2020 Deleted
                // }else if(data == 'Sensor Binding'){
                //     this.$bus.$emit('changeSensorBindingTab', data);
            } else {
                this.$bus.$emit('changeAlertBindingTab', data)
            }
            this.selectedCategory = data
        },
    },
    computed: {
        limitedFloors() {
            let from = this.listPagination.paginationLimit * this.listPagination.floorCurrentPage - 4
            let to = this.listPagination.paginationLimit * this.listPagination.floorCurrentPage
            return this.floorList.slice(from, to)
        },
        limitedRooms() {
            let from = this.listPagination.paginationLimit * this.listPagination.roomCurrentPage - 4
            let to = this.listPagination.paginationLimit * this.listPagination.roomCurrentPage
            return this.roomList.slice(from, to)
        },
        limitedRoomsTarget() {
            let from = this.listPagination.paginationLimit * this.listPagination.roomTargetCurrentPage - 4
            let to = this.listPagination.paginationLimit * this.listPagination.roomTargetCurrentPage
            return this.roomTargetList.slice(from, to)
        },
        limitedGateways() {
            let from = this.listPagination.paginationLimit * this.listPagination.gatewayCurrentPage - 4
            let to = this.listPagination.paginationLimit * this.listPagination.gatewayCurrentPage
            return this.gatewayList.slice(from, to)
        },
        limitedDevices() {
            let from = this.listPagination.paginationLimit * this.listPagination.deviceCurrentPage - 4
            let to = this.listPagination.paginationLimit * this.listPagination.deviceCurrentPage
            return this.deviceList.slice(from, to)
        },
    },
    mounted() {
        this.$i18n.locale = this.$parent.locale

        Echo.channel('test-binding').listen('testBindingEvent', value => {
            console.log(value.data)
        })
    },
}
</script>
