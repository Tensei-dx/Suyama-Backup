<template>
    <div class="modal d-block" tabindex="-1">
        <div class="modal-background" @click="closeModal()"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{ $t('remotelock.updateGuestAccount')}}</h5>
                    <button type="button" class="close" @click="closeModal()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <div class="col-12 mb-3">
                        <form class="font-weight-bold px-2">

                            <!-- [Task 001] 20210722 -->
                            <!-- Status -->
                            <div class="form-group row">
                                <label for="status" class="col col-form-label">{{ $t('remotelock.status') }}:</label>
                                <div class="col-12">
                                    <select class="form-control" v-model="form_input.room_status" @change="changeRoomStatus">
                                        <option value="201">{{ $t('remotelock.checkedIn') }}</option>
                                        <option value="202">{{ $t('remotelock.checkedOut') }}</option>
                                        <option value="203">{{ $t('remotelock.available') }}</option>
                                        <option value="204">{{ $t('remotelock.unavailable') }}</option>
                                        <option value="205">{{ $t('remotelock.booked') }}</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                         <!-- [Task 001] 20210722 -->
                        <form class="font-weight-bold px-2" id="booking-form">
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

                            <!-- PIN Code -->
                            <div class="form-group row">
                                <label for="pin" class="col-md-12 col-form-label">{{ $t('remotelock.pin') }}:</label>
                                <div class="row col">
                                    <div class="col-8">
                                        <input class="form-control"
                                            type="number"
                                            v-model="form_input.pin"
                                            id="pin"
                                            readonly/>
                                    </div>
                                    <div class="col-4 px-0">
                                        <button type="button"
                                        class="btn btn-custom-color-rl font-weight-bold"
                                        @click="genPin()">
                                        {{$t("management.keyManagementPage.generateNewPin")}}
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            <span v-if="errors.pin == 'empty'" class="text-danger col">{{$t("remotelock.errors.pin.empty")}}</span>
                            <span v-if="errors.pin == 'invalid'" class="text-danger col">{{$t("remotelock.errors.pin.invalid")}}</span>
                            <span v-if="errors.pin == 'duplicate'" class="text-danger col">{{$t("remotelock.errors.pin.duplicate")}}</span>
                            
                        </form>
                    </div>
                    <!-- Task[001] -->
                    <!-- <div class="col text-center pt-4 pr-4" v-if="this.isSuccess == true">
                        <span class="alert alert-success text-center" role="alert">{{$t("management.keyManagementPage.successUpdatingGuestAccount")}}</span>
                        <div class="col text-center pt-4 pr-4">
                            <button type="button"
                                class="btn btn-custom-color-rl font-weight-bold"
                                @click="redirect()"
                                >
                                {{$t("management.keyManagementPage.ok")}}
                            </button>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-custom-color-rl font-weight-bold"
                        @click="submit()"
                        :disabled="isSendButtonDisabled"
                        >
                        {{$t("management.keyManagementPage.updateGuestAccount")}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            booking_data:'',
        },

        data() {
            return {
                isSendButtonDisabled: false,
                isSuccess: false,
                default_room_status:'',
                change_room_status_mode:false,
                form_input: {
                    guest_id:'',
                    first_name: null,
                    last_name: null,
                    pin: null,
                    email: null,
                    check_in: null,
                    check_out: null,
                    room_status:201,
                },
                errors: {
                    first_name: null,
                    last_name: null,
                    email: null,
                    check_in: null,
                    check_out: null,
                    pin:null,
                },
            }
        },//
        created(){

                
                // [Task 001]
                //if Remote Lock Id exist, input to guest ID.
                this.form_input.guest_id    = this.REMOTE_LOCK_STATUS ? this.REMOTE_LOCK_INFO.id : '-';
                this.form_input.first_name  = this.booking_data.FIRST_NAME;
                this.form_input.last_name   = this.booking_data.LAST_NAME;
                this.form_input.email       = this.booking_data.EMAIL;
                this.form_input.check_in    = new Date(this.booking_data.bookings_with_room[0].CHECK_IN_TIME).toISOString().substring(0,16);
                this.form_input.check_out   = new Date(this.booking_data.bookings_with_room[0].CHECK_OUT_TIME).toISOString().substring(0,16);
                this.form_input.pin         = this.booking_data.bookings_with_room[0].PIN;
                this.form_input.room_status = this.booking_data.bookings_with_room[0].room.STATUS_ID;
                
                
                //Set to default Room status in case of changing Room Status
                this.default_room_status =  JSON.parse(JSON.stringify(this.booking_data.bookings_with_room[0].room.STATUS_ID));
                this.default_pin =          JSON.parse(JSON.stringify(this.booking_data.bookings_with_room[0].PIN));

                //Change Room Status
                this.changeRoomStatus()
        },
        methods:{
            /**
             * @name isInputValid
             * @desc Check if inputs are all valid
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
                axios.get('getRemoteLockPinSettings')
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
                if(this.change_room_status_mode == true){

                    //Update room status
                    this.updateRoomStatus();
                }else{
                    if (!this.isInputValid()) {
                        return
                    }
                    //check if pin and email is duplicated
                    this.duplicationPinCheck();
                }
            },

            /**
             * @name duplicationPinCheck
             * @desc Check if PIN is already taken
             *
             * @params
             * @returns null
             */
            duplicationPinCheck () {
                axios.post('duplicationPinCheck', {
                    TYPE: 'update',
                    OLD_PIN: this.default_pin,
                    PIN:this.form_input.pin,
                    ROOM_ID:this.booking_data.bookings_with_room[0].ROOM_ID,
                })
                .then((response) =>{
                    if (response.data == 'duplicate') {
                        this.errors.pin = 'duplicate';
                    } else {
                        this.updateGuestAccount();
                    }
                })
                .catch((error)=> {
                    console.log(error)
                })
            },

            /**
             * @name updateRoomStatus
             * @desc Update Room Status
             *
             * @params
             * @returns null
             */
            updateRoomStatus(){

                axios.post('client/room/status/update', {
                    ROOM_ID: this.booking_data.bookings_with_room[0].ROOM_ID,
                    STATUS_ID: this.form_input.room_status
                })
                .then(response => {
                    if(response.data){
                        
                        this.$bus.emit("updateAccountList");
                        this.$swal({
                            type:'success',
                            title: this.$t('modalText.editSuccess'),
                            showConfirmButton:false,
                            timer:1500
                        }).then(()=>{
                            this.closeModal();
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            /**
             * @name updateGuestAccount
             * @desc Update a guest account
             *
             * @params
             * @returns null
             */
            updateGuestAccount() {

                axios.post('updateGuestAccount', {
                    GUEST_ID:   this.guest_id,
                    CHECK_IN:   this.form_input.check_in,
                    CHECK_OUT:  this.form_input.check_out,
                    PIN:        this.form_input.pin,
                    USERNAME:   this.form_input.email,
                    EMAIL:      this.form_input.email,
                    LAST_NAME:  this.form_input.last_name,
                    FIRST_NAME: this.form_input.first_name,
                    STATUS_ID:  this.form_input.room_status,
                    REMOTE_LOCK_STATUS: this.booking_data.REMOTE_LOCK_STATUS,
                    
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.$bus.emit("updateAccountList");
                        this.isSuccess = true;

                        //Show Confirmation Modal
                        this.$swal({
                        type:'success',
                        title: this.$t('modalText.editSuccess'),
                        showConfirmButton:false,
                        timer:1500
                        }).then(()=>{
                            this.closeModal();
                        });
                    }
                })
                .catch(error => {
                    console.log(error)
                });
            },

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
                    }, 1000)
                }
            },
            /**
             * @name closeModal
             * @desc Close this Modal
             *
             * @params
             * @returns null
             */
            closeModal(){
                this.$emit("closeModal");
            },
            /**
             * @name changeRoomStatus
             * @desc Change Room Status
             *
             * @params
             * @returns null
             */
            changeRoomStatus(){
                
                if(this.default_room_status != this.form_input.room_status){
                    this.change_room_status_mode = true;
                    $('#booking-form').slideUp();
                }else{
                    this.change_room_status_mode = false;
                    $('#booking-form').slideDown();
                }
            }
        },
        watch:{
        }
    }
</script>
