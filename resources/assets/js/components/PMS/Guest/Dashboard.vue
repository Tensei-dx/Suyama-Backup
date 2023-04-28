<!--UPDATED: TP Jermaine SPRINT_06 TASK140 20210910 -->
<!--UPDATED: TP Leo Sprint_07 TASK018 20210922 -->
<template>
    <div class="h-100 local-height">
        <!-- Main content -->
        <div class="row mx-0 h-100">
            <!-- Menu bar -->
            <!-- <div class="col-xl-4 pt-2 pb-2 hotel-theme-color-1" style="color:white;">
                <MenuBar :room_id="room.id" v-on:changePage="changePage" :locale="this.locale"></MenuBar>
            </div> -->
            <!-- <div class="col-xl-4 pt-2 pb-2 hotel-theme-color-1" style="color:white;"> -->
            <div v-if="room_id != ''" class="col-xl-4 pt-2 pb-2 hotel-theme-color-1 h-100" style="color:white;">
                <MenuBar :room_id="room_id" v-on:changePage="changePage" :locale="this.locale"
                         :currentPage="currentPage" />
            </div>
            <!-- Device Data -->
            <!-- <div v-if="this.currentPage == 'dashboard'" class="col-xl-3 hotel-theme-color-2">
                <DeviceData :room_id="room.id"></DeviceData>
            </div> -->
            <div v-if="this.currentPage == 'dashboard' && room_id != ''" class="col-xl-3 hotel-theme-color-2 h-100">
                <DeviceData :room_id="room_id" />
            </div>
            <!-- Control Panel -->
            <!-- <div v-if="this.currentPage == 'dashboard'" class="col-xl-5 hotel-theme-color-1">
                <ControlPanel :room_id="room_id" /></ControlPanel>
            </div> -->
            <div v-if="this.currentPage == 'dashboard' && room_id != ''" class="col-xl-5 hotel-theme-color-1 h-100">
                <ControlPanel :room_id="room_id" />
            </div>
            <!-- <div v-if="this.currentPage == 'room-service'" class="col-xl-8 hotel-theme-color-2">
                <RoomService></RoomService> -->
            <div v-else-if="this.currentPage == 'room-service'" class="col-xl-8 hotel-theme-color-2 h-100">
                <RoomService />
            </div>
            <!-- <div v-if="this.currentPage == 'room-manual'" class="col-xl-8 hotel-theme-color-2"> -->
            <!-- <About></About> -->
            <!-- <RoomManual></RoomManual> -->
            <!-- </div> -->
            <div v-else-if="this.currentPage == 'room-manual'" class="col-xl-8 hotel-theme-color-2 h-100">
                <!-- <About></About> -->
                <RoomManual :currentSection="currentSection" @updateSection="changeSectionTo" />
            </div>
            <div v-else-if="this.currentPage == 'manual'" class="col-xl-8 hotel-theme-color-2 h-100">
                <Manual />
            </div>
        </div>
        <!-- Footer -->
        <!-- <Footer v-on:changeLang="changeLang"></Footer> -->
        <Footer @:changeLang="changeLang" :localeLang="this.locale"/>
        <!-- = SPRINT_06 TASK140 -->
    </div>
</template>

<script>
import MenuBar from './MenuBar/MenuBar.vue'
// = SPRINT_06 TASK140
// import DeviceData from './Home/DeviceData';
import DeviceData from './Home/DeviceData.vue'
// = SPRINT_06 TASK140
import ControlPanel from './Home/ControlPanel.vue'
import RoomService from './RoomService/RoomService.vue'
import RoomManual from './RoomManual/RoomManual.vue'
import About from './About/About.vue'
import Footer from './Footer/Footer.vue'
import Manual from '../Management//Manual/Manual.vue'
export default {
    components: {
        MenuBar,
        DeviceData,
        ControlPanel,
        RoomService,
        RoomManual,
        About,
        Footer,
        Manual,
    },

    data() {
        return {
            date: '',
            currentPage: null,
            currentSection: null,
            // = SPRINT_06 TASK140
            // room: {
            //     id: 1
            // },
            // gateway: {},
            room_id: '',
            // = SPRINT_06 TASK140
            locale: 'ja',
        }
    },
    created() {
        // + SPRINT_07 Task018
        let params = new URL(window.location)
        this.currentPage = params.searchParams.get('page') ? params.searchParams.get('page') : 'dashboard'
        this.currentSection = params.searchParams.get('section')
        // + SPRINT_07 Task018

        // = SPRINT_06 TASK140
        // this.getRoomGateway();
        this.getCurrentLoginUserDetails()
        this.getSession()
        // = SPRINT_06 TASK140
    },
    methods: {
        /**
         *
         */
        // + SPRINT_07 Task018
        changeSectionTo(section) {
            this.currentSection = section
            let url = new URL(window.location.href)
            url.searchParams.set('section', this.currentSection)
            window.history.pushState('', '', url)
            // + SPRINT_07 Task018
        },
        /**
         *
         */
        changePage(value) {
            this.currentPage = value
            // + SPRINT_07 Task018
            this.currentSection = 1
            let url = new URL(window.location)
            url.searchParams.set('page', this.currentPage)
            url.searchParams.delete('section')
            window.history.pushState('', '', url)
            // + SPRINT_07 Task018
        },
        changeLang(value) {
            this.locale = value
        },
        getSession() {
            axios.get('/getSession').then(response => {
                if (response.data.locale) {
                    this.locale = response.data.locale
                }
            })
        },
        // = SPRINT_06 TASK140
        // getRoomGateway() {
        //     axios.get('client/gateway', {
        //         params: {
        //             ROOM_ID: this.room.id
        //         }
        //     })
        //     .then(response => {
        //         this.gateway.id = response.data.GATEWAY_ID;
        //         this.gateway.status = response.data.ONLINE_FLAG;
        getCurrentLoginUserDetails() {
            axios
                .get('getCurrentLoginUserDetails')
                .then(response => {
                    this.room_id = response.data
                    // = SPRINT_06 TASK140
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
}
</script>
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
}
.custom-bg-menu {
    background-color: #262626;
    color: white;
}
.custom-bg-control-panel {
    background-color: #262626;
    /* color: white; */
}
.custom-bg-room-service {
    background-color: #262626;
}
.custom-bg-device-data {
    background-color: #404040;
}
.custom-bg-card-items {
    background-color: #404040;
}
.custom-bg-footer {
    background-color: lightgray;
}
.accent-light-blue {
    background-color: #b4c7e7;
}
.border-light-blue {
    border-color: #b4c7e7;
    /* border: none; */
}
.custom-text-light-blue {
    color: #b4c7e7;
}
.custom-i {
    text-align: center;
}
.custom-i-bg {
    color: #b4c7e7;
}
.custom-i-fg {
    color: black;
}
.local-height {
    max-height: 700px !important;
    height: 665px !important;
}
</style>
