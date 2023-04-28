<!--
    <System Name> iBMS for Hotel
    <Program Name> TabletRegisterModal.vue

    <Created>   TP Chris  SPRINT_10 TASK188 10/05/2021 
-->
<template>

<transition name="fade">
    <div v-if="showModal" class="modal d-block">
        <div class="modal-background" @click="closeModal()"/>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">{{ $t('tablet.tabletBtn') }}</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tablet-uuid" class="label text-dark">{{ $t('tablet.uuid') }}</label>
                        <input id="tablet-uuid" selected disabled v-model="this.uuid" type="text" class="form-control" maxlength="25">
                    </div>
                    <div class="form-group">
                        <label for="tablet-room" class="label text-dark">{{ $t('tablet.room') }}</label>
                        <select v-model="selectedRoom" class="custom-select">
                            <option v-for="rooms in filteredRooms" :value="rooms.ROOM_ID" :key="rooms.ROOM_ID">{{ rooms.ROOM_NAME }}</option>
                        </select>
                        <span class="text-danger">{{ roomsErrorEmpty }}</span>
                    </div>
                    <div class="form-group">
                        <label for="tablet-user" class="label text-dark">{{ $t('tablet.userType') }}</label>
                        <select v-model="selectedUser" class="custom-select">
                            <option v-for="(users, index) in filteredUsers" :key="index" :value="users.value">{{ users.name }}</option>
                        </select>
                    </div>
                    <span class="text-danger">{{ usersErrorEmpty }}</span>
                </div>
                <div class="modal-footer">
                    <button @click="saveTabletData()" type="button" class="btn btn btn-primary" :disabled="isLoading">
                        <span class="text-center">{{ $t('tablet.regist') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</transition>

</template>

<script>

export default {

    data() {
        return {
            uuid: '',
            rooms: [],
            selectedRoom: null,
            selectedUser: null,
            roomsErrorEmpty: null,
            usersErrorEmpty: null,
            showModal: false,
            isLoading: false,
            errors: []
        }
    },
     created() {
            this.getUuid();
            this.getFloors();
            this.getRooms();
    },

    mounted() {
        this.$bus.on('openModal', payload => {
            if (payload === 'TabletRegisterModal') {
                this.showModal = true
            }
        })
    },

    beforeDestroy() {
        this.$bus.off('openModal')
    },

    computed: {
        /**
         * @name filteredRooms
         * @description List of rooms filtered by FLOOR_ID
         *
         * @returns {object[]}
         */
        filteredRooms: function () {
            return this.selectedFloor
            ? this.rooms.filter(i => i.FLOOR_ID === this.selectedFloor)
            : this.rooms
        },

        /**
         * @name filteredUsers
         * @description List of users type
         *
         * @returns {object[]}
         */
        filteredUsers: function () {
            const USER_TYPES = this.$t('tablet.choices')
            return [
                { name: USER_TYPES.admin, value: '1' },
                { name: USER_TYPES.guest, value: '0' },
            ]
        },
    },

    methods: {
        /**
         * @name saveTabletData
         * @description Save the tablet data
         *
         * @returns {void}
         */
        saveTabletData() {
            const lang = this.$t('tablet')
            const isValid = this.validateInput()
             if (isValid) {
                this.isLoading = true
                axios.post('/clientDevice/registerDevice', {
                    uuid: this.uuid,
                    room_id: this.selectedRoom,
                    admin: this.selectedUser
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 300) {
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: lang.success,
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
                        title: lang.fail,
                        showConfirmButton: false,
                        timer: 2000
                    })
                })
                .then(() => {
                    this.$emit('refreshData')
                    this.isLoading = false
                    this.closeModal()
                })
            }
        },

        /**
         * @name closeModal
         * @description Close and clear the data of the modal
         *
         * @returns {void}
         */
        closeModal() {
            this.showModal = false
            this.selectedRoom = null
            this.selectedUser = null
            this.roomsErrorEmpty = null
            this.usersErrorEmpty = null
        },

        /**
         * @name validateInput
         * @description Validate all input
         *
         * @returns {boolean}
         */
        validateInput() {
            const lang = this.$t('tablet')
            let isRoomNotEmpty = false
            let isUserNotEmpty = false

            // Validate room type input
            if (!this.selectedRoom) {
                this.roomsErrorEmpty = lang.errors.ifEmptyRoom
            } else {
                this.roomsErrorEmpty = null
                isRoomNotEmpty = true
            }

            // Validate user type input
            if (!this.selectedUser) {
                this.usersErrorEmpty = lang.errors.ifEmptyUser
            } else {
                this.usersErrorEmpty = null
                isUserNotEmpty = true
            }

            //Will only return true if all validations are true
            return isRoomNotEmpty && isUserNotEmpty
        },

        /**
         * @name getUuid
         * @description Get the device uuid
         *
         * @returns {void}
         */
        getUuid() {
            this.uuid = DeviceUUID.get();
            console.log(this.uuid);
        },

        /**
         * @name getFloors
         * @description Get all floors
         * @since 1.0.0
         *
         * @returns {void}
         */
        getFloors() {
            axios.post('getFloorAll')
            .then(response => {
                if (response.status == 200) {
                    this.floors = response.data
                } else {
                    this.errors.push(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        },
        
        /**
         * @name getRooms
         * @description Get all rooms
         * @since 1.0.0
         *
         * @returns {void}
         */
        getRooms() {
            axios.get('getRoomAll')
            .then(response => {
                if (response.status == 200) {
                    this.rooms = response.data
                } else {
                    this.errors.push(response.data)
                }
            })
            .catch(error => this.errors.push(error))
        }
    }
}
</script>