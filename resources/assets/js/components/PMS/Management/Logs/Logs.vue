<template>
    <div>
        <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
            <div class="col">
                <span class="d-block h1 font-weight-bold pl-3">
                    {{$t("management.logs")}}
                </span>
            </div>
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div id="logs-tabs" role="tablist" class="nav nav-tabs nav-line w-100">
                    <a id="error-logs-tab" data-toggle="tab" href="#error-logs-list" role="tab"
                       aria-controls="error-logs-list" aria-selected="true"
                       class="nav-item nav-link active">{{$t('management.systemLogs.error')}}</a>
                    <a id="warning-logs-tab" data-toggle="tab" href="#warning-logs-list" role="tab"
                       aria-controls="warning-logs-list" aria-selected="false"
                       class="nav-item nav-link">{{$t('management.systemLogs.warning')}}
                    </a>
                    <a id="info-logs-tab" data-toggle="tab" href="#info-logs-list" role="tab"
                       aria-controls="info-logs-list" aria-selected="false"
                       class="nav-item nav-link">{{$t('management.systemLogs.info')}}
                    </a>
                </div>
            </div>
            <div id="logs-content" class="tab-content tab-bg-gray" style="height: 100%">
                <div id="error-logs-list" role="tabpanel" aria-labelledby="error-logs-list"
                     class="tab-pane fade show active">
                    <Error />
                </div>
                <div id="warning-logs-list" role="tabpanel" aria-labelledby="warning-logs-list" class="tab-pane fade">
                    <Warning />
                </div>
                <div id="info-logs-list" role="tabpanel" aria-labelledby="info-logs-list" class="tab-pane fade">
                    <Information />
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import Error from './Error/Error.vue'
import Warning from './Warning.vue'
import Information from './Information.vue'

export default {
    components: {
        Error,
        Information,
        Warning,
    },

    data() {
        return {
            logs: [],
        }
    },

    computed: {
        errorLogs: function () {
            return this.logs.filter(log => log.EVENT_STATUS === 0 || log.EVENT_STATUS === 1)
        },
        warningLogs: function () {
            return this.logs.filter(log => log.EVENT_STATUS === 2 || log.EVENT_STATUS === 3)
        },
        infoLogs: function () {
            return this.logs.filter(log => log.EVENT_STATUS === 4 || log.EVENT_STATUS === 5)
        },
    },
}
</script>

<style scoped>
.nav-tabs .nav-header-dark-gray.active,
.nav-tabs .nav-header-dark-gray.show {
    position: relative;
    background-color: #595959;
    color: #fff;
    border-color: transparent;
    border-radius: inherit;
}
.nav-tabs .nav-header-dark-gray {
    position: relative;
    background-color: #bfbfbf;
    color: white;
    border-color: transparent;
    border-radius: inherit;
}
.nav-tabs .nav-header-dark-gray:hover,
.nav-tabs .nav-header-dark-gray:focus {
    border-color: transparent;
}
.nav-tabs .nav-header-dark-gray:after {
    content: ' ';
    position: absolute;
    display: block;
    width: 7% !important;
    height: 106%;
    top: -1px;
    left: 100%;
    z-index: -1;
    background: #bfbfbf;
    transform-origin: bottom left;
    -ms-transform: skew(-30deg, 0deg);
    -webkit-transform: skew(-30deg, 0deg);
    transform: skew(18deg, 180deg);
    z-index: 10;
    color: rgb(0, 0, 0);
}
.nav-tabs .nav-header-dark-gray.active:after {
    content: ' ';
    position: absolute;
    display: block;
    width: 7% !important;
    height: 106%;
    top: -1px;
    left: 100%;
    z-index: -1;
    background: #595959;
    transform-origin: bottom left;
    -ms-transform: skew(-30deg, 0deg);
    -webkit-transform: skew(-30deg, 0deg);
    transform: skew(18deg, 180deg);
    z-index: 10;
}
.tab-bg-gray {
    background-color: #595959 !important;
    background-image: linear-gradient(180deg, #595959, #595959);
    color: #fff;
    box-shadow: 4px 2px 12px 0px #999;
}
.button-create-base-color {
    background-color: white;
}
.loader {
    height: 50px;
    width: 50px;
    border: 5px solid #45474b;
    border-top-color: white;
    position: relative;
    margin-top: inherit;
    margin-bottom: auto;
    margin-left: auto;
    margin-right: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: 50%;
    animation: spin 1.5s infinite linear;
}
a {
    text-decoration: none;
    color: white;
    font-weight: 600;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    font-size: 18px;
}
.tab-content {
    font-size: 16px;
}
@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
.h-date {
    height: 0;
}
</style>
