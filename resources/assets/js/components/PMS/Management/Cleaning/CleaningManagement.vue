<template>
    <div class="row pt-2">
        <div class="col">
            <span class="d-block h1 font-weight-bold pl-3">{{ $t('management.cleaning') }}</span>
        </div>
        <div class="col-auto pointer text-center" style="margin-top:2px;">
            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
            <small class="d-block mt-1">{{ $t('mobile.menu.notif') }}</small>
        </div>
        <div class="w-100"></div>
        <div class="col text-left">
            <span class="d-block h4 pt-2 pl-4">{{ $t('management.cleaningPage.subtitle') }}</span>
        </div>
        <div class="w-100 py-3"></div>
        <div class="col-4 align-self-center">
            <div class="card">
                <div class="card-header py-1 text-center text-white" style="background-color: #5b9ad5;">
                    <span class="d-block">{{ $t('management.cleaningPage.card1.title') }}</span>
                </div>
                <div class="card-body p-1 text-black" style="background-color: #bdd7ee;">
                    <ul class="pl-4">
                        <li>{{ $t('management.cleaningPage.card1.content1') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-4 align-self-center text-center">
            <span class="d-block">{{ $t('management.cleaningPage.r2l') }}</span>
            <img src="img/symbols/long-arrows.png" class="w-75">
            <span class="d-block">{{ $t('management.cleaningPage.l2r') }}</span>
        </div>
        <div class="col-4 align-self-center">
            <div class="card">
                <div class="card-header py-1 text-center text-white" style="background-color: #5b9ad5;">
                    <span class="d-block">{{ $t('management.cleaningPage.card2.title') }}</span>
                </div>
                <div class="card-body p-1 text-black" style="background-color: #bdd7ee;">
                    <ul class="pl-4">
                        <li>{{ $t('management.cleaningPage.card2.content1') }}</li>
                        <li>{{ $t('management.cleaningPage.card2.content2') }}</li>
                        <li>{{ $t('management.cleaningPage.card2.content3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-100 py-3"></div>
        <div class="col-6 text-center">
            <span class="d-block h4 text-left">1. {{ $t('management.cleaningPage.listview') }}</span>
            <img v-if="selectedLang == 'ja'" src="img/cleaning/list-view-ja.jpg" class="img-fluid" style="height: 175px;">
            <img v-else-if="selectedLang == 'en'" src="img/cleaning/list-view-en.jpg" class="img-fluid" style="height: 175px;">
            <img v-else-if="selectedLang == 'ko'" src="img/cleaning/list-view-en.jpg" class="img-fluid" style="height: 175px;">
            <!-- <img v-else-if="selectedLang == 'ch_s'" src="img/cleaning/list-view-en.jpg" class="img-fluid" style="height: 175px;"> -->
            <img v-else-if="selectedLang == 'ch_t'" src="img/cleaning/list-view-en.jpg" class="img-fluid" style="height: 175px;">
        </div>
        <div class="col-6 text-center">
            <span class="d-block h4 text-left">2. {{ $t('management.cleaningPage.calview') }}</span>
            <img v-if="selectedLang == 'ja'" src="img/cleaning/calendar-view-ja.jpg" class="img-fluid" style="height: 175px;">
            <img v-else-if="selectedLang == 'en'" src="img/cleaning/calendar-view-en.jpg" class="img-fluid" style="height: 175px;">
            <img v-else-if="selectedLang == 'ko'" src="img/cleaning/calendar-view-en.jpg" class="img-fluid" style="height: 175px;">
            <!-- <img v-else-if="selectedLang == 'ch_s'" src="img/cleaning/calendar-view-en.jpg" class="img-fluid" style="height: 175px;"> -->
            <img v-else-if="selectedLang == 'ch_t'" src="img/cleaning/calendar-view-en.jpg" class="img-fluid" style="height: 175px;">
        </div>
        <div class="w-100 pb-1"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {

        }
    },
    computed: {
        selectedLang: function () {
            return this.$i18n.locale;
        }
    },

}
</script>
