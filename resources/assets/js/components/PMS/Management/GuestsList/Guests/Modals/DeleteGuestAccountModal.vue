<template>
    <div class="modal d-block" tabindex="-1">
        <div class="modal-background" @click="closeModal()"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{$t('management.keyManagementPage.deleteGuestAccount')}}</h5>
                    <button type="button" class="close" @click="closeModal()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <p>{{ $t('management.keyManagementPage.deleteModal') }} {{this.booking_data.EMAIL}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" data-dismiss="modal" @click="closeModal()">
                        {{$t('management.keyManagementPage.cancel')}}
                    </button>
                    <button type="button" class="btn btn-danger" @click="deleteAccount()"
                            :disabled="isDeleteButtonDisabled">
                        {{$t('management.keyManagementPage.delete')}}
                    </button>
                </div>
                <div v-if="this.message == 'success'" class="alert alert-success">
                    <strong>{{$t('remotelock.message.success.title')}}</strong>
                    <span>{{$t('remotelock.message.success.body')}}</span>
                </div>
                <div v-if="this.message == 'error'" class="alert alert-danger">
                    <strong>{{$t('remotelock.errors.unknownError.title')}}</strong>
                    <span>{{$t('remotelock.errors.unknownError.redirect')}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        booking_data: '',
        //Task[001]
        // delete_guest_id : String,
        // delete_guest_username: String,
        // delete_guest_name: String,
        // delete_guest_check_in: String,
        // delete_guest_check_out: String,
        // delete_guest_room_name: String
    },

    data() {
        return {
            message: '',
            isDeleteButtonDisabled: false,
        }
    },

    methods: {
        /**
         * @name deleteAccount
         * @desc Delete an account
         *
         * @params
         * @returns null
         */
        deleteAccount() {
            this.isDeleteButtonDisabled = true
            axios
                .post('deleteGuestAccount', {
                    USER_ID: this.booking_data.REMOTE_LOCK_STATUS ? this.booking_data.REMOTE_LOCK_INFO.id : '',
                    USERNAME: this.booking_data.EMAIL,
                    REMOTE_LOCK_STATUS: this.booking_data.REMOTE_LOCK_STATUS,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.message = 'success'
                        this.sendBookCancellationEmail()
                    } else {
                        this.message == 'error'
                        this.redirectToDashboard()
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },

        //[Task 024]
        /**
         * @name sendBookCancellationEmail
         * @desc Send an email when reservation deletes
         *
         * @params
         * @returns null
         */
        sendBookCancellationEmail() {
            var email = this.booking_data.EMAIL
            var name = this.booking_data.FIRST_NAME + ' ' + this.booking_data.LAST_NAME
            var checkInTime = this.booking_data.bookings_with_room[0].CHECK_IN_TIME
            var checkOutTime = this.booking_data.bookings_with_room[0].CHECK_OUT_TIME
            var roomName = this.booking_data.bookings_with_room[0].room.ROOM_NAME

            axios
                .post('sendBookCancellationEmail', {
                    EMAIL: email,
                    NAME: name,
                    CHECK_IN_TIME: checkInTime,
                    CHECK_OUT_TIME: checkOutTime,
                    ROOM_NAME: roomName,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.redirectToDashboard()
                    } else {
                        this.message == 'error'
                        this.redirectToDashboard()
                    }
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name redirectToDashboard
         * @desc Redirect to Management Dashboard
         *
         * @params
         * @returns null
         */
        redirectToDashboard() {
            setTimeout(() => {
                window.location.replace('/management')
            }, 3000)
        },

        /**
         * @name closeModal
         * @desc Close this Modal
         *
         * @params
         * @returns null
         */
        closeModal() {
            this.$emit('closeModal')
        },
    },
}
</script>

<style scoped>
.cancel-btn {
    background-color: #aaa;
    border-color: #aaa;
    color: #fff;
}
</style>
