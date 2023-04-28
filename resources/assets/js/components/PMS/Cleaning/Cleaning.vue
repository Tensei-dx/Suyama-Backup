<template >
    <div>
        <!-- Main Content -->
        <div v-if="start === 'cleaning'" class="row mx-0 " style= "height:630px">
            <!-- MENU -->
            <div class="col-xl-6 pt-1 pb-1 hotel-theme-color-1" style="color:white;">
                <Menu v-on:selectedRoom="selectedRoom"  :locale="this.locale"></Menu>
            </div>
            <div class="col-xl-6 pt-1 hotel-theme-color-2" style="color:white;">
                <CleaningRequirements v-on:selectedRoom="selectedRoom" :locale="this.locale"></CleaningRequirements>
            </div>
        </div>    
        <!-- Task List -->
        <div v-else  class="row mx-0 " style= "height:680px">
            <div class="col-xl-5 pt-1 pb-1 hotel-theme-color-1" style="color:white;">
                <TaskList :roomId="roomId" :locale="this.locale"></TaskList>
            </div>
            <div class="col-xl-7 pt-1 hotel-theme-color-1" style="color:white;">
                <TaskNeeded :roomId="roomId" :locale="this.locale"></TaskNeeded>
            </div>
        </div>
        <!-- Footer -->
        <Footer v-on:changeLang="changeLang"></Footer>
    </div>
</template>

<script>
    import Menu from './House/Menu.vue';
    import CleaningRequirements from './House/CleaningRequirements.vue';
    import TaskList from './Task/TaskList.vue';
    import TaskNeeded from './Task/TaskNeeded.vue';
    import Footer from '../Guest/Footer/Footer.vue';

    export default {
        components: {
            Menu,
            CleaningRequirements,
            TaskList,
            TaskNeeded,
            Footer,
        },

        data() {
            return {
                date: '',
                currentPage: 'dashboard',
                roomName: '',
                roomId: '',
                room:'',
                locale: 'en',
                start:'cleaning'
            }
        },

        methods: {

            /**
             * @name changePage
             * @desc Change the page depending on the icon that has beem selected
             * @param {String} value
             */
            changePage(value) {
                this.currentPage = value;
            },

            /**
             * @name changeLang
             * @desc Change the language
             * @param {String} value
             */
            changeLang(value) {
            this.locale = value;
             },

            /**
             * @name selectedRoom
             * @desc Selects a room and will directs to Room Device Operation Page
             * @param {Object}
             */
            selectedRoom(...args) {
               this.start       =  args[0];
               this.roomId      =  args[1];
            },

            /**
             * @name getRoomDevices
             * @desc Get all devices in the specific rooms
             */
            getRoomDevices() {
                axios.get('getRoomDevices/' + this.roomId )
                .then(response =>{
                    this.devices = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
            }
        },

        watch: {
            roomId: function() {
                this.getRoomDevices();
            }
        }
    }
</script>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }
    
    .room{
        margin-bottom: 15px;
    }
    .indicator{
        margin: auto;
        margin-top:1px;
        margin-bottom: 8px;
        font-size: 16px;
    }
    div.gfg {
        margin-left:17px;
        padding:15px;
        background-color:transparent;
        width: 740px;
        height: 520px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .room-status-border{
        border-radius: 35px;
    }
    .room-status-footer{
        border-radius: 0px 0px 35px 35px !important;
        height: 30px !important;
        padding-top: 0.15rem !important;
    }
</style>
