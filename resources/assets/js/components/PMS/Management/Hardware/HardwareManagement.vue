<!-- CREATED: TP Chris   SPRINT_07 TASK147 09132021 -->
<!-- UPDATE   TDN Okadaa SPRINT_07 TASK148 09172021 -->
<!-- UPDATE   TDN Chris  SPRINT_08 TASK177 09232021 -->
<!-- UPDATE   TDN Chris  SPRINT_09 TASK188 09302021 -->
<!-- UPDATED: TP Rhea SPRINT_12 TASK236 10212021 -->
<template>
    <div>
        <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3">
                    {{$t("management.hardware")}}
                </span>
            </div>
        </div>
        <div class="row align-right">
            <div class="col mt-5 text-left" @click="$emit('changePage', 'gateway')">
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                        {{$t("navbar.gatewaym")}}
                    </button>
                </a>
            </div>
            <!-- = SPRINT_07 [TASK148] -->
            <!-- <div class="col mt-5 text-left"> -->
            <div class="col mt-5 text-left" @click="$emit('changePage', 'device')">
                <!-- = SPRINT_07 [TASK148] -->
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                        {{$t("navbar.devicem")}}
                    </button>
                </a>
            </div>
        </div>
        <!-- = SPRINT_08 [TASK177] -->
        <!-- <div class="col mt-5 text-left"> -->

        <div class="row align-right">
            <div class="col mt-5 text-left" @click="$emit('changePage', 'remotelock_management')">
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                        {{ $t('management.remotelock.title') }}
                    </button>
                </a>
            </div>
            <div class="col mt-5 text-left" @click="$emit('changePage', 'nature_IR')">
                <!-- = SPRINT_08 [TASK177] -->
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                        {{$t("navbar.ir-m")}}
                    </button>
                </a>
            </div>
        </div>
        <!-- <div class = "align-center"> -->
        <!-- Tablet Registration -->
        <!-- <div class="col mt-5 text-left" @click="$emit('changePage', 'tablet_registration')">
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                    {{$t("navbar.tabletReg")}}
                    </button>
                </a>
            </div> -->
        <!-- </div> -->
        <div class="align-center">
            <div class="col mt-5 text-left" @click="$emit('changePage', 'param')">
                <a style="color:white; text-decoration:none;">
                    <button type="button" class="hotel-theme-color-1 btn-gateway ml-2 font-weight-bold pl-2">
                        {{ $t('management.param.title') }}
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
</script>

<style>
.btn-gateway {
    margin-left: 10px;
    margin-top: 10px;
    color: white;
    border: 2px solid #263033;
    border-radius: 12px;
    box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.7), 0 5px 8px 0 rgba(0, 0, 0, 0.5);
    font-size: 20px;
    width: 320px;
    height: 60px;
}
.align-right {
    margin-top: 2%;
    margin-left: 5%;
    margin-bottom: 25px;
}
.align-center {
    margin-top: 7%;
    margin-left: 30%;
}
</style>
