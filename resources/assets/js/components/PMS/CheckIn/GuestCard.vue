<!-- CRETED: TDN　Okada SPRINT_11 TASK218 20211012 -->

<template>
    <div>
        <div class="guestcard-scroll">
            <form id="guest-card-form" @submit.prevent="isInputValid">
                <div class="text-black bg-white row mx-0">
                    <div class="col-12 mb-3 text-left">
                        <b-form-radio-group id="btn-radios-2" v-model="selected" :options="options"
                                            button-variant="outline-primary" size="lg" name="radio-btn-outline" buttons
                                            class="my-4" />

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" v-text="$t('guestcard.name')" />
                            <input id="name" type="text" class="form-control" v-model.trim="form.name"
                                   @input="errors.name = null" />
                            <span v-show="errors.name" class="text-danger" v-text="$t('guestcard.errors.name.empty')" />
                        </div>

                        <!-- Sex -->
                        <div class="form-group">
                            <label for="sex" v-text="$t('guestcard.sex')" />
                            <select id="sex" v-model.number="form.sex" class="custom-select">
                                <option value="1">{{ $t('guestcard.male') }}</option>
                                <option value="2">{{ $t('guestcard.female') }}</option>
                                <option value="3">{{ $t('guestcard.others') }}</option>
                            </select>
                            <span v-show="errors.sex" class="text-danger" v-text="$t('guestcard.errors.sex.empty')" />
                        </div>

                        <!-- Age -->
                        <div class="form-group">
                            <label for="age" v-text="$t('guestcard.age')" />
                            <input id="age" type="number" v-model.number="form.age" class="form-control" min="0"
                                   @input="errors.age = null" />
                            <span v-show="errors.age" class="text-danger" v-text="$t('guestcard.errors.age.empty')" />
                        </div>

                        <!-- Occupation -->
                        <div class="form-group">
                            <label for="occupation" v-text="$t('guestcard.occupation')" />
                            <input id="occupation" type="text" class="form-control" v-model.trim="form.occupation"
                                   @input="errors.occupation = null" />
                            <span v-show="errors.occupation" class="text-danger"
                                  v-text="$t('guestcard.errors.occupation.empty')" />
                        </div>

                        <!-- Tel -->
                        <div class="form-group">
                            <label for="tel" v-text="$t('guestcard.tel')" />
                            <input id="tel" type="tel" class="form-control" v-model.trim="form.tel"
                                   @input="errors.tel = null" />
                            <span v-show="errors.tel" class="text-danger" v-text="$t('guestcard.errors.tel.empty')" />
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" v-text="$t('guestcard.email')" />
                            <input id="email" type="email" class="form-control" v-model.trim="form.email"
                                   @input="errors.email = null" />
                            <span v-if="errors.email === 'invalid'"
                                  class="text-danger">{{ $t('guestcard.errors.email.invalid') }}</span>
                            <span v-else-if="errors.email === 'empty'"
                                  class="text-danger">{{ $t('guestcard.errors.email.empty') }}</span>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address" v-text="$t('guestcard.address')" />
                            <input id="address" type="text" class="form-control" v-model.trim="form.address"
                                   @input="errors.address = null" />
                            <span v-show="errors.address" class="text-danger"
                                  v-text="$t('guestcard.errors.address.empty')" />
                        </div>

                        <!-- Only international -->
                        <div v-if="selected === 'international'">
                            <!-- Passport_URL -->
                            <!-- active camera -->
                            <div class="form-group" v-if="isStatusReady">
                                <label for="passport-url" v-text="$t('guestcard.passportURL')" />
                                <div class="button-relative">
                                    <div class="form-group" v-if="form.passport_url">
                                        <img :src="form.passport_url" width="300" height="200" />
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" aria-hidden="true"
                                                @click="sendCameraModal()">{{ $t('guestcard.upload') }}</button>
                                    </div>
                                </div>
                                <span v-show="errors.passport_url" class="text-danger"
                                      v-text="$t('guestcard.errors.passportURL.empty')" />
                            </div>

                            <!-- Nationality -->
                            <div class="form-group">
                                <label for="nationality" v-text="$t('guestcard.nationality')" />
                                <input id="nationality" type="text" class="form-control" v-model.trim="form.nationality"
                                       @input="errors.nationality = null" />
                                <span v-show="errors.nationality" class="text-danger"
                                      v-text="$t('guestcard.errors.nationality.empty')" />
                            </div>

                            <!-- Passport_No -->
                            <div class="form-group">
                                <label for="passport-no" v-text="$t('guestcard.passportNo')" />
                                <input id="passport-no" type="text" class="form-control" v-model.trim="form.passport_no"
                                       @input="errors.passport_no = null" />
                                <span v-show="errors.passport_no" class="text-danger"
                                      v-text="$t('guestcard.errors.passportNo.empty')" />
                            </div>

                            <!-- Previous_Place -->
                            <div class="form-group">
                                <label for="previous-place" v-text="$t('guestcard.previousPlace') + ':'" />
                                <input id="previous-place" type="text" class="form-control"
                                       v-model.trim="form.previous_place" @input="errors.previous_place = null" />
                                <span v-show="errors.previous_place" class="text-danger"
                                      v-text="$t('guestcard.errors.previousPlace.empty')" />
                            </div>

                            <!-- Next_Destination -->
                            <div class="form-group">
                                <label for="next-destination" v-text="$t('guestcard.nextDestination')" />
                                <input id="next-destination" type="text" class="form-control"
                                       v-model.trim="form.next_destination" @input="errors.next_destination = null" />
                                <span v-show="errors.next_destination" class="text-danger"
                                      v-text="$t('guestcard.errors.nextDestination.empty')" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Dashboard from '../Guest/Dashboard.vue'
import Footer from '../Guest/Footer/Footer.vue'
import CameraCaptureModal from '../CheckIn/Modals/CameraCaptureModal.vue'

export default {
    components: {
        Footer,
        Dashboard,
        CameraCaptureModal,
    },

    name: 'GuestCard',

    props: {
        count: Number,
        reservationMember: Object,
    },

    data() {
        return {
            selected: 'national',
            options: [
                { text: '日本', value: 'national' },
                { text: 'FOREIGN CITIZENS', value: 'international' },
            ],
            status: 'ready',
            sendingForm: true,
            resizedImg: null,
            fileInfo: null,
            res: null,
            form: {
                book_id: null,
                booking_number: null,
                members_id: null,
                member_type: null,
                name: null,
                sex: null,
                age: null,
                occupation: null,
                tel: null,
                email: null,
                address: null,
                passport_url: null,
                passport_path: null,
                file: null,
                nationality: null,
                passport_no: null,
                previous_place: null,
                next_destination: null,
            },

            errorMessage: [],

            errors: {
                members_id: null,
                member_type: null,
                name: null,
                sex: null,
                age: null,
                occupation: null,
                tel: null,
                email: null,
                address: null,
                passport_url: null,
                nationality: null,
                passport_no: null,
                previous_place: null,
                next_destination: null,
            },
        }
    },
    created() {
        if (this.reservationMember !== null) {
            this.form.book_id = this.id
            this.form.booking_number = this.booking_number
            this.form.members_id = this.reservationMember.id
            this.form.member_type = this.reservationMember.member_type
            this.form.name = this.reservationMember.name
            this.form.sex = this.reservationMember.sex
            this.form.age = this.reservationMember.age
            this.form.occupation = this.reservationMember.occupation
            this.form.tel = this.reservationMember.tel
            this.form.email = this.reservationMember.email
            this.form.address = this.reservationMember.address
            this.form.passport_url = this.reservationMember.passport_url
            this.form.nationality = this.reservationMember.nationality
            this.form.passport_no = this.reservationMember.passport_no
            this.form.previous_place = this.reservationMember.previous_place
            this.form.next_destination = this.reservationMember.next_destination
        } else {
            this.invalidUserProcess()
        }
    },
    computed: {
        localReservationMember: {
            get: function () {
                return this.reservationMember
            },
            set: function (value) {
                this.$emit('update:reservationmember', value)
            },
        },
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
            this.errors.name = null
            this.errors.sex = null
            this.errors.age = null
            this.errors.occupation = null
            this.errors.tel = null
            this.errors.email = null
            this.errors.address = null
            this.errors.passport_url = null
            this.errors.nationality = null
            this.errors.passport_no = null
            this.errors.previous_place = null
            this.errors.next_destination = null
            // Name Input Check
            if (!this.form.name) {
                this.errors.name = 'empty'
                errorFlag = true
            }
            // Sex Input Check
            if (!this.form.sex) {
                this.errors.sex = 'empty'
                errorFlag = true
            }
            // Age Input Check
            if (!this.form.age) {
                this.errors.age = 'empty'
                errorFlag = true
            }
            // Occupation Input Check
            if (!this.form.occupation) {
                this.errors.occupation = 'empty'
                errorFlag = true
            }
            // Tel Input Check
            if (!this.form.tel) {
                this.errors.tel = 'empty'
                errorFlag = true
            }
            // Email Format Check
            if (this.form.email) {
                if (
                    !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
                        this.form.email
                    )
                ) {
                    this.errors.email = 'invalid'
                    errorFlag = true
                }
            } else {
                this.errors.email = 'empty'
                errorFlag = true
            }
            // Address Input Check
            if (!this.form.address) {
                this.errors.address = 'empty'
                errorFlag = true
            }
            if (this.selected == 'international') {
                // Passport_URL Input Check
                if (!this.form.passport_url) {
                    this.errors.passport_url = 'empty'
                    errorFlag = true
                }
                // Nationality Input Check
                if (!this.form.nationality) {
                    this.errors.nationality = 'empty'
                    errorFlag = true
                }
                // Passport_No Input Check
                if (!this.form.passport_no) {
                    this.errors.passport_no = 'empty'
                    errorFlag = true
                }
                // Previous_Place Input Check
                if (!this.form.previous_place) {
                    this.errors.previous_place = 'empty'
                    errorFlag = true
                }
                // Next_Destination Input Check
                if (!this.form.next_destination) {
                    this.errors.next_destination = 'empty'
                    errorFlag = true
                }
            }
            return !errorFlag
        },
        // addGuestDetails(book_id, book_no) {
        //     axios
        //         .post('addGuestDetails', {
        //             BOOK_ID: book_id,
        //             BOOK_NO: book_no,
        //             MEMBERS_ID: this.form.members_id,
        //             MEMBER_TYPE: this.form.member_type,
        //             NAME: this.form.name,
        //             SEX: this.form.sex,
        //             AGE: this.form.age,
        //             OCCUPATION: this.form.occupation,
        //             TEL: this.form.tel,
        //             EMAIL: this.form.email,
        //             ADDRESS: this.form.address,
        //             PASSPORT_URL: this.form.passport_path,
        //             NATIONALITY: this.form.nationality,
        //             PASSPORT_NO: this.form.passport_no,
        //             PREVIOUS_PLACE: this.form.previous_place,
        //             NEXT_DESTINATION: this.form.next_destination,
        //         })
        //         .then(response => {})
        // },
        // getImagePath() {
        //     if (this.selected == 'international') {
        //         const formData = new FormData()
        //         formData.append('file', this.fileInfo)

        //         axios.post('imageStore', formData).then(response => (this.res = response.data))
        //         axios
        //             .post('urlStore', { MEMBERS_ID: this.form.members_id })
        //             .then(response => (this.form.passport_path = response.data))
        //     }
        // },
        // getCSV() {
        //     axios.post('csvStore', { MEMBERS_ID: this.form.members_id }).then(response => (this.res = response.data))
        // },
        updateURL(url, file, fileInfo) {
            this.form.passport_url = url
            this.form.file = file
            this.fileInfo = fileInfo
        },
        sendCameraModal() {
            this.$emit('modalEvent', this.form.passport_url)
        },
    },
    computed: {
        isStatusReady() {
            return this.status === 'ready'
        },
        isStatusCropping() {
            return this.status === 'cropping'
        },
        isStatusSubmitting() {
            return this.status === 'submitting'
        },
    },
    watch: {},
}
</script>

<style>
.bg-light-blue {
    background-color: #b4c7e7;
}
.fg-light-blue {
    color: #b4c7e7;
}
.bg-dark-gray {
    background-color: #262626;
}
.fg-dark-gray {
    color: #262626;
}
.bg-light-gray {
    background-color: lightgray;
}
.h-60 {
    height: 60px;
}
.example-slide {
    align-items: center;
    background-color: #666;
    color: #999;
    display: flex;
    font-size: 1.5rem;
    justify-content: center;
    min-height: 10rem;
}

.guestcard-scroll {
    overflow-y: scroll;
    height: 420px;
    width: 100%;
}
.guestcard-scroll::-webkit-scroll-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    background-color: #f5f5f5;
}
.guestcard-scroll::-webkit-scrollbar {
    width: 7px;
    background-color: transparent;
}
.guestcard-scroll::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-color: #b3b1ae;
}

.button-relative {
    position: relative;
}
.button-bottom {
    position: absolute;
    bottom: 0;
}

.form-group {
    font-size: 18px !important;
}
.form-control {
    font-size: 18px !important;
}
.custom-select {
    font-size: 18px !important;
}
</style>
