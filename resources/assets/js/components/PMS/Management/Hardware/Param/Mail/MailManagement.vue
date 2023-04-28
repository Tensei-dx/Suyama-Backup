<template>
    <div class="container" id="mail-management-tab">
        <div class="text-white px-0 mt-3 h-400 scrollable pb-5">
            <span class="stayseeHeader h4 text-left text-white" v-text="$t('management.param.mailHeader')" />
            <div class="thankyouMail">
                <span class="h4 text-left text-white" v-text="$t('management.param.jaThankyouMail')" />
            </div>
            <div class="mt-1">
                <textarea style="font-size:24px" name="mail" id="" cols="61" rows="5"
                          v-model="formData.MAIL_THANKYOU_JA_CONTENT"></textarea>
            </div>
            <div class="thankyouMail mt-4">
                <span class="h4 text-left text-white" v-text="$t('management.param.enThankyouMail')" />
            </div>
            <div class="mt-1">
                <textarea style="font-size:24px" name="mail" id="" cols="61" rows="5"
                          v-model="formData.MAIL_THANKYOU_EN_CONTENT"></textarea>
            </div>
            <div>
                <div class="row-12 mt-1">
                    <span class="remindMail px-0 col-6 h4 text-left" v-text="$t('management.param.remindMail')" />
                    <form class="form pl-4 d-inline-block">
                        <select class="custom-select" id="inlineFormCustomSelectPref"
                                v-model="formData.MAIL_REMIND_OFFSET">
                            <template v-for="(offset, index) in offsetOptions">
                                <option :key="index" :value="offset.value" v-text="offset.text" />
                            </template>
                        </select>
                    </form>
                    <span class="h4 text-left pl-3 text-white" v-text="$t('management.param.dayBefore')" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        parameters: {
            type: Object,
            required: true,
            default: {},
        },
    },

    data() {
        return {
            offsetOptions: [
                { value: 0, text: 0 },
                { value: 1, text: 1 },
                { value: 2, text: 2 },
            ],
            formData: {},
        }
    },

    methods: {
        /**
         * @name fillUp
         * @description Assign the cached data to the form
         *
         * @returns {void}
         */
        fillUp() {
            this.formData = {
                MAIL_THANKYOU_EN_CONTENT: this.parameters.MAIL_THANKYOU_EN_CONTENT,
                MAIL_THANKYOU_JA_CONTENT: this.parameters.MAIL_THANKYOU_JA_CONTENT,
                MAIL_REMIND_OFFSET: this.parameters.MAIL_REMIND_OFFSET,
            }
        },
    },

    watch: {
        parameters: {
            handler() {
                this.fillUp()
            },
            deep: true,
        },
        formData: {
            handler(value) {
                this.$emit('update', value)
            },
            deep: true,
        },
    },
}
</script>

<style scoped>
.scrollable {
    max-height: 400px !important;
    overflow: auto;
}
.stayseeHeader {
    margin-top: 30px;
}
.bg-apply {
    background-color: #898989 !important;
    color: #fff;
    padding-top: 20px;
}
.autoStart {
    display: inline-block;
    margin-top: 30px;
}
.remindMail {
    display: inline-block;
    margin-top: 18px;
}
.dropMode {
    display: inline-block;
    margin-top: 90px;
}
.form-check-label {
    font-size: 24px;
}
.card {
    background-color: white;
    height: 140px;
}
.thankyouMail {
    margin-top: 10px;
}
.dropdown-menu {
    min-width: inherit;
}
</style>
