<template>
    <div class="row">
        <!-- TIME AND DATE -->
        <div class="col-md-12">
            <div class = "row">
                <div class= "col-12 ">
                    <span class="font-weight-bold" style="font-size:40px; margin-top:15px;">
                        {{cleaningInfo.ROOM_NAME}}
                    </span>
                    <div class="row h5">
                        <div class="col-md-5" >
                           <span class = "font-weight-bold"> Room Type: </span> 
                           {{cleaningInfo.room_type.NAME}}
                        </div>
                        <div class="col-md-7 text-right pr-5">
                            <span class = "font-weight-bold ">Check in Time: </span>
                            {{$d(new Date(cleaningInfo.book.CHECK_IN_TIME), 'long', $i18n.locale)}}
                        </div>
                    </div>
                    <div class="row h5">
                        <div class= "col-md-5">
                            <span class = "font-weight-bold"> Status:</span>
                                {{cleaningInfo.status_code.STATUS_NAME}}
                        </div>
                        <div class= "col-md-7 text-right pr-4">
                            <span class = "font-weight-bold">Check out Time: </span>
                            {{$d(new Date(cleaningInfo.book.CHECK_OUT_TIME), 'long', $i18n.locale)}}
                        </div>
                    </div>
                    <div class="row h5"> 
                        <div class="col-md-6" >
                            <span class = "font-weight-bold"> Due Time: </span>
                                {{$d(new Date(cleaningInfo.cleaning_log[0].DUE_TIME), 'long', $i18n.locale)}}
                        </div>
                        <div class="col-md-6 text-left" >
                            <span class = "font-weight-bold"> People: </span>
                            {{cleaningInfo.book.NO_OF_PEOPLE}}
                        </div>
                    </div>
                    <div class="row h5">
                        <div class="col-md-12" >
                            <span class = "font-weight-bold">Message:</span>
                            {{cleaningInfo.cleaning_log[0].status_code.STATUS_NAME}}
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div class="col-md-12 px-2">             
            <div class= "border-2 border-bottom"  style="height:40px; width:99%;"></div>
            <div class="pl-1 font-weight-bold" style="font-size:25px;">Items needed:</div>
            <div class="card mb-3" style="max-width: 100%;" v-for="(item, index) in cleaningInfo.room_type.room_item" :key="index">
                <div class="row no-gutters">
                    <div class="col-md-1 card-color">
                        <span class ="text-center" style="font-size:30px;"> 
                            {{index + 1}}
                        </span>
                    </div>
                    <div class="col-md-3 card-color">
                        <span class ="text-center" style="font-size:30px;"> 
                            
                        </span>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <span class ="text-center" style="font-size:30px; color:black;"> 
                            {{item.ITEM_NAME}}
                        </span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <span class ="text-center" style="font-size:30px; color:black;"> 
                        x{{item.QTY}}
                        </span>
                    </div>
                </div>
            </div>
            <div class= "text-right">
                <button @click="$emit('selectedRoom', 'next', cleaningInfo.ROOM_ID)" class="btn btn-orange">Start Cleaning</button>
            </div>
            
            <!-- </div> -->
        </div>
       
    </div>
    
</template>

<script>
    import Clock from '../Event/Clock.vue';
    //import LogoutModal from '../Modal/LogoutModal.vue';

    export default {
        props: {
            locale: '',
        },

        data() {
            return {
                cleaningInfo:[],
                roomId:'',
                start:'',
                
                  
            }
        },

        components: {
            Clock,
            // LogoutModal
        },

        created() {
            this.getRoomInformation();     
        },

        mounted(){
            this.$bus.$on('selectedRoomId', data => {
            this.roomId = data; 
            this.getRoomInformation(this.roomId);
            
             
        });
        },

        methods: {
            getRoomInformation(roomId) {

                axios.get('getRoomInformation',{
                        params:{
                            ROOM_ID:roomId
                        }   
                }).then(response=>{    
                    this.cleaningInfo = response.data;
                    
                    
                });
            },
            // startCleaning(){
            //      this.$bus.$emit('selectedRoom', 'next');
            //      console.log(this.$bus.$emit('selectedRoom', 'next')); 
            // },
            showLogout() {
            $('#logoutModal').modal({
                backdrop: 'static'
            }).modal('show');
            }
        }
    }
</script>

<style>
    .ct-bg-icon {
        height: 120px;
        width: 120px;
    }
    .ct-bg-icon:hover {
        background-color: hsl(218, 80%, 90%) !important;
        height: 120px;
        width: 120px;
    }
    .border-2 {
        border-width:2px !important;
    }
    .font-1{
        font-size: 1.35em !important;
    } 
    .data-hover:hover {
        border:3px solid #ffa500 !important;
        cursor:pointer;
    }
    .data-selected{
        background: none repeat scroll 0 0 #f0e8dd;
    } 
    .card-color{
        background-color:#ADD8E6 !important;
    }

</style>
