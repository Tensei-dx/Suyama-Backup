<!-- UPDATED: TP Chris SPRINT_03 TASK107 20210817 -->
<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3">{{$t("mobile.roomservice.amenityMenu")}}</div>
        <div class="col-sm-12">
            <div>
                <div v-for="item in items" v-bind:key="item.id" class="col-sm-12 border border-black p-3 text-white ">
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
                <button type="button" :disabled="totalItems === 0" @click="orderBtn()"
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
            items: [
                {
                    id: 1,
                    name: {
                        en: 'Tooth Brush',
                        ja: '歯ブラシ',
                        ko: '칫솔',
                        ch_t: '牙刷',
                    },
                    image: '/img/amenities/item1.jpg',
                    price: '0',
                    qty: 0,
                },
                {
                    id: 2,
                    name: {
                        en: 'Tooth Paste',
                        ja: '歯磨き粉',
                        ko: '치약',
                        ch_t: '牙膏',
                    },
                    image: '/img/amenities/item2.jpg',
                    price: '0',
                    qty: 0,
                },
                {
                    id: 3,
                    name: {
                        en: 'Face Towel',
                        ja: 'フェイスタオル',
                        ko: '페이스 타올',
                        ch_t: '洗臉毛巾',
                    },
                    image: '/img/amenities/item3.jpg',
                    price: '0',
                    qty: 0,
                },
                {
                    id: 4,
                    name: {
                        en: 'Bath Towel',
                        ja: 'バスタオル',
                        ko: '목욕 수건',
                        ch_t: '浴巾',
                    },
                    image: '/img/amenities/item4.jpg',
                    price: '0',
                    qty: 0,
                },
                {
                    id: 5,
                    name: {
                        en: 'Face Wash',
                        ja: '洗顔',
                        ko: '목욕 수건',
                        ch_t: '浴巾',
                    },
                    image: '/img/amenities/item5.jpg',
                    price: '100',
                    qty: 0 },
                {
                    id: 6,
                    name: {
                        en: 'Conditioner',
                        ja: 'コンディショナー',
                        ko: '컨디셔너',
                        ch_t: '護髮素',
                    },
                    image: '/img/amenities/item6.jpg',
                    price: '100',
                    qty: 0,
                },
                // = SPRINT_03 TASK017
                // {id:7,name:"BBQ Set",nameJ:"バーベキューセット",image:"/img/amenities/item7.jpg",price:"6500",qty:0},
                // {id:8,name:"Fishing Tools",nameJ:"釣り道具",image:"/img/amenities/item8.jpg",price:"5000",qty:0}
                // = SPRINT_03 TASK017
            ],
            totalPrice: 0,
            totalItems: 0,
        }
    },
    mounted() {},
    methods: {
        qtyUp(id) {
            this.items = this.items.map(item => {
                if (item.id === id) {
                    item.qty += 1
                    this.totalItems += 1
                }
                return item
            })

            this.computeTotal()
        },

        qtyDown(id) {
            this.items = this.items.map(item => {
                if (item.id === id) {
                    if (item.qty !== 0) {
                        item.qty -= 1
                        this.totalItems -= 1
                    }
                }
                return item
            })

            this.computeTotal()
        },

        computeTotal() {
            var total = 0

            this.items.map(item => {
                total += item.price * item.qty
            })

            this.totalPrice = total
        },
        orderBtn() {
            this.items.map(items => {
                items.qty = 0
            })
            this.totalPrice = 0
            this.totalItems = 0

            this.$swal({
                title: this.$t('mobile.menu.order'),
                text: this.$t('mobile.menu.success'),
                type: 'success',
            })
        },
        itemName(item) {
            if (this.$i18n.locale == 'en')
            return item.name.en
            else if (this.$i18n.locale == 'ko')
            return item.name.ko
            else if (this.$i18n.locale == 'ch_t')
            return item.name.ch_t
            else if (this.$i18n.locale == 'ja')
            return item.name.ja
            else return item.name[process.env.MIX_DEFAULT_LOCALE]
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
