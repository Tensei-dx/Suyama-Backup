<template>
    <div class="card pointer w-100" @click="$bus.emit('onSelectDeviceItem', device)">
        <div class="row no-gutters align-items-center device-content">
            <div class="col-12">
                <div class="row w-100 px-0 mx-0">
                    <div class="col-2 py-1 text-center hotel-base-color rounded-top device-icon">
                        <span class="d-block pt-2 pb-2">
                            <img src="imgs/devices/device-icon-co2.png" class="co2-temp-humid" />
                        </span>
                    </div>
                    <div class="col-5 text-center align-self-center border-right border-black">
                        <span class="d-block text-black" aria-hidden="true">
                            <i :class="['fa fa-circle fa-1x', carbonDioxideState.color]" />
                            {{ carbonDioxideState.value }}
                        </span>
                    </div>
                    <div class=" col-5 text-center text-black align-self-center">
                        <span class="d-block" aria-hidden="true">PPM</span>
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
                        <span class="d-block text-black" aria-hidden="true">
                            {{ temperatureState.value }}
                        </span>
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
                            <img src="imgs/devices/device-icon-humidity.png" class="co2-temp-humid">
                        </span>
                    </div>
                    <div class="col-5 text-center align-self-center border-right border-black">
                        <span class="d-block text-black" aria-hidden="true">
                            {{ humidityState.value }}
                        </span>
                    </div>
                    <div class="col-5 text-center text-black align-self-center">
                        <span class="d-block" aria-hidden="true">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// text displayed when a data cannot be fetched
const NO_DATA = 'NO DATA'

export default {
    props: {
        device: Object,
    },

    computed: {
        isDeviceEmpty() {
            return !this.device || !this.device['DATA']
        },
        temperatureState() {
            const state = { value: NO_DATA }
            if (this.isDeviceEmpty) return state
            const data = this.device['DATA']['temperature']
            state.value = this.parseData(data, 1)
            return state
        },
        humidityState() {
            const state = { value: NO_DATA }
            if (this.isDeviceEmpty) return state
            const data = this.device['DATA']['humidity']
            state.value = this.parseData(data, 1)
            return state
        },
        carbonDioxideState() {
            const state = { value: NO_DATA, color: 'text-secondary' }
            if (this.isDeviceEmpty) return state
            const data = this.device['DATA']['co2']
            if (data <= 800) {
                state.color = 'text-success'
            } else if (800 < data && data <= 1000) {
                state.color = 'text-primary'
            } else if (1000 < data && data <= 2500) {
                state.color = 'text-warning'
            } else if (2500 < data) {
                state.color = 'text-danger'
            }
            state.value = this.parseData(data, 1)
            return state
        },
    },

    methods: {
        /**
         * Display appropriate representation of the data.
         *
         * @param   {Number}   value
         * @param   {Number}   decimals
         * @returns {String}
         */
        parseData(value, decimals = 0) {
            return Number(value).toFixed(decimals)
        },
    },
}
</script>

<style>
.co2-temp-humid {
    max-width: 2rem;
    height: auto;
}
.device-content {
    font-size: 18px !important;
}
</style>
