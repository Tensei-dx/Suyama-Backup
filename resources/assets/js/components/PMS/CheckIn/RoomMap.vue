<template>
    <div class="container">
        <div class="row p-4 bg-dark-gray">
            <div class="col-12">
                <span class="text-white text-left" style="font-size: 3em;">Room Map</span>
            </div>
            <div class="col-12 text-center p-3">
                <img src="img/hotel_pics/room_map_pic_1.jpg" style="width: 1000px;">
            </div>
            <div class="col p-3 text-right">
                <a type="button" href="roomMap" class="btn bg-light-blue">Start</a>
            </div>
        </div>
        <Footer class="bg-light-gray"></Footer>
    </div>
</template>

<script>
import Footer from '../Guest/Footer/Footer.vue';
export default {
    components: {
        Footer
    },
    data() {
        return {

        }
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
</style>
