<template>
    <div class="card pointer" @click="$bus.emit('onSelectDeviceItem', device)">
        <div class="row no-gutters align-items-center device-content">
            <div class="col-2 h-100 py-1 text-center hotel-base-color rounded device-icon">
                <span class="d-block pt-2 pb-2">
                    <img src="imgs/devices/device-icon-door-sensor.png" class="door-sensor">
                </span>
            </div>
            <div class="col-5 h-100 text-center">
                <span class="d-block text-black border-right border-black" aria-hidden="true">
                    {{ $t(device.DATA.status === 1 ? 'management.roomOperation.vacant' : 'management.roomOperation.occupied') }}
                </span>
            </div>
            <div class="col-5 h-100 text-center text-black">
                <!-- <span class="d-block" aria-hidden="true" v-if="$i18n.locale === 'ja'">
                    {{ $t('management.roomOperation.toilet') }}
                </span>
                <span class="d-block" aria-hidden="true" v-if="$i18n.locale === 'en'">
                    {{ $t('management.roomOperation.rest') + ' ' + $t('management.roomOperation.room') }}
                </span> -->
                <span class="d-block" aria-hidden="true">
                    {{ $t('management.roomOperation.shower') + ' ' + $t('management.roomOperation.room') }}
                </span>
                <!-- <span class="d-block" aria-hidden="true" v-if="$i18n.locale === 'en'">
                    {{ $t('management.roomOperation.room') }}
                </span> -->
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
.door-sensor {
    max-width: 2rem;
    height: auto;
}
.device-content {
    font-size: 18px !important;
}
</style>
