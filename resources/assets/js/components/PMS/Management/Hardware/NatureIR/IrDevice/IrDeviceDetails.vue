<template>
<div class="col-sm-6">
    <div class="col-sm-12 mt-5">
        <div class="hardware-right-pane pb-3">
            <div class="box-inside-right-pane"/>
            <div class="hardware-right-pane-details">
                <div class="text-dark font-weight-bold h5">
                    <div class="divider-line">{{ $t('natureIr.irDevices.details.irDevice.title') }}</div>
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.irDevices.details.irDevice.floor') }}&#58;</span>&nbsp;{{ details.floor.FLOOR_NAME }}
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.irDevices.details.irDevice.room') }}&#58;</span>&nbsp;{{ details.room.ROOM_NAME }}
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.irDevices.details.irDevice.name') }}&#58;</span>&nbsp;{{ details.DEVICE_NAME }}
                </div>
                <div class="w-100 mt-3"/>
                <div class="text-dark font-weight-bold h5">
                    <div class="divider-line">{{ $t('natureIr.irDevices.details.appliance.title') }}&#58;</div>
                </div>
                <multiselect
                    v-model="selectedAppliances"
                    :options="appliances"
                    :multiple="true"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="false"
                    :placeholder="$t('natureIr.irDevices.details.appliance.select.placeholder')"
                    label="APPLIANCE_NAME"
                    track-by="APPLIANCE_NAME"
                    :preselect-first="false"
                    :hide-selected="true"
                    open-direction="above"
                    :max-height="150"
                    :disabled="!editMode"
                />
                <div class="d-flex justify-content-end mt-3">
                    <button @click="editDetails()" v-if="!editMode && !isLoading" class="btn background-orange">{{ $t('natureIr.irDevices.details.appliance.select.edit') }}</button>
                    <button @click="updateDetails()" v-if="editMode || isLoading" class="btn btn-primary ml-auto" :disabled="isLoading">
                        <span v-if="isLoading"><i class="fa fa-spinner fa-spin fa-fw"/>&nbsp;{{ $t('natureIr.irDevices.details.appliance.select.saving') }}</span>
                        <span v-else>{{ $t('natureIr.irDevices.details.appliance.select.save') }}</span>
                    </button>
                    <button @click="cancelEdit()" v-if="editMode || isLoading" class="btn btn-secondary ml-1">{{ $t('natureIr.irDevices.details.appliance.select.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.16
 * @version 1.0.0
 */

export default {
    props: {
        details: {
            required: true,
            type: Object,
            default: {}
        },
        appliances: {
            required: true,
            type: Array,
            default: []
        }
    },

    data() {
        return {
            selectedAppliances: [],
            selectedAppliancesCache: [],
            isLoading: false,
            editMode: false,
            errors: []
        }
    },

    created() {
        this.selectedAppliances = this.details.nature_remo_appliances
    },

    methods: {
        /**
         * @name editDetails
         * @description Store current data to cache then turn edit mode on
         *
         * @returns {void}
         */
        editDetails() {
            this.editMode = true
            this.selectedAppliancesCache = this.selectedAppliances
        },

        /**
         * @name cancelEdit
         * @description Disregard changes then turn edit mode off
         *
         * @returns {void}
         */
        cancelEdit() {
            this.editMode = false
            this.selectedAppliances = this.selectedAppliancesCache
            this.selectedAppliancesCache = []
        },

        /**
         * @name updateDetails
         * @description Update the selected appliances of the IR device
         *
         * @returns {void}
         */
        updateDetails() {
            const lang = this.$t('natureIr.irDevices.details')
            this.isLoading = true
            this.editMode = false
            axios.post('updateNatureRemoDeviceAppliances', {
                DEVICE_ID: this.details.DEVICE_ID,
                APPLIANCES: this.selectedAppliances
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
                this.selectedAppliancesCache = []
                this.isLoading = false
                this.$emit('refreshData')
            })
        }
    }
}
</script>
