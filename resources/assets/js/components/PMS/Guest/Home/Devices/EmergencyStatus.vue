<template>
    <div class="card">
        <div class="row no-gutters">
            <div class="col-xl-5 col-4 hotel-base-color d-flex align-items-center justify-content-center">
                <div class="py-4">
                    <i class="fa fa-exclamation-triangle fa-3x" />
                    <small class="d-block mt-2 text-center" v-text="$t('mobile.control.emergency.emergencyStatus')" />
                </div>
            </div>

            <div class="col p-2 d-flex align-items-center justify-content-center">
                <button class="bg-red text-white rounded-circle border-0 diameter-90 pointer" data-toggle="modal"
                        data-target="#emergency-status-modal">
                    <span class="h2 font-weight-bold" v-text="$t('mobile.control.emergency.sos')" />
                </button>
            </div>
        </div>

        <ModalEmergency id="emergency-status-modal" :room_id="room_id" />

    </div>
</template>

<script>
import ModalEmergency from './ModalEmergency.vue'

export default {
    props: ['room_id'],

    components: { ModalEmergency },
}
</script>

<style scoped>
.bg-red {
    background-color: red;
}
.diameter-90 {
    width: 90px;
    height: 90px;
}
</style>
