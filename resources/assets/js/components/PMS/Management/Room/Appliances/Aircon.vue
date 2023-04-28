<template>
    <div class="card mb-4">
        <div class="rounded hotel-base-color w-100 py-2 align-items-center">
            <!-- <h3 class="text-black text-center my-0">
                Aircon
            </h3> -->

            <h3 class="text-black text-center my-0"><img src="img/guest_dashboard/air-con.png"
                     style="width: 30px; margin-right: 10px" />{{ this.deviceInfo.APPLIANCE_NAME }}</h3>
        </div>
        <div class="p-3">
            <div class="row align-items-center justify-content-center">
                <div class="col-5 ">
                    <div class="align-items-center text-center">
                        <!-- OFF Button -->
                        <button type="button" :disabled="isEmpty" class="button-mode bm-off"
                                @click="sendSignals(buttons.power)">{{ $t('mobile.control.aircon.off') }}</button>
                    </div>
                </div>
                <div class="col-7">
                    <div class="row">
                        <!-- MODE -->
                        <div class="col-12 text-center">
                            <div class="row no-gutters justify-content-around">
                                <div class="col-6 mb-2">
                                    <button type="button" :disabled="isEmpty" class="button-mode bm-cool"
                                            @click="sendSignals(buttons.cool)">{{ $t('mobile.control.aircon.cool') }}</button>
                                </div>
                                <div class="col-6 mb-2">
                                    <button type="button" :disabled="isEmpty" class="button-mode bm-dry"
                                            @click="sendSignals(buttons.dry)">{{ $t('mobile.control.aircon.dry') }}</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" :disabled="isEmpty" class="button-mode bm-warm"
                                            @click="sendSignals(buttons.warm)">{{ $t('mobile.control.aircon.warm') }}</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" :disabled="isEmpty" class="button-mode bm-auto"
                                            @click="sendSignals(buttons.auto)">{{ $t('mobile.control.aircon.auto') }}</button>
                                </div>
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
                dry: null,
                warm: null,
                cool: null,
                auto: null,
            },

            device_type: 'ir_remote',
            remote_data: '',
            commandStatus: null,
            aircon: {
                status: false,
                power: true,
            },
            isProcessingData: false,
            status: '',
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
            this.buttons.power = this.signals.find(signal => signal.SIGNAL_LABEL === 'power-off button')
            this.buttons.dry = this.signals.find(signal => signal.SIGNAL_LABEL === 'dry mode')
            this.buttons.cool = this.signals.find(signal => signal.SIGNAL_LABEL === 'cool mode')
            this.buttons.warm = this.signals.find(signal => signal.SIGNAL_LABEL === 'warm mode')
            this.buttons.auto = this.signals.find(signal => signal.SIGNAL_LABEL === 'auto mode')
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
.bm-off {
    color: crimson;
}
</style>
