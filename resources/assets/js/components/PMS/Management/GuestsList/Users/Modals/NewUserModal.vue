<template>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">ADD NEW USER ACCOUNT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
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

                            <!-- Username -->
                            <div class="form-group row">
                                <label for="username" class="col col-form-label">USERNAME:</label>
                                <div class="col-12">
                                    <input
                                        class="form-control"
                                        type="text"
                                        v-model="form_input.username"
                                        id="username"
                                        @keydown="errors.username = null"
                                    />
                                </div>
                                <span v-if="errors.username == 'empty'" class="text-danger col">{{ $t('remotelock.errors.lastName.empty') }}</span>
                            </div>

                            <!-- Password -->
                            <div class="form-group row">
                                <label for="password" class="col col-form-label">PASSWORD:</label>
                                <div class="col-12">
                                    <input
                                        class="form-control"
                                        type="password"
                                        v-model="form_input.password"
                                        id="password"
                                        @keydown="errors.password = null"
                                    />
                                </div>
                                <span v-if="errors.password == 'empty'" class="text-danger col">{{ $t('remotelock.errors.lastName.empty') }}</span>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group row">
                                <label for="confirmPassword" class="col col-form-label">CONFIRM PASSWORD:</label>
                                <div class="col-12">
                                    <input
                                        class="form-control"
                                        type="password"
                                        v-model="form_input.confirmPassword"
                                        id="confirmPassword"
                                        @keydown="errors.confirmPassword = null"
                                        @input="checkPassword()"
                                    />
                                </div>
                                <span v-if="errors.confirmPassword == 'wrong'" class="text-danger col">Password didn't match</span>
                                <span v-if="errors.confirmPassword == 'empty'" class="text-danger col">Enter Password again</span>
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
                        </form>
                    </div>

                    <!-- Submit button -->
                    <div class="col text-right pr-4">
                        <button type="button"
                            class="btn btn-custom-color-rl font-weight-bold"
                            @click="submit()"
                            :disabled="isSendButtonDisabled"
                            >
                            Create User Account
                        </button>
                    </div>
                    <div class="col text-right pr-4" v-if="this.isSuccess == true">
                        <span class="alert alert-success" role="alert">Success</span>
                        <button type="button"
                            class="btn btn-custom-color-rl font-weight-bold"
                            @click="redirect()"
                            >
                            Okay
                        </button>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ $t('management.logoutbtn.cancel') }}
                    </button>
                    <form method="POST" @submit="logoutFunction">
                        <button type="submit" class="btn btn-danger">
                            {{ $t('management.logoutbtn.logout') }}
                        </button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
    },

    data() {
        return {
            code: null,
            remote_lock: [],
            isSendButtonDisabled: false,

            form_input: {
                first_name: null,
                last_name: null,
                username: null,
                password: null,
                confirmPassword: null,
                email: null,
                phoneNumber: ''
            },

            isSuccess: false,
            errorMessage: [],
            floors: [],
            floorIds: [],

            errors: {
                first_name: null,
                last_name: null,
                username: null,
                password: null,
                confirmPassword: null,
                email: null,
                phoneNumber: null
            },
        }
    },

    created() {
        self = this;
            //Get Floor Data from Database
            axios.post('getFloorAll')
                .then(response=>{
                    if(response.data.length > 0){
                        this.floors = response.data;
                        for (var i in this.floors) {
                            this.floorIds.push(this.floors[i]['FLOOR_ID']);
                        }
                    }
                })
                .catch((error)=> console.log(error));

    },

    methods: {
        checkPassword(){
            if(this.form_input.password == this.form_input.confirm_password){
                this.errors.confirmPassword = "";
            }else{
                this.errors.confirmPassword = "wrong";
            }
        },
        isInputValid() {
            let errorFlag = false
            this.errors.first_name = null
            this.errors.last_name = null
            this.errors.username = null
            this.errors.password = null
            this.errors.confirmPassword = null
            this.errors.email = null
            this.errors.phoneNumber = null

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
            if (!this.form_input.username) {
                this.errors.username = 'empty'
                errorFlag = true
            }

            // Check Out DateTime Input Check
            if (!this.form_input.password) {
                this.errors.password = 'empty'
                errorFlag = true
            }

            if (!this.form_input.confirmPassword) {
                this.errors.confirmPassword = 'empty'
                errorFlag = true
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
            this.createiBMSUserAccount();
        },

        createiBMSUserAccount() {
            axios.post('createUser', {
                USERNAME: this.form_input.username,        // uses the email as the username for iBMS guest account
                PASSWORD: this.form_input.password,          // uses the pin as the password for iBMS guest account
                EMAIL: this.form_input.email,
                USER_TYPE: 1,
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
                moduleids: '3,4,5,6,7,8,9',
                floorids: this.floorIds.toString(),
            })
            .then(response => {
                if (response.data == 'success') {
                    this.isSuccess = true;
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

        redirect() {
            if (this.isSuccess) {
                setTimeout(() => {
                    window.location.replace('/management')
                }, 1000)
            }
        }
    }
}
</script>
