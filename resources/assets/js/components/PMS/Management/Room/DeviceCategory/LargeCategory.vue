<template>
    <div class="row no-gutters justify-content-between align-items-center">
        <div v-for="(device, index) in largeDevices" :key="index" class="col-12 p-1">
            <component :is="getComponent(device)" :device="device">
            </component>
        </div>
    </div>
</template>

<script>
import Co2TempHumid from '../Devices/Co2TempHumid.vue'
import OccupancyTempLight from '../Devices/OccupancyTempLight.vue'

export default {
    components: {
        Co2TempHumid,
        OccupancyTempLight,
    },

    props: {
        largeDevices: Array,
    },

    data() {
        return {
            types: [
                { type: 'co2_temp_humid', component: 'Co2TempHumid' },
                { type: 'occupancy_temp_light', component: 'OccupancyTempLight' },
            ],
        }
    },

    methods: {
        getComponent(device) {
            return this.types.find(type => type.type === device.DEVICE_TYPE).component
        },
    },
}
</script>

<style>
</style>
