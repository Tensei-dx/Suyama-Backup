<template>
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">
                {{ $t('management.roomInfo.co2TempHum') }}
            </h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/c02_temp_hum.png"
                     style="max-width:100%; margin-top:10px;" class="img-fluid">
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.co2Level') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold" v-if="!!Co2TempHumid_device">
                        {{ Co2TempHumid_device.DATA.co2 }} PPM
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.hum') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold" v-if="!!Co2TempHumid_device">
                        {{ Co2TempHumid_device.DATA.humidity }} %
                    </div>
                </div>
                <div class="row text-black text-left w-100 ml-1 mb-2">
                    <div class="col-5 px-0">
                        <span>
                            <i class="fa fa-circle fa-1x text-success"></i>
                            <span class="text-black b">
                                {{ $t('management.roomInfo.low') }}
                            </span>
                        </span>
                    </div>
                    <div class="col-7 px-0">
                        <span>
                            <i class="fa fa-circle fa-1x text-warning"></i>
                            <span class="text-black b">
                                {{ $t('management.roomInfo.elevated') }}
                            </span>
                        </span>
                    </div>
                    <div class="col-5 px-0">
                        <span>
                            <i class="fa fa-circle fa-1x text-info"></i>
                            <span class="text-black b">
                                {{ $t('management.roomInfo.normal') }}
                            </span>
                        </span>
                    </div>
                    <div class="col-7 px-0">
                        <span>
                            <i class="fa fa-circle fa-1x text-danger"></i>
                            <span class="text-black b">
                                {{ $t('management.roomInfo.dangerous') }}
                            </span>
                        </span>
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
            Co2TempHumid_device: null,
        }
    },

    created() {
        this.getDataFromDatabase()
    },

    methods: {
        /**
         * @name getDataFromDatabase
         * @desc Get the data from the database
         */
        getDataFromDatabase() {
            axios
                .post('getDevice/' + this.deviceId)
                .then(response => {
                    this.Co2TempHumid_device = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },

    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getDataFromDatabase()
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
