<template>
    <div v-if="cat == 'camera'">
        <div class="modal" :class="show">
            <div class="modal-background" @click="close"></div>
            <div class="modal-dialog modal-dialog-centered" role=document>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark">{{$t('device.addDevice')}}</h5>
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-black">
                        <div class="form-group">
                            <label class="label">{{$t('floor.floor')}}</label>
                            <div class="">
                                <select
                                    class="custom-select"
                                    v-model="modalData.FLOOR_ID"
                                    @change="getRooms">
                                    <option v-for="floor,key in floors"
                                        :key="floor.FLOOR_ID"
                                        :value="floor.FLOOR_ID">
                                        {{floor.FLOOR_NAME}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">{{$t('floor.room')}}</label>
                            <div class="">
                                <select
                                    class="custom-select"
                                    v-model="modalData.ROOM_ID"
                                    @change="getRooms"
                                    :disabled="roomDisabled">
                                    <option v-for="room,key in rooms"
                                        :key="room.ROOM_ID"
                                        :value="room.ROOM_ID">
                                        {{room.ROOM_NAME}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">{{$t('device.devType')}}</label>
                            <input class="form-control"
                                type="text"
                                v-model="modalData.DEVICE_TYPE"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label class="label">{{$t('device.devName')}}</label>
                            <input class="form-control alphanumericOnly"
                                v-model="modalData.DEVICE_NAME"
                                type="text"
                                maxlength="15"
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-_ ]/g, '')"
                                :class="{'border border-danger':errors.DEVICE_NAME}"
                                :disabled="nameDisabled">
                            <!-- display this when error occured -->
                            <small v-if="errors.DEVICE_NAME" class="text-danger">
                                {{errors.DEVICE_NAME[0] }}
                            </small>
                        </div>
                        <div class="form-group">
                            <label class="label">{{$t('device.devCat')}}</label>
                            <div class="">
                                <select class="custom-select" v-model="modalData.DEVICE_CATEGORY">
                                    <option v-for="type, key in deviceType"
                                        :key="type.ID"
                                        :value="type.ID"
                                        :selected="type.ID == modalData.DEVICE_CATEGORY">
                                        {{type.NAME}}
                                    </option>
                                </select>
                                <small v-if="errors.DEVICE_CATEGORY" class="text-danger">
                                    {{errors.DEVICE_CATEGORY[0]}}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary"
                            @click="addData()"
                            :disabled="devSaveDisabled">
                            <span class="pull-left" v-if="loading">
                                <i class="fa fa-spinner fa=pulse fa-1x fa-fw"></i>
                            </span>
                            <span>{{btn_text}}</span>
                        </button>
                        <button type="button"
                            class="btn btn-secondary"
                            @click="close">
                            {{$t('close')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['cat', 'show', 'currentPage', 'modalData'],
        created() {
            this.getFloors();
            $('body').addClass('modal-open');
        },
        data() {
            return {
                floors: {},
                rooms: {},
                loading: false,
                deviceType: [
                    {
                        'ID': 0,
                        'NAME': 'Device'
                    },
                    {
                        'ID': 1,
                        'NAME': 'Sensor'
                    }
                ],
                errors: {},
                btn_text: this.$t('user.save'),
                error_text: 'This field is required'
            }
        },
        mounted() {

        },
        methods: {
            getFloors() {
                axios.post('getFloorAll')
                .then(response => {
                    this.floors = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
            },
            getRooms() {
                axios.post('getFloorRooms/' + this.modalData.FLOOR_ID)
                .then(response => {
                    this.rooms = response.data;
                })
            },
            addData() {
                let message = this.$t('device.deviceModals');
                let errorMessage = this.$t('error_message_code');
                this.loading = true;
                this.btn_text = this.$t('gateway.save');
                axios({
                    url: 'registerCamera',
                    method: 'POST',
                    data: {
                        DEVICE_ID: this.modalData.DEVICE_ID,
                        FLOOR_ID: this.modalData.FLOOR_ID,
                        ROOM_ID: this.modalData.ROOM_ID,
                        DEVICE_NAME: this.modalData.DEVICE_NAME,
                        DEVICE_CATEGORY: this.modalData.DEVICE_CATEGORY
                    }
                })
                .then(response => {
                    if (response.data == 'success') {
                        this.$swal({
                            position: 'center',
                            title: message.devRegistered,
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500
                            });
                        this.close();
                    } else if (response.data == 'name exists') {
                        this.$swal(
                            this.$t('modalText.error'),
                            'Device name already exists',
                            'error'
                        );
                        this.loading = false;
                        this.btn_text = this.$t('device.save');
                    } else if (response.data == 'name invalid') {
                        this.$swal(
                            this.$t('modalText.error'),
                            'Device Name invalid',
                            'error'
                        );
                        this.loading = false;
                        this.btn_text = this.$t('device.save');
                    } else if (response.data == 'failed') {
                        this.$swal(
                            this.$t('modalText.error'),
                            'Device can not be registered',
                            'error'
                        );
                        this.loading = false;
                        this.btn_text = this.$t('device.save');
                    }
                })
                .catch(error => {
                    this.errs(error);
                });
            },
            close() {
                this.errors = {};
                this.loading = false;
                this.btn_text = this.$t('gateway.save');
                this.$emit('loaddata', this.currentPage);
                this.$emit('close');
            },
            errs(error) {
                this.errors = error.data.errors;
                this.loading = false;
                this.btn_text = this.$t('gateway.save');
            }
        },
        computed: {
            roomDisabled() {
                if (this.modalData.FLOOR_ID == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            nameDisabled() {
                if (this.modalData.ROOM_ID == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            devSaveDisabled() {
                if (this.modalData.FLOOR_ID == 0 || this.modalData.ROOM_ID == 0 || this.modalData.DEVICE_NAME == null || this.modalData.DEVICE_NAME.length <= 3) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        beforeDestroy() {
            $('body').removeClass('modal-open');
        }
    };
</script>
