<!-- UPDATED: TP Chris   SPRINT_04 TASK113 08232021 -->
<template>
    <div class="modal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-black font-weight-bold text-uppercase">
                    <!-- = SPRINT_04 TASK127 -->
                        <!-- {{ $t('restaurant.help.title')}} -->
                        {{ $t('hotel.help.title')}}
                    <!-- = SPRINT_04 TASK127 -->
                    </h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black" style="margin-bottom:1rem;">
                    <div clas="row">
                        <div class="col-12" style="margin-bottom:2rem;">
                            <span style="font-size:22px;">
                           <!-- = SPRINT_04 TASK127 -->
                                <!-- {{ $t('restaurant.help.body')}} -->
                                {{ $t('hotel.help.body')}}
                            <!-- = SPRINT_04 TASK127 -->
                            </span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="green-indicator">
                            </div>
                        </div>
                        <h3 class="col-6">
                            <div class="row m-0 p-0">
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.low.amount')}} -->
                                    {{ $t('hotel.help.low.amount')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.low.status')}} -->
                                    {{ $t('hotel.help.low.status')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="blue-indicator"></div>
                        </div>
                        <h3 class="col-6">
                            <div class="row m-0 p-0">
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.normal.amount')}} -->
                                    {{ $t('hotel.help.normal.amount')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.normal.status')}} -->
                                    {{ $t('hotel.help.normal.status')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="yellow-indicator"></div>
                        </div>
                        <h3 class="col-6">
                            <div class="row m-0 p-0">
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.elevated.amount')}} -->
                                    {{ $t('hotel.help.elevated.amount')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.elevated.status')}} -->
                                    {{ $t('hotel.help.elevated.status')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="red-indicator"></div>
                        </div>
                        <h3 class="col-6">
                            <div class="row m-0 p-0">
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.dangerous.amount')}} -->
                                    {{ $t('hotel.help.dangerous.amount')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                                <div class="col-12 m-0 p-0">
                                <!-- = SPRINT_04 TASK127 -->
                                    <!-- {{ $t('restaurant.help.dangerous.status')}} -->
                                    {{ $t('hotel.help.dangerous.status')}}
                                <!-- = SPRINT_04 TASK127 -->
                                </div>
                            </div>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .green-indicator {
        width: 150px;
        height: 20px;
        margin-left: auto;
        margin-top: 10px;
        background-color: rgb(0, 176, 80);
    }
    .blue-indicator {
        width: 150px;
        height: 20px;
        margin-left: auto;
        margin-top: 10px;
        background-color: rgb(91, 155, 213);
    }
    .yellow-indicator {
        width: 150px;
        height: 20px;
        margin-left: auto;
        margin-top: 10px;
        background-color: rgb(255, 192, 0);
    }
    .red-indicator {
        width: 150px;
        height: 20px;
        margin-left: auto;
        margin-top: 10px;
        background-color: red;
    }
</style>
