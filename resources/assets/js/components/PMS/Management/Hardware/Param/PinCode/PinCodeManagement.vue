<template>
    <div class="container" id="pin-management-tab">
        <div class="text-white px-0 mt-3 h-400">
            <span class="stayseeHeader h4 text-left text-white" v-text="$t('management.param.pinHeader')" />
            <div>

                <!-- PIN LENGTH -->
                <div class="row-12 mt-2">
                    <span class="numberOfDigits px-0 col-4 h4 text-left"
                          v-text="$t('management.param.numberOfDigits')" />
                    <form class="form pl-4 d-inline-block">
                        <select class="custom-select" id="inlineFormCustomSelectPref" v-model="formData.RL_NUM_PIN">
                            <template v-for="(length, index) in lengthOptions">
                                <option :key="index" :value="length.value" v-text="length.text" />
                            </template>
                        </select>
                    </form>
                    <span class="h4 text-left pl-3 text-white" v-text="$t('management.param.digits')" />
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
            lengthOptions: [
                { text: 4, value: 4 },
                { text: 5, value: 5 },
                { text: 6, value: 6 },
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
                RL_NUM_PIN: this.parameters.RL_NUM_PIN,
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
.numberOfDigits {
    display: inline-block;
    margin-top: 30px;
}
.dropMode {
    display: inline-block;
    margin-top: 90px;
}
.form-check-label {
    font-size: 24px;
}
.dropdown-menu {
    min-width: inherit !important;
}
</style>
