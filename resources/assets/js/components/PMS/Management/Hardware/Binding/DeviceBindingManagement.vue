<!--
    <System Name> iBMS
    <Program Name> DeviceBindingManagement.vue

    <Created>            TP Robert
    <Updated>   2019.08.04 TP Harvey  Refactor Code
                2019.09.04 TP Jethro  Added Header Comment
                2020.03.17 TP Harvey  Add Custom Condition
                2020.05.26 TP Uddin   Modify axios URL according to the URL list
                2020.09.23 TP Russell Add Binding Threshold Setting
-->
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
                            <b-collapse :id="'accordion'+index" accordion="my-accordion" role="tabpanel"
                                        class="target-devices">
                                <div class="d-flex justify-content-between align-items-center px-1"
                                     v-for="binding,key in bindList.bindings" @click="selectBindedDevice(binding)"
                                     :class="{'bg-white text-dark': (binding.BINDING_ID == selectedBinding)}">
                                    <div>
                                        <span class="w-25">{{binding.binding_list.SOURCE_DEVICE_CONDITION}}</span>
                                        <i class="fa fa-arrow-circle-o-right"></i>
                                        <span class="list-group-item-text">{{binding.target_device.DEVICE_NAME}}</span>
                                    </div>
                                    <!-- call delmodal function on line 178 -->
                                    <div class="d-flex">
                                        <div v-b-tooltip.hover
                                             :title="binding.binding_list.TARGET_DEVICE_CONDITION_READABLE">
                                            {{binding.binding_list.TARGET_DEVICE_CONDITION}}
                                        </div>
                                        <div class="px-2">
                                            <a class="custom-pointer" @click="deleteSpecificBinding(binding)">
                                                <span class="">
                                                    <i class="text-danger fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <a v-if="binding.BINDING_STATUS == 0" class="custom-pointer"
                                               @click="disableBinding(binding)">
                                                <span class="">
                                                    <i class="text-warning fa fa-times-circle-o fa-lg"
                                                       aria-hidden="true"></i>
                                                </span>
                                            </a>
                                            <a v-else class="custom-pointer" @click="enableBinding(binding)">
                                                <span class="">
                                                    <i class="text-success fa fa-check-circle-o fa-lg"
                                                       aria-hidden="true"></i>
                                                </span>
                                            </a>
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
            <div class="col-sm-6" v-if="showSelectedBinding">
                <div class="border border-dark">
                    <div class="m-2">
                        <img :src="sourceImage" class="w-100" @error="imgUrlAlt">
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
        this.$bus.$on('getDeviceBindingData', data => {
            this.getBinding()
        })
        this.$bus.$on('changeBindingTab', data => {
            this.changeSize()
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
        //Function Name: getBinding
        //Funciton Description: Get sensor to device binding
        //Param: pages(pagination), filterByFloor (filter by floor or room)
        getBinding(pages) {
            //Filter by Floor ID -- Harvey
            let filterByFloor = ''
            let floorId = this.selectedBindingListFLoor['FLOOR_ID']
            let roomId = this.selectedRoom['ROOM_ID']
            if (this.selectedBindingListFLoor['FLOOR_ID'] != null) {
                filterByFloor = '?filter=FLOOR_ID:' + this.selectedBindingListFLoor['FLOOR_ID']

                //To be filter by room.
                if (this.selectedRoom.length != 0) {
                    filterByFloor += '|ROOM_ID:' + this.selectedRoom['ROOM_ID']
                }
            }
            //ajax call
            //PARAMS: pageLength,include,search,page
            axios
                .get('getDevicesWithBindings' + filterByFloor, {
                    params: {
                        BINDING_CATEGORY: 0,
                        include: 'floor>room>gateway',
                        FLOOR_ID: floorId,
                        ROOM_ID: roomId,
                        search: this.drawData.search,
                    },
                })
                .then(response => {
                    let data = response.data
                    this.bindLists = data
                    this.pagination.total = data.length

                    //Input an overall status for each group bindings
                    for (var i in data) {
                        let statusCounter = 0
                        let sCustomCondition = []
                        for (var j in data[i]['bindings']) {
                            if (data[i]['bindings'][j]['BINDING_STATUS'] == 1) {
                                statusCounter++
                            }

                            // 9/23/2020 Added for Binding Threshold
                            if (data[i]['bindings'][j]['BINDING_LIST_ID'] == 0) {
                                var operator = data[i]['bindings'][j]['SOURCE_DEVICE_CONDITION']['operator']
                                var sourceConditionData = data[i]['bindings'][j]['SOURCE_DEVICE_CONDITION']['data']

                                var tempBindingList = {
                                    SOURCE_DEVICE_CONDITION: operator + ':' + sourceConditionData,
                                    SOURCE_DEVICE_TYPE: data[i]['DEVICE_TYPE'],
                                    TARGET_DEVICE_TYPE: data[i]['bindings'][j]['target_device']['DEVICE_TYPE'],
                                }

                                var sourceDeviceType = { SOURCE_DEVICE_TYPE: data[i]['DEVICE_TYPE'] }
                                var targetDeviceType = {
                                    TARGET_DEVICE_TYPE: data[i]['bindings'][j]['target_device']['DEVICE_TYPE'],
                                }
                                this.bindLists[i].bindings[j].binding_list = tempBindingList
                            }

                            //Binding Custom Condition
                            let aCustomCondition = data[i]['bindings'][j]['CUSTOM_CONDITION']
                            let deviceType = data[i]['bindings'][j]['target_device']['DEVICE_TYPE']
                            sCustomCondition = this.convertCustomCondition(aCustomCondition, deviceType)
                            if (sCustomCondition[0] != '') {
                                this.bindLists[i].bindings[j].binding_list.TARGET_DEVICE_CONDITION = sCustomCondition[0]
                                this.bindLists[i].bindings[j].binding_list.TARGET_DEVICE_CONDITION_READABLE =
                                    sCustomCondition[1]
                            }
                        }
                        if (statusCounter > 0 || statusCounter == data[i]['bindings'].length) {
                            data[i]['BINDING_STATUS_GROUP'] = 1
                        } else {
                            data[i]['BINDING_STATUS_GROUP'] = 0
                        }
                    }
                    //------------------------------------------------

                    this.$bus.$emit('setDeviceBindingData', data.data)
                })
                .catch(errors => {
                    console.log(errors)
                })

            this.$forceUpdate()
        },
        //Function name: deleteBinding
        //Function Description: Delete binding
        //Param: data (device)
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
                        .post('deleteSensorBinding', {
                            DEVICE_ID: data.DEVICE_ID,
                        })
                        .then(response => {
                            if (response.data == 'success') {
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
        //Function Name: deleteSpecificBinding
        //Function Description: deletes a specific binding with device
        //Param: data (binding)
        deleteSpecificBinding(data) {
            let text = this.$t('binding.modalText.delDevice')
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
                        .post('deleteBinding', {
                            BINDING_ID: data.BINDING_ID,
                        })
                        .then(response => {
                            if (response.data == 'success') {
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
        //Funciton Name: enableBinding
        //Function Description: enables binding
        //Param: data (binding)
        enableBinding(data) {
            let text = this.$t('binding.modalText.disBinding')
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
                        .post('disableBinding', {
                            BINDING_ID: data.BINDING_ID,
                        })
                        .then(response => {
                            if (response.data == 'success') {
                                let title = this.$t('binding.modalText.disable')
                                let message = this.$t('binding.modalText.bindingEnable')
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_021')
                                this.$swal('Error', message, 'error')
                            }
                            this.$bus.$emit('getDeviceBindingData')
                        })
                }
            })
        },
        //Funciton Name: enableAllBindings
        //Function Description: enables group binding status
        //Param: data (device)
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
                        .post('enableAllBinding', {
                            BINDING: data.DEVICE_ID,
                        })
                        .then(response => {
                            let title = this.$t('binding.modalText.enable')
                            let message = this.$t('binding.modalText.groupEnable')
                            if (response.data == 'success') {
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
                            this.$bus.$emit('getDeviceBindingData')
                        })
                }
            })
        },
        //Funciton Name: disableBinding
        //Function Description: disables binding
        //Param: data (binding)
        disableBinding(data) {
            let text = this.$t('binding.modalText.enBinding')
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
                        .post('enableBinding', {
                            BINDING_ID: data.BINDING_ID,
                        })
                        .then(response => {
                            let title = this.$t('binding.modalText.enable')
                            let message = this.$t('binding.modalText.bindingEnable')
                            if (response.data == 'success') {
                                this.$swal({
                                    title: title,
                                    text: message,
                                    type: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            } else {
                                let message = this.$t('error_message_code.ERR_OPS_022')
                                this.$swal('Error', message, 'error')
                            }
                            this.$bus.$emit('getDeviceBindingData')
                        })
                }
            })
        },
        //Function Name: disableAllBindings
        //Function Description: disables group binding status
        //Param: data (device)
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
                        .post('disableAllBinding', {
                            BINDING: data.DEVICE_ID,
                        })
                        .then(response => {
                            if (response.data == 'success') {
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
                            this.$bus.$emit('getDeviceBindingData')
                        })
                }
            })
        },
        //Function Name: changeSize
        //Function Description: changes table size
        changeSize() {
            if (this.showSelectedBinding) {
                this.isBinded = true
            } else {
                this.isBinded = false
            }
            this.$emit('change-size', this.isBinded)
        },
        //Function name: reloadData
        //Function Description: resets page data
        reloadData() {
            this.$bus.$emit('getDeviceBindingData')
            this.selectedBinding = ''
            this.isBinded = false
            this.$emit('change-size', this.isBinded)
        },
        //Function Name: selectBindedSensor
        //Function Description: selects device binding
        //Param: data (binding)
        selectBindedDevice(data) {
            if (data != '') {
                this.isBinded = true
            } else {
                this.isBinded = false
            }
            this.selectedBinding = data.BINDING_ID
            this.binded.SOURCE_DEVICE_TYPE = data.binding_list.SOURCE_DEVICE_TYPE
            this.binded.TARGET_DEVICE_TYPE = data.binding_list.TARGET_DEVICE_TYPE
            this.$emit('change-size', this.isBinded)
        },
        //Function Name: imgUrlAlt
        //Function Description: replaces binding image with a not found on 404 error
        //Param: event
        imgUrlAlt(event) {
            event.target.src = 'img/image_not_found.png'
        },
        //Function Name: convertCustomCondition
        //Function Description: Convert Custom Conditin to make it more readable to layout
        //                      Generate a more readable condition for Switches
        //Param: customCondition,DeviceType
        convertCustomCondition(customCondition, deviceType) {
            var sCondition = ''
            var sConditionInformative = ''
            var aReturn = ['', '']
            var switchCtr = 0

            if (customCondition != []) {
                // 9/23/2020 Added Custom Condition for ir_remote
                if (deviceType == 'ir_remote') {
                    var command = customCondition['command']
                    var operator = customCondition['operator']
                    aReturn[0] = operator
                    aReturn[1] = 'Set the IR Remote to ' + operator
                } else {
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
                        aReturn[0] = sCondition.substr(0, sCondition.length - 1)
                        aReturn[1] = sConditionInformative
                    } else {
                        aReturn[0] = sCondition
                        aReturn[1] = sConditionInformative
                    }
                }
            }
            return aReturn
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
        showSelectedBinding() {
            //Check if BInding is empty
            if (this.bindLists.length == 0) {
                return false
            }

            if (this.selectedBinding != '') {
                return true
            } else {
                return false
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
        sourceImage() {
            var sourceDeviceType = this.binded.SOURCE_DEVICE_TYPE
            var targetDeviceType = this.binded.TARGET_DEVICE_TYPE
            var image_source = ''
            if (targetDeviceType.includes('wall_switch')) {
                image_source = 'img/' + sourceDeviceType + '/' + sourceDeviceType + '_wall_switch.gif'
            } else if (targetDeviceType.includes('embedded_switch')) {
                image_source = 'img/' + sourceDeviceType + '/' + sourceDeviceType + '_embedded_switch.gif'
            } else {
                image_source = 'img/' + sourceDeviceType + '/' + sourceDeviceType + '_' + targetDeviceType + '.gif'
            }
            return image_source
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
