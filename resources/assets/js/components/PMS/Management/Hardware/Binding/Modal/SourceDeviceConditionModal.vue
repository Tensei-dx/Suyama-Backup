<!--
    <System Name> iBMS
    <Program Name> SourceDeviceConditionModal.vue

    <Created> 2020.09.01 TP Yani
              2020.09.22 TP Russell Updated Threshold for temp_hum,dust_detector,co2_detector
    <Update>  
-->

<template>
    <div class="modal d-block">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-dialog modal-dialog-centered w-600" role="document">
            <div class="modal-content text-black">
                <div class="modal-header">
                    <h3>Device Condition</h3>
                </div>
                <!-- if temp_hum -->
                <div class="modal-body" v-if="device=='temp_hum'">
                    <div class="row">
                        <div class="col-md-4 text-center"><h4>Temperature</h4></div>
                    </div>
                     <div class="row mt-2">
                        <div class="col-md-4 text-center"><h4>Max:</h4>
                        </div>
                        <div class="col-md-8 text-left">
                            <select class="custom-select col-md-6" v-model="temp.MAX">
                                <!-- loop the temperature data -->
                                <option v-for="tempe,index in temps" :key="index" :value="tempe.value">{{tempe.value}}</option>
                            </select>
                            <h4 class="col-md-2 d-inline">℃</h4>
                        </div>
                    </div>
                     <div class="row mt-2">
                        <div class="col-md-4 text-center"><h4>Min:</h4>
                        </div>
                        <div class="col-md-8 text-left">
                            <select class="custom-select col-md-6" v-model="temp.MIN">
                                <option v-for="tempe,index in temps" :key="index" :value="tempe.value">{{tempe.value}}</option>
                            </select>
                            <h4 class="col-md-2 d-inline">℃</h4>
                        </div>
                    </div>
                </div>
                <!-- if co2 -->
                <div class="modal-body" v-else-if="device=='co2_detector'">
                    <div class="row">
                        <div class="col-md-4 text-center"><h4>Co2 Detector</h4></div>
                    </div>
                     <div class="row mt-2">
                        <div class="col-md-4 text-center"><h4>Max:</h4>
                        </div>
                        <div class="col-md-8 text-left">
                            <select class="custom-select col-md-6" v-model="co2.MAX">
                                <option v-for="ppm,index in ppms" :key="index" :value="ppm.value">{{ppm.value}}</option>
                            </select>
                            <h4 class="col-md-2 d-inline">ppm</h4>
                        </div>
                    </div>
                </div>
                <!-- if dust sensor -->
                <div class="modal-body" v-else-if="device=='dust_detector'">
                    <div class="row">
                        <div class="col-md-4 text-center"><h4>Dust Sensor</h4></div>
                    </div>
                     <div class="row mt-2">
                        <div class="col-md-4 text-center"><h4>Max:</h4>
                        </div>
                        <div class="col-md-8 text-left">
                            <select class="custom-select col-md-6" v-model="dustsen.MAX">
                                <option v-for="dust,index in dusts" :key="index" :value="dust.value">{{dust.value}}</option>
                            </select>
                            <h4 class="col-md-2 d-inline">μg/m3</h4>
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
                temps:[ {"value":1},{"value":2},{"value":3},{"value":4},{"value":5},
                        {"value":6},{"value":7},{"value":8},{"value":9},{"value":10},
                        {"value":11},{"value":12},{"value":13},{"value":14},{"value":15},
                        {"value":16},{"value":17},{"value":18},{"value":19},{"value":20},
                        {"value":21},{"value":22},{"value":23},{"value":24},{"value":25},
                        {"value":26},{"value":27},{"value":28},{"value":29},{"value":30},
                        {"value":31},{"value":32},{"value":33},{"value":34},{"value":35},
                        {"value":36},{"value":37},{"value":38},{"value":39},{"value":40},],
                ppms:[  {"value":1200},{"value":1220},{"value":1240},{"value":1260},{"value":1280},
                        {"value":1300},{"value":1320},{"value":1340},{"value":1360},{"value":1380},
                        {"value":1400},{"value":1420},{"value":1440},{"value":1460},{"value":1480},
                        {"value":1500},{"value":1520},{"value":1540},{"value":1560},{"value":1580},
                        {"value":1600},{"value":1620},{"value":1640},{"value":1660},{"value":1680},
                        {"value":1700},{"value":1720},{"value":1740},{"value":1760},{"value":1780},
                        {"value":1800},{"value":1820},{"value":1840},{"value":1860},{"value":1880},
                        {"value":1900},{"value":1920},{"value":1940},{"value":1960},{"value":1980},{"value":2000},],
                dusts:[ {"value":35},{"value":36},{"value":37},{"value":38},{"value":39},
                        {"value":40},{"value":41},{"value":42},{"value":43},{"value":44},
                        {"value":45},{"value":46},{"value":47},{"value":48},{"value":49},
                        {"value":50},{"value":51},{"value":52},{"value":53},{"value":54},
                        {"value":55},{"value":56},{"value":57},{"value":58},{"value":59},
                        {"value":60},{"value":61},{"value":62},{"value":63},{"value":64},
                        {"value":65},{"value":66},{"value":67},{"value":68},{"value":69},
                        {"value":70},],
                temp:{
                    MAX: null,
                    MIN: null
                },
                co2:{
                    MAX: null
                },
                dustsen:{
                    MAX: null
                },
            }
        },
        created(){
        },
        methods:{
            //Function Name: saveCondition
            //Function Description: Save Condition and record to binding data
            //PARAM: element
            saveConditionBtn(element){
                if(this.temp.MAX != null || this.temp.MIN != null){
                    if (this.temp.MAX > this.temp.MIN){
                        //Sweet Alert
                        
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Condition successfully added.',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        this.$emit("saveDeviceCondition",this.temp);
                        this.$emit("closeModal");
                    }
                    else if (this.temp.MAX ==null && this.temp.MIN){
                        //Sweet Alert
                        
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Condition successfully added.',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        this.$emit("saveDeviceCondition",this.temp);
                        this.$emit("closeModal");
                    }
                    else{
                    //Sweet Alert
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Invalid Minimum Value. ',
                        showConfirmButton: false,
                        timer: 1800
                    });
                }
                    
                }
                else if(this.co2.MAX != null){
                    //Sweet Alert
                    this.$swal({
                        position: 'center',
                        type: 'success',
                        title: 'Condition successfully added.',
                        showConfirmButton: false,
                        timer: 1200
                    });

                    this.$emit("saveDeviceCondition",this.co2);
                    this.$emit("closeModal");
                }
                else if(this.dustsen.MAX != null){
                    //Sweet Alert
                    this.$swal({
                        position: 'center',
                        type: 'success',
                        title: 'Condition successfully added.',
                        showConfirmButton: false,
                        timer: 1200
                    });

                    this.$emit("saveDeviceCondition",this.dustsen);
                    this.$emit("closeModal");
                }
                else{
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
                if(this.temp.MAX != null || this.temp.MIN != null){
                    return false;
                }
                else if(this.co2.MAX != null){
                    return false;
                }
                else if(this.dustsen.MAX != null){
                    return false;
                }else{
                    return true;
                }
            },
        }
    };
</script>