<template>
    <div>
        <Aircon :deviceInfo="passAppliance.ac" v-if="!!passAppliance.ac" />
        <LightSwitch :deviceInfo="passAppliance.light" v-if="!!passAppliance.light" />
        <Television :deviceInfo="passAppliance.tv" v-if="!!passAppliance.tv" />
    </div>
</template>

<script>
import Aircon from './Aircon.vue'
import LightSwitch from './LightSwitch.vue'
import Television from './Television.vue'

export default {
    components: {
        Aircon,
        LightSwitch,
        Television,
    },

    props: {
        device: Array,
    },

    data() {
        return {
            passAppliance: {
                ac: {},
                light: {},
                tv: {},
            },
        }
    },

    mounted() {
        this.passApplianceInfo()
    },

    methods: {
        passApplianceInfo() {
            this.passAppliance.ac = this.device.find(device => device.APPLIANCE_TYPE === 'AC')
            this.passAppliance.light = this.device.find(
                device => device.APPLIANCE_TYPE === 'IR' && device.APPLIANCE_NAME === 'Light'
            )
            this.passAppliance.tv = this.device.find(device => device.APPLIANCE_TYPE === 'TV')
        },
    },
}
</script>

