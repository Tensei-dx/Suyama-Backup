<template>
    <!-- <div>
        <div v-for="appliance in appliances" :key="appliance.APPLIANCE_ID" class="card mb-3">
            <Television   v-if="appliance.APPLIANCE_TYPE === 'TV'"  :appliance="appliance" @command="sendCommand"/>
            <AirCon  v-else-if="appliance.APPLIANCE_TYPE === 'AC'"  :appliance="appliance" @command="sendCommand"/>
            <Generic    v-else                                      :appliance="appliance" @command="sendCommand"/>
        </div>
    </div> -->
    <div>
        <div class="rounded hotel-base-color w-100 py-2">
            <h3 class="text-black text-center my-0">IR Remote NR 2</h3>
        </div>
        <div class="row px-3 py-4">
            <div class="col-4 device-image align-middle">
                <img src="/img/ManagementDashboard/realdevices/ir_nature_remo.PNG" style="margin-top:10px;"
                     class="img-fluid">
            </div>
            <div class="col-8 pt-2">
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
                    </div>
                </div>
                <div class="row text-black text-center bg-secondary w-100 ml-1 rounded mb-2">
                    <div class="col-5 px-0 py-2 data-label rounded">
                        Label
                    </div>
                    <div class="col-7 px-0 py-2 text-white" style="font-weight: bold">
                        Data
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AirCon from '../ApplianceList/AirCon.vue'
import Television from '../ApplianceList/Television.vue'
import Generic from '../ApplianceList/Generic.vue'

export default {
    props: {
        device: {
            type: Object,
            required: true,
        },
    },

    components: {
        AirCon,
        Television,
        Generic,
    },

    data() {
        return {
            errors: [],
            appliances: [],
        }
    },

    created() {
        this.getRemoteInfo()
    },

    methods: {
        /**
         *
         */
        getRemoteInfo() {
            axios
                .get('getNatureRemoDevice', {
                    validateStatus: status => status >= 200 && status < 300,
                    params: {
                        DEVICE_ID: this.device.DEVICE_ID,
                    },
                })
                .then(response => (this.appliances = response.data.nature_remo_appliances))
                .catch(error => this.errors.push(error))
        },

        /**
         *
         */
        sendCommand({ SIGNAL_ID }) {
            let isLoading = true
            axios
                .post('sendNatureRemoSignal', {
                    ROOM_ID: this.device.ROOM_ID,
                    SIGNAL_ID: SIGNAL_ID,
                })
                .then(response => {})
                .catch(error => this.errors.push(error))
                .then(() => (isLoading = false))
        },
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
