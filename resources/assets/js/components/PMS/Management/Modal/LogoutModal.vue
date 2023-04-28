<template>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{ $t('management.logoutbtn.title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <p>{{ $t('management.logoutbtn.body') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" data-dismiss="modal">
                        {{ $t('management.logoutbtn.cancel') }}
                    </button>
                    <form method="POST" @submit="logoutFunction">
                        <button type="submit" class="btn btn-danger">
                            {{ $t('management.logoutbtn.logout') }}
                        </button>
                    </form>
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
         *
         */
        logoutFunction(e) {
            let self = this
            var error
            e.preventDefault()
            axios
                .post('logout')
                .then(response => {
                    window.location = 'login'
                })
                .catch(error => {
                    console.log(error)
                })
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
