<template>
    <div>
        {{ localeDateTime }}
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        format: '',
        locale: '',
    },
    data() {
        return {
            localeDateTime: '',
        }
    },
    created() {
        if (this.locale === undefined || this.locale == '') {
            this.locale = 'ja';
        }
        this.localeDateTime = moment().locale(this.locale).format(this.format);
        // continuously update clock
        setInterval(() => {
            this.localeDateTime = moment().locale(this.locale).format(this.format);
        })
    },
    watch: {
        locale: function() {
            if (this.locale !== undefined) {
                this.localeDateTime = moment().locale(this.locale).format(this.format);
            } else {
                this.localeDateTime = moment().locale('en').format(this.format);
            }
        }
    },
}
</script>
