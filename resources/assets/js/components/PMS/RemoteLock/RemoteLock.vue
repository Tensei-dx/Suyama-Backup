<!--
Created : xxxx.xx.xx
Updated : 2021.04.05 TP Chris Added createUser features
          2021.04.07 TP Uddin Added getRemoteLockPinSettings features
          2021.04.08 TP Uddin Added phone number input field
-->

<template>
<div class="container key-management-width key-management-scroll">
    <div class="text-white row mx-0" style="background-color: black;">
        <div class="col-12 text-center">
            <i class="fa fa-key fa-3x" aria-hidden="true"></i>
            <!-- <span class="font-weight-bold" style="font-size:3rem;">{{ $t('management.key') }}</span> -->
        </div>
        <div class="col-12 mb-3">
            <form class="font-weight-bold px-2">

                <!-- First Name -->
                <div class="form-group row">
                    <label for="firstName" class="col col-form-label">{{ $t('remotelock.firstname') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="text"
                            v-model="form_input.first_name"
                            id="firstName"
                            @keydown="errors.first_name = null"
                        />
                    </div>
                    <span v-if="errors.first_name == 'empty'" class="text-danger col">{{ $t('remotelock.errors.firstName.empty') }}</span>
                </div>

                <!-- Last Name -->
                <div class="form-group row">
                    <label for="lastName" class="col col-form-label">{{ $t('remotelock.lastname') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="text"
                            v-model="form_input.last_name"
                            id="lastName"
                            @keydown="errors.last_name = null"
                        />
                    </div>
                    <span v-if="errors.last_name == 'empty'" class="text-danger col">{{ $t('remotelock.errors.lastName.empty') }}</span>
                </div>

                <!-- Check In DateTime -->
                <div class="form-group row">
                    <label for="checkIn" class="col col-form-label">{{ $t('remotelock.checkin') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="datetime-local"
                            v-model="form_input.check_in"
                            id="checkIn"
                            @change="errors.check_in = null"
                        />
                    </div>
                    <span v-if="errors.check_in == 'empty'" class="text-danger col">{{ $t('remotelock.errors.checkIn.empty') }}</span>
                    <span v-else-if="errors.check_in == 'later'" class="text-danger col">{{ $t('remotelock.errors.checkIn.later') }}</span>
                </div>

                <!-- Check Out DateTime -->
                <div class="form-group row">
                    <label for="checkOut" class="col col-form-label">{{ $t('remotelock.checkout') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="datetime-local"
                            v-model="form_input.check_out"
                            id="checkOut"
                            @change="errors.check_out = null"
                        />
                    </div>
                    <span v-if="errors.check_out == 'empty'" class="text-danger col">{{ $t('remotelock.errors.checkOut.empty') }}</span>
                    <span v-else-if="errors.check_out == 'earlier'" class="text-danger col">{{ $t('remotelock.errors.checkOut.earlier') }}</span>
                </div>

                <!-- Email -->
                <div class="form-group row">
                    <label for="email" class="col col-form-label">{{ $t('remotelock.email') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="text"
                            v-model="form_input.email"
                            id="email"
                            placeholder="example@email.com"
                            @keydown="errors.email = null"
                        />
                    </div>
                    <span v-if="errors.email == 'invalid'" class="text-danger col">{{ $t('remotelock.errors.email.invalid') }}</span>
                    <span v-else-if="errors.email == 'empty'" class="text-danger col">{{ $t('remotelock.errors.email.empty') }}</span>
                </div>

                <!-- Phone Number -->
                <div class="form-group row">
                    <label for="phone_number" class="col col-form-label">{{ $t('remotelock.phoneNumber') }}:</label>
                    <div class="col-12">
                        <input
                            type="number"
                            class="form-control"
                            v-model="form_input.phoneNumber"
                            id="phone_number"
                            @keydown="errors.phoneNumber = null"
                        />
                    </div>
                    <span v-if="errors.phoneNumber == 'empty'" class="text-danger col">{{ $t('remotelock.errors.phoneNumber.empty') }}</span>
                </div>

                <!-- PIN Code -->
                <div class="form-group row">
                    <label for="pin" class="col col-form-label">{{ $t('remotelock.pin') }}:</label>
                    <div class="col-12">
                        <input
                            class="form-control"
                            type="number"
                            v-model="generatePIN"
                            id="pin"
                            readonly
                        />
                    </div>
                </div>

                <!-- Remote Lock Device -->
                <div class="form-group row">
                    <label for="device" class="col col-form-label">{{ $t('remotelock.device') }}:</label>
                    <div class="col-12">
                        <div>
                            <select
                                class="form-control"
                                v-model="form_input.remoteLockId"
                                @change="updateRoomName(); errors.device = null"
                                >
                                <option
                                    v-for="remote in remote_lock"
                                    :key="remote.DEVICE_ID"
                                    :label="remote.DEVICE_NAME"
                                    >
                                    {{ remote.DATA.remote_lock_id }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <span v-if="errors.device == 'empty'" class="text-danger col">{{ $t('remotelock.errors.remoteLockId.empty') }}</span>
                </div>
            </form>
        </div>

        <!-- Back to Management Dashboard button -->
        <div class="col-6 text-left pl-4">
            <a href="/management" class="btn btn-custom-color-rl col-3 font-weight-bold text-black text-decoration-none">{{ $t('remotelock.back') }}</a>
        </div>

        <!-- Submit button -->
        <div class="col-6 text-right pr-4">
            <button type="button"
                class="btn btn-custom-color-rl col-3 font-weight-bold"
                @click="submit()"
                :disabled="isSendButtonDisabled"
                >
                {{ $t('remotelock.send') }}
            </button>
        </div>

        <div class="col-12 my-3">
            <Message
                :email="form_input.email"
                :isSuccess="isSuccess"
                :errorMessage="errorMessage"
            />
        </div>
    </div>
    <Footer />
</div>
</template>

<script>
import Message from '../RemoteLock/Message/Message.vue'
import Footer from '../Guest/Footer/Footer.vue'
import { DateTime } from '@syncfusion/ej2-charts'

export default {
    components: {
        Message, Footer
    },

    data() {
        return {
            dsdadadadadad:'',
            code: null,
            pinLength: 4,
            pinRule: 0,
            remote_lock: [],
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
                device: null,
            },
        }
    },

    created() {
        this.getAuthCode()
        this.getRemoteLockPinSettings()
        this.getRemoteLockDevices()
    },

    methods: {
        /**
         *
         */
        getAuthCode() {
            const urlParams = new URLSearchParams(window.location.search)
            if (urlParams.has('code')) {
                this.code = urlParams.get('code')
            } else {
                this.isSendButtonDisabled = true
                this.errorMessage.push(this.$t('remotelock.errors.authCode.empty'))
                this.redirectToDashboard()
            }

        },

        /**
         *
         */
        getRemoteLockPinSettings() {
            axios.get('getRemoteLockPinSettings')
            .then(response => {
                this.pinLength = response.data.numOfPin
                this.pinRule = response.data.pinRule
                this.form_input.pin = response.data.pin
            })
            .catch(error => console.log(error))
        },

        /**
         *
         */
        getRemoteLockDevices() {
            axios.get('getDeviceType', {
                params: {
                    DEVICE_TYPE: 'remote_lock',
                    REG_FLAG: 1,
                }
            })
            .then(response => this.remote_lock = response.data)
            .catch(error => console.log(error))
        },

        /**
         *
         */
        updateRoomName() {
            for (let i in this.remote_lock) {
                if (this.remote_lock[i].DATA['remote_lock_id'] === this.form_input.remoteLockId) {
                    this.form_input.roomName = this.remote_lock[i].room.ROOM_NAME
                    this.form_input.floorID = this.remote_lock[i].FLOOR_ID
                }
            }
        },

        /**
         *
         */
        isInputValid() {
            let errorFlag = false

            this.errors.first_name = null
            this.errors.last_name = null
            this.errors.check_in = null
            this.errors.check_out = null
            this.errors.email = null
            this.errors.phoneNumber = null
            this.errors.device = null

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
                if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.form_input.email)) {
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
            if (!this.form_input.remoteLockId) {
                this.errors.device = 'empty'
                errorFlag = true
            }

            return !errorFlag
        },

        /**
         *
         */
        submit() {
            if (!this.isInputValid()) {
                return
            }
            this.isSendButtonDisabled = true
            this.createRemoteLockGuestAccount()
        },

        /**
         * @name createRemoteLockGuestAccount
         * @desc Create a Remote Lock guest user using the Remote Lock API
         */
        createRemoteLockGuestAccount() {
            axios.post('createRemoteLockGuestAccount', {
                API_ID: 2,
                AUTH_CODE: this.code,
                FIRST_NAME: this.form_input.first_name,
                LAST_NAME: this.form_input.last_name,
                CHECK_IN_TIME: this.form_input.check_in,
                CHECK_OUT_TIME: this.form_input.check_out,
                PIN: this.form_input.pin,
                DEVICE_ID: this.form_input.remoteLockId
            })
            .then(response => {
                if (response.data == 'success') {
                    this.createiBMSGuestAccount()
                } else {
                    this.errorMessage = response.data
                }
            })
            .catch(error => {
                this.errorMessage.push(this.$t('remotelock.errors.createRemoteLockGuestAccount.general'))
            });
        },

        /**
         * @name createiBMSGuestAccount
         * @desc Create User to iBMS
         */
        createiBMSGuestAccount() {
            axios.post('createUser', {
                USERNAME: this.form_input.email,        // uses the email as the username for iBMS guest account
                PASSWORD: this.form_input.pin,          // uses the pin as the password for iBMS guest account
                EMAIL: this.form_input.email,
                USER_TYPE: 2,
                CONTACT_NUMBER: this.form_input.phoneNumber,
                ALLOW_ALERT_NOTIFICATION: JSON.stringify({
                    email: false,
                    sms: false,
                    voice: false
                }),
                hasFile: 0,
                file: null,
                fileName: null,
                hasModule: 1,
                moduleids: '1',         // Module 1: Dashboard
                floorids: this.form_input.floorID.toString(),
            })
            .then(response => {
                if (response.data == 'success') {
                    this.sendAlertEmail();
                } else if (response.data == 'duplicate') {
                    this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.duplicate'))
                    // this.deleteRemoteLockGuestAccount()
                } else {
                    this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.general'))
                    // this.deleteRemoteLockGuestAccount()
                }
            })
            .catch(error => {
                this.errorMessage.push(this.$t('remotelock.errors.createiBMSGuestAccount.general'))
                // this.deleteRemoteLockGuestAccount()
            });
        },

        // deleteRemoteLockGuestAccount() {
        //     axios.post('deleteRemoteLockGuestAccount', {
        //         token: this.token,
        //         guestAccountId: this.remoteLockGuestAccountId
        //     })
        //     .then(response => {
        //         console.log('deleteRemoteLockGuestAccount process successful')
        //     })
        //     .catch(error => {
        //         console.log('deleteRemoteLockGuestAccount process failed')
        //     });
        // },

        /**
         * @name sendAlertEmail
         * @desc Send Email with the Remote Lock PIN code and iBMS Guest account
         */
        sendAlertEmail() {
            axios.post('sendRemoteLockAlertEmail', {
                EMAIL: this.form_input.email,
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
            });
        },

        /**
         *
         */
        redirectToDashboard() {
            if (this.isSuccess) {
                setTimeout(() => {
                    window.location.replace('/management')
                }, 3000)
            }
        }
    },

    computed: {

        /**
         * @name generatePIN
         * @desc Determine how will the PIN Code be generated
         */
        generatePIN: function () {
            if (this.pinRule == 0) {
                return this.form_input.pin
            } else if (this.pinRule == 1) {
                return this.getPinFromPhoneNumber
            }
        },

        /**
         * @name getPinFromPhoneNumber
         * @desc Generate the PIN code from the last digits of the phone number
         */
        getPinFromPhoneNumber: function () {
            this.form_input.pin = this.form_input.phoneNumber.substr(-this.pinLength)
            return this.form_input.pin
        }
    },
}
</script>

<style>
.btn-custom-color-rl {
    background-color: #b4c7e7;
}
/* .key-management-scroll{
    overflow-y: auto;
    max-height: 620px;
} */
.key-management-width {
    max-width: 895px !important;
}
</style>

