// UPDATED: TP Harvey SPRINT_03 TASK024 20210818 Fixing bug in change language
// UPDATED: TDN OKADA SPRINT_05 TASK131 20210903 Add Vue.component
// UPDATED: TP JERMAINE SPRINT_08 TASK149 20210923 Add Vue.component for logs
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

//Vue Bus
var VueBus = require('vue-bus');

//Vue Color
var VueColor = require('vue-color');

//JQuery Zoom
var JQuery = require('jquery-zoom');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'

//Boostrap Vue
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';

//izitoast notification
import VueIziToast from "vue-izitoast";
import 'izitoast/dist/css/iziToast.min.css';

//sweetalert2 notification
import VueSweetalert2 from 'vue-sweetalert2';

//Vue Carousel
import VueCarousel from 'vue-carousel';

//vue multiple select
import Multiselect from 'vue-multiselect';

//mobile detect
import isMobile from 'mobile-device-detect';

// Pusher JS
import Pusher from 'pusher-js';


//Vuetify
// import Vuetify from 'vuetify';
// import 'vuetify/dist/vuetify.min.css';
// Vue.use(Vuetify);

//Syncfusion Ej2 Charts
import { Browser } from '@syncfusion/ej2-base';
import { ChartPlugin, LineSeries, Legend, Tooltip, DateTime } from "@syncfusion/ej2-vue-charts";
Vue.use(ChartPlugin);

//for vue localization 18n
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

Vue.use(VueIziToast);
Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);
Vue.use(VueCarousel);
Vue.prototype.$isMobile = isMobile;

//for session expire popup and cookie manipulation
Vue.use(require('vue-cookies'));

//moment for time
import moment from 'moment';
import 'moment/locale/ja';
Vue.use(require('vue-moment'));

//iBMS Core
Vue.component('multiselect', Multiselect);
//PMS
Vue.component('checkin', require('./components/PMS/CheckIn/CheckInScreen.vue'));
Vue.component('roomselect', require('./components/PMS/CheckIn/RoomSelect.vue'));
Vue.component('welcome', require('./components/PMS/CheckIn/Welcome.vue'));
Vue.component('terms', require('./components/PMS/CheckIn/TermsAndCondition.vue'));
Vue.component('guest', require('./components/PMS/Guest/Dashboard.vue'));
Vue.component('guestcard', require('./components/PMS/CheckIn/DemoGuestCardNumber.vue'));
Vue.component('management', require('./components/PMS/Management/ManagementDashboard.vue'));
Vue.component('remotelock', require('./components/PMS/Management/GuestsList/NewAccount/NewUserAccount.vue'));
Vue.component('remotelockguest', require('./components/PMS/Management/GuestsList/NewAccount/NewGuestAccount.vue'));
Vue.component('update-account', require('./components/PMS/Management/GuestsList/Guests/UpdateAccount/UpdateGuestAccount.vue'));
Vue.component('cleaning', require('./components/PMS/Cleaning/Cleaning.vue'));
Vue.component('Footer', require('./components/PMS/Footer/Footer.vue'));
Vue.component('logs', require('./components/PMS/Management/Logs/Logs.vue'));

Vue.component('thankyou', require('./components/PMS/CheckIn/LogoutScreen.vue'));
Vue.component('guestinfo', require('./components/PMS/Management/GuestsList/GuestInfo/GuestInfo.vue'));


//spinner
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue'));

//for language select
//set languages to select from
const messages = {
    //retrieve message data from json files located in components/lang
    en: require('./lang/en.json'),
    ja: require('./lang/ja.json'),
    // ch_s: require('./lang/ch_s.json'),
    ch_t: require('./lang/ch_t.json'),
    ko: require('./lang/ko.json')
}
const dateTimeFormats = {
    'en': {
        long: {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric"
        }
    },
    'ja': {
        long: {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric"
        }
    },
    'ko': {
        long: {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric"
        }
    },
    'ch_t': {
        long: {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric"
        }
    },
    // 'ch_s': {
    //     long: {
    //         year: "numeric",
    //         month: "short",
    //         day: "numeric",
    //         hour: "numeric",
    //         minute: "numeric"
    //     }
    // }
}
// localization settings
const i18n = new VueI18n({
    locale: process.env.MIX_DEFAULT_LOCALE,
    dateTimeFormats,
    // fallback in case of error in ja
    fallbackLocale: [process.env.MIX_FALLBACK_LOCALE_EN, process.env.MIX_FALLBACK_LOCALE_KO, process.env.MIX_FALLBACK_LOCALE_CHT, process.env.MIX_FALLBACK_LOCALE_JA],
    messages,
})
//changed const to window for access in blade files
window.app = new Vue({
    //set i18n for all vue components
    i18n,
    el: '#app',
    // vuetify : new Vuetify(),
    data: {
    	messages: {
            notification: []
        },
        screenH: '',
        user: '',
        page: '',
        user_id: '',
        // store locale for reactivity
        locale: 'ja',
    },
    created() {
        var url = window.location.href;
        url = url.split('#').pop().split('?').pop();
        this.page = url.substring(url.lastIndexOf('/') + 1);
        if (this.page !== 'login' && this.page !== 'thankyou') {
            this.checkTimeout();
            //retrieve set language every redirect
            axios.get('/getSession')
            .then(response => {
                if (response.data.locale) {
                    this.locale = response.data.locale;
                /* + SPRINT_03 TASK024 */
                    this.changeLanguage();
                /* + SPRINT_03 TASK024 */
                }
            });
        }
        window.addEventListener('resize', this.handleResize)
        this.handleResize();
    },
    methods: {
        // Function Name: checkTimeout
        // Function Description: check for session expiration on all components every 30s
        // Param: user_id
        checkTimeout() {
            setInterval(() => {
                axios.get('/checkSession/' + authUser.USER_ID)
                .then(response => {
                    var timeout = response.data;
                    if (timeout == 'loggedOut') {
                        window.location.reload(true);
                    }
                });
            }, 1800000);
        },
        // Function Name: handleResize
        // Funciton Description: change screensize
        handleResize() {
            Vue.prototype.$screenHeight = window.innerHeight;
            Vue.prototype.$screenWidth = window.innerWidth;
        },
        // Function Name: lowBattery
        // Function Description: displays warning after low battery event
        lowBattery(data) {
            var name = data.DEVICE_NAME ? data.DEVICE_NAME : "-";
            this.$toast.warning(name, "Low Battery Alert: ", { position: 'topRight'});
        },
        // Function Name: changeLocale
        // Function Description: store language setting to session on change
        // Param: data ('en' for english, 'ja' for nihongo)
        changeLocale(data) {
            this.locale = data;

            axios.get('changeLocale/' + this.locale)
            .then((response) => {
            /* + SPRINT_03 TASK024 */
                this.changeLanguage();
            /* + SPRINT_03 TASK024 */
            }).catch((error) =>{
                console.log(error);
            });
        },
    /* + SPRINT_03 TASK024 */
        // Function Name: changeLanguage
        // Function Description: change language of entire iBMS
        changeLanguage(){
            this.$i18n.locale = this.locale;
            this.$children[0].$i18n.locale = this.locale;

            var jp = this.$i18n.messages.ja.navbar;
            var en = this.$i18n.messages.en.navbar;
            // loop through messages to change text in navbar
            if (this.locale == 'ja') {
                Object.keys(jp).forEach(function(mess) {
                    if (document.getElementById(mess)) {
                        document.getElementById(mess).innerHTML = jp[mess];
                    }
                })
            } else if (this.locale == 'en') {
                Object.keys(en).forEach(function(mess) {
                    if (document.getElementById(mess)) {
                        document.getElementById(mess).innerHTML = en[mess];
                    }
                })
            }
        }
    /* + SPRINT_03 TASK024 */
    },
    mounted() {
    	Echo.channel('test').listen('.testEvent', (e) => {
           //
        }).listen('.notificationEvent', (e) => {
            // push notif only on sensor event
            if (e.data.NOTIFICATION.ERROR_FLAG == 4) {
                this.messages.notification.push(e.data);
            }
        }).listen('.LowBatteryDeviceEvent', (e) => {
            console.log("LowBattery")
            if (this.page !== 'login') {
                this.lowBattery(e.data);
            }
        });
    },
    watch: {
        // watch for change in langauge setting to change text in app.blade
        locale: function() {
        /* - SPRINT_03 TASK024 */
            // this.$i18n.locale = this.locale;
            // this.$children[0].$i18n.locale = this.locale;

            // var jp = this.$i18n.messages.ja.navbar;
            // var en = this.$i18n.messages.en.navbar;
            // // loop through messages to change text in navbar
            // if (this.locale == 'ja') {
            //     Object.keys(jp).forEach(function(mess) {
            //         if (document.getElementById(mess)) {
            //             document.getElementById(mess).innerHTML = jp[mess];
            //         }
            //     })
            // } else if (this.locale == 'en') {
            //     Object.keys(en).forEach(function(mess) {
            //         if (document.getElementById(mess)) {
            //             document.getElementById(mess).innerHTML = en[mess];
            //         }
            //     })
            // }
        /* - SPRINT_03 TASK024 */
        /* + SPRINT_03 TASK024 */
            this.changeLanguage();
        /* + SPRINT_03 TASK024 */
        }
    },
    destroyed() {
        window.removeEventListener('resize', this.handleResize)
    },
});
