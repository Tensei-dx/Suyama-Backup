<!--
    <System Name> iBMS
    <Program Name> CompleteScanModal.vue
    <Create>             TP Robert
    <Update> 2019.07.11  TP Ivin Insert Comment header
             2020.05.27  TP Uddin Modify axios URL according to the URL list
-->
<template>
    <!-- openmodal are props of vue contain the data from other component -->
    <!-- modal -->
    <div class="modal" :class="show">
        <!-- call close function on line 48 -->
        <div class="modal-background" @click="close"></div>
        <!-- modal dialog -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- modal content -->
            <div class="modal-content">
                <!-- modal body -->
                <div class="modal-body">
                    <!-- <div v-if="showSpinner" class="m-3 text-center"> -->
                    <!-- <div class="spinner">
                            <spinner :status="spinner.status" :color="spinner.color" :size="spinner.size"
                                     :depth="spinner.depth" :rotation="spinner.rotation" :speed="spinner.speed">
                            </spinner>
                        </div> -->
                    <!-- <h5 class="fa fa-spin text-black mt-4"> {{$t('gateway.scan')}} .......</h5>
                        <div class="text-center mt-3">
                            <a class="btn btn-secondary col-sm-4 text-white" @click="close">{{$t('user.cancel')}}</a>
                        </div> -->
                    <!-- </div> -->
                    <div class="display-4 text-center">
                        <div class="text-center">
                            <i v-if="showSpinner" class="fa fa-check-circle-o fa-2x fa-fw" style="color:green"
                               :class="{'fa-spin': showSpinner}" aria-label="true"></i>

                            <h5 class=" text-black mt-4"> {{$t('gateway.scanComplete')}}</h5>
                            <!-- <div class="text-center mt-3">
                            <a class="btn btn-secondary col-sm-4 text-white" @click="close">{{$t('close')}}</a>
                        </div> -->
                        </div>
                    </div>
                </div>
                <!-- modal body end -->
            </div>
            <!-- modal content end -->
        </div>
        <!-- modal dialog end -->
    </div>
    <!-- modal end -->
</template>
<script>
export default {
    //get the attributes from other components
    props: ['show', 'category', 'currentPage'],
    created() {
        this.getScan()
        $('body').addClass('modal-open')
    },
    data() {
        return {
            showSpinner: true,
            // spinner: {
            //     size: 100,
            //     status: true,
            //     // color: '#4fc08d',
            //     // color: '#fd9500',
            //     color: '#28a745',
            //     depth: 5,
            //     rotation: true,
            //     speed: 0.7,
            // },
        }
    },
    methods: {
        //Function Name: getScan
        //Function Description: scan for gateway or device
        //Param: category
        getScan() {
            var url = ''
            var met = ''
            if (this.category == 'device') {
                url = 'scanDeviceAll'
                met = 'get'
            } else if (this.category == 'camera') {
                url = 'scanAllCameras'
                met = 'post'
            } else {
                url = 'scanGatewayAll'
                met = 'get'
            }
            axios({
                method: met,
                url: url,
            })
                .then(response => {
                    let data = response.data
                    for (let i = 0; i <= 50; i++) {
                        setTimeout(() => {
                            this.showSpinner = false
                        }, 10 * i)
                    }
                    this.$emit('loaddata', this.currentPage)
                })
                .catch(errors => {
                    console.log(errors)
                })
                .then(() => {
                    this.showSpinner = false
                    this.$emit('scanComplete')
                })
        },
        //Function Name: close
        //Function Description: close modal function
        close() {
            this.$emit('loaddata', this.currentPage)
            this.$emit('close')
        },
    },
    beforeDestroy() {
        $('body').removeClass('modal-open')
    },
}
</script>
<style scoped>
/* .modal-body {
    background-color: white !important; */
/* } */
/* .fa-check-circle {
    color: #4fc08d;
    background-color: white;
}  */
</style>
