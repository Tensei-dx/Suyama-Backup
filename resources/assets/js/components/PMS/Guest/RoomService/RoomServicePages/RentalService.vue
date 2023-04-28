<!-- CREATED: TP Chris SPRINT-03 TASK107 20210816 -->
<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3">{{$t("mobile.roomservice.rentalserviceMenu")}}</div>
        <div class="col-sm-12">
            <!-- TASK106 TP Russell 20211014 -->
            <div>
                <div v-for="item in items" v-bind:key="item.id" class="col-sm-12 border border-black p-3 text-white">
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
                <button type="button" :disabled="totalPrice === 0" @click="rentBtn()"
                        class="btn h4 text-black hotel-base-color py-2 px-4 pointer">
                    <b>{{$t('mobile.roomservice.rent')}}</b>
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
                        en: 'BBQ Set',
                        ja: 'バーベキューセット',
                        ko: '바베큐 세트',
                        ch_t: '燒烤套裝',
                    },
                    image: '/img/amenities/item7.jpg',
                    price: '6500',
                    qty: 0,
                },
                {
                    id: 2,
                    name: {
                        en: 'Fishing Tools',
                        ja: '釣り道具',
                        ko: '낚시 도구',
                        ch_t: '釣魚工具',
                    },
                    image: '/img/amenities/item8.jpg',
                    price: '5000',
                    qty: 0,
                },
            ],
            totalPrice: 0,
        }
    },
    mounted() {},
    methods: {
        qtyUp(id) {
            this.items = this.items.map(item => {
                if (item.id === id) {
                    item.qty += 1
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
        rentBtn() {
            this.items.map(items => {
                items.qty = 0
            })
            this.totalPrice = 0

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
