<template>
    <div class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog"
         aria-labelledby="emergency-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header justify-content-center">
                    <i v-if="agreed" class="fa fa-check-circle fa-100px fg-red" />
                    <i v-else class="fa fa-exclamation-circle fa-100px fg-red" />
                </div>

                <div class="modal-body text-black">
                    <span class="h1 font-weight-bold d-block mb-3"
                          v-text="$t('mobile.control.emergency.' + (agreed ? 'sosButton' : 'emergencyHeader'))" />
                    <p class="mb-0" v-text="$t('mobile.control.emergency.' + (agreed ? 'staff' : 'notify'))" />
                </div>

                <div class="modal-footer justify-content-center">
                    <form @submit.prevent="onSubmit">
                        <button v-if="!agreed || isLoading" type="submit" class="pointer btn btn-danger"
                                v-text="$t('mobile.control.emergency.yes')" />
                        <button type="close" data-dismiss="modal" data-target="#emergency-status-modal"
                                class="pointer btn cancel-btn"
                                v-text="$t('mobile.control.emergency.' + (agreed ? 'close' : 'cancel'))" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['room_id'],

    data() {
        return {
            agreed: false,
            isLoading: false,
        }
    },

    mounted() {
        $('#emergency-status-modal').on('hidden.bs.modal', e => {
            this.agreed = false
        })
    },

    methods: {
        /**
         * @name onSubmit
         * @description Send API request on submit
         *
         * @returns {void}
         */
        onSubmit() {
            this.isLoading = true
            axios
                .post('/notifications/room-emergency', {
                    ROOM_ID: this.room_id,
                })
                .then(response => {
                    this.agreed = true
                    this.isLoading = false
                })
        },
    },
}
</script>

<style scoped>
.fa-100px {
    font-size: 100px;
}
.cancel-btn {
    background-color: #aaa;
    border-color: #aaa;
    color: #fff;
}
.fg-red {
    color: red;
}
</style>


