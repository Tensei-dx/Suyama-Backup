<!--UPDATED: TP Jermaine SPRINT_03 TASK101 20210816 -->
<!--UPDATED: TP Jermaine SPRINT_03 TASK102 20210817 -->
<!--UPDATED: TP Chris    SPRINT_12 TASK239 20211022 -->

<template>
    <div>
        <!-- = SPRINT_03 [Task101] -->
        <!-- <div class="row no-gutters p-4 h-100 justify-content-center"> -->
        <div class="row no-gutters p-4 h-100 justify-content-center text-center">
            <!-- = SPRINT_03 [Task101] -->
            <div class="col-12 d-block custom-bg-title-darkgray px-3">
                <!-- = SPRINT_03 [Task101] -->
                <!-- <span v-if="this.slideNumber === 0" class="text-white text-left" style="font-size: 2.75rem;">
                    {{ $t('mobile.welcome.welcome') }}
                </span> -->

                <span v-if="this.slideNumber === 0" class="text-white text-center"
                      style="font-size: 2.75rem; text-align: center;">
                    {{ $t('mobile.welcome.welcome') }}
                </span>
                <!-- = SPRINT_03 [Task101] -->
                <!-- - SPRINT_03 [Task102] -->
                <!-- <span v-else-if="this.slideNumber === 1" class="text-white text-left" style="font-size: 2.75rem;">
                    {{ $t('mobile.welcome.roommap') }}
                </span> -->
                <span v-else-if="this.slideNumber === 1" class="text-white text-left" style="font-size: 2.75rem;">
                    {{ $t('mobile.welcome.roomguide') }}
                </span>
                <!-- - SPRINT_03 [Task102] -->
            </div>
            <div class="col-12 text-center p-3">
                <div id="carouselHotelPics" class="carousel slide" data-ride="false" data-interval="false">
                    <div class="carousel-inner" style="height: 400px;">
                        <div class="carousel-item active">
                            <img src="img/hotel_pics/hotel_pic_1.jpg" class="img-fluid">
                        </div>
                        <div class="carousel-item">
                            <div class="row no-gutters">
                                <div class="col-lg-7 col-12 p-1">
                                    <img src="img/hotel_pics/RoomMapLabels.png" class="img-fluid">
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div class="row no-gutters">
                                        <div v-for="item in items" :key="item.id" class="col-3 col-md-4 p-1">
                                            <div class="card p-1 custom-bg-card-items">
                                                <span class="d-block text-left ml-1">
                                                    {{ item.id }} {{ $t(item.name) }}
                                                </span>
                                                <div class="card-body p-1">
                                                    <img :src="item.image" :class="item.class || 'h-60'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col p-4">
                <div v-if="this.slideNumber === 0">
                    <div class="row">
                        <!-- <div class="col-6 text-left pl-4">
                            <button type="button" class="btn btn-danger" @click="startSlide()">
                                    {{ $t('mobile.welcome.prev') }}
                            </button>
                        </div> -->
                        <div class="col-12 text-right pr-4">
                            <button type="button" class="btn btn btn-primary" @click="nextSlide()">
                                {{ $t('mobile.welcome.next')}}
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else-if="this.slideNumber === 1">
                    <div class="row">
                        <div class="col-6 text-left pl-4">
                            <button type="button" class="btn btn-danger" @click="prevSlide()">
                                {{ $t('mobile.welcome.prev') }}
                            </button>
                        </div>
                        <div class="col-6 text-right pr-4">
                            <button type="button" class="btn btn btn-primary" @click="start()">
                                {{ $t('mobile.welcome.next') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer :localeLang="this.$attrs.locale" class="bg-light-gray"></Footer>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue'
export default {
    components: {
        Footer,
    },
    data() {
        return {
            slideNumber: 0,
            items: [
                {
                    id: '①',
                    name: 'mobile.roomservice.items.lock',
                    class: '',
                    image: 'img/hotel_pics/items/lock.jpg',
                },
                {
                    id: '②',
                    name: 'mobile.roomservice.items.tv',
                    class: '',
                    image: 'img/hotel_pics/items/tv.jpg',
                },
                {
                    id: '③',
                    name: 'mobile.roomservice.items.aircon',
                    class: '',
                    image: 'img/hotel_pics/items/aircon.jpg',
                },
                {
                    id: '④',
                    name: 'mobile.roomservice.items.sofa',
                    class: '',
                    image: 'img/hotel_pics/items/sofa.jpg',
                },
                {
                    id: '⑤',
                    name: 'mobile.roomservice.items.table',
                    class: '',
                    image: 'img/hotel_pics/items/table.jpg',
                },
                {
                    id: '⑥',
                    name: 'mobile.roomservice.items.wash_basin',
                    class: '',
                    image: 'img/hotel_pics/items/wash_basin.jpg',
                },
                {
                    id: '⑦',
                    name: 'mobile.roomservice.items.mirror',
                    class: '',
                    image: 'img/hotel_pics/items/mirror.jpg',
                },
                {
                    id: '⑧',
                    name: 'mobile.roomservice.items.shower_booth',
                    class: '',
                    image: 'img/hotel_pics/items/shower_booth.jpg',
                },
                {
                    id: '⑨',
                    name: 'mobile.roomservice.items.toilet',
                    class: '',
                    image: 'img/hotel_pics/items/toilet.jpg',
                },
            ],
        }
    },
    methods: {
        nextSlide() {
            $('#carouselHotelPics.carousel').carousel('next')
        },
        prevSlide() {
            $('#carouselHotelPics.carousel').carousel('prev')
        },
        start() {
            window.location = 'guest'
        },
    },
    mounted() {
        var self = this
        $('#carouselHotelPics').on('slide.bs.carousel', function (event) {
            self.slideNumber = event.to
        })
    },
}
</script>

<style>
.bg-light-blue {
    background-color: #b4c7e7;
}
.fg-light-blue {
    color: #b4c7e7;
}
.bg-dark-gray {
    background-color: #262626;
}
.fg-dark-gray {
    color: #262626;
}
.bg-light-gray {
    background-color: lightgray;
}
.h-60 {
    height: 60px;
}
.col-11 {
    padding-right: 12px;
}
</style>
