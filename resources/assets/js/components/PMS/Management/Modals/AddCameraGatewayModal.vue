<!--
    <System Name> iBMS
    <Program Name> AddCameraGatewayModal.vue
    <Create> 2020.10.19 TP Uddin
    <Update>
-->
<template>
    <!-- cat,show are props of vue contain the data from other component -->
    <!-- gateway display -->
    <!-- modal -->
    <div class="modal" :class="show">
        <!-- call close function -->
        <div class="modal-background" @click="close"></div>
        <!-- modal dialog -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- modal content -->
            <div class="modal-content">
                <!-- modal header -->
                <div class="modal-header text-dark">
                    <h5 class="modal-title">{{$t('gateway.addGateway')}}</h5>
                    <!-- call close function -->
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- modal header end-->
                <!-- modal body -->
                <div class="modal-body text-dark">
                    <div class="form-group">
                        <label class="label">{{$t('gateway.manufacturer')}}</label>
                        <div class="">
                            <select class="custom-select" v-model="modalData.MANUFACTURER_ID">
                                <!-- loop the rooms data -->
                                <option
                                    v-for="manufacturer in manufacturers"
                                    :key="manufacturer.MANUFACTURER_ID"
                                    :value="manufacturer.MANUFACTURER_ID"
                                    :disabled="manuDisabled(manufacturer.MANUFACTURER_ID)">
                                    {{manufacturer.MANUFACTURER_NAME}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="label">{{$t('gateway.gatewayIp')}}</label>
                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <input class="form-control" :class="{'border border-danger':errors.GATEWAY_IP}" type="text" v-model="modalData.GATEWAY_IP" @keydown="clearFields" :disabled="ipDisabled">
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" class="btn" @click="getCameraGatewayInfo" :disabled="checkDisabled">{{checkButtonText}}</button>
                            </div>
                        </div>
                        <!-- display this when error occured -->
                        <small v-if="errors.GATEWAY_IP" class="text-danger">{{ errors.GATEWAY_IP[0] }}</small>
                    </div>
                    <div class="form-group">
                        <label class="label">{{$t('gateway.gatewaySerial')}}</label>
                        <input class="form-control" :class="{'border border-danger':errors.GATEWAY_SERIAL_NO}" type="text" v-model="modalData.GATEWAY_SERIAL_NO" readonly>
                        <!-- display this when error occured -->
                        <small v-if="errors.GATEWAY_SERIAL_NO" class="text-danger">{{ errors.GATEWAY_SERIAL_NO[0] }}</small>
                    </div>
                    <div class="form-group">
                        <label class="label">{{$t('gateway.gatewayName')}}</label>
                        <!-- enable /disable the gateway name input using nameDisabled function -->
                        <!-- when the input is enter call the addData funtion-->
                        <input class="form-control" :class="{'border border-danger':errors.GATEWAY_NAME}" type="text" v-model="modalData.GATEWAY_NAME" :disabled="nameDisabled">
                        <!-- display this when error occured -->
                        <small v-if="errors.GATEWAY_NAME" class="text-danger">{{ errors.GATEWAY_NAME[0] }}</small>
                    </div>
                </div>
                <!-- modal body end -->
                <!-- modal footer -->
                <div class="modal-footer">
                    <!-- call addData function -->
                    <!-- enable /disable the save button using gwSaveDisabled function -->
                    <button class="btn btn-primary" @click="addData()" :disabled="gwSaveDisabled">
                        <!-- display loading animation -->
                        <span class="pull-left" v-if="loading">
                            <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                        </span>
                        <!-- change text value when click -->
                        <span> {{ btn_text }} </span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="close">{{$t('close')}}</button>
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
            this.getManufacturer();
            $("body").addClass("modal-open");
        },
        data(){
            return{
                // where data variables is declare and initialize
                manufacturers: {},
                modalData:{
                    MANUFACTURER_ID: 4,
                    FLOOR_ID: 1,
                    GATEWAY_SERIAL_NO: null,
                    GATEWAY_NAME: null
                },
                errors:{},
                loading:false,
                btn_text: this.$t('user.save'),
                error_text: 'This field is required',
                nameDisabled: true,
                checkButtonText: "Check",
                checkDisabled: false,
            }
        },
        methods: {
            //Function Name: getManufacturer
            //Function Description: get manufacturer function
            getManufacturer(){
                //ajax call
                axios.get('getManufacturerAll')
                .then(response => {
                    this.manufacturers = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            //Function Name: close
            //Function Description: close modal function
            close(){
                this.errors = {},
                this.loading = false,
                this.btn_text = this.$t('gateway.save'),
                this.$emit('loaddata', this.currentPage),
                this.$emit('close')
            },
            //Function Name: errs
            //Function Description: error function
            //PARAM: error = response
            errs(error){
                this.errors = error.response.data.errors
                this.loading = false,
                this.btn_text = this.$t('gateway.save')
            },
            //Function Name: addData
            //Function Description: add device or gateway function
            //PARAM = KEY: 0 = gateway, 1 = device
            addData(){
                let message = this.$t('gateway.modalText');
                let errors = this.$t('error_message_code');
                this.loading = true;
                this.btn_text = this.$t('gateway.saving');
                // Check if input is incomplete
                if (this.modalData.MANUFACTURER_ID == '' || this.modalData.MANUFACTURER_ID == null) {
                    let title = this.$t('modalText.error');
                    this.$swal(title, 'Manufacturer name is required', 'error'); // should update the error message
                    this.loading = false;
                    this.btn_text = this.$t('gateway.save');
                } else if (this.modalData.GATEWAY_IP == '' || this.modalData.GATEWAY_IP == null) {
                    let title = this.$t('modalText.error');
                    this.$swal(title, errors.ERR_OPS_080, 'error'); // should update the error message
                    this.loading = false;
                    this.btn_text = this.$t('gateway.save');
                } else if (this.modalData.GATEWAY_SERIAL_NO == '' || this.modalData.GATEWAY_SERIAL_NO == null) {
                    let title = this.$t('modalText.error');
                    this.$swal(title, errors.ERR_OPS_081, 'error');
                    this.loading = false;
                    this.btn_text = this.$t('gateway.save');
                } else if (this.modalData.GATEWAY_NAME == '' || this.modalData.GATEWAY_NAME == null) {
                    let title = this.$t('modalText.error');
                    this.$swal(title, errors.ERR_OPS_075, 'error');
                    this.loading = false;
                    this.btn_text = this.$t('gateway.save');
                }
                // Save camera gateway to Gateway Table
                // Param: MANUFACTURER_ID, GATEWAY_IP, GATEWAY_SERIAL_NO, GATEWAY_NAME
                axios({
                    url: 'registerCameraGateway',
                    method: 'POST',
                    data: {
                        'MANUFACTURER_ID': this.modalData.MANUFACTURER_ID,
                        'GATEWAY_IP': this.modalData.GATEWAY_IP,
                        'GATEWAY_SERIAL_NO': this.modalData.GATEWAY_SERIAL_NO,
                        'GATEWAY_NAME': this.modalData.GATEWAY_NAME
                    }
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.$swal({
                            title: message.regGateway,
                            text: message.camGatewayRegister,
                            type: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        this.close();
                    } else if (response.data == 'already registered') {
                        let title = this.$t('modalText.error');
                        this.$swal(title, errors.ERR_OPS_008, 'error');
                        this.loading = false;
                        this.btn_text = this.$t('gateway.save');
                    } else if (response.data == 'name exists') {
                        let title = this.$t('modalText.error');
                        this.$swal(title, errors.ERR_OPS_082, 'error');
                        this.loading = false;
                        this.btn_text = this.$t('gateway.save');
                    } else {
                        let title = this.$t('modalText.error');
                        this.$swal(title, message.camGatewayCantRegister, 'error');
                        this.loading = false;
                        this.btn_text = this.$t('gateway.save');
                    }
                })
                .catch((error) => this.errs(error));
            },
            //Function Name: manuDisabled
            //Function Description: Disable option for Wulian Manufacturer
            //PARAM = MANUFACTURER_ID: 1 : Wulian
            manuDisabled(data){
                if(data != 4){
                    return true;
                }else{
                    return false;
                }
            },
            // Function Name: getCameraGatewayInfo
            // Function Description: Get camera gateway's serial number and
            //
            getCameraGatewayInfo(){
                this.checkDisabled = true;
                axios.get('checkCameraGatewayInfo', {
                    params: {
                        GATEWAY_IP: this.modalData.GATEWAY_IP
                    }
                })
                .then(response => {
                    if (response.data == 'error') {
                        let title = this.$t('modalText.error');
                        this.$swal(title, "No gateway is recognized in the IP given", 'error');
                        this.loading = false;
                        this.btn_text = this.$t('gateway.save');
                        this.nameDisabled = true;
                        this.checkDisabled = false;
                    }
                    this.modalData.GATEWAY_SERIAL_NO = response.data.Id;
                    this.modalData.GATEWAY_NAME = response.data.Name;
                    this.nameDisabled = false;
                    this.checkDisabled = false;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            //
            //
            //
            clearFields(){
                this.modalData.GATEWAY_SERIAL_NO = null;
                this.modalData.GATEWAY_NAME = null;
            }
        },
        computed: {
            //disable ip field
            ipDisabled(){
                if(this.modalData.MANUFACTURER_ID == null){
                    return true;
                }else{
                    return false;
                }
            },
            //disable gateway button
            gwSaveDisabled(){
                if(this.modalData.GATEWAY_IP == null || this.modalData.GATEWAY_IP == "" || this.modalData.GATEWAY_SERIAL_NO == null || this.modalData.GATEWAY_NAME == ""){
                    return true;
                }else{
                    return false;
                }
            },
        },
        //trigger this function before this view component exit
        beforeDestroy() {
            $("body").removeClass("modal-open");
        }
    };
</script>
