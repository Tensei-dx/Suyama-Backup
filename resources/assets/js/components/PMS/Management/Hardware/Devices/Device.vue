<!--
<System Name> iBMS
<Function Name> Device.vue
<Create>
<Update> 2020.05.26 TP Uddin    Modify axios URL according to the URL list
         2020.07.21 TP Ivin     【Task025】Comment Out ElectricMeterDeviceManagement and BacNetDeviceManagement
         2021.09.02 TP Harvey   SPRINT_05 TASK021 Fixing bug in change language
         2021.09.17 TDN Okada   SPRINT_07 TASK148 Migrate Hardware Management
 -->
<template>
    <div class="h-100">
        <!-- + SPRINT_07 TASK148 -->
        <!-- <div class="row mt-3">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3 text-uppercase text-white">
                    {{$t("navbar.devicem")}}
                </span>
            </div>
        </div> -->
        <div class="row h-100">
            <div :class="isUnregister ? 'col-sm-12 grow h-100' : 'col-sm-6 shrink h-100'">
                <!-- = SPRINT_07 TASK148 -->
                <!-- tab-navigation -->
                <!-- <div class="d-flex justify-content-between align-items-center"> -->
                <!-- 【TASK025】Comment out ElectricMeterDeviceManagement and BacNetDeviceManagement -->
                <!-- <div class="nav nav-tabs nav-line w-100" role="tablist">
		                <a class="nav-item nav-link nav-header-blue active" data-toggle="tab" href="#system-device-list" role="tab" aria-controls="nav-device" aria-selected="true" @click="showTab('sysDevice')"> {{$t('device.sysDevice')}}</a>
		                <a class="nav-item nav-link nav-header-blue" data-toggle="tab" href="#modbus-device-list" role="tab" aria-controls="nav-device" aria-selected="false" @click="showTab('modDevice')"> {{$t('device.modDevice')}}</a>
                        <a class="nav-item nav-link nav-header-blue" data-toggle="tab" href="#camera-device-list" role="tab" aria-controls="nav-device" aria-selected="false" @click="showTab('cameraDevice')"> {{$t('device.cameraDevice')}}</a>
                        <a class="nav-item nav-link nav-header-blue" data-toggle="tab" href="#bacnet-device-list" role="tab" aria-controls="nav-device" aria-selected="false" @click="showTab('bacnetDevice')"> {{$t('device.bacnetDevice')}}</a>
		            </div> -->
                <!-- = SPRINT_07 TASK148 -->
                <!-- <div class="nav nav-tabs nav-line w-100" role="tablist">  -->
                <div class="mt-3">
                    <span class="d-block h1 font-weight-bold pl-3 text-uppercase text-white">
                        {{$t("navbar.devicem")}}
                    </span>
                </div>
                <div class="nav nav-tabs nav-line px-0 mt-3" role="tablist">
                    <!-- = SPRINT_07 TASK148 -->
                    <a class="nav-item nav-link active" data-toggle="tab" href="#system-device-list" role="tab"
                       aria-controls="nav-device" aria-selected="true" @click="showTab('sysDevice')">
                        {{$t('device.sysDevice')}}</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#camera-device-list" role="tab"
                       aria-controls="nav-device" aria-selected="false" @click="showTab('cameraDevice')">
                        {{$t('device.cameraDevice')}}</a>
                </div>
                <!-- div-tablist-end -->

                <!-- <ul class="nav nav-tabs nav-line px-0" role="tablist">
                    <li class="nav-item">
                        <a id="device-management-systems-device-tab" class="nav-link active" href="#system-device-list"
                           role="tab" data-toggle="tab" aria-controls="nav-device" aria-selected="true"
                           v-text=" $t('device.sysDevice')" />
                    </li>
                    <li class="nav-item">
                        <a id="device-management-camera-device-tab" class="nav-link active" href="#camera-device-list"
                           role="tab" data-toggle="tab" aria-controls="nav-device" aria-selected="false"
                           v-text=" $t('device.cameraDevice')" />
                    </li>
                </ul> -->
                <!-- </div> -->

                <!-- 【TASK025】Comment out ElectricMeterDeviceManagement and BacNetDeviceManagement -->
                <!-- <div class="tab-content tab-bg-blue" id="nav-tabContent">
                    <div class="tab-pane fade show active" style="height:705px" id="system-device-list" role="tabpanel">
                		<DeviceManagement @change-size="changeSize($event)"></DeviceManagement>
                    </div>
                    <div class="tab-pane fade" style="height:705px" id="modbus-device-list" role="tabpanel">
                		<ElectricMeterDeviceManagement @change-size="changeSize($event)"></ElectricMeterDeviceManagement>
                    </div>
                    <div class="tab-pane fade" style="height:705px" id="camera-device-list" role="tabpanel">
                        <CameraManagement @change-size="changeSize($event)"></CameraManagement>
                    </div>
                    <div class="tab-pane fade" style="height:705px" id="bacnet-device-list" role="tabpanel">
                        <BacNetDeviceManagement @change-size="changeSize($event)"></BacNetDeviceManagement>
                    </div>
                </div> -->
                <!-- tab-content -->
                <!-- div-tab-content -->
                <!-- = SPRINT_07 TASK148 -->
                <!-- <div class="tab-content tab-bg-blue" id="nav-tabContent"> -->

                <div class="tab-content px-0" id="nav-tabContent">
                    <!-- <div class="tab-pane fade show active" style="height:705px" id="system-device-list" role="tabpanel"> -->
                    <div class="tab-pane fade show active" id="system-device-list" role="tabpanel">
                        <!-- = SPRINT_05 TASK021  -->
                        <!-- <DeviceManagement @change-size="changeSize($event)"></DeviceManagement> -->
                        <DeviceManagement :locale="$i18n.locale" @change-size="changeSize($event)"></DeviceManagement>
                        <!-- = SPRINT_05 TASK021  -->
                    </div>
                    <!-- <div class="tab-pane fade" style="height:705px" id="camera-device-list" role="tabpanel"> -->
                    <div class="tab-pane fade" id="camera-device-list" role="tabpanel">
                        <CameraManagement :locale="$i18n.locale" @change-size="changeSize($event)"></CameraManagement>
                    </div>
                </div>
            </div>

            <!-- divtab-content-end -->
            <!-- </div> -->
            <DeviceDetails> </DeviceDetails>
        </div>
        <!-- = SPRINT_07 TASK148 -->
        <!-- - SPRINT_07 TASK148 -->
        <!-- </div> -->
        <!-- </div> -->
        <!-- content-fluid-end -->
        <!-- </div> -->
        <!-- content-warpper-end -->
        <!-- <Footer></Footer> -->
        <!-- - SPRINT_07 TASK148 -->
    </div>
</template>

<script>
//【TASK025】Comment out ElectricMeterDeviceManagement and BacNetDeviceManagement
// import DeviceManagement from './DeviceManagement.vue';
// import ElectricMeterDeviceManagement from './ElectricMeterDeviceManagement.vue';
// import DeviceDetails from './DeviceDetails.vue';
// import BacNetDeviceManagement from './BacNetDeviceManagement.vue';
// import CameraManagement from './CameraManagement.vue';

// import components
import DeviceManagement from './DeviceManagement.vue'
import DeviceDetails from './DeviceDetails.vue'
import CameraManagement from './CameraManagement.vue'

export default {
    //initialize component
    components: {
        DeviceManagement,
        DeviceDetails,
        CameraManagement,
    },
    //【TASK025】Comment out ElectricMeterDeviceManagement and BacNetDeviceManagement
    // components: {
    //     DeviceManagement,
    //     ElectricMeterDeviceManagement,
    //     BacNetDeviceManagement,
    //     DeviceDetails,
    //     CameraManagement
    // },

    data() {
        return {
            isUnregister: false,
            user: '',
        }
    },

    mounted() {
        this.$i18n.locale = this.$parent.locale
    },

    methods: {
        // Function Name: changeSize
        // Function Description: Change table div size
        // Param: data (boolean)
        changeSize(data) {
            this.isUnregister = data
        },

        // Function Name: tabShow
        // Function Description: change tab shown
        // Param: data (sysDevice, modDevice or bacnetDevice)
        showTab(data) {
            var details = ''
            this.$bus.$emit('selectedDeviceData', details)

            //【TASK025】Comment out ElectricMeterDeviceManagement and BacNetDeviceManagement
            // if (data == 'sysDevice') {
            //     this.$bus.$emit('changeDeviceTab', data);
            // } else if (data == 'bacnetDevice') {
            //     this.$bus.$emit('changeDeviceTab', data);
            // } else if (data == 'modDevice') {
            //     this.$bus.$emit('changeDeviceTab', data);
            // } else if (data == 'cameraDevice') {
            //     this.$bus.$emit('changeDeviceTab', data);
            // }

            if (data == 'sysDevice') {
                this.$bus.$emit('changeDeviceTab', data)
            } else if (data == 'cameraDevice') {
                this.$bus.$emit('changeDeviceTab', data)
            }
        },
    },
}
</script>
<style scoped>
.font-size-hotel {
    font-size: 20px;
}
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
}
</style>
