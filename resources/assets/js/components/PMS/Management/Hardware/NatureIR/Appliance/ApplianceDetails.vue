<template>

<div class="col-sm-6">
    <div class="col-sm-12 mt-5">
        <div class="hardware-right-pane pb-3">
            <div class="box-inside-right-pane"/>
            <div class="hardware-right-pane-details">
                <div class="text-dark font-weight-bold h5">
                    <div class="divider-line">{{ $t('natureIr.appliances.details.appliance.title')}}</div>
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.appliances.details.appliance.name')}}&#58;</span>&nbsp;{{ details.APPLIANCE_NAME }}
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.appliances.details.appliance.type')}}&#58;</span>&nbsp;{{ details.APPLIANCE_TYPE }}
                </div>
                <div class="text-dark line-height-1">
                    <span class="font-weight-bold">{{ $t('natureIr.appliances.details.appliance.brand')}}&#58;</span>&nbsp;{{ details.BRAND_NAME }}
                </div>
                <div class="w-100 mt-3"/>
                <div class="text-dark font-weight-bold h5">
                    <div class="divider-line">{{ $t('natureIr.appliances.details.operation.title')}}&#58;</div>
                </div>
                <div v-if="details.nature_remo_signals.length === 0" class="d-block text-dark text-center">{{ $t('natureIr.appliances.details.operation.ifEmpty')}}</div>
                <ul v-else>
                    <li v-for="operation in details.nature_remo_signals" :key="operation.SIGNAL_ID">{{ operation.SIGNAL_NAME }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

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
        details: {
            required: true,
            type: Object,
            default: {}
        }
    }
}
</script>
