<template>
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">
                {{ $t('management.roomInfo.peopleCountCamera') }}
            </h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/camera.jpg" style="max-width:100%; margin-top:10px;"
                     class="img-fluid">
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.deviceName') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ device.DEVICE_NAME }}
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        {{ $t('management.roomInfo.people') }}
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        {{ people_count }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        device: '',
    },

    data() {
        return {
            isLoading: false,
            people_count: 0,
        }
    },

    created() {
        this.getPoepleCounterData()
    },

    methods: {
        /**
         *
         *
         */
        getPoepleCounterData() {
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
        Echo.channel('newdevice-event').listen('NewDeviceDataEvent', value => {
            this.getPoepleCounterData()
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
