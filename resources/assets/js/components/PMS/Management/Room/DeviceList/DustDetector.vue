<template>
    <!-- <div class="row">
        <div class="col" style="background-color:white; color:white; text-align:center;">
            <img src="/img/ManagementDashboard/realdevices/dust_sensor.png" style="max-width:100%; margin-top:10px;"
                 class="img-fluid"><br>
            <button type="button" class="btn btn-secondary btn-lg" style="margin-top:5px; margin-bottom:5px;"
                    disabled>{{ device.DEVICE_NAME }}</button>
        </div>
        <div class="col" style="color:black; text-align:center; font-size:20px; margin-top:70px; margin-right:15px;">
            <div v-for="dust in dust_device" :key="dust.DEVICE_ID">
                Dust Level: <br>
                {{ dust.DATA.status }} μPPM
            </div>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">Dust Detector</h3>
        </div>
        <div class="row px-3 py-4">
            <!-- Image of the Camera -->
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/dust_sensor.png" style="max-width:100%; margin-top:10px;"
                     class="img-fluid">
            </div>
            <!-- People Counter -->
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
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
            dust_device: [],
        }
    },

    created() {
        this.getDustDevice()
    },

    methods: {
        /**
         * @name getDustDevice
         * @desc Get all temp_hum devices from the database
         */
        getDustDevice() {
            axios
                .get('getDevice', {
                    params: {
                        DEVICE_TYPE: 'dust_detector',
                        REG_FLAG: 1,
                        DEVICE_ID: this.deviceId,
                    },
                })
                .then(response => {
                    this.dust_device = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getDustDevice()
        })
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
