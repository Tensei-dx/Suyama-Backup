<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3">{{$t("mobile.roomservice.foodMenu")}}</div>
        <div class="col-sm-12">
            <div>
                <div v-for="item in foods" v-bind:key="item.id" class="col-sm-12 border border-black p-3 text-white">
                    <div class="row text-white">
                        <div class="col-sm-6">
                            <img :src="item.image" class="mt-2" style="height:110px;" />
                            <div class="text-center h-6">{{itemName(item)}}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row" style="height:80%;">
                                <div class="my-auto ml-auto col-sm-3 bg-white border-1-5">
                                    <span class="h3 text-black"> {{item.qty}}</span>
                                </div>
                                <div class="my-auto h3 col-sm-2 hotel-base-color border-1-5">
                                    <span @click="qtyUp(item.id)" class="text-black">
                                        +
                                    </span>
                                </div>
                                <div class="my-auto h3 mr-auto col-sm-2 hotel-base-color border-1-5">
                                    <span @click="qtyDown(item.id)" class="text-black">
                                        -
                                    </span>
                                </div>
                            </div>
                            <div class="row h5 m-0" style="height:20%;">
                                <div class="col-sm-auto my-0 ml-auto">
                                    ¥ {{item.price}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 position-sticky total-order">
            <div class="h3 d-flex justify-content-between mt-1 mx-3">
                <div class="mt-2">{{$t("mobile.roomservice.total")}}: <u>¥ {{totalPrice}}</u></div>
                <button type="button" :disabled="totalPrice === 0" @click="orderBtn()"
                        class="btn h4 text-black hotel-base-color py-2 px-4 pointer">
                    <b>{{$t('mobile.roomservice.order')}}</b>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components: {},
    created() {},
    data() {
        return {
            foods: [
                {
                    id: 1,
                    name: {
                        en: 'Curry Rice',
                        ja: 'カレーライス',
                        ko: '카레 밥',
                        ch_t: '咖哩飯',
                    },
                    image: '/img/foods/food1.jpg',
                    price: '430',
                    qty: 0,
                },
                {
                    id: 2,
                    name: {
                        en: 'Shoyu Ramen',
                        ja: '醤油ラーメン',
                        ko: '간장라면',
                        ch_t: '醬油拉麵',
                    },
                    image: '/img/foods/food2.jpg',
                    price: '600',
                    qty: 0,
                },
                {
                    id: 3,
                    name: {
                        en: 'Katsudon',
                        ja: 'カツ丼',
                        ko: '가츠동',
                        ch_t: '勝蓋飯',
                    },
                    image: '/img/foods/food3.jpg',
                    price: '650',
                    qty: 0 },
                {
                    id: 4,
                    name: {
                        en: 'Karaage Meal',
                        ja: '唐揚げ定食',
                        ko: '가라아게 정식',
                        ch_t: '炸雞粉',
                    },
                    image: '/img/foods/food4.jpg',
                    price: '820',
                    qty: 0,
                },
                {
                    id: 5,
                    name: {
                        en: 'Edameme',
                        ja: '枝豆',
                        ko: '완두콩',
                        ch_t: '毛豆',
                    },
                    image: '/img/foods/food5.jpg',
                    price: '230',
                    qty: 0
                },
                {
                    id: 6,
                    name: {
                        en: 'French Fries',
                        ja: 'フライドポテト',
                        ko: '감자 튀김',
                        ch_t: '炸薯條',
                    },
                    image: '/img/foods/food6.jpg',
                    price: '290',
                    qty: 0,
                },
                {
                    id: 7,
                    name: {
                        en: 'Oolong Tea',
                        ja: '烏龍茶',
                        ko: '우롱 차',
                        ch_t: '烏龍茶',
                    },
                    image: '/img/foods/food7.jpg',
                    price: '190',
                    qty: 0 },
                {
                    id: 8,
                    name: {
                        en: 'Beer',
                        ja: 'ビール',
                        ko: '맥주',
                        ch_t: '啤酒',
                    },
                    image: '/img/foods/food8.jpg',
                    price: '210',
                    qty: 0 },
            ],
            totalPrice: 0,
        }
    },
    mounted() {},
    methods: {
        qtyUp(id) {
            this.foods = this.foods.map(food => {
                if (food.id === id) {
                    food.qty += 1
                }
                return food
            })

            this.computeTotal()
        },
        qtyDown(id) {
            this.foods.map(food => {
                if (food.id === id) {
                    if (food.qty !== 0) {
                        food.qty -= 1
                    }
                }
                return food
            })

            this.computeTotal()
        },
        computeTotal() {
            var total = 0

            this.foods.map(food => {
                total += food.price * food.qty
            })

            this.totalPrice = total
        },
        orderBtn() {
            this.foods.map(food => {
                food.qty = 0
            })
            this.totalPrice = 0
            this.$swal({
                title: this.$t('mobile.menu.order'),
                text: this.$t('mobile.menu.success'),
                type: 'success',
            })
        },
        itemName(food) {
            if (this.$i18n.locale == 'en')
            return food.name.en
            else if (this.$i18n.locale == 'ko')
            return food.name.ko
            else if (this.$i18n.locale == 'ch_t')
            return food.name.ch_t
            else if (this.$i18n.locale == 'ja')
            return food.name.ja
            else return food.name[process.env.MIX_DEFAULT_LOCALE]
        },
    },
}
</script>
<style>
.h-70 {
    height: 70px;
}
.border-1-5 {
    border: 1.5px solid black !important;
}
.total-order {
    bottom: 0px !important;
    background-color: #262626;
    margin-top: 30px;
}
</style>
