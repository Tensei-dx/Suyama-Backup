<template>
    <div class="row mx-3 my-5">
        <div class="col-12 py-2 text-center title-tile">
            <h1 class="title-text">
                Please choose a room to stay
            </h1>
        </div>
        <div class="col-12" style="margin-top:90px;">
            <div class="row">
                <div v-for="book_details in booking_details" :key="book_details.BOOK_ID"
                     class="col-6 my-2 text-center radio">
                    <input class="radio__input" type="radio" @click="roomSelected(book_details.room.ROOM_ID)"
                           name="room-select-id" :id="book_details.room.ROOM_NAME">
                    <label class="radio__label text-uppercase"
                           :for="book_details.room.ROOM_NAME">{{book_details.room.ROOM_NAME}}</label>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12" style="text-align:end">
                    <button type="button" :disabled="isNextButtonDisabled" class="btn next-btn" @click="welcome()">
                        <span class="font-weight-bold text-uppercase">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <Footer :localeLang="this.$attrs.locale" class="bg-light-gray"></Footer>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
export default {
    components: {
        Footer,
    },

    data() {
        return {
            booking_details: [],
            value: '',
            isNextButtonDisabled: true,
        }
    },
    created() {
        this.getBookingRoomDetails()
    },
    methods: {
        getBookingRoomDetails() {
            axios
                .get('getBookRoomDetails')
                .then(response => {
                    this.booking_details = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },

        roomSelected(value) {
            this.value = value
            this.isNextButtonDisabled = false
        },

        welcome() {
            axios
                .post('updateRoomStatusForGuest', {
                    ROOM_ID: this.value,
                })
                .then(response => {
                    window.location = 'welcome'
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },

    // watch: {
    //     value: function () {
    //         this.value = value
    //     },
    // },
}
</script>

<style>
.title-tile {
    background-color: rgba(13, 13, 13, 0.69) !important;
    border-color: transparent !important;
}
.title-text {
    opacity: 1 !important;
    font-weight: 1000;
    color: white;
    font-size: 50px;
}

.radio {
    overflow: hidden;
}
.radio__input {
    display: none;
}
.radio__label {
    padding: 23px 0px;
    width: 530px;
    background: white;
    cursor: pointer;
    border-radius: 15px;
    border: 5px solid rgb(255, 165, 0);
    font-weight: 1000;
    font-size: 28px;
}
.radio__input:checked + .radio__label {
    background: rgb(255, 165, 0);
}
.next-btn {
    background: rgb(127, 127, 127);
    padding: 10px 25px;
    color: white;
}
</style>

