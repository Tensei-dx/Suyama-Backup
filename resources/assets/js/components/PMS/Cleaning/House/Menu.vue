<template>
    <div class="row">
        <!-- TIME AND DATE -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-6 mx-3">
                    <span class="custom-clock-time font-weight-bold" style="font-size:40px;">
                        <Clock format="LT" :locale="this.locale"></Clock>
                    </span>
                    <span class="text-capitalize date font-weight-bold" style="font-size:25px;">
                        <Clock format="LL" :locale="this.locale"></Clock>
                    </span>
                </div>
            </div>

        </div>

        <!-- card-body -->
        <div class="col-md-12 px-4 mt-5">
            <!-- Table -->
            <table class="px-2 py-6" style="width:100%">

                <tr class="px-2  h3 border-2 border-bottom  text-center ">
                    <th>No</th>
                    <th>Room</th>
                    <th>Due Time</th>
                    <th>Cleaning Status</th>
                    <th>Room Status</th>
                </tr>
                <!-- <tr class="bg-primary " style="font-size:20px;height:2px;" >

                    </tr> -->
                <tr class="  h6 border-bottom border-top-0 border-left-0 border-right-0 text-center"
                    :class="isSelected(index)" v-for="(item, index) in cleaning" @click="selectRow(index, item.ROOM_ID)"
                    :key="index">
                    <td class="py-2">{{index + 1}}</td>
                    <td>{{item.ROOM_NAME}}</td>
                    <td>{{$d(new Date(item.cleaning_log[0].DUE_TIME), 'long', $i18n.locale)}}</td>
                    <td>{{item.cleaning_log[0].status_code.STATUS_NAME}}</td>
                    <td>{{item.status_code.STATUS_NAME}}</td>
                </tr>

            </table>
            <!-- End Table -->

            <!-- </div> -->
        </div>
    </div>
</template>

<script>
import Clock from '../Event/Clock.vue'
//import LogoutModal from '../Modal/LogoutModal.vue';

export default {
    props: {
        locale: '',
    },

    data() {
        return {
            cleaning: [],
            selectedRoomId: '',
            showCleanReq: '',
            roomId: '',
        }
    },

    components: {
        Clock,
        // LogoutModal
    },

    created() {
        //getAllCleaningLogs

        this.getAllCleaningLogs()
        // this.$bus.$emit('selectedRoomId', roomId);
    },
    mounted() {
        //this.getAllCleaningLogs();
    },

    methods: {
        getAllCleaningLogs() {
            axios.get('getAllCleaningLogs').then(response => {
                this.cleaning = response.data
            })
        },
        selectRow(key, roomId) {
            this.selectedRoomId = key

            this.$bus.$emit('selectedRoomId', roomId)
        },
        isSelected(key) {
            if (key === this.selectedRoomId) {
                return 'data-sel'
            }
        },

        showLogout() {
            $('#logoutModal')
                .modal({
                    backdrop: 'static',
                })
                .modal('show')
        },
    },
}
</script>

<style>
.border-2 {
    border-width: 2px;
}
.font-1 {
    font-size: 1.35em !important;
}
.data-sel {
    border: 3px solid #ffa500 !important;
}
</style>
