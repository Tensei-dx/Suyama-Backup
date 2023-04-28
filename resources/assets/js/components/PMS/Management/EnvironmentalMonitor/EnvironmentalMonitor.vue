<!-- UPDATED: TP Chris   SPRINT_04 TASK113 08232021 -->
<!-- UPDATED: TP Chris   SPRINT_04 TASK127 08272021 -->
<template>
    <div>
        <div class="row m-2">
            <!-- <div class="col-auto "> -->
            <!-- = SPRINT_04 TASK127 -->
            <!-- <img src="/img/RestaurantDashboard/wind-sign_white.png" class="menu-icon-size-monitor"> -->
            <!-- <img src="/img/HOTEL_imgs/wind-sign_white.png" class="menu-icon-size-monitor"> -->
            <!-- = SPRINT_04 TASK127 -->
            <!-- </div> -->
            <div class="col-auto px-2 text-left">
                <span class="h1 font-weight-bold text-uppercase">
                    <!-- = SPRINT_04 TASK127 -->
                    <!-- {{$t('restaurant.admin.menu.monitor')}} -->
                    {{$t('hotel.co2_monitor')}}
                    <!-- = SPRINT_04 TASK127 -->
                </span>
            </div>
            <div class="col-auto pl-1 text-left">
                <!-- = SPRINT_04 TASK127 -->
                <!-- <img src="/img/RestaurantDashboard/help.png" class="pointer help-img-size mt-3" @click="showHelpModal()"> -->
                <img src="/img/HOTEL_imgs/help.png" class="pointer help-img-size mt-3" @click="showHelpModal()">
                <!-- = SPRINT_04 TASK127 -->
                <HelpModal id="HelpModal" />
            </div>
            <!-- = SPRINT_04 TASK127 -->
            <!-- <div class="col-auto ml-auto text-right p-0"> -->
            <div class="col-auto ml-auto text-right mb-1" style="height:65px;">
                <!-- = SPRINT_04 TASK127 -->
                <div class="row no-gutters">
                    <div class="col-auto mx-2">
                        <img :src="chartSelection()[0]" class="pointer" @click="changeChart(1)">
                    </div>
                    <div class="col-auto mx-0">
                        <img :src="chartSelection()[1]" class="pointer chart-img-size" @click="changeChart(2)">
                    </div>
                    <div class="col-auto mx-0">
                        <img :src="chartSelection()[2]" class="pointer chart-img-size" @click="changeChart(3)">
                    </div>
                </div>
            </div>
            <!-- - SPRINT_04 TASK127 -->
            <!-- <div class="col-auto ml-2 p-0">
                <img src="/img/RestaurantDashboard/maximize.png" class="pointer" @click="$emit('changePage', 'device_slide_show' , rooms)">
            </div> -->
            <!-- - SPRINT_04 TASK127 -->
        </div>
        <div class="row m-3 mt-4 graph-scrollable">
            <div v-for="room in this.rooms" :key="room.ROOM_ID">
                <div v-for="device in room.devices" :key="device.DEVICE_ID">
                    <!-- = SPRINT_04 TASK127 -->
                    <!-- <div v-if="device.DEVICE_TYPE == 'co2_temp_humid'" class="card m-1">
                        <img :src="'/img/RestaurantDashboard/'+room.ROOM_ID+'.png'" alt="/img/RestaurantDashboard/monitor_background_image.png" class="card-img monitor-background-img-size img-fluid"> -->
                    <div v-if="device.DEVICE_TYPE == 'co2_temp_humid'" class="card m-1 mb-3">
                        <img :src="'/img/HOTEL_imgs/20.png'" alt="/img/HOTEL_imgs/monitor_background_image.png"
                             class="card-img monitor-background-img-size img-fluid" style="height:247px;">
                        <!-- = SPRINT_04 TASK127 -->
                        <div class="card-img-overlay mx-3">
                            <div class="row">
                                <span class="h4 font-weight-bold">{{room.ROOM_NAME}}</span>
                            </div>
                            <div class="row mt-0 d-flex align-items-center">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-1"></div>
                                        <div :class="checkCo2Concentration(device.DATA.co2)">
                                            <div class="col-12">
                                                <img src="/img/ManagementDashboard/icon/Devices/co2_detector.png"
                                                     class="co2-icon-size img-fluid">
                                            </div>
                                            <div class="col-12 font-weight-bold text-center" style="font-size:20px;">
                                                <span>{{device.DATA.co2.split(".")[0]}} PPM</span>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                    <!-- = SPRINT_04 TASK127 -->
                                    <!-- <div class="row mt-5"> -->
                                    <div class="row mt-3">
                                        <!-- = SPRINT_04 TASK127 -->
                                        <div class="col-6 p-0">
                                            <div class="col-12 text-center">
                                                <!-- = SPRINT_04 TASK127 -->
                                                <!-- <img src="/img/RestaurantDashboard/temp.png" class="img-fluid"> -->
                                                <img src="/img/HOTEL_imgs/temp.png" class="img-fluid">
                                                <!-- = SPRINT_04 TASK127 -->
                                            </div>
                                            <div class="col-12 text-center font-weight-bold" style="font-size:20px;">
                                                <span>{{device.DATA.temperature.split(".")[0]+'.'+device.DATA.temperature.split(".")[1].slice(0,1)}}
                                                    ℃</span>
                                            </div>
                                        </div>
                                        <div class="col-6 p-0">
                                            <div class="col-12 text-center font-weight-bold">
                                                <!-- = SPRINT_04 TASK127 -->
                                                <!-- <img src="/img/RestaurantDashboard/humid.png" class="img-fluid" style="height:34px;"> -->
                                                <img src="/img/HOTEL_imgs/humid.png" class="img-fluid"
                                                     style="height:34px;">
                                                <!-- = SPRINT_04 TASK127 -->
                                            </div>
                                            <div class="col-12 text-center font-weight-bold" style="font-size:20px;">
                                                <span>{{device.DATA.humidity.split(".")[0]+'.'+device.DATA.humidity.split(".")[1].slice(0,1)}}
                                                    %</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 p-1">
                                    <!-- = SPRINT_04 TASK127 -->
                                    <!-- <Co2ConcentrationChart1 v-if="returnChart == 1" :height="height"  :device_id="213213240" style="background-color:white;"/>
                                    <Co2ConcentrationChart2 v-if="returnChart == 2" :height="height"  :device_id="213213240" style="background-color:white;"/>
                                    <Co2ConcentrationPeopleCounterChart3 v-if="returnChart == 3" :height="height"  :device_id="213213240" :room_id="device.ROOM_ID" style="background-color:white;"/> -->
                                    <Co2ConcentrationChart1 v-if="returnChart == 1" :height="height"
                                                            :device_id="device.DEVICE_ID"
                                                            style="height:180px; background-color:white;" />
                                    <Co2ConcentrationChart2 v-if="returnChart == 2" :height="height"
                                                            :device_id="device.DEVICE_ID"
                                                            style="height:180px; background-color:white;" />
                                    <Co2ConcentrationPeopleCounterChart3 v-if="returnChart == 3" :height="height"
                                                                         :device_id="device.DEVICE_ID"
                                                                         :room_id="device.ROOM_ID"
                                                                         style="height:180px; background-color:white;" />
                                    <!-- = SPRINT_04 TASK127 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Co2ConcentrationChart1 from '../EnvironmentalMonitor/Graph/Co2ConcentrationChart1.vue'
import Co2ConcentrationChart2 from '../EnvironmentalMonitor/Graph/Co2ConcentrationChart2.vue'
import Co2ConcentrationPeopleCounterChart3 from '../EnvironmentalMonitor/Graph/Co2ConcentrationPeopleCounterChart3.vue'
import HelpModal from '../EnvironmentalMonitor/Help/HelpModal.vue'

export default {
    components: {
        HelpModal,
        Co2ConcentrationChart1,
        Co2ConcentrationChart2,
        Co2ConcentrationPeopleCounterChart3,
    },

    data() {
        return {
            rooms: [],
            height: 280,
            selected_chart: 1,
        }
    },

    created() {
        this.getRoomWithDevices()
    },

    methods: {
        /**
         * @name getRoomWithDevices
         * @desc Get all Hotel rooms
         *
         * @returns null
         */
        getRoomWithDevices() {
            // = SPRINT_04 TASK127
            // axios.get('getRestaurantRoom')
            axios
                .get('getHotelRoom')
                // = SPRINT_04 TASK127
                .then(response => {
                    this.rooms = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name checkCo2Concentration
         * @desc Check the Co2 level of co2_temp_humid device
         *
         * @param {Object} value
         * @returns string
         */
        checkCo2Concentration(value) {
            if (value < 800) {
                return 'col-10 co2-concentration-low co2-concentration'
            } else if (value >= 800 && value <= 1000) {
                return 'col-10 co2-concentration-normal co2-concentration'
            } else if (value > 1000 && value <= 2500) {
                return 'col-10 co2-concentration-elevated co2-concentration'
            } else if (value > 2500) {
                return 'col-10 co2-concentration-high co2-concentration'
            }
        },

        // getRoomImage() {
        //     return ''
        // }

        /**
         * @name changeChart
         * @desc Change the display of charts
         *
         * @param {Number} value
         * @returns null
         */
        changeChart(value) {
            this.selected_chart = value
        },

        /**
         * @name chartSelection
         * @desc Change the color of the chart selections
         *
         * @returns array
         */
        chartSelection() {
            if (this.selected_chart == 1) {
                return [
                    // = SPRINT_04 TASK127
                    // '/img/RestaurantDashboard/ChartSelection/1_selected.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/2.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/3.png'
                    '/img/HOTEL_imgs/ChartSelection/1_selected.png',
                    '/img/HOTEL_imgs/ChartSelection/2.png',
                    '/img/HOTEL_imgs/ChartSelection/3.png',
                    // = SPRINT_04 TASK127
                ]
            } else if (this.selected_chart == 2) {
                return [
                    // = SPRINT_04 TASK127
                    // '/img/RestaurantDashboard/ChartSelection/1.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/2_selected.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/3.png'
                    '/img/HOTEL_imgs/ChartSelection/1.png',
                    '/img/HOTEL_imgs/ChartSelection/2_selected.png',
                    '/img/HOTEL_imgs/ChartSelection/3.png',
                    // = SPRINT_04 TASK127
                ]
            } else if (this.selected_chart == 3) {
                return [
                    // = SPRINT_04 TASK127
                    // '/img/RestaurantDashboard/ChartSelection/1.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/2.png' ,
                    // '/img/RestaurantDashboard/ChartSelection/3_selected.png'
                    '/img/HOTEL_imgs/ChartSelection/1.png',
                    '/img/HOTEL_imgs/ChartSelection/2.png',
                    '/img/HOTEL_imgs/ChartSelection/3_selected.png',
                    // = SPRINT_04 TASK127
                ]
            }
        },

        /**
         * @name showHelpModal
         * @desc Display help modal
         *
         * @returns null
         */
        showHelpModal() {
            $('#HelpModal').modal('show')
        },
    },

    computed: {
        returnChart() {
            return this.selected_chart
        },
    },

    mounted() {
        /**
         * @desc Listen to NewDeviceDataEvent for new process data
         *
         * @returns null
         */
        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            this.getRoomWithDevices()
        })
    },
    beforeDestroy() {
        Echo.leave('newdevice-data')
    },
}
</script>

<style>
.monitor-background-img-size {
    width: 1200px;
    height: 350px;
    position: relative;
}
.graph-size {
    display: block;
    width: 370px;
    height: 170px;
}
.chart-img-size {
    width: 95px;
    height: 125px;
}
.help-img-size {
    width: 20px;
    height: 30px;
}
.menu-icon-size-monitor {
    width: 40px;
    height: 40px;
}
.graph-scrollable {
    max-height: 540px !important;
    overflow: auto !important;
    position: relative;
    padding-bottom: 20px;
    padding-top: 0px;
}
</style>
