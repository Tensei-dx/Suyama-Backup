<!--
    <System Name> iBMS
    <Program Name> CreateBindingMap.vue

    <Created>            TP
    <Updated> 2019.09.04 TP Jethro  Added Header Comment
              2020.05.26 TP Uddin   Modify axios URL according to the URL list
-->
<template>
    <div class="col-md-12">
        <div class="row">
            <!-- Floor Map -->
            <div id="zoomMap" class="col-md-12">
                <svg class="svg" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <path v-b-tooltip.hover
                        :title="room['ROOM_NAME']"
                        :d="room['coor']"
                        :class="room['roomMap']==roomMapName ? 'hilight-selected' : ''"
                        v-for="room,key in currentFloor['FLOOR_MAP_DATA']['roomMap']"
                        class="hilight hilight-bind"
                        vector-effect="non-scaling-stroke"/>
                </svg>
                <img id="FloorMap" class="svg-image unselectable" :src="currentFloor['FLOOR_MAP_DATA']['floorImage']">
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default{
        props:{
            currentFloor:'',
            currentRoom:'',
        },
        data(){
            return{
                selectedRoom:'',
                displayMode:'floor',
                hilight_key:'0',
                roomMapName:''
            }
        },
        created(){

        },
        mounted(){

        },
        watch:{
            //Watch for changes of Current Floor
            currentFloor:function(){
                $('#FloorMap').fadeTo(500,1);
                this.displayMode='';
            },
            currentRoom:function(){
                var rooms = this.currentFloor.FLOOR_MAP_DATA.roomMap;
                var count = rooms.length;
                for(var i = 0; i <= count; i++){
                    if (this.currentRoom.ROOM_MAP_DATA.ROOM_MAP == rooms[i].roomMap) {
                        this.roomMapName = this.currentRoom.ROOM_MAP_DATA.ROOM_MAP;
                        break;
                    }
                }
            }
        },
        methods:{
        }
    };
</script>
