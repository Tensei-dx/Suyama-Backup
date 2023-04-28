<!--CREATED: TP Chris SPRINT_03 TASK110 20210818 -->
<template>
    <div class="card">
        <div class="row no-gutters">
            <div class="col-xl-5 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="img/guest_dashboard/TOILETICON.png" style="width: 50px;" />
                    <small class="d-block mt-2 text-align:center">{{ $t('mobile.control.restroom.restroom') }}</small>
                </div>
            </div>
            <div class="col p-2 align-self-center ">
                <div class="row d-flex align-items-center justify-content-center">
                    <img :src="restroomStatus.img" class="align-items-center" style="width: 50px;" />
                    <div class="col-12 mt-2">
                        <div class="row d-flex align-items-center justify-content-center">
                            <div class="col text-center">
                                <h5><span class="badge badge-secondary p-2">
                                        {{ $t(restroomStatus.message) }}
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
            occupancy_temp_light: undefined,
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
                .get('client/devices/' + this.room_id, {
                    params: {
                        DEVICE_TYPE: 'occupancy_temp_light',
                    },
                })
                .then(response => {
                    if ((response.status >= 200) & (response.status < 300)) {
                        this.occupancy_temp_light = response.data.find(i => i.DEVICE_TYPE === 'occupancy_temp_light')
                    } else {
                        throw new Error(response.data)
                    }
                })
                .catch(error => this.errors.push(error))
        },

        /**
         * @name updateData
         * @description Update the data of occupancy temp light sensor
         */
        updateData(processedData) {
            if (processedData.DEVICE_ID === this.occupancy_temp_light.DEVICE_ID) {
                this.occupancy_temp_light.DATA = processedData.DATA
            }
        },
    },

    computed: {
        /**
         * @name restroomStatus
         * @description Return the status of the restroom using the occupancy temp light sensor
         *
         * @returns {object}
         */
        restroomStatus: function () {
            // Restroom status will be 0: occupied, 1: vacant
            if (!this.occupancy_temp_light) {
                return {
                    occupied: false,
                    message: 'mobile.control.showerstatus.nosensor',
                    img: '/img/guest_dashboard/door_closed.png',
                }
            }
            // If occupancy=1, it will display that its occupied.
            if (this.occupancy_temp_light.DATA['occupancy']) {
                return {
                    occupied: true,
                    message: 'mobile.control.showerstatus.occupied',
                    img: '/img/guest_dashboard/door_closed.png',
                }
                // If occupancy=0, it will display that its vacant.
            } else {
                return {
                    occupied: false,
                    message: 'mobile.control.showerstatus.empty',
                    img: '/img/guest_dashboard/door_open.png',
                }
            }
        },
    },
}
</script>
