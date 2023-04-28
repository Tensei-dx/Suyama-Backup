<template>
    <div class="col-12 p-1" style="height:calc(100% - 22px);">
        <div class="row mt-1 ml-1 no-gutters">
            <div class="col-auto ">
                <img src="/img/HOTEL_imgs/wind-sign_white.png" class="menu-icon-size-monitor">
            </div>
            <div class="col-auto px-0 text-left">
                <span class="h1 ml-1 font-weight-bold text-uppercase">
                    {{$t('hotel.co2_monitor')}}
                </span>
            </div>
            <div class="col-auto pl-1 text-left">
                <img src="/img/HOTEL_imgs/help.png" class="pointer help-img-size mt-3" @click="showHelpModal()">
                <HelpModal id="HelpModal" />
            </div>
            <div class="col-auto ml-auto p-0">
            </div>
        </div>
        <div class="row mx-2" style="height:calc(100% - 55px);">
            <div class="col-6 ml-auto">
                <div class="row no-gutters h-100">
                    <div class="col-12 card h-auto m-1 p-0" v-for="room in this.rooms" :key="room.ROOM_ID"
                         :style="backgroundImage(room.ROOM_ID)">
                        <div class="row m-1">
                            <div class="col-12 text-left">
                                <span class="font-weight-bold external-icon-font-size">
                                    {{room.ROOM_NAME}}
                                </span>
                            </div>
                        </div>
                        <div class="row m-1" style="height:calc(100% - 41px);">
                            <div class="col-6">
                                <div class="row h-100 no-gutters">
                                    <div :class="checkCo2Concentration(room.devices[0].DATA.co2)">
                                        <div class="row no-gutters my-1">
                                            <div class="col-12">
                                                <img src="/img/HOTEL_imgs/EnviromentalScreen/co2_detector.png"
                                                     class="external-co2-icon-size">
                                            </div>
                                        </div>
                                        <div class="row no-gutters my-1">
                                            <div class="col-12 font-weight-bold" style="font-size:1.5vw;">
                                                <span>{{room.devices[0].DATA.co2.split(".")[0]}} PPM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row h-50 no-gutters">
                                    <div class="col-12 mt-auto mb-1 mr-auto ml-0 p-0">
                                        <div class="row no-gutters">
                                            <div class="col-2 my-auto text-left px-0">
                                                <img src="/img/HOTEL_imgs/temp.png"
                                                     class="img-fluid external-temp-hum-icon-size">
                                            </div>
                                            <div
                                                 class="col my-auto text-left px-0 font-weight-bold external-icon-font-size">
                                                <span>{{room.devices[0].DATA.temperature.split(".")[0]+'.'+room.devices[0].DATA.temperature.split(".")[1].slice(0,1)}}
                                                    ℃</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row h-50 no-gutters">
                                    <div class="col-12 mb-auto mt-1 mr-auto ml-0 p-0">
                                        <div class="row no-gutters">
                                            <div class="col-2 my-auto text-left px-0">
                                                <img src="/img/HOTEL_imgs/humid.png"
                                                     class="img-fluid external-temp-hum-icon-size">
                                            </div>
                                            <div
                                                 class="col my-auto text-left px-0 font-weight-bold external-icon-font-size">
                                                <span>{{room.devices[0].DATA.humidity.split(".")[0]+'.'+room.devices[0].DATA.humidity.split(".")[1].slice(0,1)}}
                                                    %</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 p-0 text-right">
                <div class="row h-100 no-gutters">
                    <div class="col-auto mt-auto ml-auto text-center">
                        <div class="col-12 low-legend-indicator align-self-center">
                            <div class="font-weight-bold legend-indicator-font-size text-uppercase">
                                {{$t('hotel.external.legends.low')}}
                            </div>
                        </div>
                        <div class="col-12 my-1 normal-legend-indicator">
                            <div class="font-weight-bold legend-indicator-font-size text-uppercase">
                                {{$t('hotel.external.legends.normal')}}
                            </div>
                        </div>
                        <div class="col-12 my-1 elevated-legend-indicator">
                            <div class="font-weight-bold legend-indicator-font-size text-uppercase">
                                {{$t('hotel.external.legends.elevated')}}
                            </div>
                        </div>
                        <div class="col-12 my-1 dangerous-legend-indicator">
                            <div class="font-weight-bold legend-indicator-font-size text-uppercase">
                                {{$t('hotel.external.legends.dangerous')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HelpModal from '../../EnvironmentalMonitor/Help/HelpModal.vue'

export default {
    components: {
        HelpModal,
    },

    created() {
        this.getRoomWithDevices()
    },

    data() {
        return {
            activeCarousel: 1,
            rooms: [],
        }
    },

    mounted() {
        $('.carousel-item:first').addClass('active')

        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getRoomWithDevices()
        })
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
            if (value <= 800) {
                return 'co2-concentration-external-low co2-concentration-external col-7 my-auto ml-auto mr-0 p-0'
            } else if (value > 800 && value < 1000) {
                return 'co2-concentration-external-normal co2-concentration-external col-7 my-auto ml-auto mr-0 p-0'
            } else if (value >= 1000 && value < 2500) {
                return 'co2-concentration-external-elevated co2-concentration-external col-7 my-auto ml-auto mr-0 p-0'
            } else if (value >= 2500) {
                return 'co2-concentration-external-high co2-concentration-external col-7 my-auto ml-auto mr-0 p-0'
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

        /**
         * @name backgroundImage
         * @desc Set the background image of a slide
         *
         * @param {String} ROOM_ID
         * @returns string
         */
        backgroundImage(ROOM_ID) {
            // = SPRINT_04 TASK127
            //return "background-image:url('../img/RestaurantDashboard/"+ROOM_ID+".png'); background-size:cover; background-position: center;"
            return (
                "background-image:url('../img/HOTEL_imgs/" +
                ROOM_ID +
                ".png'); background-size:cover; background-position: center;"
            )
            // = SPRINT_04 TASK127
        },
    },

    computed: {
        /**
         *
         */
        getRoomCo2Devices: function () {
            return this.rooms.filter
        },
    },
}
</script>

<style>
.external-co2-icon-size {
    width: 70px;
    height: 50px;
}
.external-temp-hum-icon-size {
    width: 30px;
    height: 37px;
}
.external-icon-font-size {
    font-size: 2vw;
}
.legend-indicator-font-size {
    font-size: 25px;
    padding-top: 15px;
}
.co2-concentration-external {
    border-radius: 300px;
}
.co2-concentration-external-low {
    border: 7px solid rgb(0, 176, 80);
}
.co2-concentration-external-normal {
    border: 7px solid rgb(91, 155, 213);
}
.co2-concentration-external-elevated {
    border: 7px solid rgb(255, 192, 0);
}
.co2-concentration-external-high {
    border: 7px solid red;
}
.low-legend-indicator {
    background-color: rgb(0, 176, 80);
    border-radius: 20px;
    height: 70px;
}
.normal-legend-indicator {
    background-color: rgb(91, 155, 213);
    border-radius: 20px;
    height: 70px;
}
.elevated-legend-indicator {
    background-color: rgb(255, 192, 0);
    border-radius: 20px;
    height: 70px;
}
.dangerous-legend-indicator {
    background-color: red;
    border-radius: 20px;
    height: 70px;
}
</style>

