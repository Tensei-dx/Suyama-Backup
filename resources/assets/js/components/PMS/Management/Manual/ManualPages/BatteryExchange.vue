<template>
    <div class="row text-white">
        <div class="col-sm-12 my-2 h3 text-center">{{$t("management.manuals.batteryExchange")}}</div>
        <div class="col-sm-12 my-2 h4 video-margin">{{ $t("management.manuals.smartlock") }}</div>
        <div class="col-sm-12">
            <div class="text-center mt-2 mb-3">
                <a type="button" text-center class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/%E3%82%B9%E3%83%9E%E3%83%BC%E3%83%88%E3%83%AD%E3%83%83%E3%82%AF%E9%9B%BB%E6%B1%A0%E4%BA%A4%E6%8F%9B%E6%89%8B%E9%A0%86.pdf"
                   target="_blank">{{$t("management.manuals.manualSmartlock")}}</a>
            </div>
        </div>
        <div class="col-sm-12 my-2 h4 text-left video-margin">{{ $t("management.manuals.netvox") }}</div>
        <div class="col-sm-12">
            <div class="text-center mt-2">
                <a type="button" text-center class="btn-manual-style btn-manual-border"
                   href="https://storage.googleapis.com/manuals-s/NETVOX%E9%9B%BB%E6%B1%A0%E4%BA%A4%E6%8F%9B%E6%89%8B%E9%A0%86.pdf"
                   target="_blank">{{$t("management.manuals.manualSensor")}}</a>
            </div>
        </div>
    </div>
</template>

<script>
</script>

<style>
.video-margin {
    margin-top: 30px;
    margin-bottom: 30px;
}
</style>
