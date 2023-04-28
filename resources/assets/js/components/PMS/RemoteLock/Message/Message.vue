<template>
    <div class="container py-2">
        <div class="row">
            <div v-if="isSuccess" class="col-12 text-left">
                <div id="message-success-alert" class="alert alert-success" role="alert">
                    <h4 class="alert-heading font-weight-bold">{{ $t('remotelock.message.success.title') }}</h4>
                    <p>{{ $t('remotelock.message.success.body') }}</p>
                    <hr>
                    <h5 class="font-weight-bold mb-0">{{ email }}</h5>
                </div>
            </div>

            <div v-if="errorMessage.length > 0" class="col-12 text-center">
                <div id="message-error-alert" v-for="(error, index) in errorMessage" :key="index" class="alert alert-danger col-auto" role="alert">
                    <p class="font-weight-bold mb-0">{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        email: String,
        isSuccess: Boolean,
        errorMessage: Array,
    },

    watch: {
        isSuccess: function () {
            window.scrollTo(0, document.body.scrollHeight)
        },

        errorMessage: function () {
            window.scrollTo(0, document.body.scrollHeight)
        }
    },
}
</script>

<style scoped>

div.container {
    background-color: transparent;
}
</style>
