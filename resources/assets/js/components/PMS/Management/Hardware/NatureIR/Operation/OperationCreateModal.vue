<template>

<transition name="fade">
    <div v-if="showModal">
        <div class="modal d-block" key="modal-1">
            <div @click="closeModal()" class="modal-background"/>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <!-- Step 1: Input information -->
                    <div v-if="currentPage === 1">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><span class="font-weight-bold">{{ $t('natureIr.operations.create.body.step1.title') }}&#58;</span>&nbsp;{{ $t('natureIr.operations.create.body.step1.text') }}</div>
                            <form>
                                <div class="form-group">
                                    <label for="appliance-name" class="label text-dark">{{ $t('natureIr.operations.create.body.step1.form.appliance.label') }}</label>
                                    <select id="appliance-name" v-model="inputData.applianceId" class="form-control" @change="inputErrors.applianceId = null; inputData.operationName = null; inputErrors.operationName = null">
                                        <option :value="null" selected disabled>{{ $t('natureIr.operations.create.body.step1.form.appliance.placeholder') }}</option>
                                        <option v-for="(appliance, index) in appliances" :value="appliance.APPLIANCE_ID" :key="index">
                                            {{ appliance.APPLIANCE_NAME }}
                                        </option>
                                    </select>
                                    <span class="text-danger">{{ inputErrors.applianceId }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="operation-name" class="label text-dark">{{ $t('natureIr.operations.create.body.step1.form.name.label') }}</label>
                                    <select id="operation-type" v-model="inputData.operationName" class="form-control" @change="inputErrors.operationName = null">
                                        <option :value="null" selected disabled>{{ $t('natureIr.operations.create.body.step1.form.name.placeholder')}}</option>
                                        <option v-for="(op, index) in operationTypes" :key="index" :value="op.value">{{ op.name }}</option>
                                    </select>
                                    <span class="text-danger">{{ inputErrors.operationName }}</span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button @click="validateInput()" type="button" class="btn btn-primary">{{ $t('natureIr.operations.create.footer.next') }}</button>
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.cancel') }}</button>
                        </div>
                    </div>

                    <!-- Step 2: Get IR signal data -->
                    <div v-else-if="currentPage === 2">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><span class="font-weight-bold">{{ $t('natureIr.operations.create.body.step2.title') }}&#58;</span>&nbsp;{{ $t('natureIr.operations.create.body.step2.text') }}</div>
                            <form>
                                <div class="form-group">
                                    <label for="receiver-ir-device" class="label text-dark">{{ $t('natureIr.operations.create.body.step2.form.label') }}</label>
                                    <select id="receiver-ir-device" v-model="selectedReceiverIrDevice" class="custom-select">
                                        <option :value="null" selected disabled>{{ $t('natureIr.operations.create.body.step2.form.placeholder') }}</option>
                                        <option v-for="(irDevice, index) in irDevices" :value="irDevice.DEVICE_ID" :key="index">
                                            {{ irDevice.DEVICE_NAME }}
                                        </option>
                                    </select>
                                </div>
                            </form>
                            <p>
                                {{ $t('natureIr.operations.create.body.step2.message1') }}<br/>
                                {{ $t('natureIr.operations.create.body.step2.message2') }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button @click="currentPage--" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.back') }}</button>
                            <button @click="fetchSignalData()" type="button" class="btn btn-primary" :disabled="isFetching || !selectedReceiverIrDevice">
                                <span v-if="isFetching" class="text-center"><i class="fa fa-spinner fa-pulse fa-1x fa-fw"/>&nbsp;{{ $t('natureIr.operations.create.footer.fetching') }}</span>
                                <span v-else class="text-center">{{ $t('natureIr.operations.create.footer.fetch') }}</span>
                            </button>
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.cancel') }}</button>
                        </div>
                    </div>


                    <!-- Step 3: Test Operation -->
                    <div v-else-if="currentPage === 3">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><span class="font-weight-bold">{{ $t('natureIr.operations.create.body.step3.title') }}&#58;</span>&nbsp;{{ $t('natureIr.operations.create.body.step3.text') }}</div>
                            <p>
                                {{ $t('natureIr.operations.create.body.step3.message1') }}<br/>
                                {{ $t('natureIr.operations.create.body.step3.message2') }}
                            </p>
                            <form>
                                <div class="form-group">
                                    <label for="sender-ir-device" class="label text-dark">{{ $t('natureIr.operations.create.body.step3.form.label') }}</label>
                                    <select id="sender-ir-device" v-model="selectedSenderIrDevice" class="custom-select">
                                        <option :value="null" selected disabled>{{ $t('natureIr.operations.create.body.step3.form.placeholder') }}.</option>
                                        <option v-for="(irDevice, index) in irDevices" :value="irDevice.DEVICE_ID" :key="index">
                                            {{ irDevice.DEVICE_NAME }}
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button @click="testOperation()" type="button" class="btn btn-primary" :disabled="isSending || !selectedSenderIrDevice">
                                <span v-if="isSending" class="text-center"><i class="fa fa-spinner fa-pulse fa-1x fa-fw"/>&nbsp;{{ $t('natureIr.operations.create.footer.sending') }}</span>
                                <span v-else class="text-center">{{ $t('natureIr.operations.create.footer.send') }}</span>
                            </button>
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.cancel') }}</button>
                        </div>
                    </div>

                    <!-- Step 4: Confirm Operation -->
                    <div v-else-if="currentPage === 4">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><span class="font-weight-bold">{{ $t('natureIr.operations.create.body.step4.title') }}&#58;</span>&nbsp;{{ $t('natureIr.operations.create.body.step4.text') }}</div>
                            <span class="d-block text-center text-info"><i class="fa fa-question-circle fa-big" aria-hidden="true"/></span>
                            <span class="d-block text-center h5 font-weight-bold">{{ $t('natureIr.operations.create.body.step4.message1') }}</span>
                            <p class="text-center">
                                {{ $t('natureIr.operations.create.body.step4.message2') }}<br/>
                                {{ $t('natureIr.operations.create.body.step4.message3') }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button @click="saveOperation()" type="button" class="btn btn-primary" :disabled="isSaving">
                                <span v-if="isSaving" class="text-center"><i class="fa fa-spinner fa-pulse fa-1x fa-fw"/>&nbsp;{{ $t('natureIr.operations.create.footer.saving') }}</span>
                                <span v-else class="text-center">{{ $t('natureIr.operations.create.footer.yes') }}</span>
                            </button>
                            <button @click="retryRegistration()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.no') }}</button>
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.cancel') }}</button>
                        </div>
                    </div>

                    <!-- Success: Saving -->
                    <div v-else-if="currentPage === 5">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <span class="d-block text-center text-success"><i class="fa fa-check-circle fa-big" aria-hidden="true"/></span>
                            <span class="text-center d-block">{{ $t('natureIr.operations.create.body.success.title') }}</span>
                        </div>
                        <div class="modal-footer">
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.finish') }}</button>
                        </div>
                    </div>

                    <!-- Error: Saving -->
                    <div v-else>
                        <div class="modal-header">
                            <h5 class="modal-title text-dark font-weight-bold">{{ $t('natureIr.operations.create.header.title')}}</h5>
                        </div>
                        <div class="modal-body">
                            <span class="d-block text-center text-danger"><i class="fa fa-exclamation-circle fa-big" aria-hidden="true"/></span>
                            <span class="d-block text-center h2 font-weight-bold mb-2">{{ $t('natureIr.operations.create.body.error.title') }}</span>
                            <span class="d-block text-center font-weight-bold">{{ errors[errors.length - 1] }}</span>
                            <span class="d-block text-center">{{ $t('natureIr.operations.create.body.error.message') }}</span>
                        </div>
                        <div class="modal-footer">
                            <button @click="retryRegistration()" type="button" class="btn btn-primary">{{ $t('natureIr.operations.create.footer.retry') }}</button>
                            <button @click="closeModal()" type="button" class="btn btn-secondary">{{ $t('natureIr.operations.create.footer.cancel') }}</button>
                        </div>
                    </div>
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
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.21
 * @version 1.0.0
 */

export default {
    props: {
        appliances: {
            type: Array,
            required: true,
            default: []
        },

        irDevices: {
            type: Array,
            required: true,
            default: []
        }
    },

    data() {
        return {
            showModal: false,
            currentPage: 1,
            isSaving: false,
            isFetching: false,
            isSending: false,
            selectedReceiverIrDevice: null,
            selectedSenderIrDevice: null,
            inputData: {
                applianceId: null,
                operationName: null,
                operationData: null,
            },
            inputErrors: {
                applianceId: null,
                operationName: null,
                operationData: null,
            },
            errors: []
        }
    },

    mounted() {
        this.$bus.on('openModal', payload => {
            if (payload === 'OperationCreateModal') {
                this.showModal = true
            }
        })
    },

    beforeDestroy() {
        this.$bus.off('openModal')
    },

    computed: {
        /**
         * @name applianceType
         * @description Determine the appliance type of the selected appliance
         *
         * @returns {string}
         */
        applianceType: function () {
            const appliance = this.appliances.find(i => i.APPLIANCE_ID === this.inputData.applianceId)
            return appliance ? appliance.APPLIANCE_TYPE : 'IR'
        },

        /**
         * @name operationTypes
         * @description Make a list of default operations
         *
         * @returns {Object[]}
         */
        operationTypes: function () {
            const OP_TYPES = this.$t('natureIr.operations.create.body.step1.form.name.options')
            let operations = [
                // For all types
                { type: 'all', name: OP_TYPES.power, value: 'power' },
                // For TV
                { type: 'TV', name: OP_TYPES.tv.source, value: 'tv_source' },
                { type: 'TV', name: OP_TYPES.tv.vol_up, value: 'tv_volume_up' },
                { type: 'TV', name: OP_TYPES.tv.vol_down, value: 'tv_volume_down' },
                { type: 'TV', name: OP_TYPES.tv.ch_up, value: 'tv_channel_up' },
                { type: 'TV', name: OP_TYPES.tv.ch_down, value: 'tv_channel_down' },
                // For AC
                { type: 'AC', name: OP_TYPES.ac.cool, value: 'ac_cool' },
                { type: 'AC', name: OP_TYPES.ac.warm, value: 'ac_warm' },
                { type: 'AC', name: OP_TYPES.ac.dry, value: 'ac_dry' },
                { type: 'AC', name: OP_TYPES.ac.dehum, value: 'ac_dehum' },
                { type: 'AC', name: OP_TYPES.ac.temp_up, value: 'ac_temp_up' },
                { type: 'AC', name: OP_TYPES.ac.temp_down, value: 'ac_temp_down' },
                { type: 'AC', name: OP_TYPES.ac.fan_up, value: 'ac_fan_up' },
                { type: 'AC', name: OP_TYPES.ac.fan_down, value: 'ac_fan_down' },
            ]
            return operations.filter(i => {
                if (i.type === 'all') return i
                if (this.applianceType === i.type) return i
                if (this.applianceType === 'IR') return i
            })
        }
    },

    methods: {
        /**
         * @name validateInput
         * @description Validate all input
         *
         * @returns {void}
         */
        validateInput() {
            const lang = this.$t('natureIr.operations.create.body.step1.form')
            let isApplianceValid = false
            let isOperationNameValid = false

            // Validate appliance input
            if (!this.inputData.applianceId) {
                this.inputErrors.applianceId = lang.appliance.errors.ifEmpty
            } else {
                this.inputErrors.applianceId = null
                isApplianceValid = true
            }

            // Validate operation name input
            if (!this.inputData.operationName) {
                this.inputErrors.operationName = lang.name.errors.ifEmpty
            } else {
                if (this.inputData.operationName.length < 3 || this.inputData.operationName.length > 25) {
                    this.inputErrors.operationName = lang.name.errors.ifInvalid
                } else {
                    this.inputErrors.operationName = null
                    isOperationNameValid = true
                }
            }
            if (isApplianceValid && isOperationNameValid) {
                this.currentPage++
            }
        },

        /**
         * @name closeModal
         * @description Close and clear the data of the modal
         *
         * @returns {void}
         */
        closeModal() {
            this.currentPage = 1
            this.showModal = false
            this.isSaving = false
            this.isFetching = false
            this.isSending = false
            this.selectedReceiverIrDevice = null
            this.selectedSenderIrDevice = null
            this.inputData.applianceId = null
            this.inputData.operationName = null
            this.inputData.operationData = null
            this.inputErrors.applianceId = null
            this.inputErrors.operationName = null
            this.inputErrors.operationData = null
            this.errors = []
        },

        /**
         * @name fetchSignalData
         * @description Retrieve the IR signal data through Nature Remo local API
         *
         * @returns {void}
         */
        fetchSignalData() {
            this.isFetching = true
            axios.get('fetchNatureRemoSignalData', {
                params: {
                    DEVICE_ID: this.selectedReceiverIrDevice
                }
            })
            .then(response => {
                if (response.status >= 200 && response.status < 300) {
                    if (response.data !== '') {
                        this.inputData.operationData = response.data
                        this.currentPage++
                    } else {
                        throw new Error(this.$t('natureIr.operations.create.body.error.ifEmpty'))
                    }
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => {
                this.errors.push(error)
                this.currentPage = 0
            })
            .then(() => {
                this.isFetching = false
            })
        },

        /**
         * @name testOperation
         * @description Get IR signal data through Nature Remo local API
         *
         * @returns {void}
         */
        testOperation() {
            this.isSending = true
            axios.post('testNatureRemoSignalData', {
                DEVICE_ID: this.selectedSenderIrDevice,
                DATA: this.inputData.operationData
            })
            .then(response => {
                if (response.status >= 200 && response.status < 300) {
                    this.currentPage++
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => {
                this.errors.push(error)
                this.currentPage = 0
            })
            .then(() => this.isSending = false)
        },

        /**
         * @name retryRegistration
         * @description Clear data then go back to step 2
         */
        retryRegistration() {
            this.selectedReceiverIrDevice = null
            this.selectedSenderIrDevice = null
            this.inputData.operationData = null
            this.inputErrors.operationData = null
            this.isFetching = false
            this.isSending = false
            this.isSaving = false
            this.currentPage = 2
        },

        /**
         * @name saveOperation
         * @description Save the operation data
         *
         * @returns {void}
         */
        saveOperation() {
            this.isSaving = true
            axios.post('createNatureRemoSignal', {
                APPLIANCE_ID: this.inputData.applianceId,
                SIGNAL_NAME: this.inputData.operationName,
                SIGNAL_DATA: this.inputData.operationData
            })
            .then(response => {
                if (response.status >= 200 && response.status < 300) {
                    this.currentPage++
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => {
                this.errors.push(error)
                this.currentPage = 0
            })
            .then(() => {
                this.$emit('refreshData')
                this.isSaving = false
            })
        }
    }
}
</script>

<style scoped>
.fa-big {
    font-size: 160px;
}
</style>
