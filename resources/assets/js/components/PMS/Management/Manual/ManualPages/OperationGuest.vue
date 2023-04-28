<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3 text-center">{{$t("management.manuals.operationGuest.tabName")}}</div>
        <div class="col-sm-12 mt-4">
            <div v-if="this.$i18n.locale == 'ja'" class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8.pdf"
                   target="_blank">{{$t("management.manuals.operationGuest.linkName")}}</a>
            </div>
            <div v-if="this.$i18n.locale == 'en'" class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(en).pdf"
                   target="_blank">{{$t("management.manuals.operationGuest.linkName")}}</a>
            </div>
            <!-- <div v-if="this.$i18n.locale == 'ch_s'" class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(en).pdf"
                   target="_blank">{{$t("management.manuals.operationGuest.linkName")}}</a>
            </div> -->
            <div v-if="this.$i18n.locale == 'ch_t'" class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(en).pdf"
                   target="_blank">{{$t("management.manuals.operationGuest.linkName")}}</a>
            </div>
            <div v-if="this.$i18n.locale == 'ko'" class="text-center">
                <a type="button" class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/iBMS(%E5%AE%BF%E6%B3%8A%E8%80%85%E5%90%91%E3%81%91)%E6%93%8D%E4%BD%9C%E6%89%8B%E9%A0%86%E6%9B%B8(en).pdf"
                   target="_blank">{{$t("management.manuals.operationGuest.linkName")}}</a>
            </div>
        </div>
    </div>
</template>

<script>
</script>

<style>
</style>
