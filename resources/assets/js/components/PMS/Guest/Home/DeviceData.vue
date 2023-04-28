<template>
    <div class="row hotel-white-color-text pb-3 d-flex align-items-center h-100">
        <!-- TEMPERATURE -->
        <div class="col-xl-12 col-6">
            <div class="card bg-transparent border-gray pt-1 pb-1">
                <span class="font-weight-bold" v-text="$t('mobile.displaydata.temp.temp')" />
                <div>
                    <span style="font-size: 1.75rem;" :class="{'text-danger': temperatureState.empty}"
                          v-text="temperatureState.value" />
                    <div v-if="!temperatureState.empty" class="row align-items-center">
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            temperatureState.level === 'low' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">COLD</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            temperatureState.level === 'normal' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">NORMAL</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            temperatureState.level === 'high' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">HOT</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- HUMIDITY -->
        <div class="col-xl-12 col-6">
            <div class="card bg-transparent border-gray pt-1 pb-1">
                <span class="font-weight-bold" v-text="$t('mobile.displaydata.hum.hum')" />
                <div>
                    <span style="font-size: 1.75rem;" :class="{'text-danger': humidityState.empty}"
                          v-text="humidityState.value" />
                    <div v-if="!humidityState.empty" class="row align-items-center">
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            humidityState.level === 'low' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">DRY</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            humidityState.level === 'normal' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">NORMAL</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            humidityState.level === 'high' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">HUMID</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CO2 -->
        <div class="col-xl-12 col-6">
            <div class="card bg-transparent border-gray pt-1 pb-1">
                <span class="font-weight-bold" v-text="$t('mobile.displaydata.co2.co2')" />
                <div>
                    <span style="font-size: 1.75rem;" :class="{'text-danger': carbonDioxideState.empty}"
                          v-text="carbonDioxideState.value" />
                    <div v-if="!carbonDioxideState.empty" class="row align-items-center">
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            carbonDioxideState.level === 'low' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">LOW</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            carbonDioxideState.level === 'normal' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">NORMAL</div>
                        <div :class="[
                            'font-weight-bold col-xl-4 col-12',
                            carbonDioxideState.level === 'high' ? 'hotel-accent-color-text' : 'hotel-white-color-text'
                            ]">HIGH</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const NO_DATA = 'NO DATA'
const [TEMP_LOW, TEMP_HIGH] = [18, 30]
const [HUM_LOW, HUM_HIGH] = [30, 50]
const [CO2_LOW, CO2_HIGH] = [500, 1500]

export default {
    props: {
        room_id: [Number, null],
    },

    data() {
        return {
            device: null,
        }
    },

    computed: {
        isDeviceFalsy() {
            return !this.device
        },
        temperatureState() {
            const state = { empty: true, value: NO_DATA, level: null }
            if (this.isDeviceFalsy) return state
            state.empty = false
            const data = this.device['DATA']['temperature']
            if (data < TEMP_LOW) {
                state.level = 'low'
            } else if (TEMP_LOW <= data && data < TEMP_HIGH) {
                state.level = 'normal'
            } else if (TEMP_HIGH <= data) {
                state.level = 'high'
            }
            state.value = this.parseData(data, 1) + ' °C'
            return state
        },

        humidityState() {
            const state = { empty: true, value: NO_DATA, level: null }
            if (this.isDeviceFalsy) return state
            state.empty = false
            const data = this.device['DATA']['humidity']
            if (data < HUM_LOW) {
                state.level = 'low'
            } else if (HUM_LOW <= data && data < HUM_HIGH) {
                state.level = 'normal'
            } else if (HUM_HIGH <= data) {
                state.level = 'high'
            }
            state.value = this.parseData(data, 1) + ' %'
            return state
        },

        carbonDioxideState() {
            const state = { empty: true, value: NO_DATA, level: null }
            if (this.isDeviceFalsy) return state
            state.empty = false
            const data = this.device['DATA']['co2']
            if (data < CO2_LOW) {
                state.level = 'low'
            } else if (CO2_LOW <= data && data < CO2_HIGH) {
                state.level = 'normal'
            } else if (CO2_HIGH <= data) {
                state.level = 'high'
            }
            state.value = this.parseData(data, 1) + ' PPM'
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

        /**
         * Get room devices.
         *
         * @returns {void}
         * @throws error
         */
        async getRoomDevices() {
            try {
                const { data: devices } = await axios.get(`client/devices/${this.room_id}`)
                this.device = devices.find(device => device.DEVICE_TYPE === 'co2_temp_humid')
            } catch (error) {
                console.error(error)
            }
        },
    },

    created() {
        this.getRoomDevices()
    },

    mounted() {
        Echo.channel('device-command').listen('DeviceCommandEvent', value => {
            this.getRoomDevices()
        })
    },
}
</script>
