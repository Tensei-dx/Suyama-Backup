<template>
    <div>
        <div class="row my-3">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3 text-uppercase text-white"
                      v-text="$t('management.param.title')" />
            </div>
        </div>

        <div>
            <!-- tab list -->
            <ul id="param-setting-management-tab" class="nav nav-tabs" role="tablist">

                <template v-for="(tab, index) in tabs">
                    <li class="nav-item" :key="index">
                        <a :id="tab.name + '-management-tab'" class="nav-link" :href="'#param-setting-' + tab.name"
                           role="tab" data-toggle="tab" :aria-controls="'param-setting-' + tab.name"
                           v-text="$t(tab.text)" />
                    </li>
                </template>
            </ul>

            <!-- tab contents -->
            <div class="tab-content tab-bg-gray mt-3" id="param-management-tab-content">
                <template v-for="(tab, index) in tabs">
                    <div :key="index" class="tab-pane pt-1" :id="'param-setting-' + tab.name" role="tabpanel"
                         aria-selected="false">
                        <component :is="tab.component" :parameters="parameters" @update="assignParameter" />
                    </div>
                </template>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex flex-row justify-content-end align-items-center mt-4">
            <!-- Reset -->
            <button class="btn btn-danger mr-2" @click="resetForm" :disabled="isLoading || isSubmitting">
                <span v-text="$t('management.param.reset')" />
            </button>
            <!-- Submit -->
            <button class="btn btn-apply text-black font-weight-bold" @click="onSubmit"
                    :disabled="isLoading || isSubmitting">
                <i v-show="isSubmitting" class="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
                <span v-text="$t('management.param.' + (isSubmitting ? 'applying' : 'apply'))" />
            </button>
        </div>
    </div>
</template>

<script>
import AcManagement from './AC/AcManagement.vue'
import StayseeManagement from './Staysee/StayseeManagement.vue'
import PinCodeManagement from './PinCode/PinCodeManagement.vue'
import MailManagement from './Mail/MailManagement.vue'
import WifiManagement from './Wifi/WifiManagement.vue'

export default {
    components: {
        AcManagement,
        StayseeManagement,
        PinCodeManagement,
        MailManagement,
        WifiManagement,
    },

    data() {
        return {
            tabs: [
                {
                    name: 'ac',
                    text: 'management.param.ac',
                    component: 'AcManagement',
                },
                {
                    name: 'staysee',
                    text: 'management.param.staysee',
                    component: 'StayseeManagement',
                },
                {
                    name: 'pin',
                    text: 'management.param.pincode',
                    component: 'PinCodeManagement',
                },
                {
                    name: 'mail',
                    text: 'management.param.mail',
                    component: 'MailManagement',
                },
                {
                    name: 'wifi',
                    text: 'management.param.wifi',
                    component: 'WifiManagement',
                },
            ],
            parameters: {},
            form: {},
            errorMessage: [],
            isLoading: false,
            isSubmitting: false,
        }
    },

    created() {
        this.getParameters()
    },

    mounted() {
        // on initial display, show the first tab
        $('#param-setting-management-tab li:first-child a').tab('show')
    },

    methods: {
        /**
         * @name getParameters
         * @description Get the parameter data
         *
         * @returns {void}
         */
        getParameters() {
            this.isLoading = true
            axios
                .get('param-settings/getParamSettings')
                .then(response => {
                    this.parameters = { ...response.data }
                })
                .catch(error => this.errorMessage.push(error.response.data))
                .then(() => {
                    return axios.get('tasks/7')
                })
                .then(response => {
                    this.parameters = {
                        ...this.parameters,
                        CRON_SCHEDULE: response.data.CRON_SCHEDULE,
                    }
                })
                .catch(error => this.errorMessage.push(error.response.data))
                .then(() => {
                    this.isLoading = false
                })
        },

        /**
         * @name assignParameter
         * @description Assign the selected data to the form data
         *
         * @param {Object} data
         * @returns {void}
         */
        assignParameter(data) {
            this.form = { ...this.form, ...data }
        },

        /**
         * @name onSubmit
         * @description Submit the form
         *
         * @returns {void}
         */
        onSubmit() {
            this.isSubmitting = true
            // deconstruct the form to separate the arguments for the two requests
            const { CRON_SCHEDULE, ...params } = this.form

            const updateTask = axios.put('tasks/7', { CRON_SCHEDULE })
            const updateParameter = axios.post('param-settings/updateParamSettings', { ...params })

            axios
                .all([updateTask, updateParameter])
                .then(responses => {
                    this.$swal({
                        type: 'success',
                        title: this.$t('modalText.editSuccess'),
                        showConfirmButton: false,
                        timer: 1500,
                    })
                })
                .catch(error => {
                    this.$swal({
                        type: 'error',
                        title: this.$t('modalText.error'),
                        showConfirmButton: false,
                        timer: 1500,
                    })
                })
                .then(() => {
                    this.isSubmitting = false
                    this.getParameters()
                })
        },

        /**
         * @name resetForm
         * @description To reset the form, fetch the latest data
         *
         * @returns {void}
         */
        resetForm() {
            this.getParameters()
        },
    },
}
</script>

<style scoped>
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.tab-bg-gray {
    background-color: #595959 !important;
    background-image: linear-gradient(180deg, #595959, #595959);
    color: #fff;
    box-shadow: 4px 2px 12px 0px #999;
}
.tab-content {
    font-size: 16px;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    font-size: 20px;
}
.applyButton {
    padding-right: 15px !important;
    padding-top: 20px !important;
    min-width: inherit;
}
.btn-apply {
    background-color: white;
    color: #000 !important;
    border-color: white;
    font-weight: bold;
}
</style>

