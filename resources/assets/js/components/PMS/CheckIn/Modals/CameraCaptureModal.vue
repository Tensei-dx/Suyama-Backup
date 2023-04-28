<template>
    <div class="modal" id="cameraCaptureModal" tabindex="-1" role="dialog" aria-labelledby="CameraCapture Modal"
         aria-hidden="true">
        <!-- <div class="modal d-block" tabindex="-1"> -->
        <div class="modal show"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{ $t('guestcard.passportURL') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div v-if="url" class="col-12">
                    <img :src="url" width="400" height="300" />
                </div>
                <label class="btn btn-info button-bottom">
                    &#x1F4F8; {{ $t('guestcard.photo') }}
                    <input type="file" id='input' ref="preview" class="d-none" accept="image/*" capture="camera"
                           @change="uploadFile" />
                </label>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ $t('guestcard.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        passport_url: {
            type: String,
        },
    },

    data() {
        return {
            message: '',
            isDeleteButtonDisabled: false,
            preview: null,
            url: this.passport_url,
            file: null,
            fileInfo: '',
            resizedImg: null,
        }
    },
    created() {},

    methods: {
        uploadFile(event) {
            this.fileInfo = event.target.files[0]
            const file = this.$refs.preview.files[0]
            this.url = URL.createObjectURL(file)
            this.$emit('updateURLEvent', this.url, file, this.fileInfo)
        },
    },

    mounted() {},
    watch: {
        passport_url(newVal) {
            this.url = newVal
        },
    },
}
</script>