<template>

<div class="col-sm-6">
    <div class="col-sm-12 mt-5">
        <div class="hardware-right-pane pb-3">
            <div class="box-inside-right-pane"/>
            <div class="hardware-right-pane-details">
                <div class="text-dark font-weight-bold h5">
                    <div class="divider-line">{{ $t('natureIr.operations.details.operation.title') }}</div>
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.operations.details.operation.name') }}&#58;</span>&nbsp;{{ details.SIGNAL_NAME }}
                </div>
                <div class="text-dark line-weight-1">
                    <span class="font-weight-bold">{{ $t('natureIr.operations.details.operation.applianceName') }}&#58;</span>&nbsp;{{ details.nature_remo_appliance.APPLIANCE_NAME }}
                </div>
                <div class="text-dark line-weight-1">
                    <span class="font-weight-bold">{{ $t('natureIr.operations.details.operation.applianceType') }}&#58;</span>&nbsp;{{ details.nature_remo_appliance.APPLIANCE_TYPE }}
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
 * @author TP Uddin <u-almujeer@tenseiph.com> 2021.06.21
 * @version 1.0.0
 */

export default {
    props: {
        details: {
            required: true,
            type: Object,
            default: {}
        }
    }
}
</script>
