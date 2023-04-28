<template>
    <div class="custom-footer hotel-footer-color hotel-black-color-text text-center d-flex justify-content-between">
        <div></div>
        <span class="text-center">{{ version }} | Copyright (c) 2022 Powered by Tensei Data Net Inc.</span>
        <span>R2 事業再構築 機-1　※事業再構築以外での使用禁止</span>
        <div>
            <form class="form-control p-0 bg-transparent border-0 rounded-0 px-1 font-weight-bold">
                <select class="p-0 bg-transparent border-0" id="selectLangFooter" v-model="FormLang">
                    <template v-for="lang in langs" >
                        <option :value="lang.value" >{{ lang.name }}</option>
                    </template>
                </select>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        localeLang: String
    },
    data() {
        return {
            locale: '',
            version: '',
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
    created() {
        this.getVersion()
    },
    methods: {
        /**
         *
         */
        getVersion() {
            axios.get('/getAppVersion').then(responce => {
                this.version = responce.data
            })
        },
    },
    watch: {
        FormLang: {
            handler(locale) {
                this.$i18n.locale = locale
                sessionStorage.setItem('locale', locale)
                this.$emit('changeLang', locale)
            },
        },
        localeLang: {
            handler(locale) {
                this.FormLang = locale
            }
        }
    },
}
</script>

<style>
.custom-footer {
    width: 100%;
    position: fixed;
    right: 0;
    bottom: 0;
}
</style>
