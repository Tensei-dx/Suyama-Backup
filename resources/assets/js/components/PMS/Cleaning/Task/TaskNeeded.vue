<template>
    <div class="row">
        <!-- TIME AND DATE -->
        <div class="col-md-12">
            <div class = "row">
                <div class= "col-12 ">
                    
                </div>
            </div>    
        </div>
        <div class="col-md-12 px-2">             
            <div class="pl-1 font-weight-bold" style="font-size:25px;">{{taskName.TASK_NAME}}</div>
            <!-- <div class="card mb-3" style="max-width: 100%;"> -->
                <div class="row px-4 no-gutters card "  style="width: 30rem;">
            
                        
                           <img :src ="taskName.IMAGE_PATH" class= "card-img-top" />
                       
                    
                    <div class="card-body">
                        <span class ="text-center text-dark" style="font-size:30px;"> 
                            {{taskName.TASK_CONTENT}}
                        </span>
                    </div>
                </div>
            <!-- </div> -->
            <div class= "text-right">
                
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
            locale:'',
            roomId:'',
        },

        data() {
            return {
                taskList:[], 
                taskName:'', 
            }
        },

        components: {
            Clock,
            // LogoutModal
        },

        created() {
            this.getCleaningTask();     
        },

        mounted(){
            this.$bus.$on('selectedTask', data => {
            this.taskName = data; 
            this.getCleaningTask(this.taskName); 
        });
        },

        methods: {
            getCleaningTask(taskName) {
                axios.get('getCleaningTask',{
                        params:{
                            ROOM_ID:this.roomId
                        }   
                }).then(response=>{    
                    this.taskList = response.data; 
                });
            },
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
    .image-fit{
        height: 50%;
        width: 50%;
    }

</style>
