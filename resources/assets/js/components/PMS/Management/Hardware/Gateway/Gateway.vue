<!--
    <System Name> iBMS
    <Program Name> Gateway.vue

    <Created>            TP Harvey
    <Updated> 2019.07.11 TP Mark   Applying Horizontal Expansion
              2020.05.26 TP Uddin  Modify axios URL according to the URL list
              2020.10.14 TP Uddin  Add CameraGatewayManagement
              2021.07.21 TP Ivin   Comment out ModBus Gateway Management tab
              2021.09.16 TP Chris  Modify layout for Hotel
-->
<template>
    <div class="h-100">
        <!-- + SPRINT_07 TASK147 -->
        <!-- <div class="row mt-3">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3">
                    {{$t("navbar.gatewaym")}}
                </span>
            </div>
        </div> -->
        <div class="row h-100">
            <div class="tableGateway" :class="isUnregister ? 'col-sm-12 grow h-100' : 'col-sm-6 shrink h-100'">
                <div class="mt-3">
                    <span class="d-block h1 font-weight-bold pl-3">
                        {{$t("navbar.gatewaym")}}
                    </span>
                </div>
                <!-- tab-navigation -->
                <!-- <div class="d-flex justify-content-between align-items-center"> -->
                <!-- div-tab-list -->
                <!-- 【TASK025】Commentout ModBus GatewayManagement -->
                <!-- <div class="nav nav-tabs nav-line" role="tablist">
                        <a class="nav-item nav-link nav-header-blue active" data-toggle="tab" href="#system-gateway-list" role="tab" aria-controls="nav-gateway" aria-selected="true" @click="tabShow('sysGateway')">{{$t('gateway.sysGateway')}}</a>
                        <a class="nav-item nav-link nav-header-blue" data-toggle="tab" href="#modbus-gateway-list" role="tab" aria-controls="nav-gateway" aria-selected="false" @click="tabShow('modGateway')">{{$t('gateway.modbusGateway')}}</a>
                        <a class="nav-item nav-link nav-header-blue" data-toggle="tab" href="#camera-gateway-list" role="tab" aria-controls="nav-gateway" aria-selected="false" @click="tabShow('camGateway')">{{$t('gateway.cameraGateway')}}</a>
                    </div> -->
                <!-- = SPRINT_07 TASK147 -->
                <!-- <div class="nav nav-tabs nav-line" role="tablist"> -->
                <div class="nav nav-tabs nav-line px-0 mt-3" role="tablist">
                    <!-- = SPRINT_07 TASK147 -->
                    <a class="nav-item nav-link active" data-toggle="tab" href="#system-gateway-list" role="tab"
                       aria-controls="nav-gateway" aria-selected="true"
                       @click="tabShow('sysGateway')">{{$t('gateway.sysGateway')}}</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#camera-gateway-list" role="tab"
                       aria-controls="nav-gateway" aria-selected="false"
                       @click="tabShow('camGateway')">{{$t('gateway.cameraGateway')}}</a>
                </div>
                <!-- div-tablist-end -->
                <!-- </div> -->
                <!-- 【TASK025】Commentout ModBus GatewayManagement -->
                <!-- <div class="tab-content tab-bg-blue" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="system-gateway-list" role="tabpanel">
                        <GatewayManagement :locale="$i18n.locale" @change-size="changeSize($event)"></GatewayManagement>
                    </div>
                    <div class="tab-pane fade" id="modbus-gateway-list" role="tabpanel">
                        <ModBusGatewayManagement :locale="$i18n.locale" @change-size="changeSize($event)"></ModBusGatewayManagement>
                    </div>
                    <div class="tab-pane fade" id="camera-gateway-list" role="tabpanel">
                        <CameraGatewayManagement :locale="$i18n.locale" @change-size="changeSize($event)"></CameraGatewayManagement>
                    </div>
                </div> -->
                <!-- tab-content -->
                <!-- div-tab-content -->
                <!-- = SPRINT_07 TASK147 -->
                <!-- <div class="tab-content tab-bg-blue" id="nav-tabContent"> -->
                <div class="tab-content px-0" id="nav-tabContent">
                    <!-- = SPRINT_07 TASK147 -->
                    <div class="tab-pane fade show active" id="system-gateway-list" role="tabpanel">
                        <!-- gateway component -->
                        <GatewayManagement :locale="$i18n.locale" @change-size="changeSize($event)"></GatewayManagement>
                    </div>
                    <div class="tab-pane fade" id="camera-gateway-list" role="tabpanel">
                        <CameraGatewayManagement :locale="$i18n.locale" @change-size="changeSize($event)">
                        </CameraGatewayManagement>
                    </div>
                </div>
                <!-- divtab-content-end -->
            </div>
            <GatewayDetails></GatewayDetails>
        </div>
        <!-- - SPRINT_07 TASK147 -->
        <!-- </div> -->
        <!-- content-fluid-end -->
        <!-- </div> -->
        <!-- content-warpper-end -->
        <!-- <Footer></Footer> -->
        <!-- - SPRINT_07 TASK147 -->
    </div>
</template>

<script>
//import components
//【TASK025】Commentout ModBus GatewayManagement
// import GatewayManagement from './GatewayManagement.vue';
// import ModBusGatewayManagement from './ModBusGatewayManagement.vue';
// import CameraGatewayManagement from './CameraGatewayManagement.vue';
// import GatewayDetails from './GatewayDetails.vue';

import GatewayManagement from './GatewayManagement.vue'
import CameraGatewayManagement from './CameraGatewayManagement.vue'
import GatewayDetails from './GatewayDetails.vue'

export default {
    //initialize component
    //【TASK025】Remove component ModBusGatewayManagement
    // components: { GatewayManagement, ModBusGatewayManagement, CameraGatewayManagement, GatewayDetails },

    components: { GatewayManagement, CameraGatewayManagement, GatewayDetails },
    data() {
        return {
            isUnregister: false,
            user: '',
        }
    },
    methods: {
        //Function Name: changeSize
        //Function Description: change table size if unregistered category
        //Param: data (boolean)
        changeSize(data) {
            this.isUnregister = data
        },
        //Function Name: tabShow
        //Function Description: Change tab
        //Param: data
        tabShow(data) {
            var details = ''
            this.$bus.$emit('selectedData', details)
            if (data == 'sysGateway') {
                this.$bus.$emit('changeTab', data)
            } else {
                this.isUnregister = false
            }
        },
    },
    mounted() {
        //set child locale to parent locale
        this.$i18n.locale = this.$parent.locale
    },
}
</script>
<style scoped>
.font-size-hotel {
    font-size: 19px;
}
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.tableGateway {
    padding-right: 10px;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
}
</style>
