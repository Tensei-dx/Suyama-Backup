<template>
    <div class="container" id="wifi-management-tab">
        <div class="text-white px-0 mt-3 h-400">
            <span class="stayseeHeader h4 text-left text-white" v-text="$t('management.param.wifiHeader')" />

            <!-- WIFI SSID -->
            <div class="row-12">
                <div class="container wifi-bg">
                    <div class="row">
                        <div class="col-4 px-0 h4 mt-4 pt-2" v-text="$t('management.param.wifiName')" />
                        <div class="wifiName text-black  col-6 h4 mt-3">
                            <input size="35" type="text" v-model.trim="formData.WIFI_NAME" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- WIFI PASSWORD -->
            <div class="row-12">
                <div class="container wifi-bg">
                    <div class="row">
                        <div class="col-4 px-0 h4 mt-4 pt-2" v-text="$t('management.param.wifiPassword')" />
                        <div class="wifiName text-black  col-6 h4 mt-3">
                            <input size="35" type="text" v-model.trim="formData.WIFI_PASSWORD" />
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
        parameters: {
            type: Object,
            required: true,
            default: {},
        },
    },

    data() {
        return {
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
                WIFI_NAME: this.parameters.WIFI_NAME,
                WIFI_PASSWORD: this.parameters.WIFI_PASSWORD,
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
.remindMail {
    display: inline-block;
    margin-top: 18px;
}
.dropMode {
    display: inline-block;
    margin-top: 90px;
}
.form-check-label {
    font-size: 24px;
}
.wifiName {
    background-color: transparent;
    height: 50px;
    justify-content: left;
    padding-top: 10px;
    font-size: 24px;
}
.thankyouMail {
    margin-top: 10px;
}
.wifiPassword {
    background-color: transparent;
    height: 50px;
    justify-content: left;
    padding-top: 10px;
    font-size: 24px;
}
.wifi-bg {
    background-color: transparent;
}
</style>
