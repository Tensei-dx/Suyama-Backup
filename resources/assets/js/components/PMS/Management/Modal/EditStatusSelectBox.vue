<template>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text status-bg">{{ $t('management.roomStats.buttonStatus')}}</span>
        </div>
        <select v-model="selectedRoomStatus" id="room-status" class="custom-select rounded-0" :disabled="!editMode">
            <option v-for="item in items" :key="item.key" :value="item.key">
                {{ item.message }}
            </option>
        </select>
        <div v-if="!editMode" class="input-group-append">
            <button @click="editStatus" class="btn btn-light" type="button">
                <i class="fa fa-edit fa-lg" aria-hidden="true" />
            </button>
        </div>
        <div v-else class="input-group-append">
            <button @click="showConfirmationModal" class="btn btn-primary" type="button"
                    :disabled="isRoomStatusUnchanged">
                <span v-if="isLoading"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true" /></span>
                <span v-else>{{ $t('management.roomOperation.update') }}</span>
            </button>
            <button @click="cancelEdit" class="btn cancel-btn"
                    type="button">{{ $t('management.roomOperation.cancel') }}</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        room: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            editMode: false,
            isLoading: false,
            selectedRoomStatus: null,
            cachedSelected: null,
        }
    },

    created() {
        this.selectedRoomStatus = this.room.STATUS_ID
    },

    computed: {
        /**
         * @name items
         * @description Options for the select box
         * @todo Add logic here to disable some of the options depending on the initial room status
         * @returns {Object[]}
         */
        items: function () {
            const OPTIONS = [
                { key: 201, message: this.$t('management.roomStats.status.checkedIn') },
                { key: 202, message: this.$t('management.roomStats.status.checkedOut') },
                { key: 203, message: this.$t('management.roomStats.status.available') },
                { key: 204, message: this.$t('management.roomStats.status.unavailable') },
                { key: 205, message: this.$t('management.roomStats.status.reserved') },
            ]
            // Room Status is Available
            if (this.room.STATUS_ID === 203) {
                return OPTIONS.filter(i => [203, 204].includes(i.key))
                // Room Status is Checked In
            } else if (this.room.STATUS_ID === 201) {
                return Element.classList.add('disabled')
                // Room Status is Checked Out
            } else if (this.room.STATUS_ID === 202) {
                return Element.classList.add('disabled')
                // Room Status is Key Unavailable
            } else if (this.room.STATUS_ID === 204) {
                return OPTIONS.filter(i => [203, 204].includes(i.key))
                // Room Status is Reserved
            } else if (this.room.STATUS_ID === 205) {
                return Element.classList.add('disabled')
            }
            return OPTIONS
        },

        /**
         * @name isRoomStatusUnchanged
         * @description Determines if the new selected status is equal to the original
         * @returns {boolean}
         */
        isRoomStatusUnchanged: function () {
            return this.selectedRoomStatus === this.room.STATUS_ID
        },
    },

    methods: {
        /**
         * @name editStatus
         * @description Make the select box editable and cache original data
         * @returns {void}
         */
        editStatus() {
            this.cachedSelected = this.selectedRoomStatus
            this.editMode = true
        },

        /**
         * @name showConfirmationModal
         * @description Displays a confirmation modal then executes the proper method for selected action
         * @returns {void}
         */
        showConfirmationModal() {
            this.isLoading = true
            const fromStatus = this.items.find(i => i.key === this.room.STATUS_ID).message
            const toStatus = this.items.find(i => i.key === this.selectedRoomStatus).message

            this.$swal({
                title:
                    this.$t('management.roomOperation.updateStatus') +
                    `${this.room.ROOM_NAME}` +
                    this.$t('management.roomOperation.questionMark') +
                    this.$t('management.roomOperation.updateStatusJa'),
                type: 'warning',
                text: `${fromStatus} → ${toStatus}`,
                cancelButtonText: this.$t('management.roomOperation.cancel'),
                showCancelButton: true,
                confirmButtonText: this.$t('management.roomOperation.update'),
            }).then(result => (result.value ? this.checkForValidation() : this.cancelEdit()))
        },

        /**
         * @name cancelEdit
         * @description Reset data then cancel edit
         * @returns {void}
         */
        cancelEdit() {
            this.selectedRoomStatus = this.cachedSelected
            this.cachedSelected = null
            this.editMode = false
            this.isLoading = false
        },

        /**
         * @name checkForValidation
         * @description Check if there is something that needs the user's attention
         * @returns {void}
         */
        checkForValidation() {
            // Confirm admin when changing status to UNAVAILABLE of room that have future reservations
            if (this.selectedRoomStatus === 204 && this.room.future_bookings_count > 0) {
                this.$swal({
                    title: this.$t('management.roomOperation.areYouSure'),
                    type: 'warning',
                    text:
                        this.$t('management.roomOperation.roomHave') +
                        `${this.room.future_bookings_count}` +
                        this.$t('management.roomOperation.reservation'),
                    cancelButtonText: this.$t('management.roomOperation.cancel'),
                    showCancelButton: true,
                    confirmButtonText: this.$t('management.roomOperation.update'),
                }).then(result => (result.value ? this.updateStatus() : this.cancelEdit()))
            } else {
                this.updateStatus()
            }
        },

        /**
         * @name updateStatus
         * @description Update room status in the database
         * @returns {void}
         */
        updateStatus() {
            axios
                .post('updateRoomStatus', {
                    ROOM_NAME: this.room.ROOM_NAME,
                    ROOM_ID: this.room.ROOM_ID,
                    STATUS_ID: this.selectedRoomStatus,
                })
                .then(response => {
                    this.$swal({
                        title: this.$t('management.roomOperation.updated'),
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                })
                .catch(error => {
                    console.log(error)
                    this.$swal({
                        title: this.$t('management.roomOperation.failedUpdate'),
                        type: 'error',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                })
                .then(() => this.closeAndSave())
        },

        /**
         * @name closeAndSave
         * @description Sync the new status from the prop data
         * @returns {void}
         */
        closeAndSave() {
            this.cachedSelected = null
            this.editMode = false
            this.isLoading = false
        },
    },
}
</script>

<style scoped>
.status-bg {
    background-color: #add8e6 !important;
}
.cancel-btn {
    background-color: #aaa;
    border-color: #aaa;
    color: #fff;
}
</style>
