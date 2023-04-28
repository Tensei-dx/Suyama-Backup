<!--
    <System Name> iBMS
    <Program Name> DeviceDetails.vue
    <Created>            TP Harvey
    <Updated> 2019.06.25 TP Mark  Applying PG Implementation Matrix (Frontend)
              2019.07.11 TP Mark  Applying Horizontal Expansion
              2019.07.25 TP Jethro Added function to check for device name length before saving
              2019.08.05 TP Ivin    Add function to disable save
              2020.03.09 TP Uddin   Add device details for Bacnet Devices
              2020.03.10 TP Uddin   Fix column size whenever tab or category is changed
              2020.04.22 TP Uddin   Add alphanumeric characters only for DEVICE NAME
              2020.05.26 TP Uddin   Modify axios URL according to the URL list
              2020.09.21 TDN Okada  SPRINT_08 TASK148 Modify axios URL according to the URL list
-->
<template>
    <div v-if="show" class="col-sm-6 shrink h-100 px-0 bg-details">
        <!-- <div class="col-sm-12 mt-5"> -->
        <div class="h-100">
            <div class="py-3 text-center">
                <h1 class="font-weight-bold">{{$t('management.roomInfo.deviceControl')}}</h1>
            </div>
            <div class="hardware-right-pane h-75 pr-0 ">
                <div class="hardware-action-position height: 100% mt-1 pr-3">
                    <a v-if="deviceData.REG_FLAG == 1" class="pointer" @click="editDeviceDetails()">
                        <span>
                            <i aria-hidden="true" class="text-white fa fa-edit fa-2x"></i>
                        </span>
                    </a>
                    <a v-if="deviceData.REG_FLAG == 9" class="pointer" @click="activateModbus()">
                        <span>
                            <i aria-hidden="true" class="text-white fa fa-check"></i>
                        </span>
                    </a>
                    <a v-if="deviceData.REG_FLAG == 1" class="pointer" @click="deleteDevice()">
                        <span>
                            <i aria-hidden="true" class="text-white fa fa-trash-o fa-2x"></i>
                        </span>
                    </a>
                </div>
                <!-- <div class="box-inside-right-pane mt-5"> -->
                <!-- <div class="hardware-action-position">
                        <a v-if="deviceData.REG_FLAG == 1" class="pointer" @click="editDeviceDetails()">
                            <span>
                                <i aria-hidden="true" class="text-white fa fa-edit"></i>
                            </span>
                        </a>
                        <a v-if="deviceData.REG_FLAG == 9" class="pointer" @click="activateModbus()">
                            <span>
                                <i aria-hidden="true" class="text-white fa fa-check"></i>
                            </span>
                        </a>
                        <a v-if="deviceData.REG_FLAG == 1" class="pointer" @click="deleteDevice()">
                            <span>
                                <i aria-hidden="true" class="text-white fa fa-trash-o"></i>
                            </span>
                        </a>
                    </div> -->
                <!-- = SPRINT_07 TASK148 -->
                <!-- <img :src="sourceImage" class="hardware-img-right-pane mt-4" @error="imgUrlAlt"> -->
                <img :src="sourceImage" class="img-thumbnail mt-5 d-block" @error="imgUrlAlt">
                <!-- = SPRINT_07 TASK148 -->
                <!-- </div> -->
                <div class="hardware-right-pane-details pl-0">
                    <div class="d-flex text-white font-weight-bold h5">

                        <input v-if="deviceData.category == 'device'" type="text" v-model="deviceData.DEVICE_NAME"
                               :disabled="!editMode"
                               :class="editMode ? 'form-control col-sm-6 fade-in' : 'view col-sm-12 px-0'"
                               placeholder="Device Name">
                        <input v-else-if="deviceData.category == 'bacnet' || deviceData.category == 'camera'"
                               type="text" v-model="deviceData.DEVICE_NAME" maxlength="15" :disabled="!editMode"
                               :class="editMode ? 'form-control col-sm-6 fade-in' : 'view col-sm-12 px-0'"
                               class="alphanumericOnly" placeholder="Device Name"
                               onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-_ ]/g, '')">
                        <div v-else class="col-sm-2">{{ deviceData.SERIAL_NO }}</div>
                    </div>
                    <div class="mt-0 h6 line-height-1">
                        <span class="text-danger line-height-1" v-if="deviceData.category != 'modMeter'">
                            <span v-if="deviceData.DEVICE_NAME.length < 3 || deviceData.DEVICE_NAME.length > 20">
                                <!-- !Input should only contain 3 to 20 characters -->
                                {{$t('imageWarning.inputWarning')}}
                            </span>
                        </span>
                    </div>
                    <!-- MODBUS EDIT -->
                    <div v-if="deviceData.category == 'modMeter'" class="text-white line-height-1">
                        {{$t('device.gatewayName')}}: {{ deviceData.GATEWAY_IP }}</div>
                    <div v-if="deviceData.category == 'modMeter'" class="text-white line-height-1">
                        {{$t('device.modbusSerial')}}:
                        <span v-if="!editMode">{{ deviceData.SERIAL_NO }}</span>
                        <input v-if="editMode" v-model="deviceData.SERIAL_NO" type="text"
                               :class="editMode ? 'form-control col-sm-6 fade-in' : 'view col-sm-12 pl-0'">
                    </div>
                    <div v-if="deviceData.category == 'modMeter'" class="text-white line-height-1">
                        <span>{{$t('device.modbusSlave')}}:</span>
                        <span v-if="!editMode">{{ deviceData.SLAVE_ID }}</span>
                        <input v-if="editMode" v-model="deviceData.SLAVE_ID" type="text"
                               :class="editMode ? 'form-control col-sm-6 fade-in' : 'view col-sm-12 pl-0'">
                    </div>
                    <!-- DEVICE EDIT -->
                    <div v-if="!editMode" class="col-sm-12 pl-0">
                        <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                            {{$t('device.gatewayName')}}:
                            <span>{{ deviceData.GATEWAY_NAME }}</span>
                        </div>
                        <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                            {{$t('device.devType')}}:
                            <span>{{ deviceData.DEVICE_TYPE }}</span>
                        </div>
                        <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                            {{$t('device.devSerial')}}:
                            <span>{{ deviceData.DEVICE_SERIAL_NO }}</span>
                        </div>
                        <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                            {{$t('device.devCat')}}:
                            <span v-for="category in devCategory">
                                <span v-if="deviceData.DEVICE_CATEGORY == category.ID">{{ category.NAME }}</span>
                            </span>
                        </div>
                        <!-- BACNET -->
                        <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                            {{$t('device.gatewayName')}}:
                            <span>{{ deviceData.gateway.GATEWAY_NAME }}</span>
                        </div>
                        <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                            {{$t('device.devSerial')}}:
                            <span>{{ deviceData.DEVICE_SERIAL_NO }}</span>
                        </div>
                        <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                            {{$t('device.devCat')}}:
                            <span v-for="category in devCategory">
                                <span v-if="deviceData.DEVICE_CATEGORY == category.ID">{{ category.NAME }}</span>
                            </span>
                        </div>
                        <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                            {{$t('device.devType')}}:
                            <span>{{ deviceData.DEVICE_TYPE }}</span>
                        </div>
                        <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                            Objects Selected:
                            <ul>
                                <li v-for="object in selectedObjects">{{ object.DESCRIPTION }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Edit Mode: True -->
                    <div v-else>
                        <div v-if="deviceType" class="row col-sm-12 px-0 mx-0">
                            <div class="col-sm-6 pl-0 fade-in">
                                <div v-if="deviceData.category == 'device'" v-for="gangs,key in deviceData.DATA"
                                     class="text-white line-height-1">
                                    {{$t('device.device')}} {{ key + 1 }} {{$t('device.gang')}}:
                                    <input v-model="deviceData.DATA[key].device_name" type="text"
                                           class="form-control col-sm-12">
                                    <small v-if="errors['DATA.[key].device_name']"
                                           class="text-danger">{{ error_text }}</small>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devCat')}}:
                                    <div>
                                        <select v-if="editMode" v-model="deviceData.DEVICE_CATEGORY"
                                                class="custom-select">
                                            <option v-for="category in devCategory"
                                                    :selected="deviceData.DEVICE_CATEGORY == category.ID"
                                                    :value="category.ID">
                                                {{ category.NAME }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 pr-0 fade-in">
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.gatewayName')}}:
                                    <input v-model="deviceData.GATEWAY_NAME" type="text" class="form-control col-sm-12"
                                           readonly>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devType')}}:
                                    <input v-model="deviceData.DEVICE_TYPE" type="text" class="form-control col-sm-12"
                                           readonly>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devSerial')}}:
                                    <input v-model="deviceData.DEVICE_SERIAL_NO" type="text"
                                           class="form-control col-sm-12" readonly>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="col-sm-12 px-0 fade-in">
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.gatewayName')}}:
                                    <input v-model="deviceData.GATEWAY_NAME" type="text" class="form-control col-sm-12"
                                           readonly>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devType')}}:
                                    <input v-model="deviceData.DEVICE_TYPE" type="text" class="form-control col-sm-12"
                                           readonly>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devSerial')}}:
                                    <input v-model="deviceData.DEVICE_SERIAL_NO" type="text"
                                           class="form-control col-sm-12" readonly>
                                </div>
                                <div v-if="deviceData.category == 'device'" class="text-white line-height-1">
                                    {{$t('device.devCat')}}:
                                    <div>
                                        <select v-if="editMode" v-model="deviceData.DEVICE_CATEGORY"
                                                class="custom-select">
                                            <option v-for="category in devCategory"
                                                    :selected="deviceData.DEVICE_CATEGORY == category.ID"
                                                    :value="category.ID">
                                                {{ category.NAME }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="col-sm-6 pl-0 fade-in">
                                    <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1 pl-0">
                                        {{$t('device.gatewayName')}}:
                                        <input v-model="deviceData.gateway.GATEWAY_NAME" type="text"
                                               class="form-control col-sm-12" readonly>
                                    </div>
                                    <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1 pl-0">
                                        {{$t('device.devSerial')}}:
                                        <input v-model="deviceData.DEVICE_SERIAL_NO" type="text"
                                               class="form-control col-sm-12" readonly>
                                    </div>
                                    <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                                        {{$t('device.devCat')}}:
                                        <select v-if="editMode" v-model="deviceData.DEVICE_CATEGORY"
                                                class="custom-select col-sm-12">
                                            <option v-for="category in devCategory"
                                                    :selected="deviceData.DEVICE_CATEGORY == category.ID"
                                                    :value="category.ID">
                                                {{ category.NAME }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 pr-0 fade-in">
                                    <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                                        {{$t('device.devType')}}
                                        <input v-model="deviceData.DEVICE_TYPE" type="text"
                                               class="form-control col-sm-12" readonly>
                                    </div>
                                    <div v-if="deviceData.category == 'bacnet'" class="text-white line-height-1">
                                        Objects:
                                        <ul style="{list-style:inside; padding-left:2em;}">
                                            <li v-for="object in selectedObjects">{{ object.DESCRIPTION }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="deviceData.category == 'device' || deviceData.category == 'bacnet'"
                         class="text-white line-height-1">{{$t('device.onStatus')}}:
                        <span v-if="deviceData.ONLINE_FLAG == 1"> {{$t('online')}} </span>
                        <span v-else>{{$t('offline')}}</span>
                    </div>
                    <!-- CAMERA DETAILS -->
                    <div v-if="deviceData.category == 'camera'">
                        <div class="text-white line-height-1">
                            Gateway Name:
                            <span v-if="!editMode">{{ deviceData.GATEWAY_NAME }}</span>
                            <input v-if="editMode" v-model="deviceData.GATEWAY_NAME" type="text"
                                   class="form-control col-sm-12 fade-in" readonly>
                        </div>
                        <div class="text-white line-height-1">
                            Device Type:
                            <span v-if="!editMode">{{ deviceData.DEVICE_TYPE }}</span>
                            <input v-if="editMode" v-model="deviceData.DEVICE_TYPE" type="text"
                                   class="form-control col-sm-12 fade-in" readonly>
                        </div>
                        <div class="text-white line-height-1">
                            Device Serial No:
                            <span v-if="!editMode">{{ deviceData.DEVICE_SERIAL_NO }}</span>
                            <input v-if="editMode" v-model="deviceData.DEVICE_SERIAL_NO" type="text"
                                   class="form-control col-sm-12 fade-in" readonly>
                        </div>
                        <div class="text-white line-height-1">
                            Device Category:
                            <span v-if="!editMode" v-for="category in devCategory">
                                <span v-model="deviceData.DEVICE_CATEGORY"
                                      v-if="deviceData.DEVICE_CATEGORY == category.ID">
                                    {{ category.NAME }}
                                </span>
                            </span>
                            <select v-if="editMode" v-model="deviceData.DEVICE_CATEGORY" class="custom-select">
                                <option v-for="category in devCategory"
                                        :selected="deviceData.DEVICE_CATEGORY == category.ID" :value="category.ID">
                                    {{ category.NAME }}
                                </option>
                            </select>
                        </div>
                        <div class="text-white line-height-1">
                            {{$t('device.onStatus')}}:
                            <span v-if="deviceData.ONLINE_FLAG == 1"> {{$t('online')}} </span>
                            <span v-else>{{$t('offline')}}</span>
                        </div>
                    </div>
                    <div v-if="editMode" class="fade-in" :class="detailsClass">
                        <button @click="saveDeviceDetails()" class="btn btn-primary"
                                :disabled="disableSave">{{$t('user.save')}}</button>
                        <button @click="cancelEdit()" class="btn"
                                style="background-color: #aaa; border-color: #aaa; color: #fff">{{$t('user.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </div>
    </div>
</template>
<script>
export default {
    created() {
        this.$bus.$on('selectedDeviceData', data => {
            this.showPanel(data)
            this.getDeviceObjects()
        })
    },
    data() {
        return {
            deviceData: {},
            devCategory: [
                { ID: 0, NAME: 'Device' },
                { ID: 1, NAME: 'Sensor' },
            ],
            selectedObjects: [],
            editMode: false,
            show: false,
            errors: {},
            cachedData: {},
        }
    },
    methods: {
        //Function Name: showPanel
        //Function Description: show selected device details
        //Param: data (device)
        showPanel(data) {
            if (data.REG_FLAG == 1 || data.METER_ID) {
                this.show = true
                this.deviceData = Object.assign({}, data)
                this.cachedData = Object.assign({}, this.deviceData)
            } else {
                this.show = false
            }
        },
        // Function Name: getDeviceObjects
        // Function Description: retrieve objects of selected device type
        getDeviceObjects() {
            axios
                .get('getDeviceObjects/' + this.deviceData.BACNETDEVICE_ID)
                .then(response => {
                    this.selectedObjects = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        //Function Name: activateModbus
        //Function Description: Activate modbus meter
        activateModbus() {
            let message = this.$t('device.deviceModals')
            this.$swal({
                title: message.activateModbus,
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
                            .post('undeleteMeter', {
                                METER_ID: this.deviceData.METER_ID,
                            })
                            .then(response => {
                                if (response.data == 'success') {
                                    this.$swal({
                                        title: message.activatedModbus,
                                        text: message.modbusActivated,
                                        type: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    })
                                } else {
                                    this.$swal(message.error, message.cantActivateModbus, 'error')
                                }
                                this.$bus.$emit('getDeviceData', response.data)
                                this.show = false
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
        //Function Name: editDeviceDetails
        //Function Description: enable editting of device
        editDeviceDetails() {
            this.cachedData = Object.assign({}, this.deviceData)
            // this.cachedDataArray = Object.assign({}, this.deviceData.DATA);
            this.editMode = true
        },
        //Function Name: deleteDevice
        //Function Description: opens modal to delete device
        deleteDevice() {
            this.openModal(0)
        },
        //Function Name: saveDeviceDetails
        //Function Description: updates device details
        saveDeviceDetails() {
            let errorMessage = this.$t('error_message_code')
            if (this.deviceData.category == 'device') {
                if (this.deviceData.DEVICE_NAME.length > 25) {
                    this.$toast.error('Error', 'Device Name should be less than 25 characters', {
                        position: 'topCenter',
                    })
                } else {
                    axios({
                        url: 'updateDevice',
                        method: 'post',
                        data: {
                            FLOOR_ID: this.deviceData.FLOOR_ID,
                            ROOM_ID: this.deviceData.ROOM_ID,
                            GATEWAY_ID: this.deviceData.GATEWAY_ID,
                            DEVICE_ID: this.deviceData.DEVICE_ID,
                            DEVICE_NAME: this.deviceData.DEVICE_NAME,
                            DEVICE_CATEGORY: this.deviceData.DEVICE_CATEGORY,
                            DEVICE_DATA: this.deviceData.DATA,
                            DEVICE_MAP_NAME: this.deviceData.DEVICE_MAP_NAME,
                        },
                    })
                        .then(response => {
                            this.$bus.$emit('getDeviceData', response.data)
                            if (response.data == 'success') {
                                let message = this.$t('device.deviceModals')
                                this.$swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Device ' + message.successfullyUpdated,
                                    showConfirmButton: false,
                                    timer: 1200,
                                })
                                this.editMode = false
                            } else {
                                let title = this.$t('modalText.error')
                                this.$swal(title, errorMessage.ERR_OPS_047, 'error')
                            }
                            this.editMode = false
                        })
                        .catch(error => {
                            let errormessage = error.response.data.file + ' : ' + error.response.data.message
                            axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                            var title = this.$t('modalText.error')
                            var message = this.$t('logs.error')
                            console.log('2')
                            this.$swal(title, message, 'error')
                        })
                }
            } else if (this.deviceData.category == 'bacnet') {
                if (this.deviceData.DEVICE_NAME.length < 3 || this.deviceData.DEVICE_NAME.length > 15) {
                    this.$toast.error('Error', 'Device Name should only contain 3 to 15 characters', {
                        position: 'topCenter',
                    })
                } else {
                    axios({
                        method: 'post',
                        url: 'updateBacnetDevice',
                        data: {
                            BACNETDEVICE_ID: this.deviceData.BACNETDEVICE_ID,
                            FLOOR_ID: this.deviceData.floor.FLOOR_ID,
                            ROOM_ID: this.deviceData.room.ROOM_ID,
                            GATEWAY_ID: this.deviceData.gateway.GATEWAY_ID,
                            DEVICE_TYPE: this.deviceData.DEVICE_TYPE,
                            DEVICE_ID: this.deviceData.DEVICE_ID,
                            DEVICE_SERIAL_NO: this.deviceData.DEVICE_SERIAL_NO,
                            DEVICE_CATEGORY: this.deviceData.DEVICE_CATEGORY,
                            DEVICE_NAME: this.deviceData.DEVICE_NAME,
                        },
                    })
                        .then(response => {
                            this.$bus.$emit('getDeviceData', response.data)
                            if (response.data == 'success') {
                                let message = this.$t('device.deviceModals')
                                this.$swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Device ' + message.successfullyUpdated,
                                    showConfirmButton: false,
                                    timer: 1200,
                                })
                            } else if (response.data == 'duplication') {
                                let title = this.$t('modalText.error')
                                this.$swal(title, 'Device Name already used', 'error')
                                this.deviceData = Object.assign({}, this.cachedData)
                            } else {
                                let title = this.$t('modalText.error')
                                this.$swal(title, errorMessage.ERR_OPS_047, 'error')
                                this.deviceData = Object.assign({}, this.cachedData)
                            }
                            this.editMode = false
                        })
                        .catch(error => {
                            let errormessage = error.response.data.file + ' : ' + error.response.data.message
                            axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                            var title = this.$t('modalText.error')
                            var message = this.$t('logs.error')
                            console.log('3')
                            this.$swal(title, message, 'error')
                        })
                }
            } else if (this.deviceData.category == 'camera') {
                if (this.deviceData.DEVICE_NAME.length < 3 || this.deviceData.DEVICE_NAME.length > 15) {
                    this.$toast.error('Error', 'Device Name should only contain 3 to 15 characters', {
                        position: 'topCenter',
                    })
                } else {
                    axios({
                        url: 'updateCamera',
                        method: 'POST',
                        data: {
                            DEVICE_ID: this.deviceData.DEVICE_ID,
                            DEVICE_NAME: this.deviceData.DEVICE_NAME,
                            DEVICE_CATEGORY: this.deviceData.DEVICE_CATEGORY,
                        },
                    })
                        .then(response => {
                            this.$bus.$emit('getDeviceData', response.data)
                            if (response.data == 'success') {
                                let message = this.$t('device.deviceModals')
                                this.$swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Device ' + message.successfullyUpdated,
                                    showConfirmButton: false,
                                    timer: 1200,
                                })
                            } else if (response.data == 'name exists') {
                                let message = 'Device Name already exists'
                                let title = this.$t('modalText.error')
                                this.deviceData.DEVICE_NAME = this.cachedData.DEVICE_NAME
                                this.deviceData.DEVICE_CATEGORY = this.cachedData.DEVICE_CATEGORY
                                this.$swal(title, message, 'error')
                            } else if (response.data == 'name invalid') {
                                let message = 'Device Name is invalid'
                                let title = this.$t('modalText.error')
                                this.deviceData.DEVICE_NAME = this.cachedData.DEVICE_NAME
                                this.deviceData.DEVICE_CATEGORY = this.cachedData.DEVICE_CATEGORY
                                this.$swal(title, message, 'error')
                            } else {
                                let title = this.$t('modalText.error')
                                this.$swal(title, errorMessage.ERR_OPS_047, 'error')
                            }
                            this.editMode = false
                        })
                        .catch(error => {
                            let errormessage = error.message
                            axios.post('createSystemLogs', {
                                ERROR_MESSAGE: error.message,
                            })
                            var title = this.$t('modalText.error')
                            var message = this.$t('logs.error')
                            console.log('4')
                            this.$swal(title, message, 'error')
                        })
                }
            } else {
                axios({
                    url: 'updateMeter',
                    method: 'post',
                    data: {
                        METER_ID: this.deviceData.METER_ID,
                        SERIAL_NO: this.deviceData.SERIAL_NO,
                        SLAVE_ID: this.deviceData.SLAVE_ID,
                    },
                })
                    .then(response => {
                        this.$bus.$emit('getDeviceData', response.data)
                        if (response.data == 'success') {
                            let message = this.$t('device.deviceModals')
                            this.$swal({
                                position: 'center',
                                type: 'success',
                                title: 'Modbus Meter ' + message.successfullyUpdated,
                                showConfirmButton: false,
                                timer: 1200,
                            })
                        } else {
                            let title = this.$t('modalText.error')
                            let message = this.$t('device.deviceModals.cantUpdate')
                            this.$swal(title, message, 'error')
                        }
                        this.editMode = false
                    })
                    .catch(error => {
                        let errormessage = error.response.data.file + ' : ' + error.response.data.message
                        axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                        var title = this.$t('modalText.error')
                        var message = this.$t('logs.error')
                        console.log('5')
                        this.$swal(title, message, 'error')
                    })
            }
        },
        //Function Name: cancelEdit
        //Function Description: cancel edit mode
        cancelEdit() {
            this.deviceData = Object.assign({}, this.cachedData)
            this.editMode = false
        },
        //Function Name: catchErrors
        //Funciton Description: store errors
        //Param: error
        catchErrors(error) {
            this.errors = error.response.data.errors
        },
        //Function Name: openModal
        //Function Description: opens specific modals and alerts
        //Param: data
        openModal(data) {
            var cat
            var url
            var id
            var irUrl
            let message = this.$t('device.deviceModals')
            let errorMessage = this.$t('error_message_code')
            if (this.deviceData.category == 'device') {
                cat = 'Device '
                url = 'deleteDevice'
                id = this.deviceData.DEVICE_ID
                irUrl = 'deleteAllRecord'
            } else if (this.deviceData.category == 'bacnet') {
                cat = 'BACnet Device '
                url = 'deleteBacnetDevice'
                id = this.deviceData.BACNETDEVICE_ID
            } else if (this.deviceData.category == 'modMeter') {
                cat = 'Modbus Meter '
                url = 'deleteMeter'
                id = this.deviceData.METER_ID
            } else if (this.deviceData.category == 'camera') {
                cat = 'Camera'
                url = 'deleteCamera'
                id = this.deviceData.DEVICE_ID
            }
            if (data == 0) {
                this.$swal({
                    title: cat + message.delete,
                    text: message.sure,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#aaa',
                    cancelButtonText: this.$t('user.cancel'),
                    confirmButtonText: this.$t('device.deviceModals.yes'),
                }).then(result => {
                    if (result.value) {
                        axios({
                            url: url,
                            method: 'POST',
                            data: {
                                DEVICE_ID: id,
                            },
                        }).then(response => {
                            if (response.data == 'success') {
                                if (this.deviceData.DEVICE_TYPE == 'ir_remote') {
                                    axios
                                        .post(irUrl, {
                                            DEVICE_ID: id,
                                        })
                                        .then(response => {
                                            console.log(response.data)
                                        })
                                }
                                this.$swal({
                                    title: message.yes,
                                    text: cat + message.hasDeleted,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                this.$swal(message.error, cat + errorMessage.ERR_OPS_047, 'error')
                            }
                            this.$bus.$emit('getDeviceData', response.data)
                            this.show = false
                        })
                    }
                })
            }
        },
        //Function Name: imgUrAlt
        //Function Description: change image target to not found on error
        //Param: event (404)
        imgUrlAlt(event) {
            event.target.src = 'img/image_not_found.png'
        },
    },
    // Function Name: beforeDestroy
    // Function description: stop listening to event
    beforeDestroy() {
        this.$bus.$off('selectedDeviceData')
    },
    watch: {
        deviceData: function () {
            this.editMode = false
        },
    },
    computed: {
        //returns class name according to device type
        detailsClass() {
            if (this.deviceData.DEVICE_TYPE) {
                if (
                    this.deviceData.DEVICE_TYPE.includes('switch') &&
                    !this.deviceData.DEVICE_TYPE.includes('switch_3')
                ) {
                    return 'py-3'
                } else {
                    return ''
                }
            } else {
                return ''
            }
        },
        //returns true to disable save button if parameters are missing
        disableSave() {
            if (this.deviceData.category == 'modMeter') {
                if (
                    this.deviceData.SERIAL_NO == this.cachedData.SERIAL_NO &&
                    this.deviceData.SLAVE_ID == this.cachedData.SLAVE_ID
                ) {
                    return true
                }
            } else if (this.deviceData.category == 'device') {
                if (
                    this.deviceData.DEVICE_NAME == this.cachedData.DEVICE_NAME &&
                    this.deviceData.DEVICE_CATEGORY == this.cachedData.DEVICE_CATEGORY
                ) {
                    return true
                } else {
                    return false
                }
            } else if (this.deviceData.category == 'bacnet') {
                if (this.deviceData.DEVICE_NAME == '' || this.deviceData.DEVICE_NAME == null) {
                    return true
                } else {
                    return false
                }
            }
        },
        //returns true if device type is a switch
        deviceType() {
            if (
                this.deviceData.DEVICE_TYPE == 'embedded_switch_1' ||
                this.deviceData.DEVICE_TYPE == 'embedded_switch_2' ||
                this.deviceData.DEVICE_TYPE == 'embedded_switch_3' ||
                this.deviceData.DEVICE_TYPE == 'wall_switch_1' ||
                this.deviceData.DEVICE_TYPE == 'wall_switch_2' ||
                this.deviceData.DEVICE_TYPE == 'wall_switch_3'
            ) {
                return true
            } else {
                return false
            }
        },
        //changes image source according to device type
        sourceImage() {
            var image_source = ''
            if (this.deviceData.category == 'modMeter') {
                image_source = 'img/device/emeter.png'
            } else if (this.deviceData.category == 'bacnet') {
                if (this.deviceData.DEVICE_TYPE == 'Temp & Humid') {
                    image_source = 'img/bacnet/temp_hum.png'
                }
            } else {
                if (this.deviceData.DEVICE_TYPE == 'smoke_detector') {
                    image_source = 'img/device/smoke_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'gas_detector') {
                    image_source = 'img/device/gas_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'dust_detector') {
                    image_source = 'img/device/dust_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'co2_detector') {
                    image_source = 'img/device/co2_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'light_detector') {
                    image_source = 'img/device/light_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'motion_detector') {
                    image_source = 'img/device/motion_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'temp_hum') {
                    image_source = 'img/device/temp_hum.png'
                } else if (this.deviceData.DEVICE_TYPE == 'h2o_detector') {
                    image_source = 'img/device/h2o_detector.png'
                } else if (this.deviceData.DEVICE_TYPE == 'wall_switch_1') {
                    image_source = 'img/device/wall_switch_1.png'
                } else if (this.deviceData.DEVICE_TYPE == 'wall_switch_2') {
                    image_source = 'img/device/wall_switch_2.png'
                } else if (this.deviceData.DEVICE_TYPE == 'wall_switch_3') {
                    image_source = 'img/device/wall_switch_3.png'
                } else if (this.deviceData.DEVICE_TYPE == 'embedded_switch_1') {
                    image_source = 'img/device/embedded_switch_1.png'
                } else if (this.deviceData.DEVICE_TYPE == 'embedded_switch_2') {
                    image_source = 'img/device/embedded_switch_2.png'
                } else if (this.deviceData.DEVICE_TYPE == 'embedded_switch_3') {
                    image_source = 'img/device/embedded_switch_3.png'
                } else if (this.deviceData.DEVICE_TYPE == 'rgb_light') {
                    image_source = 'img/device/rgb_light.png'
                } else if (this.deviceData.DEVICE_TYPE == 'temp_light') {
                    image_source = 'img/device/temp_light.png'
                } else if (this.deviceData.DEVICE_TYPE == 'door_lock') {
                    image_source = 'img/device/door_lock.png'
                } else if (this.deviceData.DEVICE_TYPE == 'curtain_1') {
                    image_source = 'img/device/curtain.png'
                } else if (this.deviceData.DEVICE_TYPE == 'gas_valve') {
                    image_source = 'img/device/gas_valve.png'
                } else if (this.deviceData.DEVICE_TYPE == 'water_valve') {
                    image_source = 'img/device/water_valve.png'
                } else if (this.deviceData.DEVICE_TYPE == 'panic_button') {
                    image_source = 'img/device/panic_button.png'
                } else if (this.deviceData.DEVICE_TYPE == 'ir_remote') {
                    image_source = 'img/device/ir_transmitter.png'
                } else if (this.deviceData.DEVICE_TYPE == 'camera') {
                    image_source = 'img/device/camera.png'
                } else if (this.deviceData.DEVICE_TYPE == 'window_door_sensor') {
                    image_source = 'img/device/window_door_sensor.png'
                } else if (this.deviceData.DEVICE_TYPE == 'emergency_button') {
                    image_source = 'img/device/emergency_sensor.png'
                } else if (this.deviceData.DEVICE_TYPE == 'occupancy_temp_light') {
                    image_source = 'img/device/occupancy_temp_light.png'
                } else if (this.deviceData.DEVICE_TYPE == 'co2_temp_humid') {
                    image_source = 'img/device/c02_temp_hum.png'
                } else {
                    image_source = 'img/image_not_found.png'
                }
            }
            return image_source
        },
    },
}
</script>
<style scoped>
.hardware-img-right-pane {
    z-index: 2;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    width: 50%;
    display: flex;
}
.img-thumbnail {
    display: block;
    width: 50%;
    margin-left: auto;
    margin-right: auto;
}
.hardware-action-position {
    position: absolute;
    right: 5px;
    z-index: 5;
}
.bg-details {
    background-color: #263033;
    max-height: 665px !important;
}
</style>
