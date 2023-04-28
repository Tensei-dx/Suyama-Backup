<template>
    <span>
        {{ localeDateTime }}
    </span>
</template>

<script>
import moment from "moment";
export default {
    props: {
        format: "",
        /**
         * @name locale
         * @type {String}
         * @desc    if empty, the locale will depend on the current locale.<br>
         *          if set, will use that locale.
         */
        locale: ""
    },
    data() {
        return {
            localeDateTime: "",
            currentLocale: ""
        };
    },
    created() {
        this.changeLang();
    },
    computed: {
        selectedLanguage: function() {
            return (this.currentLocale = this.$i18n.locale);
        },
        givenLanguage: function() {
            return this.locale;
        }
    },
    methods: {
        /**
         *
         */
        changeLang() {
            if (this.givenLanguage) {
                this.localeDateTime = moment()
                    .locale(this.locale)
                    .format(this.format);
                setInterval(() => {
                    this.localeDateTime = moment()
                        .locale(this.locale)
                        .format(this.format);
                });
            } else {
                this.localeDateTime = moment()
                    .locale(this.selectedLanguage)
                    .format(this.format);
                setInterval(() => {
                    this.localeDateTime = moment()
                        .locale(this.selectedLanguage)
                        .format(this.format);
                });
            }
        }
    }
};
</script>
