<!--CREATED: TP Jermaine SPRINT_04 TASK109 20210824 -->
<template>
    <div class="card">
        <div class="row no-gutters">
            <div class="col-xl-5 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="/img/guest_dashboard/shower.png" style="width: 50px; " />
                    <small
                           class="d-block mt-2 text-align:center">{{ $t('mobile.control.showerstatus.showerstatus') }}</small>
                </div>
            </div>
            <div class="col p-2 align-self-center ">
                <div class="row d-flex align-items-center justify-content-center">
                    <img :src="ShowerStatus.img" class="align-items-center" style="width: 50px;" />
                    <div class="col-12 mt-2">
                        <div class="row d-flex align-items-center justify-content-center">
                            <div class="col text-center">
                                <div class="col-md-12 bs-linebreak">
                                </div>
                                <h5><span class="badge badge-secondary p-2">
                                        {{ $t(ShowerStatus.message) }}
                                    </span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['room_id'],

    data() {
        return {
            errors: [],
            window_door_sensor: undefined,
        }
    },

    created() {
        this.getSensors()
    },

    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.updateData(value.data)
        })
    },

    methods: {
        /**
         * @name getSensors
         * @description Get sensor data
         *
         * @returns {void}
         */
        getSensors() {
            axios
                .get(`client/devices/` + this.room_id, {
                    params: {
                        DEVICE_TYPE: 'window_door_sensor',
                    },
                })
                .then(response => {
                    console.warn(response)
                    if ((response.status >= 200) & (response.status < 300)) {
                        this.window_door_sensor = response.data.find(i => i.DEVICE_TYPE === 'window_door_sensor')
                    } else {
                        throw new Error(response.data)
                    }
                })
                .catch(error => this.errors.push(error))
        },

        /**
         * @name updateData
         * @description Update the data of window/door sensor
         */
        updateData(processedData) {
            if (processedData.DEVICE_ID === this.window_door_sensor.DEVICE_ID) {
                this.window_door_sensor.DATA = processedData.DATA
            }
        },
    },

    computed: {
        /**
         * @name ShowerStatus
         * @description Returns the status of the shower room status using the window/door sensor
         *
         * @returns {object}
         */
        ShowerStatus: function () {
            // If no sensor is detected, it will display no sensor.
            if (this.window_door_sensor) {
                return this.window_door_sensor.DATA['status']
                    ? {
                          occupied: false,
                          message: 'mobile.control.restroom.vacant',
                          img: '/img/guest_dashboard/door_open.png',
                      }
                    : {
                          occupied: true,
                          message: 'mobile.control.restroom.occupied',
                          img: '/img/guest_dashboard/door_closed.png',
                      }
            } else {
                return {
                    occupied: false,
                    message: 'mobile.control.restroom.nosensor',
                    img: '/img/guest_dashboard/door_closed.png',
                }
            }
        },
    },
}
</script>
