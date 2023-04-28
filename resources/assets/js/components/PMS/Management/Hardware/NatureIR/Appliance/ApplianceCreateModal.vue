<template>

<transition name="fade">
    <div v-if="showModal" class="modal d-block">
        <div class="modal-background" @click="closeModal()"/>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">{{ $t('natureIr.appliances.create.header.title') }}</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="appliance-type" class="label text-dark">{{ $t('natureIr.appliances.create.body.form.applianceType.label') }}</label>
                        <select id="appliance-type" v-model="inputData.applianceType" class="custom-select mr-sm-2" @change="inputErrors.applianceType = null">
                            <option value="null" selected disabled>{{ $t('natureIr.appliances.create.body.form.applianceType.placeholder') }}</option>
                            <option v-for="(applianceType, index) in applianceTypes" :key="index" :value="applianceType.value">
                                {{ applianceType.name }}
                            </option>
                        </select>
                        <span class="text-danger">{{ inputErrors.applianceType }}</span>
                    </div>
                    <div class="form-group">
                        <label for="appliance-name" class="label text-dark">{{ $t('natureIr.appliances.create.body.form.applianceName.label') }}</label>
                        <input id="appliance-name" v-model="inputData.applianceName" type="text" class="form-control" maxlength="25" required @keypress="inputErrors.applianceName = null">
                        <span class="text-danger">{{ inputErrors.applianceName }}</span>
                    </div>
                    <div class="form-group">
                        <label for="brand-name" class="label text-dark">{{ $t('natureIr.appliances.create.body.form.brandName.label') }}</label>
                        <input id="brand-name" v-model="inputData.brandName" type="text" class="form-control" maxlength="25" required @keypress="inputErrors.brandName = null">
                        <span class="text-danger">{{ inputErrors.brandName }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="saveAppliance()" type="button" class="btn background-orange" :disabled="isLoading">
                        <span v-if="isLoading" class="text-center"><i class="fa fa-spinner fa-pulse fa-1x fa-fw"/>&nbsp;{{ $t('natureIr.appliances.create.footer.saving') }}</span>
                        <span v-else class="text-center">{{ $t('natureIr.appliances.create.footer.save') }}</span>
                    </button>
                    <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.appliances.create.footer.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</transition>

</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.09
 * @version 1.0.0
 */

export default {
    props: {
        appliances: {
            type: Array,
            required: true,
            default: []
        }
    },

    data() {
        return {
            inputData: {
                applianceName: null,
                applianceType: null,
                brandName: null
            },
            inputErrors: {
                applianceName: null,
                applianceType: null,
                brandName: null
            },
            showModal: false,
            isLoading: false,
            errors: []
        }
    },

    mounted() {
        this.$bus.on('openModal', payload => {
            if (payload === 'ApplianceCreateModal') {
                this.showModal = true
            }
        })
    },

    beforeDestroy() {
        this.$bus.off('openModal')
    },

    computed: {
        /**
         * @name applianceTypes
         * @description Make the appliance type support multi-language
         *
         * @returns {Object[]} appliance_types
         */
        applianceTypes: function () {
            const APPLIANCE_TYPES = this.$t('natureIr.appliances.create.body.form.applianceType.choices')
            return [
                { name: APPLIANCE_TYPES.ac, value: 'AC' },
                { name: APPLIANCE_TYPES.tv, value: 'TV' },
                { name: APPLIANCE_TYPES.ir, value: 'IR' }
            ]
        }
    },

    methods: {
        /**
         * @name saveAppliance
         * @description Save the appliance data
         *
         * @returns {void}
         */
        saveAppliance() {
            const lang = this.$t('natureIr.appliances.create')
            const isValid = this.validateInput()
            if (isValid) {
                this.isLoading = true
                axios.post('createNatureRemoAppliance', {
                    APPLIANCE_NAME: this.inputData.applianceName,
                    APPLIANCE_TYPE: this.inputData.applianceType,
                    BRAND_NAME: this.inputData.brandName
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 300) {
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: lang.success,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        return response.data
                    } else {
                        throw new Error(response.data)
                    }
                })
                .catch(error => {
                    this.errors.push(error)
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: lang.fail,
                        showConfirmButton: false,
                        timer: 2000
                    })
                })
                .then(() => {
                    this.$emit('refreshData')
                    this.isLoading = false
                    this.closeModal()
                })
            }
        },

        /**
         * @name closeModal
         * @description Close and clear the data of the modal
         *
         * @returns {void}
         */
        closeModal() {
            this.showModal = false
            this.inputData.applianceName = null
            this.inputData.applianceType = null
            this.inputData.brandName = null
            this.inputErrors.applianceName = null
            this.inputErrors.applianceType = null
            this.inputErrors.brandName = null
        },

        /**
         * @name validateInput
         * @description Validate all input
         *
         * @returns {boolean}
         */
        validateInput() {
            const lang = this.$t('natureIr.appliances.create.body.form')
            let isApplianceTypeValid = false
            let isApplianceNameValid = false
            let isBrandNameValid = false

            // Validate appliance type input
            if (!this.inputData.applianceType) {
                this.inputErrors.applianceType = lang.applianceType.errors.ifEmpty
            } else {
                this.inputErrors.applianceType = null
                isApplianceTypeValid = true
            }

            // Validate appliance name input
            if (!this.inputData.applianceName) {
                this.inputErrors.applianceName = lang.applianceName.errors.ifEmpty
            } else {
                if (this.inputData.applianceName.length > 25) {
                    this.inputErrors.applianceName = lang.applianceName.errors.ifInvalid
                } else {
                    if (this.appliances.findIndex(i => i.APPLIANCE_NAME === this.inputData.applianceName) >= 0) {
                        this.inputErrors.applianceName = lang.applianceName.errors.ifTaken
                    } else {
                        this.inputErrors.applianceName = null
                        isApplianceNameValid = true
                    }
                }
            }

            // Validate brand name input
            if (!this.inputData.brandName) {
                this.inputErrors.brandName = lang.brandName.errors.ifEmpty
            } else {
                if (this.inputData.brandName.length < 3 || this.inputData.brandName.length > 25) {
                    this.inputErrors.brandName = lang.brandName.errors.ifInvalid
                } else {
                    this.inputErrors.brandName = null
                    isBrandNameValid = true
                }
            }

            // Will only return true if all validations are true
            return isApplianceTypeValid && isApplianceNameValid && isBrandNameValid
        }
    }
}
</script>
