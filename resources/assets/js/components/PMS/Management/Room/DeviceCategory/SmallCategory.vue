<template>
    <div class="row no-gutters justify-content-start align-items-center">
        <div v-for="(device, index) in smallDevices" :key="index" class="col-2 ml-1 device-icon">
            <component :is="getComponent(device)" :device="device">
            </component>
        </div>
        <div v-if="roomAppliances.length > 0" class="col-2 ml-1 device-icon">
            <div class="card pointer" @click="$bus.emit('onSelectAppliances', roomAppliances)">
                <div class="no-gutters align-items-center">
                    <div class="h-100 py-1 text-center hotel-base-color rounded-left">
                        <span class="d-block pt-2 pb-2">
                            <i class="fa fa-home fa-lg text-black fa-2x" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RemoteLock from '../Devices/RemoteLock.vue'

export default {
    components: {
        RemoteLock,
    },

    props: {
        smallDevices: Array,
        roomAppliances: Array,
    },

    data() {
        return {
            types: [{ type: 'remote_lock', component: 'RemoteLock' }],
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
