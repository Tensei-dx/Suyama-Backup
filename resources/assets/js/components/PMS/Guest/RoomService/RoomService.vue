<!-- UPDATED: TP Chris SPRINT_03 TASK107 20210816 -->
<template>
    <div class="row h-100">
        <div class="col-md-4 p-1 custom-bg-device-data">
            <div class="text-left pb-2"><i class="fa fa-bars fa-inverse fa-2x"></i> <span
                      class="h3 text-white">{{$t('mobile.roomservice.menu')}}</span></div>
            <div @click="menuClicked(1)" :class="isSelected(1)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.entertainment')}}</div>
            <div @click="menuClicked(2)" :class="isSelected(2)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.food')}}</div>
            <div @click="menuClicked(3)" :class="isSelected(3)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.amenities')}}</div>
            <div @click="menuClicked(4)" :class="isSelected(4)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.rentalservices')}}</div>
            <div @click="menuClicked(5)" :class="isSelected(5)" class="pl-1 h3 bg-white border text-left pointer">
                {{$t('mobile.roomservice.sightseeingMap')}}</div>
            <!-- + SPRINT_03 TASK107 -->

            <!-- + SPRINT_03 TASK107 -->
        </div>
        <div class="col-md-8 custom-bg-room-service room-service-scroll">
            <Entertainment v-if="currentPage===1" />
            <Food v-if="currentPage===2" />
            <Amenities v-if="currentPage===3" />
            <RentalServices v-if="currentPage===4" />
            <TouristInformation v-if="currentPage===5" />
            <!-- + SPRINT_03 TASK107 -->
            <!-- + SPRINT_03 TASK107 -->
        </div>
    </div>
</template>

<script>
import Entertainment from './RoomServicePages/Entertainment.vue'
import Food from './RoomServicePages/Food.vue'
import Amenities from './RoomServicePages/Amenities.vue'
import TouristInformation from './RoomServicePages/TouristInformation.vue'
// + SPRINT_03 TASK107
import RentalServices from './RoomServicePages/RentalService.vue'
// + SPRINT_03 TASK107

export default {
    // = SPRINT_03 TASK107
    components: { Entertainment, Food, Amenities, TouristInformation, RentalServices },
    // = SPRINT_03 TASK107

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
.room-service-scroll {
    overflow-y: auto;
    max-height: 665px !important;
}
</style>
