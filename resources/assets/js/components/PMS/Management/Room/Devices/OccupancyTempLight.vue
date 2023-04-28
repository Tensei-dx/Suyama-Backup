<template>
    <div class="card pointer" @click="$bus.emit('onSelectDeviceItem', device)">
        <!-- <div class="row no-gutters align-items-center device-content">
            <div class="col-3 py-1 text-center hotel-base-color rounded-top device-icon">
                <span class="d-block pt-2 pb-2">
                    <img src="imgs/devices/device-icon-occupancy.png" class="occupancy-temp-light">
                </span>
            </div>
            <div class="col-5 text-center text-black align-self-center border-right border-black">
                <span class="d-block custom-font-size-xs text-black" aria-hidden="true">
                    {{ $t(device.DATA.occupancy === 1 ? 'management.roomOperation.occupied' : 'management.roomOperation.vacant') }}
                </span>
            </div>
            <div class="col-4 text-center text-black">
                <span class="d-block custom-font-size-xs" aria-hidden="true">
                    {{ $t('management.roomOperation.shower') }}
                </span>
                <span class="d-block custom-font-size-xs" aria-hidden="true">
                    {{ $t('management.roomOperation.room') }}
                </span>
            </div>
            <div class="col-3 py-1 text-center hotel-base-color device-icon">
                <span class="d-block pt-2 pb-2">
                    <i class="fa fa-thermometer-full fa-lg text-black fa-2x" aria-hidden="true"></i>
                </span>
            </div>
            <div class="col-5 text-center align-self-center border-right border-black">
                <span class="d-block custom-font-size-xs text-black"
                      aria-hidden="true">{{ device.DATA.temperature }}</span>
            </div>
            <div class="col-4 text-center text-black">
                <span class="d-block custom-font-size-xs" aria-hidden="true">ºC</span>
            </div>
            <div class="col-3 py-1 text-center hotel-base-color rounded-bottom device-icon">
                <span class="d-block pt-2 pb-2">
                    <img src="imgs/devices/device-icon-light-intensity.png" class="occupancy-temp-light">
                </span>
            </div>
            <div class="col-5 text-center align-self-center border-right border-black">
                <span class="d-block custom-font-size-xs text-black" aria-hidden="true">{{ device.DATA.lux }}</span>
            </div>
            <div class="col-4 text-center text-black">
                <span class="d-block custom-font-size-xs" aria-hidden="true">LUX</span>
            </div>

        </div> -->
        <div class="row no-gutters align-items-center device-content">
            <div class="col-12">
                <div class="row w-100 px-0 mx-0">
                    <div class="col-2 py-1 text-center hotel-base-color rounded-top device-icon">
                        <span class="d-block pt-2 pb-2">
                            <img src="imgs/devices/device-icon-occupancy.png" class="occupancy-temp-light">
                        </span>
                    </div>
                    <div class="col-5 text-center text-black align-self-center border-right border-black">
                        <span class="d-block text-black" aria-hidden="true">
                            {{ $t(device.DATA.occupancy === 1 ? 'management.roomOperation.occupied' : 'management.roomOperation.vacant') }}
                        </span>
                    </div>
                    <div class="col-5 text-center text-black align-self-center">
                        <!-- <span class="d-block" aria-hidden="true">
                            {{ $t('management.roomOperation.shower') + ' ' + $t('management.roomOperation.room') }}
                        </span> -->
                        <span class="d-block" aria-hidden="true" v-if="$i18n.locale === 'en'">
                            {{ $t('management.roomOperation.rest') + ' ' + $t('management.roomOperation.room') }}
                        </span>
                        <span class="d-block" aria-hidden="true" v-else>
                            {{ $t('management.roomOperation.toilet') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row w-100 px-0 mx-0">
                    <div class="col-2 py-1 text-center hotel-base-color device-icon">
                        <span class="d-block pt-2 pb-2">
                            <i class="fa fa-thermometer-full fa-lg text-black fa-2x" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="col-5 text-center align-self-center border-right border-black">
                        <span class="d-block text-black"
                              aria-hidden="true">{{ Number(device.DATA.temperature).toFixed(1) }}</span>
                    </div>
                    <div class="col-5 text-center text-black align-self-center">
                        <span class="d-block" aria-hidden="true">ºC</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row w-100 px-0 mx-0">
                    <div class="col-2 py-1 text-center hotel-base-color rounded-bottom device-icon">
                        <span class="d-block pt-2 pb-2">
                            <img src="imgs/devices/device-icon-light-intensity.png" class="occupancy-temp-light">
                        </span>
                    </div>
                    <div class="col-5 text-center align-self-center border-right border-black">
                        <span class="d-block text-black"
                              aria-hidden="true">{{ Number(device.DATA.lux).toFixed(1) }}</span>
                    </div>
                    <div class="col-5 text-center text-black align-self-center">
                        <span class="d-block" aria-hidden="true">LUX</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        device: Object,
    },
}
</script>

<style>
.occupancy-temp-light {
    max-width: 2rem;
    height: auto;
}
.device-content {
    font-size: 18px !important;
}
</style>
