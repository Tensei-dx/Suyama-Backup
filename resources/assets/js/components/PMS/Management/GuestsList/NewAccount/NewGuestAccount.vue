<template>
    <div class="container key-management-width key-management-scroll">
        <div class="text-white row mx-0" style="background-color: black;">
            <div class="col-12 text-center">
                <i class="fa fa-key fa-3x" aria-hidden="true"></i>
                <span class="font-weight-bold" style="font-size:3rem;">{{ $t('remotelock.guestTitle') }}</span>
            </div>
            <div class="col-12 mb-3">
                <form class="font-weight-bold px-2">

                    <!-- First Name -->
                    <div class="form-group row">
                        <label for="firstName" class="col col-form-label">{{ $t('remotelock.firstname') }}:</label>
                        <div class="col-12">
                            <input class="form-control" type="text" v-model="form_input.first_name" id="firstName"
                                   @keydown="errors.first_name = null" />
                        </div>
                        <span v-if="errors.first_name == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.firstName.empty') }}</span>
                    </div>

                    <!-- Last Name -->
                    <div class="form-group row">
                        <label for="lastName" class="col col-form-label">{{ $t('remotelock.lastname') }}:</label>
                        <div class="col-12">
                            <input class="form-control" type="text" v-model="form_input.last_name" id="lastName"
                                   @keydown="errors.last_name = null" />
                        </div>
                        <span v-if="errors.last_name == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.lastName.empty') }}</span>
                    </div>

                    <!-- Check In DateTime -->
                    <div class="form-group row">
                        <label for="checkIn" class="col col-form-label">{{ $t('remotelock.checkin') }}: fixed at
                            2pm</label>
                        <div class="col-12">
                            <input class="form-control" type="datetime-local" v-model="form_input.check_in" id="checkIn"
                                   @change="errors.check_in = null" />
                        </div>
                        <span v-if="errors.check_in == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.checkIn.empty') }}</span>
                        <span v-else-if="errors.check_in == 'later'"
                              class="text-danger col">{{ $t('remotelock.errors.checkIn.later') }}</span>
                    </div>

                    <!-- Check Out DateTime -->
                    <div class="form-group row">
                        <label for="checkOut" class="col col-form-label">{{ $t('remotelock.checkout') }}: fixed at
                            12pm</label>
                        <div class="col-12">
                            <input class="form-control" type="datetime-local" v-model="form_input.check_out"
                                   id="checkOut" @change="errors.check_out = null" />
                        </div>
                        <span v-if="errors.check_out == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.checkOut.empty') }}</span>
                        <span v-else-if="errors.check_out == 'earlier'"
                              class="text-danger col">{{ $t('remotelock.errors.checkOut.earlier') }}</span>
                    </div>

                    <!-- Email -->
                    <div class="form-group row">
                        <label for="email" class="col col-form-label">{{ $t('remotelock.email') }}:</label>
                        <div class="col-12">
                            <input class="form-control" type="text" v-model="form_input.email" id="email"
                                   placeholder="example@email.com" @keydown="errors.email = null" />
                        </div>
                        <span v-if="errors.email == 'invalid'"
                              class="text-danger col">{{ $t('remotelock.errors.email.invalid') }}</span>
                        <span v-else-if="errors.email == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.email.empty') }}</span>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group row">
                        <label for="phone_number" class="col col-form-label">{{ $t('remotelock.phoneNumber') }}:</label>
                        <div class="col-12">
                            <input type="number" class="form-control" v-model="form_input.phoneNumber" id="phone_number"
                                   @keydown="errors.phoneNumber = null" />
                        </div>
                        <span v-if="errors.phoneNumber == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.phoneNumber.empty') }}</span>
                    </div>

                    <!-- PIN Code -->
                    <div class="form-group row">
                        <label for="pin" class="col col-form-label">{{ $t('remotelock.pin') }}:</label>
                        <div class="col-12">
                            <input class="form-control" type="number" v-model="generatePIN" id="pin" readonly />
                        </div>
                    </div>
                    <!-- Available Rooms -->
                    <div class="form-group row">
                        <label for="device" class="col col-form-label">{{ $t('remotelock.room') }}:</label>
                        <div class="col-12">
                            <div>
                                <select class="form-control" v-model="form_input.roomID"
                                        @change="updateRoomName(); errors.device = null">
                                    <option v-for="room in available_rooms" :key="room.ROOM_ID" :value="room.ROOM_ID"
                                            :label="room.ROOM_NAME + ' : ' + room.status_name.STATUS_NAME">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <span v-if="errors.room == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.remoteLockId.empty') }}</span>
                        <span v-if="errors.room == 'no_available'"
                              class="text-danger col">{{ $t('remotelock.errors.remoteLockId.no_available') }}</span>
                    </div>
                </form>
            </div>

            <!-- Back to Management Dashboard button -->
            <div class="col-6 text-left pl-4">
                <a href="/management"
                   class="btn btn-custom-color-rl col-3 font-weight-bold text-black text-decoration-none">{{ $t('remotelock.back') }}</a>
            </div>

            <!-- Submit button -->
            <div class="col-6 text-right pr-4">
                <button type="button" class="btn btn-custom-color-rl font-weight-bold" @click="submit()"
                        :disabled="isSendButtonDisabled">
                    {{$t("management.keyManagementPage.createGuestAccount")}}
                </button>
            </div>
            <div class="col-12 my-3">
                <Message :email="form_input.email" :isSuccess="isSuccess" :errorMessage="errorMessage" />
            </div>
        </div>
        <Footer />
    </div>
</template>

<script>
import Message from '../../../RemoteLock/Message/Message.vue'
import Footer from '../../../Guest/Footer/Footer.vue'

export default {
    components: {
        Message,
        Footer,
    },

    data() {
        return {
            code: null,
            pinLength: 6,
            pinRule: 0,
            remote_lock: [],
            available_rooms: [],
            isSendButtonDisabled: false,

            form_input: {
                first_name: null,
                last_name: null,
                check_in: null,
                check_out: null,
                email: null,
                phoneNumber: '',
                pin: '',
                remoteLockId: null,
                floorID: null,
                roomName: null,
                roomID: null,
            },

            isSuccess: false,
            errorMessage: [],

            errors: {
                first_name: null,
                last_name: null,
                check_in: null,
                check_out: null,
                email: null,
                phoneNumber: null,
                room: null,
            },
        }
    },

    created() {
        // this.getAuthCode()
        this.getRemoteLockPinSettings()
        this.getAvailableRoomWithRemoteLockDevice()
    },

    methods: {
        /**
         * @name getAuthCode
         * @desc Get authorization code
         *
         * @params
         * @returns null
         */
        getAuthCode() {
            //[Task [0024] 20210803
            // const urlParams = new URLSearchParams(window.location.search)
            // if (urlParams.has('code')) {
            //     this.code = urlParams.get('code')
            // } else {
            //     this.isSendButtonDisabled = true
            //     this.errorMessage.push(this.$t('remotelock.errors.authCode.empty'))
            //     this.redirectToDashboard()
            // }
        },

        /**
         * @name getRemoteLockPinSettings
         * @desc Get remote lock pin code settings
         *
         * @params
         * @returns null
         */
        getRemoteLockPinSettings() {
            axios
                .get('getRemoteLockPinSettings')
                .then(response => {
                    this.pinLength = response.data.numOfPin
                    this.pinRule = response.data.pinRule
                    this.form_input.pin = response.data.pin
                })
                .catch(error => console.log(error))
        },

        /**
         * @name getAvailableRoomWithRemoteLockDevice
         * @desc Get all available rooms with remote lock devices
         *
         * @params
         * @returns null
         */
        getAvailableRoomWithRemoteLockDevice() {
            axios
                .get('getAvailableRooms')
                .then(response => {
                    if (response.data.length == 0) {
                        this.errors.room = 'no_available'
                    } else {
                        // this.remote_lock = response.data
                        this.available_rooms = response.data
                    }
                })
                .catch(error => console.log(error))
        },

        /**
         * @name updateRoomName
         * @desc Update room name
         *
         * @param
         * @returns null
         */
        updateRoomName() {
            const selected = this.remote_lock.find(i => i.DATA['remote_lock_id'] === this.form_input.remoteLockId)
            if (selected) {
                this.form_input.roomName = selected.room.ROOM_NAME
                this.form_input.floorID = selected.FLOOR_ID
                this.form_input.roomID = selected.ROOM_ID
            }
        },

        /**
         * @name isInputValid
         * @desc Check if all inputs are valid
         *
         * @params
         * @returns {Object} !errorFlag
         */
        isInputValid() {
            let errorFlag = false

            this.errors.first_name = null
            this.errors.last_name = null
            this.errors.check_in = null
            this.errors.check_out = null
            this.errors.email = null
            this.errors.phoneNumber = null
            this.errors.room = null

            // First Name Input Check
            if (!this.form_input.first_name) {
                this.errors.first_name = 'empty'
                errorFlag = true
            }

            // Last Name Input Check
            if (!this.form_input.last_name) {
                this.errors.last_name = 'empty'
                errorFlag = true
            }

            // Check In DateTime Input Check
            if (!this.form_input.check_in) {
                this.errors.check_in = 'empty'
                errorFlag = true
            }

            // Check Out DateTime Input Check
            if (!this.form_input.check_out) {
                this.errors.check_out = 'empty'
                errorFlag = true
            }

            if (this.form_input.check_in && this.form_input.check_out) {
                const startsAt = new Date(this.form_input.check_in)
                const endsAt = new Date(this.form_input.check_out)
                if (startsAt > endsAt) {
                    this.errors.check_in = 'later'
                    this.errors.check_out = 'earlier'
                    errorFlag = true
                }
            }

            // Email Format Check
            if (this.form_input.email) {
                if (
                    !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
                        this.form_input.email
                    )
                ) {
                    this.errors.email = 'invalid'
                    errorFlag = true
                }
            } else {
                this.errors.email = 'empty'
                errorFlag = true
            }

            // Phone Number Check
            if (!this.form_input.phoneNumber) {
                this.errors.phoneNumber = 'empty'
                errorFlag = true
            }

            // Remote Lock Device Check
            if (!this.form_input.roomID) {
                this.errors.room = 'empty'
                errorFlag = true
            }

            return !errorFlag
        },

        /**
         * @name submit
         * @desc Disabled send button
         *
         * @params
         * @returns null
         */
        submit() {
            //clear existing errors
            this.errorMessage = []

            if (!this.isInputValid()) {
                return
            }
            this.isSendButtonDisabled = true
            this.duplicationPinCheck()
        },

        /**
         * @name duplicationPinCheck
         * @desc Check if PIN is already taken
         *
         * @params
         * @returns null
         */
        duplicationPinCheck() {
            axios
                .post('duplicationPinCheck', {
                    TYPE: 'new',
                    PIN: this.form_input.pin,
                })
                .then(response => {
                    if (response.data == 'duplicate') {
                        this.errorMessage.push('PIN IS ALREADY IN USE')
                    } else {
                        this.createRemoteLockGuestAccount()
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * @name createRemoteLockGuestAccount
         * @desc Create a remote lock guest account
         *
         * @params
         * @returns null
         */
        createRemoteLockGuestAccount() {
            axios
                .post('createRemoteLockGuestAccount', {
                    FIRST_NAME: this.form_input.first_name,
                    LAST_NAME: this.form_input.last_name,
                    EMAIL: this.form_input.email,
                    CHECK_IN_TIME: this.form_input.check_in,
                    CHECK_OUT_TIME: this.form_input.check_out,
                    PIN: this.form_input.pin,
                    ROOM_ID: this.form_input.roomID,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.createiBMSGuestAccount()
                    } else if (response.data == 'no_remote_lock') {
                        this.errorMessage.push('No Remote Lock Connected to this room.')
                        this.isSendButtonDisabled = false
                    } else {
                        this.errorMessage = response.data
                    }
                })
                .catch(error => {
                    console.log(error)
                    this.errorMessage.push(this.$t('remotelock.errors.createRemoteLockGuestAccount.general'))
                })
        },

        /**
         * @name createiBMSGuestAccount
         * @desc Create an iBMS User account
         *
         * @params
         * @returns null
         */
        createiBMSGuestAccount() {
            axios
                .post('createUser', {
                    USERNAME: this.form_input.email, // uses the email as the username for iBMS guest account
                    PASSWORD: this.form_input.pin, // uses the pin as the password for iBMS guest account
                    EMAIL: this.form_input.email,
                    FIRST_NAME: this.form_input.first_name,
                    LAST_NAME: this.form_input.last_name,
                    USER_TYPE: 2,
                    CONTACT_NUMBER: this.form_input.phoneNumber,
                    ALLOW_ALERT_NOTIFICATION: JSON.stringify({
                        email: false,
                        sms: false,
                        voice: false,
                    }),
                    hasFile: 0,
                    file: null,
                    fileName: null,
                    // moduleids: '1',         // Module 1: Dashboard
                    // floorids: '',
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.createBookAccount()
                    } else if (response.data == 'duplicate') {
                        this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.duplicate'))
                    } else {
                        this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.general'))
                    }
                })
                .catch(error => {
                    this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.general'))
                })
        },

        /**
         * @name createBookAccount
         * @desc Create an iBMS Book account
         *
         * @params
         * @returns null
         */
        createBookAccount() {
            axios
                .post('createBookAccount', {
                    FIRST_NAME: this.form_input.first_name,
                    LAST_NAME: this.form_input.last_name,
                    EMAIL: this.form_input.email,
                    CHECK_IN_TIME: this.form_input.check_in,
                    CHECK_OUT_TIME: this.form_input.check_out,
                    CONTACT_NUMBER: this.form_input.phoneNumber,
                    ROOM_ID: this.form_input.roomID,
                    PIN: this.form_input.pin,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.sendAlertEmail()
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * @name sendAlertEmail
         * @desc Send an email for reservation details
         *
         * @params
         * @returns null
         */
        sendAlertEmail() {
            axios
                .post('sendRemoteLockAlertEmail', {
                    EMAIL: this.form_input.email,
                    EMAIL_FROM: 'r-russell@tenseiph.com',
                    CHECK_IN_TIME: this.form_input.check_in,
                    CHECK_OUT_TIME: this.form_input.check_out,
                    PIN: this.form_input.pin,
                    LAST_NAME: this.form_input.last_name,
                    FIRST_NAME: this.form_input.first_name,
                    ROOM_NAME: this.form_input.roomName,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.isSuccess = true
                        this.redirectToDashboard()
                    } else {
                        this.errorMessage.concat(response.data)
                    }
                })
                .catch(error => {
                    this.errorMessage.push(this.$t('remotelock.errors.sendAlertEmail.general'))
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
            if (this.isSuccess) {
                setTimeout(() => {
                    window.location.replace('/management')
                }, 3000)
            }
        },
    },

    computed: {
        /**
         * @name showUpdateGuestAccountModal
         * @desc Show guest account update modal
         *
         * @params {String} id,first_name,last_name,email,check_in,check_out,pin
         * @returns null
         */
        generatePIN: function () {
            if (this.pinRule == 0) {
                return this.form_input.pin
            }
            // else if (this.pinRule == 1) {
            //     return this.getPinFromPhoneNumber
            // }
        },
    },
}
</script>

<style>
.btn-custom-color-rl {
    background-color: #b4c7e7;
}
.key-management-width {
    max-width: 895px !important;
}
</style>

