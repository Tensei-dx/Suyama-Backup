<template>
    <div class="no-gutters p-4 h-100 justify-content-center text-center">
        <div class="col-12 d-block custom-bg-title-darkgray px-3">
            <div class="text-white text-center">
                <span class="text-white text-center"
                      style="font-size: 2.75rem; text-align: center;">{{ $t('guestcard.guestcard') }}</span>
            </div>
        </div>

        <div class="container key-management-width key-management-scroll">
            <div class="col-12 text-center p-3">
                <div id="carousel-guest-card" class="carousel slide" data-ride="false" data-interval="false"
                     data-wrap="false">
                    <div class="carousel-inner">
                        <div v-for="(member, index) in guestData.reservation_members" :key="member.id"
                             class="carousel-item" :class="slideNumber === index ? 'active' : ''">
                            <GuestCard @modalEvent="showCamera" :reservationMember="member" ref="guestCard" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button class="btn btn-danger" @click="prev"
                    :disabled="slideNumber === 0">{{ $t('guestcard.prev') }}</button>

            <span
                  class="h1 col-1 text-center text-white custom-bg-title-darkgray">{{ (slideNumber + 1) + '/' + person_number }}</span>

            <button v-if="(slideNumber + 1) < person_number" class="btn btn-primary"
                    @click="next">{{ $t('guestcard.next') }}</button>
            <button v-else class="btn btn-primary" @click="end">{{ $t('guestcard.complete') }}</button>
        </div>
        <Footer :locale="this.$attrs.locale" class="bg-light-gray"></Footer>
        <CameraCaptureModal @updateURLEvent="updateURLCatch" id="cameraCaptureModal" :passport_url="this.url" />
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
import GuestCard from './GuestCard.vue'
import CameraCaptureModal from '../CheckIn/Modals/CameraCaptureModal.vue'
export default {
    components: {
        Footer,
        GuestCard,
        CameraCaptureModal,
    },

    name: 'DemoGuestCardNumber',

    props: {
        guestData: Object,
    },

    data() {
        return {
            classActive: 'carousel-item active',
            classInActive: 'carousel-item',
            slideNumber: 0,
            booking_id: null,
            person_number: null,
            members_id: null,
            room_id: null,
            url: null,
            sendingForm: true,
            resizeImg: null,
        }
    },
    created() {
        this.booking_id = this.guestData.id
        this.booking_no = this.guestData.booking_number
        this.person_number = this.guestData.person_number
        this.members_id = this.guestData.reservation_members.id
    },
    methods: {
        /**
         * @name prev
         * @desc Go to previous slide
         */
        prev() {
            $('#carousel-guest-card.carousel').carousel('prev')
        },
        /**
         * @name next
         * @desc Go to next slide
         */
        next() {
            if (this.$refs.guestCard[this.slideNumber].isInputValid()) {
                // this.$refs.guestCard[this.slideNumber].getImagePath()
                // this.$refs.guestCard[this.slideNumber].addGuestDetails(this.booking_id, this.booking_no)
                // this.$refs.guestCard[this.slideNumber].getCSV()
                $('#carousel-guest-card.carousel').carousel('next')
            }
        },
        /**
         * @name end
         * @desc Execute this function at the end of the carousel
         */
        end() {
            if (this.$refs.guestCard[this.slideNumber].isInputValid()) {
                // this.$refs.guestCard[this.slideNumber].getImagePath()
                // this.$refs.guestCard[this.slideNumber].addGuestDetails(this.booking_id, this.booking_no)
                // this.$refs.guestCard[this.slideNumber].getCSV()
                window.location = 'terms'
            }
        },
        showCamera(passport_url) {
            this.url = passport_url
            $('#cameraCaptureModal').modal('show')
        },
        updateURLCatch(passport_url, file, fileInfo) {
            this.$refs.guestCard[this.slideNumber].updateURL(passport_url, file, fileInfo)
        },
    },

    mounted() {
        const self = this
        $('#carousel-guest-card.carousel').on('slide.bs.carousel', event => (self.slideNumber = event.to))
    },
}
</script>
