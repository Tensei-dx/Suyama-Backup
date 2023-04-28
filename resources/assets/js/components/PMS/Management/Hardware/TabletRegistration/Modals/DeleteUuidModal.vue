<!--  
    <System Name> iBMS for Hotel
    <Program Name> DeleteUuidModal.vue

    <Created>   TP Chris   SPRINT_10 TASK188 10/12/2021 
-->
<template>
    <div class="modal d-block" tabindex="-1">
        <div class="modal-background" @click="closeModal()"></div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black">{{$t('tablet.deleteMessage.deleteUuid')}}</h5>
                    <button type="button" class="close" @click="closeModal()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-black">
                    <p>{{ $t('tablet.deleteMessage.deleteModal') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">
                        {{$t('tablet.deleteMessage.cancel')}}
                    </button>
                    <button type="button" class="btn btn-danger" @click="deleteUuidAccount()" :disabled="isDeleteButtonDisabled">
                        {{$t('tablet.deleteMessage.delete')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            uuid_data:'',
        },

        data(){
            return {
                id: '',
                message: '',
                isDeleteButtonDisabled: false,
            }
        },

        methods: {
            /**
             * @name deleteUuidAccount
             * @desc Delete an uuid account
             *
             * @params
             * @returns null
             */
            deleteUuidAccount() {
                const lang = this.$t('tablet')
                this.isDeleteButtonDisabled = true;
                axios.post('/clientDevice/deleteDevice', {
                    id: this.uuid_data.id
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 300) {
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: lang.message.success,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        return response.data
                    } else {
                        throw new Error(response.data)
                    }
                })
                .catch(error => {
                    this.errors.push(error)
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: lang.message.fail,
                        showConfirmButton: false,
                        timer: 2000
                    })
                })
                .then(() => {
                    this.$emit('refreshData')
                    this.isDeleteButtonDisabled = false
                    this.closeModal()
                })
            },

            /**
             * @name closeModal
             * @desc Close this Modal
             *
             * @params
             * @returns null
             */
            closeModal(){
                this.$emit("closeModal");
            },
        }
    }
</script>
