<!-- UPDATED: TP Shannie SPRINT_09 TASK189 20210929 -->
<template>
    <!-- <div class="row">
        <div class="col" style="background-color:white; color:black; text-align:center;">
            <img src="/img/ManagementDashboard/realdevices/emergency_sensor.png" style="max-width:100%; margin-top:10px;" class="img-fluid"><br>
            <button type="button" class="btn btn-secondary btn-lg" style="margin-top:10px; margin-bottom:10px;" disabled>{{ device.DEVICE_NAME }}</button>
        </div>
        <div class="col" style="color:black; text-align:center; font-size:20px; margin-top:70px; margin-right:15px;">
            <div v-if="emergency_button_device.DATA != undefined">
                BATTERY LEVEL: <br>
                {{ emergency_button_device.DATA.battery }} V <br>
                <div :class="updateBatteryLevel[1]">
                {{ updateBatteryLevel[0] }}
                </div>
            </div>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">Emergency Button</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/camera.jpg" style="max-width:100%; margin-top:10px;"
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
            emergency_button_device: [],
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
            let bat = this.emergency_button_device.DATA.battery
            let th = this.emergency_button_device.LOW_VOLTAGE
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
                        DEVICE_TYPE: 'emergency_button',
                        REG_FLAG: 1,
                        DEVICE_ID: this.deviceId,
                    },
                })
                .then(response => {
                    this.emergency_button_device = response.data[0]
                    console.log('This will be your data')
                    console.log(response.data[0])
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
