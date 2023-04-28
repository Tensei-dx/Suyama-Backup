<template>
    <div class="container py-2">
        <div class="row">
            <div v-if="isSuccess" class="col-12 text-center">
                <div id="message-success-alert" class="alert alert-success" role="alert">
                    <h4 class="alert-heading font-weight-bold">Success</h4>
                    <p>This page will redirect to the iBMS Management Dashboard screen in 3 seconds.</p>
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
