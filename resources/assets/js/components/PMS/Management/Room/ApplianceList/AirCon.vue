<template>
    <div class="card border-0">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col-md-4 bg-white text-black text-center">
                    <img src="/img/ManagementDashboard/realdevices/AC.png" class="img-fluid" alt="tv-remote-control">
                    <button type="button" class="btn btn-secondary btn-lg my-2" disabled>{{ appliance.APPLIANCE_NAME }}</button>
                </div>
                <div class="col pl-4 text-black text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col-12 w-100 mb-2">
                            <button @click="command('power')" type="button" class="btn btn-lg hotel-base-color">
                                <span><i class="fa fa-power-off" aria-hidden="true"/></span>
                            </button>
                            <small class="d-block">POWER</small>
                        </div>
                        <div class="col-6 pl-4 pr-3">
                            <button @click="command('ac_cool')" type="button" class="btn btn-secondary btn-block">COOL</button>
                        </div>
                        <div class="col-6 pl-3 pr-4">
                            <button @click="command('ac_warm')" type="button" class="btn btn-secondary btn-block">WARM</button>
                        </div>
                        <div class="w-100 mb-2"/>
                        <div class="col-6 pl-4 pr-3">
                            <button @click="command('ac_dry')" type="button" class="btn btn-secondary btn-block">DRY</button>
                        </div>
                        <div class="col-6 pl-3 pr-4">
                            <button @click="command('ac_dehum')" type="button" class="btn btn-secondary btn-block">DEHUM</button>
                        </div>
                        <div class="w-100 mb-2"/>
                        <div class="col">
                            <div class="btn-group-vertical">
                                <button @click="command('tv_volume_up')" type="button" class="btn btn-secondary">
                                    <span><i class="fa fa-plus" aria-hidden="true"/></span>
                                </button>
                                <button @click="command('tv_volume_down')" type="button" class="btn btn-secondary">
                                    <span><i class="fa fa-minus" aria-hidden="true"/></span>
                                </button>
                            </div>
                            <small class="d-block">VOLUME</small>
                        </div>
                        <div class="col">
                            <div class="btn-group-vertical">
                                <button @click="command('tv_channel_up')" type="button" class="btn btn-secondary">
                                    <span><i class="fa fa-chevron-up" aria-hidden="true"/></span>
                                </button>
                                <button @click="command('tv_volume_down')" type="button" class="btn btn-secondary">
                                    <span><i class="fa fa-chevron-down" aria-hidden="true"/></span>
                                </button>
                            </div>
                            <small class="d-block">CHANNEL</small>
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
        appliance: {
            type: Object,
            required: true
        }
    },

    methods: {
        /**
         *
         */
        command(operation) {
            const signal = this.appliance.nature_remo_signals.find(i => i.SIGNAL_NAME === operation)
            if (!signal) return 'signal not found'
            this.$emit('command', signal)
            return 'signal request sent'
        }
    },
}
</script>

<style>

</style>
