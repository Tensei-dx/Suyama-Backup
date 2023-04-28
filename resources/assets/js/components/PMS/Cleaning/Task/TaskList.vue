<template>
    <div class="row">
        <!-- TIME AND DATE -->
        <div class="col-md-12">
            <div class="row">
                <div class="px-4 py-3 col-6">
                    <span class=" custom-clock-time font-weight-bold" style="font-size:40px;">

                        {{cleaningTask.ROOM_NAME}}
                    </span>

                </div>
                <div class="col-6">
                    <div class="custom-clock-time font-weight-bold " style="font-size:40px;margin-bottom:-12px ">
                        <Clock format="LT" :locale="this.locale"></Clock>
                    </div>
                    <div class="text-capitalize date font-weight-bold" style="font-size:25px; margin-bottom:15px">
                        <Clock format="LL" :locale="this.locale"></Clock>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="h4 px-4 col-md-12">
                    Due Time: {{$d(new Date(cleaningTask.cleaning_log[0].DUE_TIME), 'long', $i18n.locale)}}
                </div>
            </div>
        </div>

        <!-- card-body -->
        <div class="col-md-12 px-4">
            <div class="px-1 " style="width:100%">
                <div class="px-2  border-2 border-top ">
                    <div class="row px-2 mt-3 no-gutters " :class="isSelected(index)"
                         v-for="(item, index) in cleaningTask.task_list" @click="selectRow(index, item)" :key="index">

                        <div class="col-md-6  px-4 py-2">
                            <span style="font-size:30px; color:black;">
                                {{item.TASK_NAME}}
                            </span>
                        </div>
                        <div class="col-md-6 px-1 text-right">
                            <button type="button" class="btn bg-transparent button-bgcolor" @click="clickDone(index)">
                                {{item.isDone ? 'Cancel' : 'Done'}}
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import Clock from '../Event/Clock.vue'
//import LogoutModal from '../Modal/LogoutModal.vue';

export default {
    props: {
        locale: '',
        roomId: '',
    },

    data() {
        return {
            cleaningTask: [],
            selectedTask: '',
            showCleanReq: '',
            taskName: '',
            fullSelect: [],
        }
    },

    components: {
        Clock,
        // LogoutModal
    },

    created() {
        //getAllCleaningLogs

        this.getCleaningTask()
        // this.$bus.$emit('selectedRoomId', roomId);
    },
    mounted() {},

    methods: {
        getCleaningTask() {
            axios
                .get('getCleaningTask', {
                    params: {
                        ROOM_ID: this.roomId,
                    },
                })
                .then(response => {
                    this.cleaningTask = response.data
                    this.cleaningTask.task_list = this.cleaningTask.task_list.map(item =>
                        Object.assign(item, { isDone: false })
                    )
                })
        },
        selectRow(key, taskName) {
            this.selectedTask = key
            this.$bus.$emit('selectedTask', taskName)
        },

        isSelected(key) {
            let ret = ''
            if (key === this.selectedTask) {
                ret += 'data-selected '
            }

            if (this.cleaningTask.task_list[key].isDone === true) {
                ret += 'bg-color-done'
            } else {
                ret += 'card-color'
            }
            return ret
        },

        clickDone(key) {
            this.cleaningTask.task_list[key].isDone = !this.cleaningTask.task_list[key].isDone
            this.$forceUpdate()
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
.ct-bg-icon {
    height: 120px;
    width: 120px;
}
.ct-bg-icon:hover {
    background-color: hsl(218, 80%, 90%) !important;
    height: 120px;
    width: 120px;
}
/* .table, td {

        border-collapse: collapse;
        }
        th, td {
        border-bottom: 2px solid white;
        padding: 5px;
        text-align: left;
        } */
/* .table, th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        color: white;
    } */
.border-2 {
    border-width: 2px !important;
}
.font-1 {
    font-size: 1.35em !important;
}
.data-hover:hover {
    border: 3px solid #ffa500 !important;
    cursor: pointer;
}
.data-selected {
    border: 5px solid #ffa500 !important;
    border-radius: 20px;
}
.bg-color-done {
    background-color: #bfbfbf !important;
}
.bg-color-cancel {
    background-color: blue !important;
}

.isDone-bgcolor {
    background-color: solid #bfbfbf !important;
}

.button-bgcolor {
    color: #263033;
    font-size: 30px;
}
</style>
