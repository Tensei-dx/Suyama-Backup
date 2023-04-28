<template>
    <div class="h-100 bg-white w-100 text-black pt-5">
        <div class="h-100 w-75 text-size">
            <div class="w-25 m-auto info-title">
                <div class="text-center w-100">
                    {{room_name}}
                </div>
            </div>
            <div class="w-75 m-auto text-left pt-3 solid-border">
                <div class="info-title">
                    {{$t("management.keyManagementPage.serial") + ':'}}
                </div>
                <div class="pt-2 pl-2">
                    {{username}}
                </div>
            </div>
            <div class="w-75 m-auto text-left pt-3 solid-border">
                <div class="info-title">
                    {{$t("management.keyManagementPage.accessCode") + ':'}}
                </div>
                <div class="pt-2 pl-2">
                    {{pin}}
                </div>
            </div>
            <div class="w-75 m-auto text-left pt-3 solid-border">
                <div class="info-title">
                    {{$t("management.keyManagementPage.expiration") + ':'}}
                </div>
                <div class="pt-2 pl-2">
                    {{transformDate(check_in) + ' - ' + transformDate(check_out)}}
                </div>
            </div>
        </div>
        <Footer @changeLang="changeLang" />
    </div>

</template>


<script>
import Footer from '../../../Guest/Footer/Footer.vue'
import moment from 'moment'
export default {
    props: {
        username: '',
        pin: '',
        room_name: '',
        check_in: '',
        check_out: '',
    },

    components: {
        Footer,
    },

    methods: {
        transformDate(date) {
            let format
            if (date === null) {
                return null
            }

            if (this.$i18n.locale === 'en') {
                format = 'MM/DD/YYYY hh:mm A'
            } else {
                format = 'YYYY/MM/DD HH:mm'
            }
            return moment(date).locale(this.$i18n.locale).format(format)
        },
    },
}
</script>

<style scoped>
.text-size {
    font-size: 32px;
    margin: auto;
}
.solid-border {
    border-bottom: 2px solid #000;
}
.info-title {
    font-weight: bold;
}
</style>
