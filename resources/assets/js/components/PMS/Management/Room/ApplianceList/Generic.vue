<template>
    <div class="card border-0">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col-md-4 bg-white text-black text-center">
                    <img src="/img/ManagementDashboard/realdevices/AC.png" alt="generic ir device" class="img-fluid"/>
                    <button type="button" class="btn btn-lg hotel-base-color" disabled>{{ appliance.APPLIANCE_NAME }}</button>
                </div>
                <div class="col pl-4 text-black text-center">
                    <button @click="command(operation)" type="button" class="btn btn-secondary my-1 mx-2" v-for="operation in appliance.nature_remo_signals" :key="operation.SIGNAL_ID">
                        {{ operation.SIGNAL_NAME.toUpperCase().replaceAll('_', ' ') }}
                    </button>
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
            this.$emit('command', operation)
        }
    }
}
</script>
