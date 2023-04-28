<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h2">{{$t("mobile.roommanual.wifiInformation")}}</div>
        <div class="col-md-12">
            <div class="card text-left h2 custom-bg-card-items mt-3">
                <div class="row-12">
                    <div class="wifi-bg">
                        <div class="row">
                            <div class="col-5 px-0 h2 mt-1 pt-1 pl-4">
                                {{ $t('mobile.roommanual.wifiName') }}
                            </div>
                            <div class="wifiName text-white text-center col-6 h3 mt-2">
                                <p>{{this.wifi_name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-12">
                    <div class="wifi-bg">
                        <div class="row">
                            <div class="col-5 px-0 h2 mt-1 pt-1 pl-4">
                                {{ $t('mobile.roommanual.wifiPassword') }}
                            </div>
                            <div class="wifiName text-white text-center col-6 h3 mt-2">
                                <p>{{this.wifi_password}}</p>
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
    data() {
        return {
            wifi_name: '',
            wifi_password: '',
        }
    },

    created() {
        this.getParamSettings()
    },

    methods: {
        getParamSettings() {
            axios.get('param-settings/getParamSettings').then(response => {
                ;(this.wifi_name = response.data.WIFI_NAME), (this.wifi_password = response.data.WIFI_PASSWORD)
            })
        },
    },

    watch: {
        wifi_name: function () {
            this.$bus.emit('wifi_parameters', [this.wifi_name, this.wifi_password])
        },
        wifi_password: function () {
            this.$bus.emit('wifi_parameters', [this.wifi_name, this.wifi_password])
        },
    },
}
</script>
