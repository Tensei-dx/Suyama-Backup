<!--
    <System Name> iBMS
    <Program Name> UpdateGuestAccount.vue
    <Created>           TDN Okada
-->

<template>
    <div class="container key-management-width key-management-scroll">
        <div class="text-white row mx-0" style="background-color: black;">
            <div class="col-12 text-center">
                <i class="fa fa-key fa-3x" aria-hidden="true"></i>
                <span class="font-weight-bold" style="font-size:3rem;">{{ $t('remotelock.updateGuestAccount') }}</span>
            </div>
            <div class="col-12 mb-3">
                <form id="booking-form" class="font-weight-bold px-2">
                    <!-- User Name -->
                    <div class="form-group row">
                        <label for="userName" class="col col-form-label">{{ $t('remotelock.username') }}:</label>
                        <div class="col-12">
                            <input class="form-control" type="text" v-model="form_input.user_name" id="userName"
                                   @keydown="errors.user_name = null" readonly />
                        </div>
                        <span v-if="errors.user_name == 'empty'"
                              class="text-danger col">{{ $t('remotelock.errors.UserName.empty') }}</span>
                    </div>

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
                        <label for="checkIn" class="col col-form-label">{{ $t('remotelock.checkin') }}:</label>
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
                        <label for="checkOut" class="col col-form-label">{{ $t('remotelock.checkout') }}:</label>
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

                    <!-- PIN Code -->
                    <div class="form-group row">
                        <label for="pin" class="col col-form-label">{{ $t('remotelock.pin') }}:</label>
                        <div class="row col-12">
                            <div class="col-8">
                                <input class="form-control" type="number" v-model="form_input.pin" id="pin" readonly />
                            </div>
                            <div class="col-4 px-0">
                                <button type="button" class="btn btn-custom-color-rl font-weight-bold"
                                        @click="genPin()">
                                    {{$t("management.keyManagementPage.generateNewPin")}}
                                </button>
                            </div>
                        </div>
                        <span v-if="errors.pin == 'empty'"
                              class="text-danger col">{{$t("management.remotelock.errors.pin.empty")}}</span>
                        <span v-if="errors.pin == 'invalid'"
                              class="text-danger col">{{$t("management.remotelock.errors.pin.invalid")}}</span>
                        <span v-if="errors.pin == 'duplicate'"
                              class="text-danger col">{{$t("management.remotelock.errors.pin.duplicate")}}</span>
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
                    {{$t("management.keyManagementPage.updateGuestAccount")}}
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
import Message from '../../../../RemoteLock/Message/Message.vue'
import Footer from '../../../../Guest/Footer/Footer.vue'

export default {
    components: {
        Message,
        Footer,
    },

    props: {
        updateData: {
            type: Object,
        },
    },
    name: 'update-account',

    data() {
        return {
            isSendButtonDisabled: false,
            isSuccess: false,
            default_room_status: '',
            change_room_status_mode: false,
            form_input: {
                user_id: null,
                user_name: null,
                first_name: null,
                last_name: null,
                pin: null,
                email: null,
                check_in: null,
                check_out: null,
            },

            errorMessage: [],

            errors: {
                user_id: null,
                user_name: null,
                first_name: null,
                last_name: null,
                email: null,
                check_in: null,
                check_out: null,
                pin: null,
            },
        }
    },
    created() {
        if (this.updateData !== null) {
            this.form_input.user_id = this.updateData.USER_ID
            this.form_input.user_name = this.updateData.USERNAME
            this.form_input.first_name = this.updateData.FIRST_NAME
            this.form_input.last_name = this.updateData.LAST_NAME
            this.form_input.email = this.updateData.EMAIL
            this.form_input.check_in = new Date(this.updateData.CHECK_IN_TIME).toISOString().substring(0, 16)
            this.form_input.check_out = new Date(this.updateData.CHECK_OUT_TIME).toISOString().substring(0, 16)
            this.form_input.pin = this.updateData.PIN

            //Set to default Room status in case of changing Room Status
            this.default_pin = JSON.parse(JSON.stringify(this.updateData.PIN))
        } else {
            this.invalidUserProcess()
        }
    },
    methods: {
        /**
         * @name isInputValid
         * @desc Check if inputs are all valid
         *
         * @params
         * @returns {Object} !errorFlag
         */
        isInputValid() {
            let errorFlag = false
            this.errors.user_id = null
            this.errors.user_name = null
            this.errors.first_name = null
            this.errors.last_name = null
            this.errors.check_in = null
            this.errors.check_out = null
            this.errors.email = null
            // User Id Input Check
            if (!this.form_input.user_id) {
                this.errors.user_id = 'empty'
                errorFlag = true
            }
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
            return !errorFlag
        },
        /**
         * @name genPin
         * @desc Generate a 6 digit PIN Code
         *
         * @params
         * @returns {String}
         */
        genPin() {
            axios
                .get('/getRemoteLockPinSettings')
                .then(response => {
                    this.pin = response.data.pin
                    this.form_input.pin = this.pin
                })
                .catch(error => console.log(error))
        },
        /**
         * @name submit
         * @desc Disable send button
         *
         * @params
         * @returns {String}
         */
        submit() {
            if (!this.isInputValid()) {
                return
            }
            //check if pin and email is duplicated
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
                .post('/duplicationPinCheck', {
                    TYPE: 'update',
                    OLD_PIN: this.default_pin,
                    PIN: this.form_input.pin,
                    ROOM_ID: this.updateData.ROOM_ID,
                })
                .then(response => {
                    if (response.data == 'duplicate') {
                        this.errors.pin = 'duplicate'
                    } else {
                        this.updateGuestAccount()
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },
        /**
         * @name updateGuestAccount
         * @desc Update a guest account
         *
         * @params
         * @returns null
         */
        updateGuestAccount() {
            axios
                .post('/updateGuestAccount', {
                    CHECK_IN: this.form_input.check_in,
                    CHECK_OUT: this.form_input.check_out,
                    PIN: this.form_input.pin,
                    USER_ID: this.form_input.user_id,
                    EMAIL: this.form_input.email,
                    LAST_NAME: this.form_input.last_name,
                    FIRST_NAME: this.form_input.first_name,
                    REMOTE_LOCK_STATUS: this.updateData.REMOTE_LOCK_STATUS,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.isSuccess = true
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
                .post('/sendRemoteLockAlertEmail', {
                    EMAIL: this.form_input.email,
                    EMAIL_FROM: 'r-russell@tenseiph.com',
                    CHECK_IN_TIME: this.form_input.check_in,
                    CHECK_OUT_TIME: this.form_input.check_out,
                    PIN: this.form_input.pin,
                    USE_ID: this.form_input.user_id,
                    LAST_NAME: this.form_input.last_name,
                    FIRST_NAME: this.form_input.first_name,
                    ROOM_NAME: this.form_input.roomName,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.isSuccess = true
                        this.redirect()
                    } else {
                        this.errorMessage.concat(response.data)
                    }
                })
                .catch(error => {
                    this.errorMessage.push(this.$t('remotelock.errors.sendAlertEmail.general'))
                })
        },
        /**
         * @name redirect
         * @desc Redirect to Management Dashboard
         *
         * @params
         * @returns null
         */
        redirect() {
            if (this.isSuccess) {
                setTimeout(() => {
                    window.location.replace('/management')
                }, 1000)
            }
        },

        /**
         * @name invalidUserProcess
         * @desc do this process when the user is invalid
         */
        invalidUserProcess() {
            this.isSendButtonDisabled = true
            this.errorMessage.push('This user is invalid. Redirect to the home.')
            setTimeout(() => {
                window.location.replace('/management')
            }, 3000)
        },
    },
    watch: {},
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
