<template>
    <div>{{ clock }}</div>
</template>
<script>
import moment from 'moment'
export default {
    props: {
        format: String,
    },
    data() {
        return {
            clock: ''
        }
    },
    methods: {
        refresh() {
            let localeFormat = ''
            if (this.$i18n.locale === 'ch_t') {
                localeFormat = 'zh_TW'
            } else {
                localeFormat = this.$i18n.locale
            }
            this.clock = moment().locale(localeFormat).format(this.format)
        }
    },
    created() {
        setInterval(this.refresh, 1000)
    },
}
</script>
