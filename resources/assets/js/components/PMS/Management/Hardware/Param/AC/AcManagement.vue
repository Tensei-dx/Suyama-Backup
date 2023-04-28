<template>
    <div class="container" id="ac-management-tab">
        <div class="text-white mt-3 h-400">
            <span class="acHeader h4 text-left text-white" v-text="$t('management.param.acHeader')" />
            <div>

                <!-- AC AUTO START -->
                <div class="row-12 mt-2">
                    <span class="autoStart col-4 px-0 h4 text-left text-white"
                          v-text="$t('management.param.autoStart')" />
                    <div class="form-check pl-4 form-check-inline">
                        <input class="form-check-input" type="radio" name="autoStartRadios" id="autoStartRadios1"
                               value=1 v-model.number="formData.AC_AUTO_START">
                        <label class="form-check-label pl-4" for="autoStartRadios1"
                               v-text="$t('management.param.on')" />
                    </div>
                    <div class="form-check  mb-2 form-check-inline">
                        <input class="form-check-input" type="radio" name="autoStartRadios" id="autoStartRadios2"
                               value=0 v-model.number="formData.AC_AUTO_START">
                        <label class="form-check-label pl-4" for="autoStartRadios2"
                               v-text="$t('management.param.off')" />
                    </div>
                </div>

                <!-- AC START OFFSET -->
                <div class="row-12 mt-2">
                    <span class="checkIn px-0 col-4 h4 text-left" v-text="$t('management.param.checkIn')" />
                    <form class="form pl-4 d-inline-block">
                        <select class="custom-select" id="inlineFormCustomSelectPref"
                                v-model="formData.AC_START_OFFSET">
                            <template v-for="(offset, index) in startOffsetOptions">
                                <option :key="index" :value="offset.value" v-text="offset.text"></option>
                            </template>
                        </select>
                    </form>
                    <span class="h4 text-left pl-3 text-white" v-text="$t('management.param.hoursBefore')" />
                </div>

                <!-- AC MODE -->
                <div class="row-12 mt-2">
                    <span class="dropMode h4 col-4 px-0 text-left" v-text="$t('management.param.dropDownMode')" />
                    <form class="form pl-4 d-inline-block">
                        <select class="custom-select" id="inlineFormCustomSelectPref" v-model="formData.AC_MODE">
                            <template v-for="(mode, index) in modeOptions">
                                <option :key="index" :value="mode.value" v-text="$t(mode.text)" />
                            </template>
                        </select>
                    </form>
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
            startOffsetOptions: [
                { value: 30, text: 0.5 },
                { value: 60, text: 1.0 },
                { value: 90, text: 1.5 },
                { value: 120, text: 2.0 },
            ],
            modeOptions: [
                { value: 1, text: 'management.param.warmMode' },
                { value: 2, text: 'management.param.coolMode' },
                { value: 3, text: 'management.param.dryMode' },
                { value: 0, text: 'management.param.autoMode' },
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
                AC_AUTO_START: this.parameters.AC_AUTO_START,
                AC_START_OFFSET: this.parameters.AC_START_OFFSET,
                AC_MODE: this.parameters.AC_MODE,
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
.acHeader {
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
.checkIn {
    display: inline-block;
    margin-top: 20px;
}
.dropMode {
    display: inline-block;
    margin-top: 33px;
}
.form-check-label {
    font-size: 22px;
}
.dropdown-menu {
    min-width: inherit;
}
.form-check-input {
    height: 20px;
    width: 20px;
}
.btn-default {
    background-color: white;
}
.button:active {
    color: blue;
}
</style>
