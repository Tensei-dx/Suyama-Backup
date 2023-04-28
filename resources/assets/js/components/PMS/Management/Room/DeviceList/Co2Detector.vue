<template>
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">Co2 Detector</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/co2_sensor.png" style="max-width:100%; margin-top:10px;"
                     class="img-fluid">
            </div>
            <div class="col-8 pt-2" v-for="co2 in co2_device" :key="co2.DEVICE_ID">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Co2 Level
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ co2.DATA.status }} PPM
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Name
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ device.DEVICE_NAME }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceId: '',
        deviceName: '',
        device: '',
    },

    data() {
        return {
            co2_device: [],
        }
    },

    created() {
        this.getCo2Devices()
    },

    methods: {
        /**
         * @name getTempDevices
         * @desc Get  h2o_detector device from the database
         */
        getCo2Devices() {
            axios
                .post('getDevice/' + this.deviceId)
                .then(response => {
                    this.co2_device = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        mounted() {
            Echo.channel('device-command').listen('DeviceCommandEvent', value => {
                this.getPeopleCounterCameras()
                this.getCo2Devices()
            })
        },
    },
}
</script>
<style scoped>
.device-image {
    border-right: solid 1px #111;
}
.data-label {
    background-color: #add8e6 !important;
    font-weight: bold !important;
}
</style>
