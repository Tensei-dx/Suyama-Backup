<template>
    <div class="card mb-5">
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0"><img src="img/guest_dashboard/monitor.png"
                     style="width: 30px; margin-right: 10px" />{{ this.deviceInfo.APPLIANCE_NAME }}</h3>
        </div>
        <div class="p-3 text-black text-center">
            <div class="row">
                <!-- POWER -->
                <div class="col-8">
                    <div class="row">
                        <div class="col-4">
                            <button type="button" :disabled="isEmpty" class="fa fa-power-off btn-push pl-3 pr-3 mt-3"
                                    @click="sendSignals(buttons.power)">
                                <i class="" aria-hidden="true"></i>
                            </button>
                            <small class="d-block mt-1">{{ $t('mobile.control.tv.power') }}</small>
                        </div>
                        <!-- SOURCE -->
                        <div class="col-4">
                            <button type="button" :disabled="isEmpty" class="fa fa-sign-in button-source mt-3"
                                    @click="sendSignals(buttons.source)">
                            </button>
                            <small class="d-block">{{ $t('mobile.control.tv.source') }}</small>
                        </div>
                        <!-- MUTE -->
                        <div class="col-4">
                            <button type="button" :disabled="isEmpty" class="fa fa-volume-off button-mute mt-3"
                                    @click="sendSignals(buttons.mute)">
                            </button>
                            <small class="d-block">{{ $t('mobile.control.tv.mute') }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <!-- VOLUME -->
                        <div class="col-5 col-pad">
                            <div class="col-pad-l">
                                <button class="fa fa-plus button-tv-vol" :disabled="isEmpty"
                                        @click="sendSignals(buttons.volume_up)"></button>
                                <small class="d-block text-center">{{ $t('mobile.control.tv.volume') }}</small>
                                <button class="fa fa-minus button-tv-vol" :disabled="isEmpty"
                                        @click="sendSignals(buttons.volume_down)"></button>
                            </div>
                        </div>
                        <!-- CHANNEL -->
                        <div class="col-7">
                            <div class="btn-group-vertical text-center">

                                <button class="button-tv-cha fa fa-chevron-up hotel-white-color-text"
                                        :disabled="isEmpty" @click="sendSignals(buttons.channel_up)"></button>
                                <small class="d-block">{{ $t('mobile.control.tv.channel') }}</small>
                                <button class="button-tv-cha fa fa-chevron-down hotel-white-color-text"
                                        :disabled="isEmpty" @click="sendSignals(buttons.channel_down)">
                                </button>
                            </div>
                        </div>
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
            signals: [],
            buttons: {
                power: null,
                source: null,
                mute: null,
                volume_up: null,
                volume_down: null,
                channel_up: null,
                channel_down: null,
            },

            device_type: 'ir_remote',
            remote_data: '',
            commandStatus: null,
            hd: '',
            tv: {
                status: false,
                power: false,
            },
            isProcessingData: false,
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
            this.buttons.power = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_power')
            this.buttons.source = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_source')
            this.buttons.mute = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_mute')
            this.buttons.volume_up = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_volume_up')
            this.buttons.volume_down = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_volume_down')
            this.buttons.channel_up = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_next_channel')
            this.buttons.channel_down = this.signals.find(signal => signal.SIGNAL_LABEL === 'TV_previous_channel')
        },

        // create function when clicking buttons

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
.col-mute {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 33.33333333%;
    flex: 0 0 33.33333333%;
    max-width: 25%;
}
.custom-ac-btn-width {
    width: 60px;
}
.temp-card {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.75rem 0.5rem !important;
}

.col-pad {
    padding: 0 3px !important;
    text-align: center !important;
}
.text-center {
    align-items: center !important;
}
.col-4 {
    padding: 5px !important;
}
</style>
