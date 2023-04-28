<!--UPDATED: TP Leo SPRINT_07 TASK018 20210922-->
<template>
    <div class="row local-height">
        <div class="col-md-4 p-1 custom-bg-device-data">
            <div class="text-left pb-2"><i class="fa fa-bars fa-inverse fa-2x"></i> <span
                      class="h3 text-white">{{$t('mobile.roomservice.menu')}}</span></div>
            <div @click="menuClicked(1)" :class="isSelected(1)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.roomDescription')}}</div>
            <div @click="menuClicked(2)" :class="isSelected(2)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.evacuationRoute')}}</div>
            <div @click="menuClicked(3)" :class="isSelected(3)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roommanual.wifiInformation')}}</div>
            <div @click="menuClicked(4)" :class="isSelected(4)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.terms_of_usage.title')}}</div>
            <div @click="menuClicked(5)" :class="isSelected(5)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.ibms_manual.title')}}</div>
        </div>
        <div class="col-md-8 custom-bg-room-service room-service-scroll">
            <!-- = SPRINT_07 Task018    -->
            <RoomDescription v-if="currentSection===1" />
            <EvacuationInformation v-if="currentSection=== 2" />
            <WifiInformation v-if="currentSection === 3" />
            <TermsOfUse v-if="currentSection == 4" />
            <OperationGuest v-if="currentSection == 5" />

            <!-- = SPRINT_07 Task018    -->
        </div>
    </div>
</template>

<script>
import RoomDescription from './RoomManualPages/RoomDescription.vue'
import EvacuationInformation from './RoomManualPages/EvacuationInformation.vue'
import WifiInformation from './RoomManualPages/WifiInformation.vue'
import TermsOfUse from './RoomManualPages/TermsOfUse.vue'
import OperationGuest from './RoomManualPages/OperationGuest.vue'

export default {
    components: { RoomDescription, EvacuationInformation, WifiInformation, TermsOfUse, OperationGuest },

    data() {
        return {
            currentPage: 1,
        }
    },
    // + SPRINT_07 Task018
    props: ['currentSection'],

    data() {
        return {
            section: 1,
        }
    },

    created() {
        this.section = this.currentSection ? this.currentSection : 1
    },

    watch: {
        currentSection: function () {
            this.section = this.currentSection
        },
        // + SPRINT_07 Task018
    },
    // + SPRINT_07 Task018
    methods: {
        menuClicked(id) {
            this.$emit('updateSection', id)
        },
        // + SPRINT_07 Task018

        isSelected(id) {
            if (id === this.currentSection) {
                return 'hotel-base-color'
            }
        },
    },
}
</script>

<style>
.room-service-scroll {
    overflow-y: auto;
    max-height: 700px !important;
    height: 665px !important;
}

.local-height {
    max-height: 700px !important;
    height: 665px !important;
}
.hotel-base-color {
    background-color: #add8e6 !important;
}
</style>
