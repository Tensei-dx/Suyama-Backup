<template>
    <div class="container" id="staysee-management-tab">
        <div class="text-white px-0 mt-3 h-400">
            <span class="stayseeHeader h4 text-left text-white" v-text="$t('management.param.stayseeHeader')" />
            <div>

                <!-- FREQUENCY -->
                <div class="row-12 mt-2">
                    <span class="syncFrequency px-0 col-4 h4 text-left" v-text="$t('management.param.syncFrequency')" />
                    <form class="form pl-4 d-inline-block">
                        <select class="custom-select" id="inlineFormCustomSelectPref"
                                v-model.trim="formData.CRON_SCHEDULE">
                            <template v-for="(frequency, index) in frequencyOptions">
                                <option :key="index" :value="frequency.value" v-text="frequency.text" />
                            </template>
                        </select>
                    </form>
                    <span class="h4 text-left pl-3 text-white" v-text="$t('management.param.minutesFrequency')" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        parameters: {
            type: Object,
            required: true,
            default: {},
        },
    },

    data() {
        return {
            frequencyOptions: [
                { text: 15, value: '*/15 * * * *' },
                { text: 30, value: '*/30 * * * *' },
                { text: 60, value: '0 * * * *' },
            ],
            formData: {},
        }
    },

    methods: {
        /**
         * @name fillUp
         * @description Assign the cached data to the form
         *
         * @returns {void}
         */
        fillUp() {
            this.formData = {
                CRON_SCHEDULE: this.parameters.CRON_SCHEDULE,
            }
        },
    },

    watch: {
        parameters: {
            handler() {
                this.fillUp()
            },
            deep: true,
        },
        formData: {
            handler(value) {
                this.$emit('update', value)
            },
            deep: true,
        },
    },
}
</script>

<style scoped>
.stayseeHeader {
    margin-top: 30px;
}
.bg-apply {
    background-color: #898989 !important;
    color: #fff;
    padding-top: 20px;
}
.autoStart {
    display: inline-block;
    margin-top: 30px;
}
.syncFrequency {
    display: inline-block;
    margin-top: 30px;
}
.dropMode {
    display: inline-block;
    margin-top: 90px;
}
.form-check-label {
    font-size: 24px;
}
.dropdown-menu {
    min-width: inherit;
}
</style>
