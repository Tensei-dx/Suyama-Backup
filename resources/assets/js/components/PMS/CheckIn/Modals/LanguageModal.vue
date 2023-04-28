<template>
    <div id="language-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="language-modal">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 50%;" role="document">
            <div class="modal-content">
                <!-- BODY -->
                <div class="modal-body px-3 py-5">
                    <div class="w-100">
                        <img src="img/iBMS logo_Black.png" style="max-width: 350px" class="img-fluid">
                        <div class="mt-4 w-50 mx-auto row text-white">
                            <template v-for="lang in langs">
                                <div class="col-6 my-2" >
                                    <button class="btn btn-primary mx-2 p-2 rounded w-100 border-0 pointer"
                                    data-dismiss="modal" aria-label="Close"
                                    type="button"
                                    @click="changeLang(lang.value)">{{ lang.name }}</button>
                                </div>
                            </template>
                        </div>
                        <div class="text-center mt-3">
                            <span>
                                Please select a language.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            locale: this.$i18n.locale,
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
        changeLang(locale){
            $('#languageModal').modal('hide')
            this.locale = locale
            this.$emit('changeLang', locale)
        }
    }
}
</script>
