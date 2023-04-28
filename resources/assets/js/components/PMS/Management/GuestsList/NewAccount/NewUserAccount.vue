<!-- UPDATED: TP Shannie SPRINT_12 TASK233 20211015 -->
<template>
    <div class="container key-management-width key-management-scroll">
        <div class="text-white row mx-0" style="background-color: black;">
            <div class="col-12 text-center">
                <i class="fa fa-key fa-3x" aria-hidden="true"></i>
                <span class="font-weight-bold" style="font-size:3rem;">{{ $t('remotelock.adminTitle') }}</span>
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
                    {{$t("management.keyManagementPage.createAdminAccount")}}
                </button>
            </div>
            <div class="col-12 my-3">
                <UserMessage :isSuccess="isSuccess" :errorMessage="errorMessage" />
            </div>
        </div>
        <Footer />
    </div>
</template>

<script>
import UserMessage from '../NewAccount/Message/UserMessage.vue'
import Footer from '../../../Guest/Footer/Footer.vue'

export default {
    components: {
        UserMessage,
        Footer,
    },

    data() {
        return {
            code: null,
            pinLength: 6,
            pinRule: 0,
            remote_lock: [],
            isSendButtonDisabled: false,

            form_input: {
                first_name: null,
                last_name: null,
                email: null,
                phoneNumber: '',
                pin: '',
            },

            isSuccess: false,
            errorMessage: [],
            floors: [],
            floorIds: [],

            errors: {
                first_name: null,
                last_name: null,
                email: null,
                phoneNumber: null,
                pin: null,
            },
        }
    },

    created() {
        // this.getAuthCode()
        this.getRemoteLockPinSettings()
        self = this
        //Get Floor Data from Database
        axios
            .post('getFloorAll')
            .then(response => {
                if (response.data.length > 0) {
                    this.floors = response.data
                    for (var i in this.floors) {
                        this.floorIds.push(this.floors[i]['FLOOR_ID'])
                    }
                }
            })
            .catch(error => console.log(error))
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
            //[Task [0024]
            // const urlParams = new URLSearchParams(window.location.search)
            // if (urlParams.has('code')) {
            //     this.code = urlParams.get('code')
            // } else {
            //     this.isSendButtonDisabled = true
            //     this.errorMessage.push(this.$t('remotelock.errors.authCode.empty'))
            //     this.redirect()
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
            this.errors.email = null
            this.errors.phoneNumber = null
            this.errors.pin = null

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

            // Phone Number Check
            if (this.form_input.pin) {
                if (this.form_input.pin.length < 6) {
                    this.errors.pin = 'invalid'
                    errorFlag = true
                }
            } else {
                this.errors.pin = 'empty'
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
            if (!this.isInputValid()) {
                return
            }
            this.isSendButtonDisabled = true
            this.duplicationPinCheck()
            // this.createRemoteLockUserAccount();
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
                        this.errorMessage.push('PIN IS ALREADY TAKEN')
                    } else {
                        this.createRemoteLockUserAccount()
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * @name createRemoteLockUserAccount
         * @desc Create a remote lock user account
         *
         * @params
         * @returns null
         */
        createRemoteLockUserAccount() {
            axios
                .post('createRemoteLockUserAccount', {
                    // API_ID: 2,
                    // AUTH_CODE: this.code,
                    FIRST_NAME: this.form_input.first_name,
                    LAST_NAME: this.form_input.last_name,
                    EMAIL: this.form_input.email,
                    PIN: this.form_input.pin,
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.createiBMSUserAccount()
                    } else {
                        this.errorMessage = response.data
                    }
                })
                .catch(error => {
                    this.errorMessage.push(this.$t('remotelock.errors.createRemoteLockGuestAccount.general'))
                })
        },

        /**
         * @name createiBMSUserAccount
         * @desc Create an iBMS User account
         *
         * @params
         * @returns null
         */
        createiBMSUserAccount() {
            axios
                .post('createUser', {
                    USERNAME: this.form_input.email, // uses the email as the username for iBMS guest account
                    PASSWORD: this.form_input.pin, // uses the pin as the password for iBMS guest account
                    EMAIL: this.form_input.email,
                    FIRST_NAME: this.form_input.first_name,
                    LAST_NAME: this.form_input.last_name,
                    USER_TYPE: 1,
                    CONTACT_NUMBER: this.form_input.phoneNumber,
                    ALLOW_ALERT_NOTIFICATION: JSON.stringify({
                        email: false,
                        sms: false,
                        voice: false,
                    }),
                    hasFile: 0,
                    file: null,
                    fileName: null,
                    // hasModule: 1,
                    // moduleids: '3,4,5,6,7,8,9',
                    // floorids: this.floorIds.toString(),
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.isSuccess = true
                        this.redirect()
                        // this.createBookAccount();
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
                })
        },

        // /**
        //  * @name createBookAccount
        //  * @desc Create an iBMS Book account
        //  *
        //  * @params
        //  * @returns null
        //  */
        // createBookAccount() {
        //     axios.post('createBookAccount',{
        //         USER_TYPE: 1,
        //         FIRST_NAME: this.form_input.first_name,
        //         LAST_NAME: this.form_input.last_name,
        //         EMAIL: this.form_input.email,
        //         CONTACT_NUMBER: this.form_input.phoneNumber,
        //         PIN: this.form_input.pin
        //     })
        //     .then(response=> {
        //         if (response.data == 'success') {
        //             this.isSuccess = true;
        //             this.redirect();
        //         }
        //     })
        //     .catch(error=> {
        //         console.log(error)
        //     })
        // },

        /**
         * @name redirectToDashboard
         * @desc Redirect to Management Dashboard
         *
         * @params
         * @returns null
         */
        redirect() {
            if (this.isSuccess) {
                setTimeout(() => {
                    window.location.replace('/management')
                }, 3000)
            }
        },
    },

    computed: {
        /**
         * @name generatePIN
         * @desc Determine how will the PIN Code be generated
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
