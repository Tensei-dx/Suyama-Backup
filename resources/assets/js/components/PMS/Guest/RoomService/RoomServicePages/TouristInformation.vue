<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3">{{$t("mobile.roomservice.sightseeingMenu")}}</div>
        <div class="container my-2" style="height: 360px;">
            <iframe :src="mapPath" width="100%" height="100%" style="border:0;" allowfullscreen=""
                    loading="lazy"></iframe>
        </div>
        <div class="container tourist-guide-scroll">
            <div v-for="item in items" v-bind:key="item.id" class="col-sm-12 border border-black text-white">
                <div class="row ">
                    <div class="col-sm-6 p-0">
                        <img :src="item.image" />
                    </div>
                    <div class="col-sm-6">
                        <div class="row h4 text-left" style="height:100%;">
                            <div class="my-auto col-sm-9 text-white">
                                <span> {{ getItemName(item) }}</span>
                            </div>
                            <div class="my-auto col-sm-3">
                                <i @click="setMapPath(item)" class="fa fa-map-marker btn btn-link"
                                   aria-hidden="true"></i>
                            </div>
                            <div class="my-auto col-sm-9">
                                <span>URL</span>
                            </div>
                            <div class="my-auto col-sm-3 text-center">
                                <a v-bind:href="item.url" target="_blank">
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
            map_path: {
                en: 'https://www.google.com/maps/embed?pb=!1m24!1m8!1m3!1d6458.126186343291!2d137.81489144127806!3d35.96988291126117!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e289dd16d95%3A0x1cbe5a91bc731047!2z5p2-5bGL6Iy25oi_!3m2!1d35.967006399999995!2d137.8129198!5e0!3m2!1sen!2sjp!4v1653296213542!5m2!1sen!2sjp',
                ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d2715.3763062626667!2d137.81430525091056!3d35.96788462402242!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e289dd16d95%3A0x1cbe5a91bc731047!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yV77yY77yTIOadvuWxi-iMtuaIvw!3m2!1d35.967006399999995!2d137.8129198!5e0!3m2!1sja!2sjp!4v1653280474772!5m2!1sja!2sjp'
            },
            items: [
                {
                    id: 1,
                    name: {
                        en: 'MATSUYASABOU',
                        ja: '松屋茶房',
                        ko: '마쓰야 차방',
                        ch_t: '松屋佐房',
                    },
                    image: '/img/tourist_guide/sample.png',
                    url: 'https://matsuyasabo.jimdofree.com/',
                    map: {
                        ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d2715.3763062626667!2d137.81430525091056!3d35.96788462402242!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e289dd16d95%3A0x1cbe5a91bc731047!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yV77yY77yTIOadvuWxi-iMtuaIvw!3m2!1d35.967006399999995!2d137.8129198!5e0!3m2!1sja!2sjp!4v1653280474772!5m2!1sja!2sjp',
                        en: 'https://www.google.com/maps/embed?pb=!1m24!1m8!1m3!1d6458.126186343291!2d137.81489144127806!3d35.96988291126117!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e289dd16d95%3A0x1cbe5a91bc731047!2z5p2-5bGL6Iy25oi_!3m2!1d35.967006399999995!2d137.8129198!5e0!3m2!1sen!2sjp!4v1653296213542!5m2!1sen!2sjp',
                    },
                },
                {
                    id: 2,
                    name: {
                        en: 'KANAMEYA',
                        ja: 'かなめや',
                        ko: '카나메야',
                        ch_t: '金屋',
                    },
                    image: '/img/tourist_guide/sample.png',
                    url: 'https://www.naraijuku.com/shop/post-33/',
                    map: {
                        ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d960.0299479576829!2d137.81497389543944!3d35.96793000938094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2852cfe1f9%3A0x21a91cc028d76d5e!2z44GL44Gq44KB44KE!3m2!1d35.964701!2d137.810668!5e0!3m2!1sja!2sjp!4v1653282246464!5m2!1sja!2sjp',
                        en: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d1357.6941552035746!2d137.813981780849!3d35.96753558288646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2852cfe1f9%3A0x21a91cc028d76d5e!2zS2FuYW1leWEg44GL44Gq44KB44KE!3m2!1d35.964701!2d137.810668!5e0!3m2!1sen!2sjp!4v1653296465460!5m2!1sen!2sjp',
                    },
                },
                {
                    id: 3,
                    name: {
                        en: 'HANANOYA',
                        ja: '花野屋',
                        ko: '하나노야',
                        ch_t: '花野屋',
                    },
                    image: '/img/tourist_guide/sample.png',
                    url: 'http://www.hananoya.jp/',
                    map: {
                        ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d1141.6631622529394!2d137.81489047470055!3d35.96871025414837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2619370c25%3A0x1b716894ea208b3d!2z6Iqx6YeO5bGLIOWuv-WgtOW6lw!3m2!1d35.967602!2d137.8135702!5e0!3m2!1sja!2sjp!4v1653282357780!5m2!1sja!2sjp',
                        en: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d523.4602227660798!2d137.81407242515957!3d35.96790258128118!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2619370c25%3A0x1b716894ea208b3d!2z6Iqx6YeO5bGLIOWuv-WgtOW6lw!3m2!1d35.967602!2d137.8135702!5e0!3m2!1sen!2sjp!4v1653360614889!5m2!1sen!2sjp',
                    },
                },
                {
                    id: 4,
                    name: {
                        en: 'Nakamura Residence (National Important Cultural Property)',
                        jp: '国指定重要文化財 中村邸',
                        ko: '국가 지정 중요 문화재 나카무라 저택',
                        ch_t: '國家重要文化財產中村家',
                    },
                    image: '/img/tourist_guide/sample.png',
                    url: 'https://www.city.shiojiri.lg.jp/soshiki/75/3812.html',
                    map: {
                        ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d3229.232078046571!2d137.81224136527794!3d35.96575115571903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e298ddb4ed9%3A0x236ddf35ca8235ff!2z5Lit5p2R6YK4!3m2!1d35.9633973!2d137.80883799999998!5e0!3m2!1sja!2sjp!4v1653281464369!5m2!1sja!2sjp',
                        en: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d3840.0974693721473!2d137.81239603945394!3d35.9683889618745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e298ddb4ed9%3A0x236ddf35ca8235ff!2zTmFrYW11cmEgUmVzaWRlbmNlIOS4readkemCuA!3m2!1d35.9633973!2d137.80883799999998!5e0!3m2!1sen!2sjp!4v1653360679577!5m2!1sen!2sjp',
                    },
                },
                {
                    id: 5,
                    name: {
                        en: 'Naragawa Historical and Folklore Museum',
                        jp: '楢川歴史民俗資料館',
                        ko: '楢川 역사 민속 자료관',
                        ch_t: '奈良川歷史民俗資料館',
                    },
                    image: '/img/tourist_guide/sample.png',
                    url: 'https://tokimeguri.jp/guide/narakawa-shiryokan/',
                    map: {
                        ja: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d4566.706486802275!2d137.81500383364155!3d35.9677794580687!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2a05b9d9bb%3A0x1cd6be83a76fc8f4!2z5qWi5bed5q205Y-y5rCR5L-X6LOH5paZ6aSo!3m2!1d35.961608399999996!2d137.8078635!5e0!3m2!1sja!2sjp!4v1653282114861!5m2!1sja!2sjp',
                        en: 'https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d3047.911539131112!2d137.8142459603168!3d35.96776425955193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z44CSMzk5LTYzMDMg6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIOagquW8j-S8muekvlNVWUFNQQ!3m2!1d35.970091499999995!2d137.81831069999998!4m5!1s0x601d1e2a05b9d9bb%3A0x1cd6be83a76fc8f4!2zTmFyYWthd2EgSGlzdG9yeSBhbmQgRm9sa2xvcmUgTXVzZXVtIOalouW3neattOWPsuawkeS_l-izh-aWmemkqA!3m2!1d35.961608399999996!2d137.8078635!5e0!3m2!1sen!2sjp!4v1653364965031!5m2!1sen!2sjp',
                    },
                },
            ],
        }
    },
    mounted() {},
    methods: {
        getItemName(item) {
            if (this.$i18n.locale == 'en')
            return item.name.en
            else if (this.$i18n.locale == 'ja')
            return item.name.ja
            else if (this.$i18n.locale == 'ch_t')
            return item.name.ch_t
            else if (this.$i18n.locale == 'ko')
            return item.name.ko
            else return item.name[process.env.MIX_DEFAULT_LOCALE]
        },
        setMapPath(item) {
            this.map_path.en = item.map.en
            this.map_path.ja = item.map.ja
        },
    },
    computed: {
        mapPath: function () {
            if (this.$i18n.locale == 'en') {
                return this.map_path.en
            } else if (this.$i18n.locale == 'ja') {
                return this.map_path.ja
            } else {
                return this.map_path[process.env.MIX_DEFAULT_LOCALE]
            }
        },
    },
}
</script>
<style scoped>
.tourist-guide-scroll {
    overflow-y: auto;
    max-height: 200px !important;
}

img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
</style>
