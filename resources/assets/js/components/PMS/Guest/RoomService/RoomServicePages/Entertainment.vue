<template>
    <div class="row justify-content-center align-items-center no-gutters mb-3">
        <div class="col-12 my-2 h3 text-white">{{ $t('mobile.roomservice.entertainment') }}</div>
        <div class="col-md-6 col-4 text-center border border-black p-3" v-for="app in apps" :key="app.id">
            <span class="d-block text-white text-uppercase pb-2">{{ app.name }}</span>
            <div class="pointer p-3 mx-auto d-block custom-app-image-size" @click="goTo(app.link, app.fallback)">
                <img :src="app.image" :class="app.class || 'mx-auto d-block w-auto h-100'">
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            userAgent: '',
            apps: [
                {
                    id: 0,
                    name: 'Netflix',
                    image: 'img/hotel_pics/room_service/Netflix_Logo_RGB.png',
                    class: '',
                    link: 'ibmscontroller://opennetflix',
                    fallback: 'https://www.netflix.com',
                },
                {
                    id: 1,
                    name: 'Amazon Prime',
                    image: 'img/hotel_pics/room_service/prime-video-logo@logotyp.us.png',
                    class: '',
                    link: 'ibmscontroller://openprime',
                    fallback: 'https://www.primevideo.com',
                },
                {
                    id: 2,
                    name: 'Youtube',
                    image: 'img/hotel_pics/room_service/youtube_social_icon_white.png',
                    class: '',
                    link: 'ibmscontroller://openyoutube',
                    fallback: 'https://www.youtube.com',
                },
                {
                    // = SPRINT_03 TASK104
                    //     id: 4,
                    // = SPRINT_03 TASK104
                    id: 3,
                    name: 'Spotify',
                    image: 'img/hotel_pics/room_service/Spotify_Icon_RGB_Green.png',
                    class: '',
                    link: 'ibmscontroller://openspotify',
                    fallback: 'https://open.spotify.com',
                },
                {
                    // = SPRINT_03 TASK104
                    //     id: 5,
                    // = SPRINT_03 TASK104
                    id: 4,
                    name: 'Apple Music',
                    image: 'img/hotel_pics/room_service/Apple_Music_Icon_RGB_sm_073120.png',
                    class: '',
                    link: 'ibmscontroller://openapplemusic',
                    fallback: 'https://music.apple.com/us/browse',
                },

                // + SPRINT_03 TASK104
                {
                    id: 5,
                    name: 'Demae-Can',
                    image: 'img/hotel_pics/room_service/Demae-Can.png',
                    class: '',
                    link: 'ibmscontroller://opendemaecan',
                    fallback: 'https://demae-can.com/',
                },
                // + SPRINT_03 TASK104

                // - SPRINT_03 TASK104
                // {
                //     id: 3,
                //     name: 'Uber Eats',
                //     image: 'img/hotel_pics/room_service/UE_Logo_Stacked_RGB_Salt Green@2x.png',
                //     class: '',
                //     link: 'ibmscontroller://openubereats',
                //     fallback: 'https://www.ubereats.com',
                // },
                // - SPRINT_03 TASK104
            ],
        }
    },
    mounted() {
        this.getDeviceType()
    },
    methods: {
        /**
         *
         */
        getDeviceType() {
            const ua = navigator.userAgent
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                this.userAgent = 'tablet'
            } else if (
                /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(
                    ua
                )
            ) {
                this.userAgent = 'mobile'
            } else if (/Kioware/i.test(ua)) {
                this.userAgent = 'kiosk'
            } else {
                this.userAgent = 'desktop'
            }
        },
        /**
         *
         */
        goTo(url, fallback_url) {
            if (this.userAgent !== 'desktop') {
                window.location = url
            } else {
                window.open(fallback_url)
            }
        },
    },
}
</script>
<style>
.custom-app-image-size {
    height: 100px;
    width: 200px;
}
</style>
