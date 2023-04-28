<template>
    <div class="card-body">
        <div class="row">
            <div :class="fullWidth ? 'col-sm-6' : 'col-sm-12'">
                <div class="position-relative vh-40" v-if="bindLists.length === 0">
                    <div class="display-4 p-3 text-center">
                        {{$t('binding.noBindingAvailable')}}
                    </div>
                </div>
                <div v-else class="position-relative" :class="totalRows ? '' : 'vh-40'">
                    <div role="tablist">
                        <div v-for="bindList,index in limitedBinding">
                            <div class="p-1" role="tab">
                                <div class="d-flex justify-content-between">
                                    <a href="#" v-b-toggle="'accordion'+index" class="text-dark">
                                        <h3 class="m-0">{{bindList.DEVICE_NAME}}</h3>
                                    </a>
                                    <div class="px-3">
                                        <a class="custom-pointer" @click="deleteBinding(bindList)">
                                            <span class="">
                                                <i class="text-danger fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                        <a v-if="bindList.BINDING_STATUS_GROUP == 1" class="custom-pointer"
                                           @click="disableAllBindings(bindList)">
                                            <span class="">
                                                <i class="text-success fa fa-check-circle-o fa-lg"
                                                   aria-hidden="true"></i>
                                            </span>
                                        </a>
                                        <a v-else class="custom-pointer" @click="enableAllBindings(bindList)">
                                            <span class="">
                                                <i class="text-warning fa fa-times-circle-o fa-lg"
                                                   aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="divider-line-2">{{bindList.floor.FLOOR_NAME}} / {{bindList.room.ROOM_NAME}}
                                    - {{bindList.gateway.GATEWAY_NAME}}</div>
                            </div>
                            <b-collapse :id="'accordion'+index" accordion="my-accordion" role="tabpanel" class="">
                                <div class="justify-content-between align-items-center px-1"
                                     v-for="binding,key in bindList.binding_alerts">
                                    <!-- <div> -->
                                    <div class="d-flex" v-for="user,key in binding.TARGET_USER_ALERT">
                                        <div class="d-flex">
                                            <span v-b-tooltip.hover :title="binding.TARGET_DEVICE_CONDITION_READABLE">
                                                USER ID: {{user.user_id}}
                                                <i class="fa fa-arrow-circle-o-right"></i>
                                                <span v-if="user.email == true">EMAIL: Activated</span>
                                                <span v-if="user.email == true && user.sms == true">/</span>
                                                <span v-if="user.sms == true">SMS: Activated</span>
                                                <i class="fa fa-arrow-circle-o-right"></i>
                                            </span>
                                            <div v-b-tooltip.hover title="Alert Activated When">
                                                {{binding.SOURCE_DEVICE_CONDITION.operator + ":" + binding.SOURCE_DEVICE_CONDITION.data }}
                                            </div>
                                            <div class="px-2">
                                                <a class="custom-pointer" @click="deleteSpecificBinding(binding,user)">
                                                    <span class="">
                                                        <i class="text-danger fa fa-trash-o fa-lg"
                                                           aria-hidden="true"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-collapse>
                        </div>
                    </div>
                    <div v-if="pagination.total > 5" :class="totalRows ? '' : 'custom-pagination-position'"
                         class="custom-pagination-orange d-flex justify-content-center pt-3">
                        <b-pagination :total-rows="pagination.total" :per-page="drawData.length"
                                      v-model="pagination.currentPage"></b-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        selectedRoom: '',
        selectedBindingListFLoor: '',
    },
    created() {
        //call getBinding function
        this.getBinding()
        this.$bus.$on('getAlertBindingData', data => {
            this.getBinding()
        })
    },
    data() {
        return {
            // where data variables is declare and initialize
            ttt: true,
            bindLists: [],
            drawData: {
                length: 5,
                search: '',
            },
            pagination: {
                currentPage: 1,
                total: 0,
            },
            selectedBinding: '',
            binded: {
                SOURCE_DEVICE_TYPE: '',
                TARGET_DEVICE_TYPE: '',
            },
            isBinded: false,
            required: true,
        }
    },

    methods: {
        getBinding(pages) {
            let filterByFloor = ''
            if (this.selectedBindingListFLoor['FLOOR_ID'] != null) {
                filterByFloor = '?filter=FLOOR_ID:' + this.selectedBindingListFLoor['FLOOR_ID']
                if (this.selectedRoom.length != 0) {
                    filterByFloor += '|ROOM_ID:' + this.selectedRoom['ROOM_ID']
                }
            }
            axios
                .get('getAlertBinding' + filterByFloor, {
                    params: {
                        include: 'floor>room>gateway',
                        search: this.drawData.search,
                    },
                })
                .then(response => {
                    let data = response.data
                    this.bindLists = data
                    this.pagination.total = data.length
                    for (var i in data) {
                        let statusCounter = 0
                        for (var j in data[i]['binding_alerts']) {
                            if (data[i]['binding_alerts'][j]['BINDING_STATUS'] == 1) {
                                statusCounter++
                            }
                        }
                        if (statusCounter > 0 || statusCounter == data[i].length) {
                            data[i]['BINDING_STATUS_GROUP'] = 1
                        } else {
                            data[i]['BINDING_STATUS_GROUP'] = 0
                        }
                    }
                    //------------------------------------------------
                    this.$bus.$emit('setAlertBindingData', data.data)
                })
                .catch(errors => {
                    console.log(errors)
                })
            this.$forceUpdate()
        },
        deleteBinding(data) {
            let text = this.$t('binding.modalText.delBinding')
            let content = this.$t('binding.modalText.sure')
            this.$swal({
                title: text,
                text: content,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then(result => {
                if (result.value) {
                    axios
                        .post('deleteAllAlertBinding', {
                            DEVICE_ID: data.DEVICE_ID,
                        })
                        .then(response => {
                            if ((response.data = 'success')) {
                                let title = this.$t('binding.modalText.deleted')
                                let message = this.$t('binding.modalText.bindingDeleted')
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_023')
                                this.$swal('Error', message, 'error')
                            }
                            this.reloadData()
                        })
                }
            })
        },
        deleteSpecificBinding(binding, user) {
            let text = this.$t('binding.modalText.delUser')
            let content = this.$t('binding.modalText.sure')
            this.$swal({
                title: text,
                text: content,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then(result => {
                if (result.value) {
                    axios
                        .post('deleteAlertBinding', {
                            BINDING_ALERT_ID: binding.BINDING_ALERT_ID,
                            USER_ID: user.user_id,
                        })
                        .then(response => {
                            if ((response.data = 'success')) {
                                let title = this.$t('binding.modalText.deleted')
                                let message = this.$t('binding.modalText.deviceDeleted')
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_024')
                                this.$swal('Error', message, 'error')
                            }
                            this.reloadData()
                        })
                }
            })
        },
        enableAllBindings(data) {
            let text = this.$t('binding.modalText.enGroup')
            let content = this.$t('binding.modalText.sure')
            this.$swal({
                title: text,
                text: content,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then(result => {
                if (result.value) {
                    axios
                        .post('enableAllAlertBinding', {
                            BINDING: data.DEVICE_ID,
                        })
                        .then(response => {
                            let title = this.$t('binding.modalText.enable')
                            let message = this.$t('binding.modalText.groupEnable')
                            if ((response.data = 'success')) {
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_087')
                                this.$swal('Error', message, 'error')
                            }
                            this.$bus.$emit('getAlertBindingData')
                        })
                }
            })
        },
        disableAllBindings(data) {
            let text = this.$t('binding.modalText.disGroup')
            let content = this.$t('binding.modalText.sure')
            this.$swal({
                title: text,
                text: content,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
            }).then(result => {
                if (result.value) {
                    axios
                        .post('disableAllAlertBinding', {
                            BINDING: data.DEVICE_ID,
                        })
                        .then(response => {
                            if ((response.data = 'success')) {
                                let title = this.$t('binding.modalText.disable')
                                let message = this.$t('binding.modalText.groupDisable')
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_088')
                                this.$swal('Error', message, 'error')
                            }
                            this.$bus.$emit('getAlertBindingData')
                        })
                }
            })
        },
        reloadData() {
            this.$bus.$emit('getAlertBindingData')
            this.selectedBinding = ''
        },
        convertCustomCondition(customCondition) {
            var sCondition = ''
            var sConditionInformative = ''
            var sReturn = ['', '']
            var switchCtr = 0
            if (customCondition.length > 0) {
                for (var i in customCondition) {
                    switchCtr += 1
                    if (customCondition[i]['enabled'] == true) {
                        if (customCondition[i]['value'] == 1) {
                            sCondition += 'ON-'
                            sConditionInformative += 'Switch ' + switchCtr + ' - ON\n'
                        } else {
                            sCondition += 'OFF-'
                            sConditionInformative += 'Switch ' + switchCtr + '- OFF\n'
                        }
                    }
                }
                if (sCondition != '') {
                    sReturn[0] = sCondition.substr(0, sCondition.length - 1)
                    sReturn[1] = sConditionInformative
                } else {
                    sReturn[0] = sCondition
                    sReturn[1] = sConditionInformative
                }
            }
            return sReturn
        },
    },
    computed: {
        //for binding list pagination
        limitedBinding() {
            let from = this.drawData.length * this.pagination.currentPage - 5
            let to = this.drawData.length * this.pagination.currentPage
            return this.bindLists.slice(from, to)
        },
        fullWidth() {
            if (this.bindLists.length === 0) {
                return false
            } else {
                if (this.selectedBinding == '') {
                    return false
                } else {
                    return true
                }
            }
        },
        totalRows() {
            var total
            var bindingList = this.bindLists
            var bindingCount = 0
            for (var x in bindingList) {
                var bindingLength = bindingList[x].bindings
                for (var i in bindingLength) {
                    bindingCount++
                }
            }

            if (this.pagination.total >= 5) {
                total = true
            } else {
                if (bindingCount >= 16) {
                    total = true
                } else {
                    total = false
                }
            }
            return total
        },
    },
    watch: {
        //update data on room change
        selectedRoom: function () {
            this.getBinding()
        },
    },
    mounted() {
        Echo.channel('test-binding').listen('testBindingEvent', value => {
            this.getBinding()
        })
    },
}
</script>
