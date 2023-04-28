<template>
    <div class="p-2 text-white">
        <span class="d-block text-center" style="font-size: 2rem;">
            {{ $t('mobile.welcome.roomguide') }}
        </span>
        <div class="row no-gutters">
            <div class="col-12 p-1">
                <img src="img/hotel_pics/RoomMapLabels.png" class="img-fluid w-100">
            </div>
            <div v-for="item in items" :key="item.id" class="col-6 col-md-4 p-2">
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
</template>
<script>
export default {
    data() {
        return {
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
                }
            ]
        }
    },
}
</script>
<style>
    .h-60 { max-height: 60px; }
</style>
