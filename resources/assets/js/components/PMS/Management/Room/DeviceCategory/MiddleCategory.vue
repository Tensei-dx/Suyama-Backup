<template>
    <div class="row no-gutters justify-content-between align-items-center mt-1">
        <div v-for="(device, index) in middleDevices" :key="index" class="col-12 p-1">
            <component :is="getComponent(device)" :device="device">
            </component>
        </div>
    </div>
</template>

<script>
import Camera from '../Devices/Camera.vue'
import WindowDoorSensor from '../Devices/WindowDoorSensor.vue'

export default {
    components: {
        Camera,
        WindowDoorSensor,
    },

    props: {
        middleDevices: Array,
    },

    data() {
        return {
            types: [
                { type: 'camera', component: 'Camera' },
                { type: 'window_door_sensor', component: 'WindowDoorSensor' },
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
