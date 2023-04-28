<template>
    <div class="row pt-2">
        <div class="col">
            <span class="d-block h1 font-weight-bold pl-3">
                {{ $t('management.key') }}
            </span>
        </div>
        <div class="col-auto pointer text-center" style="margin-top:2px;">
            <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
            <small class="d-block mt-1">{{ $t('mobile.menu.notif') }}</small>
        </div>
        <div class="w-100"></div>
        <div class="col text-left">
            <span class="d-block h5 pl-5">
                {{ $t('management.keyManagementPage.keytext1')}}
            </span>
        </div>
        <div class="w-100"></div>
        <div class="col text-left">
            <span class="d-block h5 pl-5">
                {{ $t('management.keyManagementPage.keytext2')}}
            </span>
        </div>
        <div class="w-100 mt-2"></div>
        <div class="col text-left">
            <span class="d-block h5 pl-5">
                {{ $t('management.keyManagementPage.keytext3')}}
            </span>
        </div>
        <div class="w-100"></div>
        <div class="col text-left">
            <span class="d-block h5 pl-5">
                {{ $t('management.keyManagementPage.keytext4')}}
            </span>
        </div>
        <div class="w-100 mt-2"></div>
        <div class="col-xl-12 col-auto  d-flex justify-content-center">
            <img src="/img/ManagementDashboard/icon/Diagram/BookingDiagram.png" style="height:310px;">
        </div>
        <div class="w-100"></div>
        <div class="col d-flex justify-content-center mt-3">
            <span class="h5 pl-5 mr-2" style="margin-top:0.7rem;">
                {{ $t('management.keyManagementPage.keytext5')}}
            </span>
            <i class="fa fa-long-arrow-right fa-3x" aria-hidden="true"></i>
            <a style="color:white; text-decoration:none;" :href="this.remote_lock_url">
                <button type="button" class="btn hotel-base-color ml-2 font-weight-bold pl-2" style="margin-top:0.3rem;">{{ $t('management.keyManagementPage.genkey')}}</button>
            </a>
        </div>
        <div class="w-100 pb-1"></div>
    </div>
</template>
<script>
export default {
    components: {
    },
    data() {
        return {
            remote_lock_url:'',
        }
    },
    created() {
        this.redirectUrl();
    },
    methods: {
        redirectUrl() {
            axios.get('getApiInfo', {
                params: {
                    API_NAME: 'remote_lock',
                }
            })
            .then((response) => {
                var apiInfo = response.data;
                for(var i in apiInfo) {
                    this.remote_lock_url = apiInfo[i].API_URL + '?client_id=' + apiInfo[i].CLIENT_ID + '&redirect_uri=' + apiInfo[i].REDIRECT_URL + '&response_type=code';
                }
            })
            .catch((error) => {
                console.log(error);
            });
        },
    }
}
</script>
