<!--
    <System Name> iBMS
    <Program Name> CustomeConditionBindingModal.vue

    <Created> 2020.02.14 TP Harvey
    <Update>  2020.02.17 TP Harvey
              2020.05.26 TP Uddin Modify axios URL according to the URL list
              2020.09.22 TP Russell Added Custom Condition for IR Remote (Binding Threshold)
-->

<template>
    <div class="modal d-block">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-dialog modal-dialog-centered w-600" role="document">
            <div class="modal-content text-black">
                <div class="modal-header">
                    <h3>{{device['DEVICE_NAME']}} - Custom Condition</h3>
                </div>
                <div class="modal-body" v-if="switch_devices.includes(device.DEVICE_TYPE)">
                    <div class="row">
                        <div class="col-md-2 text-center"><h5>Enabled</h5></div>
                        <div class="col-md-4 text-center"><h5>Name</h5></div>
                        <div class="col-md-6"><h5>Status</h5></div>
                    </div>
                    <!--
                    ************************************************
                    * For Loop Switch Based on Device Type         *
                    ************************************************
                     -->
                    <div v-for="item,key in device['DATA'].length" class="row">
                        <div class="col-md-2 text-center">
                            <b-form-checkbox v-model="condition_data[key]['enabled']">
                            </b-form-checkbox>
                        </div>
                        <div class="col-md-4 text-center" :class="isDisabled(key)">
                            <label>{{device['DATA'][key]['device_name']}}</label>
                        </div>
                        <div class="col-md-6" :class="isDisabled(key)">
                            <input class="tgl tgl-ios"
                                    :id="'switch_' + key"
                                    v-model="condition_data[key]['value']"
                                    type="checkbox">
                            <label class="tgl-btn" :for="'switch_' + key"></label>
                        </div>
                    </div>
                </div>

                <!-- 9/22/2020 Added Custom Condtion for ir_remote -->
                <div class="modal-body" v-else-if="device.DEVICE_TYPE =='ir_remote'">
                     <div class="row mt-2">
                        <div class="col-md-4 text-center"><h4>Operation:</h4>
                        </div>
                        <div class="col-md-8 text-left">
                            <select class="custom-select col-md-6" v-model="ir.operator">
                                <!-- loop the operator data -->
                                <option v-for="ope,index in operators" :key="index" :value="ope.value">{{ope.value}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" @click="closeModal">Cancel</button>
                    <button class="btn btn-primary" @click="saveConditionBtn" :disabled="saveDisabled">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default{
        props:{
            device:'',
        },
        data(){
            return{
                condition_data:
                    [{"enabled":false,"command":"status_1","value":false},
                    {"enabled":false,"command":"status_2","value":false},
                    {"enabled":false,"command":"status_3","value":false}],

                switch_devices:['wall_switch_1','wall_switch_2','wall_switch_3',
                                'embedded_switch_1','embedded_switch_2','embedded_switch_3'],

                operators:[ {"value":"TEMP_16"},{"value":"TEMP_17"},{"value":"TEMP_18"},{"value":"TEMP_19"},{"value":"TEMP_20"},
                            {"value":"TEMP_21"},{"value":"TEMP_22"},{"value":"TEMP_23"},{"value":"TEMP_24"},{"value":"TEMP_25"},
                            {"value":"TEMP_26"},{"value":"TEMP_27"},{"value":"TEMP_28"},{"value":"TEMP_29"},{"value":"TEMP_30"},
                            {"value":"AC_POWER_ON"},{"value":"AC_POWER_OFF"} ],
                ir : {
                    command : "status",
                    operator : ""
                }
            }
        },
        created(){
            console.log("Hi");
            this.checkSavedCondition();
        },
        methods:{
            //Function Name: checkSavedCondition
            //Function Description: Check if there are existing condition saved
            //PARAM: none
            checkSavedCondition(){
                console.log("Hello");
                console.log(this.device);
                if (this.device.CUSTOM_CONDITION) {
                    if(this.device.CUSTOM_CONDITION.length == this.condition_data.length){
                        this.condition_data = this.device.CUSTOM_CONDITION;
                    }
                }
            },
            //Function Name: isDisabled
            //Function Description: Disable the Switch if not needed
            //PARAM: element
            isDisabled(element){
                if(this.condition_data[element]['enabled'] == false){
                    return "disableDiv";
                }
            },
            //Function Name: saveCondition
            //Function Description: Save Condition and record to binding data
            //PARAM: element
            saveConditionBtn(element){
                let value = 0;
                let conditionLength = 0;
                let self = this;
                // 9/22/2020 Added Save Condition for ir_remote
                if(this.switch_devices.includes(this.device.DEVICE_TYPE)){
                    this.condition_data.forEach(function(item,key){
                            if(item["value"] == true){
                                value = 1;
                            }else{
                                value = 0;
                            }
                            self.condition_data[key]['value'] = value;
                    });
                    //Check if condition is empty
                    conditionLength = this.condition_data.filter(item => item.enabled == true).length;

                }else if(this.device.DEVICE_TYPE == "ir_remote"){

                    this.condition_data = this.ir;
                    conditionLength = this.condition_data['operator'].length;

                }

                if(conditionLength > 0){
                        //Sweet Alert
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Condition successfully added.',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        this.$emit("saveDeviceCondition",this.condition_data);
                        this.$emit("closeModal");
                    }else{
                        //Sweet Alert
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: 'Cannot add empty condition.',
                            showConfirmButton: false,
                            timer: 1200
                        });
                }


            },
            //Function Name: closeModal
            //Function Description: this will close this modal
            //PARAM: element
            closeModal(){
                this.$emit("closeModal");
            }
        },
        computed:{
            //*****************************************
            //Check if there are selected binding
            //*****************************************
            saveDisabled () {
                if(this.switch_devices.includes(this.device.DEVICE_TYPE)){
                   //Length of selected Condition
                    let conditionLength = this.condition_data.filter(item => item.enabled == true).length;
                    if(conditionLength == 0){
                        return true;    //disable button
                    }else{
                        return false;   //enable button
                    }
                // 9/22/2020 Added Save Disabled for ir_remote
                }else if(this.device.DEVICE_TYPE == "ir_remote"){
                    //Length of selected Condition
                    let conditionLength = this.ir['operator'].length;
                    if(conditionLength == 0){
                        return true;    //disable button
                    }else{
                        return false;   //enable button
                    }
                }
            },
        }
    };
</script>
