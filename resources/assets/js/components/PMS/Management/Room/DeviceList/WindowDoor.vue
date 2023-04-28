<!-- <Create> 2020.04.23 TP Shannie Wireless Window/Door Sensor -->
<!-- UPDATED: TP Shannie SPRINT_09 TASK189 20210929 -->
<template>
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">
                {{ $t('management.roomInfo.windowDoorSensor') }}
            </h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/window_door_sensor.png"
                     style="max-width:100%; margin-top:10px;" class="img-fluid">
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.serialNumber') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ device.DEVICE_SERIAL_NO }}
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.battery') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ device.DATA.battery }} V
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
            window_door_sensor_device: null,
        }
    },

    created() {
        this.getDataFromDatabase()
    },

    computed: {
        /**
         * @name updateBatteryLevel
         * @desc
         * @returns {String[]}
         */
        updateBatteryLevel: function () {
            let bat = this.window_door_sensor_device.DATA.battery
            let th = this.window_door_sensor_device.LOW_VOLTAGE
            if (!bat) {
                return ['NO DATA', 'text-danger']
            } else if (bat <= th) {
                return ['LOW', 'text-danger']
            } else {
                return ['NORMAL', 'text-black']
            }
        },
    },

    methods: {
        /**
         * @name getDataFromDatabase
         * @desc Get the data from the database
         */
        getDataFromDatabase() {
            axios
                .get('getDevice', {
                    params: {
                        DEVICE_TYPE: 'window_door_sensor',
                        REG_FLAG: 1,
                        DEVICE_ID: this.deviceId,
                    },
                })
                .then(response => {
                    this.window_door_sensor_device = response.data[0]
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },

    mounted() {
        Echo.channel('newtemp-data').listen('NewTempDataEvent', value => {
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
