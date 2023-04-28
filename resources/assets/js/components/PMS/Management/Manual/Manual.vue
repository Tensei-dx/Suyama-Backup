<template>
    <div>
        <div class="row h-100">
            <div class="col-md-4 p-1 custom-bg-device-data">
                <div class="text-left pb-2"><i class="fa fa-bars fa-inverse fa-2x"></i> <span
                          class="h3 text-white">{{ $t('management.manuals.manualitem') }}</span></div>
                <div class="manual-menu-scroll">
                    <!--   <div @click="menuClicked(1)" :class="isSelected(1)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.cleaningVideo')}}</div>
                    <div @click="menuClicked(2)" :class="isSelected(2)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.cleaningImage')}}</div> -->
                    <div @click="menuClicked(3)" :class="isSelected(3)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.operationGuest.tabName')}}</div>
                    <div @click="menuClicked(4)" :class="isSelected(4)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.operationAdmin.tabName')}}</div>
                    <div @click="menuClicked(5)" :class="isSelected(5)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.batteryExchange')}}</div>
                    <!--  <div @click="menuClicked(6)" :class="isSelected(6)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.pmsSetting')}}</div> -->
                    <div @click="menuClicked(7)" :class="isSelected(7)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.ibmsScreen.tabName')}}</div>
                    <!-- <div @click="menuClicked(8)" :class="isSelected(8)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.pmsOfficial')}}</div>-->
                    <div @click="menuClicked(9)" :class="isSelected(9)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.support')}}</div>
                    <!--  <div @click="menuClicked(10)" :class="isSelected(10)"
                         class="pl-1 h3 bg-white border text-black text-left pointer">
                        {{ $t('management.manuals.operationFlow')}}</div>-->
                </div>
            </div>

            <div class="col-md-8 custom-bg-room-service manual-scroll custom-bg-manual">
                <!--   <CleaningVideo class="cleaningVideo" v-if="currentPage === 1" /> -->
                <!--  <CleaningImage v-if="currentPage === 2" /> -->
                <OperationGuest v-if="currentPage === 3" />
                <OperationAdmin v-if="currentPage === 4" />
                <BatteryExchange v-if="currentPage === 5" />
                <!-- <PMSSetting v-if="currentPage === 6" />-->
                <IBMSScreen v-if="currentPage === 7" />
                <!-- <PMSOfficial v-if="currentPage === 8" />-->
                <Support v-if="currentPage === 9" />
                <!-- <OperationFlow v-if="currentPage === 10" /> -->
            </div>
        </div>
    </div>
</template>

<script>
import CleaningVideo from './ManualPages/CleaningVideo.vue'
import CleaningImage from './ManualPages/CleaningImage.vue'
import OperationGuest from './ManualPages/OperationGuest.vue'
import OperationAdmin from './ManualPages/OperationAdmin.vue'
import BatteryExchange from './ManualPages/BatteryExchange.vue'
import PMSSetting from './ManualPages/PMSSetting.vue'
import IBMSScreen from './ManualPages/IBMSScreen.vue'
import PMSOfficial from './ManualPages/PMSOfficial.vue'
import Support from './ManualPages/Support.vue'
import OperationFlow from './ManualPages/OperationFlow.vue'

export default {
    components: {
        CleaningVideo,
        CleaningImage,
        OperationGuest,
        OperationAdmin,
        BatteryExchange,
        PMSSetting,
        IBMSScreen,
        PMSOfficial,
        Support,
        OperationFlow,
    },
    data() {
        return {
            currentPage: 1,
        }
    },
    methods: {
        menuClicked(id) {
            this.currentPage = id
        },
        isSelected(id) {
            if (id === this.currentPage) {
                return 'hotel-base-color'
            }
        },
    },
}
</script>

<style>
.manual-menu-scroll {
    overflow-y: auto;
}
.manual-scroll {
    overflow-y: auto;
    height: 645px;
}
.custom-bg-manual {
    background-color: #262626;
}
.cleaningVideo {
    height: 665px;
}
</style>
