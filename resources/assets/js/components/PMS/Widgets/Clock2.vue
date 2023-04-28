<template>
    <span>{{ localeDateTime }}</span>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        format: String,
    },

    data() {
        return {
            localeDateTime: ''
        }
    },

    mounted() {
        setInterval(() => {
            this.localeDateTime = moment().locale(this.$i18n.locale).format(this.format)
        })
    },
}
</script>
