<!-- UPDATE   TDN Chris  SPRINT_08 TASK177 09232021 -->
<template>

<div>
    <div class="row" style="margin-top: 10px;" @click="$emit('changePage', 'hardware_management')">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3" style="margin-bottom: -15px;">
                    {{$t("navbar.hardwarem")}}
                </span>
            </div>
    </div>
    <!-- <div class="wrapper hardware-img-bg"> -->
        <!-- <div class="container-fluid"> -->
            <!-- <div class="d-flex justify-content-between py-3"> -->
            <div class="d-flex justify-content-between py-3 pl-3" style="margin-bottom: 15px; font-size: 20px">
                <div>
                    <div>{{ $t('management.natureIR') }}</div>
                </div>
                <!-- <clock/> -->
            </div>
            <!-- <div class="row pb-1 h-826"> -->
            <div class="row pb-1 h-500">
                <div class="col-lg-6 mb-3">
                    <!-- <div class="d-flex justify-content-between align-items-center"> -->
                    <div class="d-flex justify-content-between align-items-center pl-3">
                        <!-- <div id="ir-management-tab" role="tablist" class="nav nav-tabs w-100"> -->
                        <div id="ir-management-tab" role="tablist" class="nav nav-tabs">
                            <a
                                id="appliance-tab"
                                data-toggle="tab"
                                href="#appliance"
                                role="tab"
                                aria-controls="appliance"
                                aria-selected="true"
                                class="nav-item nav-link nav-header-blue active"
                                @click="hideDetails()"
                            >{{ $t('natureIr.appliances.tab') }}</a>
                            <a
                                id="operation-tab"
                                data-toggle="tab"
                                href="#operation"
                                role="tab"
                                aria-controls="operation"
                                aria-selected="true"
                                class="nav-item nav-link nav-header-blue"
                                @click="hideDetails()"
                            >{{ $t('natureIr.operations.tab') }}</a>
                            <a
                                id="ir-device-tab"
                                data-toggle="tab"
                                href="#irdevice"
                                role="tab"
                                aria-controls="ir-device"
                                aria-selected="false"
                                class="nav-item nav-link nav-header-blue"
                                @click="hideDetails()"
                            >{{ $t('natureIr.irDevices.tab') }}</a>
                        </div>
                    </div>
                    <!-- <div id="ir-management-tab-content" class="tab-content tab-bg-blue"> -->
                    <div id="ir-management-tab-content" class="tab-content">
                        <div id="appliance" role="tabpanel" aria-labelledby="appliance-tab" class="tab-pane fade show active">
                            <Appliance
                                :appliances="returnAppliances"
                                @showDetails="showDetails"
                                @hideDetails="hideDetails"
                                @refreshData="refreshData"
                            />
                        </div>
                        <div id="operation" role="tabpanel" aria-labelledby="operation-tab" class="tab-pane fade">
                            <Operation
                                :operations="returnOperations"
                                :appliances="returnAppliances"
                                @showDetails="showDetails"
                                @hideDetails="hideDetails"
                                @refreshData="refreshData"
                            />
                        </div>
                        <div id="irdevice" role="tabpanel" aria-labelledby="ir-device-tab" class="tab-pane fade">
                            <IrDevice
                                :irDevices="returnIrDevices"
                                @showDetails="showDetails"
                                @hideDetails="hideDetails"
                                @refreshData="refreshData"
                            />
                        </div>
                    </div>
                </div>
                <ApplianceDetails v-if="showApplianceDetails" :details="selectedAppliance"/>
                <OperationDetails v-if="showOperationDetails" :details="selectedOperation"/>
                <IrDeviceDetails v-if="showIrDeviceDetails" :details="selectedIrDevice" :appliances="returnAppliances" @refreshData="refreshData"/>
                <ApplianceCreateModal @refreshData="refreshData" :appliances="returnAppliances"/>
                <OperationCreateModal @refreshData="refreshData" :irDevices="returnIrDevices" :appliances="returnAppliances"/>
            </div>
        <!-- </div> -->
    <!-- </div>
    <Footer/> -->
</div>

</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.09
 * @version 1.0.0
 */

import Appliance from './Appliance/Appliance.vue'
import ApplianceDetails from './Appliance/ApplianceDetails.vue'
import ApplianceCreateModal from './Appliance/ApplianceCreateModal.vue'
import Operation from './Operation/Operation.vue'
import OperationDetails from './Operation/OperationDetails.vue'
import OperationCreateModal from './Operation/OperationCreateModal.vue'
import IrDevice from './IrDevice/IrDevice.vue'
import IrDeviceDetails from './IrDevice/IrDeviceDetails.vue'

export default {
    components: {
        Appliance,
        ApplianceDetails,
        ApplianceCreateModal,
        Operation,
        OperationDetails,
        OperationCreateModal,
        IrDevice,
        IrDeviceDetails
    },

    data() {
        return {
            appliances: [],
            operations: [],
            irDevices: [],
            showApplianceDetails: false,
            showOperationDetails: false,
            showIrDeviceDetails: false,
            selectedAppliance: {},
            selectedOperation: {},
            selectedIrDevice: {},
            errors: []
        }
    },

    created() {
        this.refreshData()
    },

    mounted() {
        // clear filters after switching tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', e => this.$bus.emit('clearFilters'))
        this.$i18n.locale = this.$parent.locale;
    },

    methods: {
        /**
         * @name getAppliances
         * @description Get all appliances
         * @since 1.0.0
         *
         * @returns {void}
         */
        getAppliances() {
            axios.get('getNatureRemoAppliances', {
                params: {
                    WITH: 'natureRemoSignals:SIGNAL_ID,SIGNAL_NAME,APPLIANCE_ID'
                }
            })
            .then(response => {
                this.appliances = []
                if (response.status >= 200 && response.status < 300) {
                    this.appliances = response.data
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },

        /**
         * @name getOperations
         * @description Get all signals
         * @since 1.0.0
         *
         * @returns {void}
         */
        getOperations() {
            axios.get('getNatureRemoSignals', {
                params: {
                    WITH: 'natureRemoAppliance'
                }
            })
            .then(response => {
                this.operations = []
                if (response.status >= 200 && response.status < 300) {
                    this.operations = response.data
                } else {
                    throw new Error(response.data)
                }
            }).catch(error => this.errors.push(error))

        },

        /**
         * @name getNatureDevices
         * @description Get all IR devices
         * @since 1.0.0
         *
         * @returns {void}
         */
        getNatureDevices() {
            axios.get('getDevicesCustomQuery', {
                params: {
                    DEVICE_TYPE: 'nature_remo',
                    REG_FLAG: 1,
                    WITH: 'floor:FLOOR_ID,FLOOR_NAME>room>natureRemoAppliances'
                }
            })
            .then(response => {
                this.irDevices = []
                if (response.status >= 200 && response.status < 300) {
                    this.irDevices = response.data
                } else {
                    throw new Error(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },

        /**
         * @name hideDetails
         * @description Clear the selected information
         *
         * @returns {void}
         */
        hideDetails() {
            this.showApplianceDetails = false
            this.showOperationDetails = false
            this.showIrDeviceDetails = false
            this.selectedAppliance = {}
            this.selectedOperation = {}
            this.selectedIrDevice = {}
        },

        /**
         * @name showDetails
         * @description Displays the details of the device
         *
         * @returns {void}
         */
        showDetails(data) {
            // Clear the data first then update
            this.hideDetails()
            if (data.element === 'ApplianceDetails') {
                this.showApplianceDetails = true
                this.selectedAppliance = data.item
            } else if (data.element === 'IrDeviceDetails') {
                this.showIrDeviceDetails = true
                this.selectedIrDevice = data.item
            } else if (data.element === 'OperationDetails') {
                this.showOperationDetails = true
                this.selectedOperation = data.item
            } else {
                this.errors.push($t('natureIr.errors.showDetails'))
            }
        },

        /**
         * @name refreshData
         * @description Initializes or refreshes the data
         *
         * @returns {void}
         */
        refreshData() {
            this.getAppliances()
            this.getOperations()
            this.getNatureDevices()
        }
    },

    computed: {
        /**
         * @name returnAppliances
         * @description Return the appliances array
         *
         * @returns {array}
         */
        returnAppliances: function () {
            return this.appliances
        },

        /**
         * @name returnOperations
         * @description Return the operations array
         *
         * @returns {array}
         */
        returnOperations: function () {
            return this.operations
        },

        /**
         * @name returnIrDevices
         * @description Return the IR devices array
         *
         * @returns {array}
         */
        returnIrDevices: function () {
            return this.irDevices
        }
    }
}
</script>
