<!-- UPDATED: TP Shannie SPRINT_09 TASK189 20210929 -->
<template>
    <!-- <div class="row">
        <div class="col" style="background-color:white; color:white; text-align:center;">
            <img src="/img/ManagementDashboard/realdevices/temp_hum.png" style="max-width:100%; margin-top:10px;" class="img-fluid"><br>
            <button type="button" class="btn btn-secondary btn-lg" style="margin-top:5px; margin-bottom:5px;" disabled>{{ device.DEVICE_NAME }}</button>
        </div>
        <div class="col" style="color:black; text-align:center; font-size:20px; margin-top:70px; margin-right:15px;">
            <div v-for="temp in temp_hum_device" :key="temp.DEVICE_ID">
                TEMPERATURE: <br>
                {{ temp.DATA.temp }}  °C <br>
                HUMIDITY: <br>
                {{ temp.DATA.hum }} %
            </div>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">Temperature & Humidity</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/temp_hum.png" style="max-width:100%; margin-top:10px;"
                     class="img-fluid">
            </div>
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
            temp_hum_device: [],
        }
    },

    created() {
        this.getTempDevice()
    },

    methods: {
        /**
         * @name getTempDevices
         * @desc Get all temp_hum devices from the database
         */
        getTempDevice() {
            axios
                .get('getDevice', {
                    params: {
                        DEVICE_TYPE: 'temp_hum',
                        REG_FLAG: 1,
                        DEVICE_ID: this.deviceId,
                    },
                })
                .then(response => {
                    this.temp_hum_device = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },

    mounted() {
        Echo.channel('newtemp-data').listen('NewTempDataEvent', value => {
            this.getTempDevice()
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
