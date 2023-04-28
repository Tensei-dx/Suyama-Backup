<template>
    <div class="card">
        <div class="row no-gutters">
            <!-- base -->
            <div class="col-xl-2 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="img/guest_dashboard/light-bulb.png" style="width: 50px;" />
                    <small class="d-block mt-2">{{ this.deviceInfo.APPLIANCE_NAME }}</small>
                </div>
            </div>
            <!-- BODY -->
            <div class="col p-1 align-self-center">
                <div class="row align-self-center">
                    <div class="col-8 px-0 light-panel">
                        <div class="row no-gutters justify-content-around">
                            <div class="col-7 pl-3 pt-4">
                                <div class="row no-gutters justify-content-around">
                                    <div class="col-6 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-cool"
                                                @click="sendSignals(buttons.light.on)">{{ $t('mobile.control.lights.on') }}</button>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode text-white"
                                                @click="sendSignals(buttons.light.warm)">{{ $t('mobile.control.lights.warm') }}</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" :disabled="isEmpty" class="button-mode bm-off"
                                                @click="sendSignals(buttons.light.off)">{{ $t('mobile.control.lights.off') }}</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" :disabled="isEmpty" class="button-mode text-white"
                                                @click="sendSignals(buttons.light.cool)">{{ $t('mobile.control.lights.cool') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row no-gutters justify-content-around">
                                    <div class="col-6 text-center">
                                        <div class="btn-group-vertical">
                                            <button class="button-tv-cha fa fa-chevron-up hotel-white-color-text"
                                                    :disabled="isEmpty" @click="sendSignals(buttons.light.up)"></button>
                                            <small
                                                   class="d-block  text-center w-100">{{ $t('mobile.control.lights.up') }}</small>
                                            <button class="button-tv-cha fa fa-chevron-down hotel-white-color-text"
                                                    :disabled="isEmpty" @click="sendSignals(buttons.light.down)">
                                            </button>
                                            <small
                                                   class="d-block text-center w-100">{{ $t('mobile.control.lights.down') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-6 pt-4 mt-2">
                                        <div class="btn-group-vertical">
                                            <button class="button-img hotel-white-color-text" :disabled="isEmpty"
                                                    @click="sendSignals(buttons.light.night)">
                                                <img src="img/ManagementDashboard/icon/moon.png"
                                                     class="hotel-white-color-text" style="max-width: 1.4rem" />
                                            </button>
                                            <small
                                                   class="d-block pt-1 text-center w-100">{{ $t('mobile.control.lights.night') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="label">
                            {{$t('mobile.control.lights.light')}}
                        </span>
                    </div>
                    <div class="col-4 px-0 pl-2">
                        <div class="row px-0 w-100">
                            <div class="col-3">
                                <div class="btn-group-vertical">
                                    <button class="button-img hotel-white-color-text" :disabled="isEmpty"
                                            @click="sendSignals(buttons.wind.on)">
                                        <img src="img/ManagementDashboard/icon/fan.png" class="hotel-white-color-text"
                                             style="max-width: 1.4rem" />
                                    </button>
                                    <!-- https://icons8.com/icons/set/fan-white--white -->
                                    <small
                                           class="d-block pt-1 text-center w-100">{{ $t('mobile.control.lights.on') }}</small>
                                    <button class="button-img hotel-white-color-text" :disabled="isEmpty"
                                            @click="sendSignals(buttons.wind.off)">
                                        <img src="img/ManagementDashboard/icon/fan.png" class="hotel-white-color-text"
                                             style="max-width: 1.4rem" />
                                    </button>
                                    <!-- https://icons8.com/icons/set/fan-white--white -->
                                    <small
                                           class="d-block pt-1 text-center w-100">{{ $t('mobile.control.lights.off') }}</small>
                                </div>
                            </div>
                            <div class="col-9 px-0 pl-4">
                                <div class="row no-gutters justify-content-around px-0">
                                    <div class="col-12 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode text-white"
                                                @click="sendSignals(buttons.wind.min)">{{ $t('mobile.control.lights.min') }}</button>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode text-white"
                                                @click="sendSignals(buttons.wind.mid)">{{ $t('mobile.control.lights.mid') }}</button>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <button type="button" :disabled="isEmpty" class="button-mode text-white"
                                                @click="sendSignals(buttons.wind.max)">{{ $t('mobile.control.lights.max') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="label">
                            {{$t('mobile.control.lights.wind')}}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceInfo: Object,
    },

    data() {
        return {
            ws: {
                ws3: {
                    device_id: '',
                    device_type: '',
                    gateway_id: '',
                    data: {
                        0: { device_name: '', status: '' },
                        1: { device_name: '', status: '' },
                        2: { device_name: '', status: '' },
                    },
                },
                ws2: {
                    device_id: '',
                    device_type: '',
                    gateway_id: '',
                    data: {
                        0: { device_name: '', status: '' },
                        1: { device_name: '', status: '' },
                    },
                },
            },
            switch_all_status: true,
            signals: [],
            buttons: {
                light: {
                    on: null,
                    off: null,
                    warm: null,
                    cool: null,
                    up: null,
                    down: null,
                    night: null,
                },
                wind: {
                    on: null,
                    off: null,
                    min: null,
                    mid: null,
                    max: null,
                },
            },
        }
    },
    methods: {
        getSignals() {
            axios
                .get('nature_remo_appliances/' + this.deviceInfo.APPLIANCE_ID + '/signals')
                .then(response => {
                    this.signals = response.data
                    this.assignSignals()
                })
                .catch(error => {
                    console.log(error)
                })
        },

        assignSignals() {
            this.buttons.light.on = this.signals.find(signal => signal.SIGNAL_LABEL === 'LIGHT_ON')
            this.buttons.light.off = this.signals.find(signal => signal.SIGNAL_LABEL === 'LIGHT_OFF')
            this.buttons.light.warm = this.signals.find(signal => signal.SIGNAL_LABEL === 'WARM')
            this.buttons.light.cool = this.signals.find(signal => signal.SIGNAL_LABEL === 'COOL')
            this.buttons.light.up = this.signals.find(signal => signal.SIGNAL_LABEL === 'UP')
            this.buttons.light.down = this.signals.find(signal => signal.SIGNAL_LABEL === 'DOWN')
            this.buttons.light.night = this.signals.find(signal => signal.SIGNAL_LABEL === 'NIGHT')
            this.buttons.wind.on = this.signals.find(signal => signal.SIGNAL_LABEL === 'WIND_ON')
            this.buttons.wind.off = this.signals.find(signal => signal.SIGNAL_LABEL === 'WIND_OFF')
            this.buttons.wind.min = this.signals.find(signal => signal.SIGNAL_LABEL === 'MIN')
            this.buttons.wind.mid = this.signals.find(signal => signal.SIGNAL_LABEL === 'MID')
            this.buttons.wind.max = this.signals.find(signal => signal.SIGNAL_LABEL === 'MAX')
        },

        sendSignals(signal) {
            axios.post('nature_remo_signals/' + signal.SIGNAL_ID + '/send').catch(error => {
                console.log(error)
            })
        },
    },
    computed: {
        isEmpty: function () {
            return Object.keys(this.deviceInfo).length === 0
        },
    },
    watch: {
        deviceInfo: {
            deep: true,
            handler: function () {
                this.getSignals()
            },
        },
    },
}
</script>

<style scoped>
.bm-off {
    color: crimson;
}
.button-fan {
    padding: 9px 9px;
    outline: none;
    background-color: #263033;
    border: none;
    border-radius: 5px;
    box-shadow: 0 4px #d3d3d3;
}
.button-img {
    padding: 9px 9px;
    outline: none;
    background-color: #263033;
    border: none;
    border-radius: 5px;
    box-shadow: 0 4px #d3d3d3;
}
.button-img:active {
    background-color: #263033;
    box-shadow: 0 1px #d3d3d3;
    transform: translateY(4px);
}
.light-panel {
    border-right: 1px solid #add8e6;
}
.label {
    font-weight: bold;
    bottom: 0;
}
</style>
