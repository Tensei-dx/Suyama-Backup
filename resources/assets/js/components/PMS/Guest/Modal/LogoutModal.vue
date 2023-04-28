<template>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{ $t('mobile.checkout.title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <p>{{ $t('mobile.checkout.body') }}</p>
                    <p>{{$t('mobile.checkout.return')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" data-dismiss="modal">
                        {{ $t('mobile.checkout.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger" :disabled="isProcessing" @click="logout">
                        {{ $t('mobile.checkout.checkout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            //  errors: '',
            processing: false,
        }
    },
    methods: {
        logout() {
            // deactivate checkout button
            this.processing = true

            axios
                .post('logout')
                .then(response => {
                    // no process
                })
                .catch(error => {
                    console.log(error.response.data.message)
                    this.processing = false
                })
                .then(function () {
                    window.location = 'thankyou'
                })
        },
    },

    computed: {
        isProcessing: function () {
            return this.processing
        },
    },
}
</script>

<style scoped>
.cancel-btn {
    background-color: #aaa;
    border-color: #aaa;
    color: #fff;
}
</style>
