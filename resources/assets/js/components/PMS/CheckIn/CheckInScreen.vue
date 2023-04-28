<template>
    <div class="container text-center align-middle">
        <div class="d-block text-center py-5">
            <div class="rounded bg-white-tint mx-auto py-4" style="max-width: 430px">
                <img src="img/iBMS logo_Black.png" style="max-width: 270px" class="img-fluid">
            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-4">
                <div class="card text-center shadow" style="height: auto;">
                    <div class="card-body">
                        <form method="POST" @submit="loginFunction">
                            <div class="form-group">
                                <!-- Username -->
                                <div class="input-group my-3">
                                    <div class="input-group-prepend text-center">
                                        <span class="input-group-text">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="text" v-model="checkin.username" :placeholder="$t('user.username')"
                                           class="form-control" required autofocus autocomplete>
                                </div>
                                <!-- Password -->
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend text-center">
                                        <span class="input-group-text">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="password" v-model="checkin.password" :placeholder="$t('user.password')"
                                           class="form-control" required autofocus autocomplete
                                           :minlength="checkin.min_pass">
                                </div>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend text-center">
                                        <span class="input-group-text"  v-text="$t('navbar.lang')" />
                                    </div>
                                    <form class="form-control p-0">
                                        <select class="custom-select rounded-0" id="inlineFormCustomSelectPref" v-model="FormLang">
                                            <template v-for="lang in langs" >
                                                <option :value="lang.value" >{{ lang.name }}</option>
                                            </template>
                                        </select>
                                    </form>
                                </div>
                                <!-- Submit -->
                                <div>
                                    <button class="btn btn-light bg-primary text-white btn-lg btn-circle"
                                            type="submit">{{ $t('mobile.menu.checkin') }} </button>
                                </div>
                                <div v-if="this.errors" class="form-group">
                                    <small class="text-danger">{{ this.errors }}</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <LanguageModal id="languageModal" v-on:changeLang="changeLang" />
        <Footer class="bg-light-gray" v-on:changeLang="changeLang" :localeLang="FormLang"></Footer>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
import LanguageModal from './Modals/LanguageModal.vue'
export default {
    components: { Footer, LanguageModal },
    data() {
        return {
            checkin: {
                username: '',
                password: '',

                // Updated 2021.04.13 TP Uddin
                // changed min_pass from 8 to 4
                min_pass: 4,
                //

                locale: 'ja',
            },
            errors: '',
            visibility: 'd-none',
            isButtonDisabled: true,
            isAgreed: false,
            termsDisabled: true,
            FormLang: this.$i18n.locale,
            langs: [
                { name: 'English', value: 'en' },
                { name: '日本語', value: 'ja' },
                { name: '한국어', value: 'ko' },
                // { name: '中文(简体)', value: 'ch_s' },
                { name: '中文(繁體)', value: 'ch_t' },
            ],
        }
    },
    methods: {
        /**
         * @name loginFunction
         * @desc Login function
         */
        loginFunction(e) {
            let self = this
            var error
            e.preventDefault()
            this.checkin.locale = this.selectedLanguage
            axios
                .post('login', self.checkin)
                .then(response => {
                    // window.location = 'welcome';
                    location.reload()
                })
                .catch(error => {
                    console.log(error)
                    if (error.response.status == 419) {
                        self.$toast.error(
                            'Error 419',
                            'There was a problem connecting to the server, this page will reload in 5 seconds to fix this problem!',
                            {
                                position: 'topCenter',
                                timeout: 5000,
                                progressBar: true,
                                onClosing: function () {
                                    location.reload()
                                },
                            }
                        )
                        return Promise.reject(error)
                    } else if (error.response.status == 401) {
                        self.errors = error.response.data.message
                    }
                    self.checkin.username = ''
                    self.checkin.password = ''
                })
        },
        /**
         * @name checkTimeout
         * @desc Display notice of session expiration/timeout if a cookie is detected
         */
        checkTimeout() {
            if (this.$cookies.isKey('test')) {
                this.$toast.warning('You have been logged out!', 'Warning', {
                    position: 'topCenter',
                })
            } else {
            }
        },
        showLang() {
            $('#languageModal').modal({
                backdrop: 'static',
                keyboard: false,
            })
        },

        changeLang(locale) {
            this.FormLang = locale
        },
    },
    computed: {
        buttonStatus: function () {
            if (this.isAgreed && this.checkin.username.length > 0 && this.checkin.password.length > 0) {
                return (this.isButtonDisabled = false)
            } else {
                return (this.isButtonDisabled = true)
            }
        },
        selectedLanguage: function () {
            this.locale = this.$i18n.locale
            return this.$i18n.locale
        },
    },
    mounted() {
        this.showLang()
        this.checkTimeout()

        self = this
        this.checkin.locale = this.selectedLanguage

        $('#termsOfUse').scroll(function () {
            var element = document.getElementById('termsOfUse')
            if (element.scrollHeight - element.scrollTop <= element.clientHeight) {
                // self.isAgreed = true;
                self.termsDisabled = false
            }
        })
    },
    watch: {
        FormLang: {
            handler(locale) {
                this.$i18n.locale = locale
                sessionStorage.setItem('locale', locale)
                // axios.get(`changeLocale/${locale}`)
            },
        }
    }
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
.bg-white-tint {
    background-color: #ffffff7a !important;
}
</style>
