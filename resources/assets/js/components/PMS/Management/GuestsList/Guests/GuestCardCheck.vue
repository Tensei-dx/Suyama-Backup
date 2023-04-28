<template>
    <div class="guestcard">
        <div class="row">
            <div class="d-block pointer ml-1 mt-0 pt-0" @click="backToRoomMenu()">
                <i class="fa fa-arrow-left fa-3x pt-0 mt-0"></i>
                <span class="h1 font-weight-bold pl-3">
                    {{ $t('management.roomOperation.guestcardcheck') }}
                </span>
            </div>
        </div>
        <div class="row mt-4">
            <div class="align-self-center guestcard-left">
                <div class="guestcard-bg">
                    <span class="guestcard-fw-label"> {{ $t('guestcard.name') }}: </span><br>
                    <span class="guestcard-fw">山田次郎</span> <br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.sex') }}: </span><br>
                    <span class="guestcard-fw">MAN</span> <br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.age') }}: </span><br>
                    <span class="guestcard-fw">25</span><br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.occupation') }}: </span><br>
                    <span class="guestcard-fw">Engineer</span> <br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.tel') }}: </span><br>
                    <span class="guestcard-fw">09012341234</span> <br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.email') }}: </span><br>
                    <span class="guestcard-fw">r-kashii@tenseidatanet.co.jp </span><br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.address') }}: </span><br>
                    <span class="guestcard-fw">福井県福井市加茂河原 </span><br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.id') }}: </span>
                    <br>
                    <!-- Button trigger modal -->
                    <img type="button" class="btn-guestimage" src="/img/background/shiojiri-compressed.jpg"
                         alt="identification_Image" data-toggle="modal" data-target="#imageModal">

                    <!-- Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="imageModalLabel">
                                        <b>{{ $t('guestcard.id') }}</b>
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img class="guestimage" src="/img/background/shiojiri-compressed.jpg"
                                         alt="identification_Image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.nationality') }}: </span><br>
                    <span class="guestcard-fw">PHILLIPINES </span><br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.passportNo') }}: </span><br>
                    <span class="guestcard-fw">1234567890</span><br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.previousPlace') }}: </span><br>
                    <span class="guestcard-fw">福井県越前市大虫本町</span> <br><br>
                    <span class="guestcard-fw-label"> {{ $t('guestcard.nextDestination') }}: </span><br>
                    <span class="guestcard-fw">東京都豊洲</span> <br><br>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {}
    },
    methods: {
        /**
         * @name backToRoomMenu
         * @desc Back to previous Display of the screen
         *
         */
        backToRoomMenu() {
            this.$emit('backToRoomMenu')
            const newURL = window.location.protocol + '//' + window.location.host + window.location.pathname
            window.history.pushState({ path: newURL }, '', newURL)
        },
    },
}
</script>
<style scoped>
.my-2 {
    color: black;
    font-size: 10px;
}
.modal-float-left {
    align-items: flex-end;
}
</style>
