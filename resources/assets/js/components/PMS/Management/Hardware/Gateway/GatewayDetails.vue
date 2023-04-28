<!--
    <System Name> iBMS
    <Program Name> GatewayDetails.vue

    <Created>            TP Harvey
    <Updated> 2019.07.02 TP Mark  Applying PG Implementation Matrix (Frontend)
              2019.07.11 TP Mark  Applying Horizontal Expansion
              2020.05.26 TP Uddin Modify axios URL according to the URL list
              2021.09.16 TP Chris  Modify layout for Hotel
-->
<template>
    <div v-if="show" class="col-sm-6 h-100 px-0 bg-details">
        <!-- <div class="col-sm-12 mt-5"> -->
        <div class="h-100">
            <div class="py-3 text-center">
                <h1 class="font-weight-bold">{{$t('gateway.gatewayInfo')}}</h1>
            </div>
            <div class="hardware-right-pane h-75 text-white pl-0">
                <!-- <div class="box-inside-right-pane"> -->
                <div class="hardware-action-position height: 100% mt-1 pr-3">
                    <a v-if="gatewayData.REG_FLAG == 1" class="pointer" @click="edit()">
                        <span>
                            <i aria-hidden="true" class="text-white float-left fa fa-edit fa-2x"></i>
                        </span>
                    </a>
                    <a v-if="gatewayData.REG_FLAG == 9" class="pointer" @click="active()">
                        <span>
                            <i aria-hidden="true" class="text-white fa fa-check"></i>
                        </span>
                    </a>
                    <a v-if="gatewayData.REG_FLAG == 1" class="pointer" @click="del(gatewayData.MANUFACTURER_ID)">
                        <span>
                            <i aria-hidden="true" class="text-white float-left fa fa-trash-o fa-2x"></i>
                        </span>
                    </a>
                </div>
                <!-- = SPRINT_07 TASK147 -->
                <!-- <img :src="sourceImage" class="hardware-img-right-pane mt-2" @error="imgUrlAlt"> -->

                <!-- = SPRINT_07 TASK147 -->
                <!-- </div> -->
                <img :src="sourceImage" class="img-thumbnail mt-5 d-block" @error="imgUrlAlt">
                <div class="hardware-right-pane-details pl-0">
                    <div class="d-flex text-white font-weight-bold h6">
                        <input type="text" v-model="gatewayData.GATEWAY_NAME" :disabled="!editMode"
                               :class="editMode ? 'form-control col-sm-6 fade-in' : 'view col-sm-12 px-0'"
                               placeholder="Gateway Name">
                        <div><small v-if="errors.GATEWAY_NAME" class="text-danger">{{ errors.GATEWAY_NAME[0] }}</small>
                        </div>
                        <div class="divider-line"></div>
                    </div>
                    <div class="text-white line-height-1">{{$t('gateway.gatewayIp')}}: {{ gatewayData.GATEWAY_IP }}
                    </div>
                    <div class="text-white line-height-1">{{$t('gateway.devSerialNo')}}:
                        {{ gatewayData.GATEWAY_SERIAL_NO }}
                    </div>
                    <div class="text-white line-height-1">{{$t('gateway.onlineStat')}}:
                        <span v-if="gatewayData.ONLINE_FLAG == 1"> {{$t('online')}} </span>
                        <span v-else>{{$t('offline')}}</span>
                    </div>
                    <div v-if="editMode" class="py-3 fade-in">
                        <button @click="save()" class="btn btn-primary" id="mark"
                                :disabled="disableSave">{{$t('user.save')}}</button>
                        <button @click="cancelEdit()" class="btn"
                                style="background-color: #aaa; border-color: #aaa; color: #fff">{{$t('user.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </div>
</template>
<script>
export default {
    created() {
        //watch for selectedData emit
        this.$bus.$on('selectedData', data => {
            ;(this.errors = {}), this.showPanel(data)
        })
    },
    data() {
        return {
            gatewayData: {},
            cachedData: {},
            editMode: false,
            show: false,
            errors: {},
        }
    },
    methods: {
        //Function Name: errs
        //Function Description: stores error data
        //Param: error
        errs(error) {
            this.errors = error.response.data.errors
        },
        //Function Name: showPanel
        //Function Description: show gateway details
        //Param: data (gateway)
        showPanel(data) {
            if (data.REG_FLAG == 1 || data.MANUFACTURER_ID == 2) {
                this.show = true
                this.gatewayData = Object.assign({}, data)
                this.cachedData = Object.assign({}, this.gatewayData)
            } else {
                this.show = false
            }
        },
        //Function Name: active
        //Function Description: activate modbus emeter
        active() {
            let message = this.$t('gateway.modalText')
            let errorMessage = this.$t('error_message_code')
            this.$swal({
                title: message.activeModbus,
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
                            .post('undeleteGateway', {
                                GATEWAY_ID: this.gatewayData.GATEWAY_ID,
                            })
                            .then(response => {
                                if (response.data == 'success') {
                                    this.$swal({
                                        title: message.activatedModbus,
                                        text: message.modbusActivate,
                                        type: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    })
                                } else {
                                    let title = this.$t('modalText.error')
                                    let message = this.$t('gateway.modalText.modbusCantActivate')
                                    this.$swal(title, errorMessage.ERR_OPS_018, 'error')
                                    this.editMode = false
                                }
                                this.$bus.$emit('getData', response.data)
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
        //Function Name: edit
        //Function Description: enable editting of gateway
        edit() {
            this.cachedData = Object.assign({}, this.gatewayData)
            this.editMode = true
        },
        //Function Name: del
        //Function Description: opens modal to delete gateway
        del(data) {
            this.openModal(data)
        },
        //Function Name: save
        //Function Description: updates gateway details
        save() {
            let errorMessage = this.$t('error_message_code')
            let cat = this.gatewayData.MANUFACTURER_ID
            if (cat == 2) {
                cat = 'modBusGateway'
            } else if (cat == 1 || cat == 6) {
                cat = 'gateway'
            } else if (cat == 4) {
                cat = 'cameraGateway'
            }
            axios
                .post('updateGateway', {
                    KEY: cat,
                    FLOOR_ID: this.gatewayData.FLOOR_ID,
                    ROOM_ID: this.gatewayData.ROOM_ID,
                    GATEWAY_ID: this.gatewayData.GATEWAY_ID,
                    GATEWAY_NAME: this.gatewayData.GATEWAY_NAME,
                })
                .then(response => {
                    this.$bus.$emit('getData', response.data)
                    if (response.data == 'success') {
                        let message = this.$t('gateway.modalText')
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: message.gatewayUpdate,
                            showConfirmButton: false,
                            timer: 1500,
                        })
                        Object.assign(this.cachedData, this.gatewayData)
                        this.editMode = false
                    } else if (response.data == 'exists') {
                        let title = this.$t('modalText.error')
                        this.$swal(title, errorMessage.ERR_OPS_008, 'error')
                    } else {
                        let title = this.$t('modalText.error')
                        this.$swal(title, errorMessage.ERR_OPS_009, 'error')
                        Object.assign(this.gatewayData, this.cachedData)
                        this.editMode = false
                    }
                })
                .catch(error => {
                    let errormessage = error.response.data.file + ' : ' + error.response.data.message
                    axios.post('createSystemLogs', {
                        ERROR_MESSAGE: errormessage,
                    })
                    let title = this.$t('modalText.error')
                    let message = this.$t('logs.error')
                    this.$swal(title, message, 'error')
                })
        },
        //Function Name: cancelEdit
        //Function Description: cancel edit mode
        cancelEdit() {
            this.gatewayData = Object.assign({}, this.cachedData)
            this.editMode = false
            var details = ''
            this.errors = {}
        },
        //Function Name: openModal
        //Function Description: opens specific modals and alerts
        //Param: data
        openModal(cat) {
            let message = this.$t('gateway.modalText')
            let errorMessage = this.$t('error_message_code')
            if (cat == 1 || cat == 6) {
                cat = 'gateway'
            } else if (cat == 2) {
                cat = 'modBusGateway'
            } else if (cat == 4) {
                cat = 'cameraGateway'
            }
            this.editMode = false
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
                            KEY: cat,
                            GATEWAY_ID: this.gatewayData.GATEWAY_ID,
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
                                }).then(result => {
                                    if (result.value) {
                                        axios
                                            .post('deleteGateway', {
                                                FORCE: true,
                                                KEY: cat,
                                                GATEWAY_ID: this.gatewayData.GATEWAY_ID,
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
                                                    let title = this.$t('modalText.error')
                                                    this.$swal(title, errorMessage.ERR_OPS_011, 'error')
                                                }
                                                this.$bus.$emit('getData', response.data)
                                                this.show = false
                                            })
                                    }
                                })
                            } else {
                                let title = this.$t('modalText.error')
                                this.$swal(title, errorMessage.ERR_OPS_011, 'error')
                            }
                            this.$bus.$emit('getData', response.data)
                            this.show = false
                        })
                }
            })
        },
        //Function Name: imgUrAlt
        //Function Description: change image target to not found on error
        //Param: event (404)
        imgUrlAlt(event) {
            event.target.src = 'img/image_not_found.png'
        },
    },
    //stops watching for selectData emit on close
    beforeDestroy() {
        this.$bus.$off('selectedData')
    },
    computed: {
        //return true to disable save button when no input change occured
        disableSave() {
            if (
                this.gatewayData.GATEWAY_NAME == this.cachedData.GATEWAY_NAME ||
                !this.gatewayData.GATEWAY_NAME.replace(/\s/g, '').length
            )
                return true
        },
        //changes source image according to category
        sourceImage() {
            var image_source = ''
            if (this.gatewayData.category == 'systemGateway') {
                image_source = 'img/gateway-image.png'
            } else if (this.gatewayData.category == 'modbusGateway') {
                image_source = 'img/modBus_gateway.png'
            } else if (this.gatewayData.category == 'cameraGateway') {
                image_source = 'img/camera_gateway-image.png'
            }
            return image_source
        },
    },
}
</script>
<style>
.hardware-img-right-pane-hotel {
    z-index: 2;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    width: 50%;
    /* height: 50%; */
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
}
</style>
