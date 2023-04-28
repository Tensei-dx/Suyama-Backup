<template>
    <div class="card pointer" @click="$bus.emit('onSelectDeviceItem', device)">
        <div class="row no-gutters align-items-center device-content">
            <div class="col-2 h-100 py-1 text-center hotel-base-color rounded device-icon">
                <span class="d-block pt-2 pb-2">
                    <img src="imgs/devices/device-icon-camera.png" class="camera">
                </span>
            </div>
            <div class="col-5 h-100 text-center">
                <span class="d-block text-black border-right border-black" aria-hidden="true">
                    <span v-if="isLoading">
                        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
                    </span>
                    <span class="v-else">{{ people_count }}</span>
                </span>
            </div>
            <div class="col-5 h-100 text-center">
                <span class="d-block text-black" aria-hidden="true">
                    {{ $t('management.roomOperation.people') }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        device: Object,
    },

    data() {
        return {
            isLoading: false,
            pc_cameras: [],
            people_count: 0,
        }
    },

    created() {
        this.getPeopleCounterData()
    },

    methods: {
        /**
         *
         */
        getPeopleCounterData() {
            this.isLoading = true
            axios
                .get(`api/axis/people_counts/${this.device.DEVICE_ID}`)
                .then(response => {
                    this.people_count = response.data.people_count
                })
                .then(() => {
                    this.isLoading = false
                })
        },
    },

    mounted() {
        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            this.getPeopleCounterData()
        })
    },
}
</script>

<style>
.camera {
    max-width: 2rem;
    height: auto;
}
.device-content {
    font-size: 18px !important;
}
</style>
