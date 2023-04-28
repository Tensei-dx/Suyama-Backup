<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3 text-center">{{$t("mobile.ibms_manual.title")}}</div>
        <div class="col-sm-12 mt-4">
            <div class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   :href="linkLocale()"
                   target="_blank">{{$t("mobile.ibms_manual.info")}}</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
        this.linkLocale()
    },

    methods: {
        linkLocale() {
            if(this.$i18n.locale == 'en') {
                return 'https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(en).pdf'
            } else if (this.$i18n.locale == 'ja') {
                return 'https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8.pdf'
            } else {
                return 'https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(' + process.env.MIX_DEFAULT_LOCALE + ').pdf'
            }
        }
    }
}
</script>

<style>
</style>
