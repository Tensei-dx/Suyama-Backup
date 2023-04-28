<template>
    <div id="emergency-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="emergency-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header align-items-center justify-content-start">
                    <i class="fa fa-exclamation-triangle fa-2x text-danger" aria-hidden="true" />
                    <span class="h3 text-uppercase text-danger font-weight-bold mb-0 ml-3"
                          v-text="$t('emergency.modal.header')" />
                    <i v-show="isLoading" class="fa fa-circle-o-notch fa-spin fa-2x text-danger ml-auto"
                       aria-hidden="true" />
                </div>

                <!-- BODY -->
                <div class="modal-body pb-0">
                    <template v-for="room in rooms">

                        <div role="alert" :key="room.ROOM_ID" :value="room.ROOM_ID"
                             class="alert alert-danger alert-dismissible fade show">
                            <h5 class="alert-heading font-weight-bold" v-text="room.ROOM_NAME" />
                            <span v-text="$t('emergency.modal.body1')" /><br />
                            <span v-text="$t('emergency.modal.body2')" />
                            <button :disabled="isLoading" type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    </template>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button :disabled="isLoading" type="button" class="btn btn-danger" @click="dismissAll"
                            v-text="$t('emergency.modal.close')" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            rooms: [],
        }
    },

    methods: {
        /**
         * @name getRoomsWithEmergency
         * @description Retrieve rooms where EMERGENCY_FLAG is true
         *
         * @returns {void}
         */
        getRoomsWithEmergency() {
            this.isLoading = true
            axios
                .get('rooms')
                .then(response => {
                    this.rooms = response.data.filter(room => room.EMERGENCY_FLAG)
                })
                .then(() => (this.isLoading = false))
        },

        /**
         * @name dismissRoomEmergency
         * @description Set the room's EMERGENCY_FLAG to false
         *
         * @param {number} id
         * @returns {void}
         */
        dismissRoomEmergency(id) {
            this.isLoading = true
            axios
                .put(`rooms/${id}`, { EMERGENCY_FLAG: false })
                .then(response => {
                    this.rooms = this.rooms.filter(room => room.ROOM_ID !== id)
                })
                .then(() => (this.isLoading = false))
        },

        /**
         * @name dismissAll
         * @description Set all room's EMERGENCY_FLAG to false
         *
         * @returns {void}
         */
        dismissAll() {
            this.isLoading = true
            axios
                .put(`rooms`, { EMERGENCY_FLAG: false })
                .then(response => {
                    this.rooms = []
                })
                .then(() => (this.isLoading = false))
        },
    },

    watch: {
        rooms: {
            deep: true,
            handler(val) {
                // show the modal if the rooms array is not empty
                $('#emergency-modal').modal(val.length > 0 ? 'show' : 'hide')
            },
        },
    },

    created() {
        this.getRoomsWithEmergency()
    },

    mounted() {
        // start listening to 'emergency' public channel 'room' event
        Echo.channel('emergency').listen('.room', e => {
            // remove all entry of the new room then append it to the beginning of the array
            this.rooms = this.rooms.filter(room => room.ROOM_ID !== e.room.ROOM_ID)
            this.rooms.unshift(e.room)
        })

        let that = this
        $('.modal-body').on('close.bs.alert', '.alert', function (e) {
            // when an alert is dismiss, remove the room from the array
            that.dismissRoomEmergency(parseInt(e.target.attributes.value.value))
        })
    },

    beforeDestroy() {
        // stop listening to 'emergency' channel
        Echo.leave('emergency')
    },
}
</script>
