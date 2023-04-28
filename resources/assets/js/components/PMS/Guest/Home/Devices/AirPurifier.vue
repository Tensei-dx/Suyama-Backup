<template>
    <div class="card">
        <div class="row no-gutters">
            <!-- ACCENT -->
            <div class="col-xl-5 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <img src="img/guest_dashboard/air-purifier.png" style="width: 50px;"/>
                    <small class="d-block mt-2">{{ $t('mobile.control.airpurifier.airpurifier') }}</small>
                </div>
            </div>
            <div class="col p-2 align-self-center">
                <button type="button" :disabled="isEmpty(deviceInfo)" class="btn hotel-base-color"><b>ON</b></button>
                <small class="d-block mt-1">{{ $t('mobile.control.airpurifier.airpurifier') }}</small>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceInfo: Object
    },

    data() {
        return {
            status: 0
        }
    },
    created() {

    },
    methods: {
        /**
         * @name isEmpty
         * @desc Check if the deviceinfo is empty or not
         * @param {string} obj
         */
        isEmpty(obj) {
            return Object.keys(obj).length === 0;
            }
    },
}
</script>
