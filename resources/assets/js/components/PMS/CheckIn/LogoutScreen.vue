<template>
    <div>
        <div class="container pt-3 text-center align-middle">
            <div class="header-title text-center align-middle font-weight-normal">
                {{ $t('mobile.checkout.checkOutTitle') }}
            </div>
            <div class="content-container text-justify pt-3">
                <div id="termsOfUse" v-if="$i18n.locale === 'ja'">
                    <div class="px-md-5 py-4">
                        <ul class="ty-font">
                            <li>{{ $t('logoutScreen.content1') }}</li><br>
                            <li>{{ $t('logoutScreen.content2') }}<br>
                                {{ $t('logoutScreen.content3') }}<br>
                                {{ $t('logoutScreen.content4') }}
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- <div id="termsOfUse" v-else-if="$i18n.locale === 'en'">
                    <div>
                        <div class="px-md-5 py-4">
                            <ul class="ty-font">
                                <li>Please pay for any additional orders at the front desk.</li><br>
                                <li>Please check if you have not forgotten anything before leaving the building.<br>
                                    If you forget something after leaving the room, open the smart lock and enter the
                                    room until check-out time.<br>
                                    If it is past the check-out time, please come to the front desk.<br>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                <div class="px-md-5 py-3">
                    <button class="btn btn-outline-secondary btn-lg"
                            @click="goToLogin()">{{ $t('mobile.checkout.checkInScreen') }}</button>
                    <div class="pt-3">
                        <p>{{ $t('mobile.checkout.caution') }}</p>
                    </div>
                </div>
            </div>
            <Footer class="bg-light-gray"></Footer>
        </div>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
export default {
    components: {
        Footer,
    },
    data() {
        return {}
    },
    methods: {
        goToLogin() {
            window.location = 'login'
        },
    },
    created() {
        setTimeout(function () {
            window.location = 'login'
        }, 60000)
    },
}
</script>

<style scoped>
.container {
    max-width: 1700px;
    height: 100% !important;
    width: 100% !important;
    margin: 0 auto !important;
}
.next-line {
    padding-top: 1rem;
}
.vertical-text {
    writing-mode: vertical-rl;
    text-orientation: mixed;
}
.header-num {
    font-size: 16px;
}
.header-title {
    margin-top: 50px;
    max-width: 1500px;
    margin: 0 auto;
    font-size: 50px;
    color: #fff;
    background-color: #0000007f;
    opacity: 1;
}
.content-container {
    max-width: 1400px;
    height: 900px !important;
    margin-top: 20px !important;
    margin: 0 auto;
    background-color: #fff;
    font-size: 13px;
    overflow-y: auto;
    padding-bottom: 5px;
}
.button-container {
    max-width: 1400px;
    max-height: auto;
    margin-top: 20px !important;
    margin: 0 auto;
}
.agree-container {
    background-color: #0000007f;
    color: #fff;
    height: 100px !important;
    display: inline;
    margin: 0 auto;
    padding: 17px 20px !important;
    border-radius: 10px;
    font-size: 16px;
}
.first-table {
    text-align: center !important;
    border: solid 1px #000 !important;
    max-width: 500px;
}
.first-table thead {
    border: solid 1px #000 !important;
}
.first-table tbody {
    border: solid 1px #000 !important;
}
.first-table thead th,
td {
    border: solid 1px #000 !important;
}
.first-table tbody th,
td {
    border: solid 1px #000 !important;
}
.first-table tbody th {
    width: 10px !important;
}
.first-table .row-first {
    height: 150px !important;
}
.second-table {
    text-align: center !important;
    border: solid 1px #000 !important;
}
.second-table thead {
    border: solid 1px #000 !important;
}
.second-table tbody {
    border: solid 1px #000 !important;
}
.second-table thead th,
td {
    border: solid 1px #000 !important;
}
.second-table tbody th,
td {
    border: solid 1px #000 !important;
}
.bg-light-gray {
    background-color: lightgray;
}
@media (max-width: 1400px) {
    .content-container {
        width: 90% !important;
    }
    .button-container {
        width: 90% !important;
    }
}
@media (max-height: 950px) {
    .content-container {
        max-height: 750px !important;
    }
}
@media (max-height: 900px) {
    .content-container {
        max-height: 650px !important;
    }
}
@media (max-height: 850px) {
    .content-container {
        max-height: 620px !important;
    }
}
@media (max-height: 800px) {
    .content-container {
        max-height: 580px !important;
    }
}
@media (max-height: 750px) {
    .content-container {
        max-height: 550px !important;
    }
}
@media (max-height: 700px) {
    .content-container {
        max-height: 500px !important;
    }
}
@media (max-height: 650px) {
    .content-container {
        max-height: 450px !important;
    }
}
@media (max-height: 600px) {
    .content-container {
        max-height: 400px !important;
    }
}
@media (max-height: 550px) {
    .content-container {
        max-height: 330px !important;
    }
}
@media (max-height: 500px) {
    .content-container {
        max-height: 310px !important;
    }
}
</style>>
