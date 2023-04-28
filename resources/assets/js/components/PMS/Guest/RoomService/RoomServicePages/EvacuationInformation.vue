<template>
    <div class="p-2">
        <span class="d-block font-weight-bold theme-color" style="font-size: 2rem;">
            {{ $t('mobile.roomservice.evacuationRouteMap') }}
        </span>
        <img :src="getImagePath()" class="img-fluid p-3">
    </div>
</template>
<script>
export default {
    components:{

    },
    data(){
        return {
            img_path: {
                en: 'img/hotel_pics/evac_route.jpg',
                ja: 'img/hotel_pics/evac_route_ja.jpg',
                ko: 'img/hotel_pics/evac_route_ja.jpg',
                ch_t: 'img/hotel_pics/evac_route_ja.jpg'
            },
        }
    },
    methods:{
        /**
         *
         */
        getImagePath() {
            switch (this.$i18n.locale) {
                case 'en':
                    return this.img_path.en
                case 'ja':
                    return this.img_path.ja
                case 'ko':
                    return this.img_path.ko
                case 'ch_t':
                    return this.img_path.ch_t
                default:
                    return this.img_path[process.env.MIX_DEFAULT_LOCALE]
                break;
            }
        }
    },
    watch: {
        // $i18n.locale : () => {
        //     this.getImage();
        // }
    },

}
</script>
