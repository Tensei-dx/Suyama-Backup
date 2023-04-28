<template>
    <div class="mt-3">
        <b-table id="remote-lock-devices-table" class="text-white tab-content" :items="items" :fields="fields"
                 :per-page="perPage" :current-page="currentPage">
            <template slot="room.ROOM_NAME" slot-scope="row">
                <span v-if="row.item.ROOM_ID">{{ row.item.room.ROOM_NAME }}</span>
                <span v-else class="text-white">{{ $t('management.remotelock.noRoomData') }}</span>
            </template>
            <template slot="status" slot-scope="row">
                <div class="d-flex flex-column">
                    <span class="mx-auto badge text-uppercase mb-1"
                          :class="row.item.REG_FLAG === 1 ? 'badge-white' : 'badge-white'">
                        {{ $t(row.item.REG_FLAG === 1 ? 'management.remotelock.registered' : 'management.remotelock.unregistered') }}
                    </span>
                    <span class="mx-auto badge text-uppercase"
                          :class="row.item.ONLINE_FLAG === 1 ? 'badge-white' : 'badge-white'">
                        {{ $t(row.item.ONLINE_FLAG === 1 ? 'management.remotelock.online' : 'management.remotelock.offline') }}
                    </span>
                </div>
            </template>
            <template slot="action" slot-scope="row" class="d-flex justify-items-around items-align-center">
                <template v-if="row.item.REG_FLAG === 0">
                    <button @click="$emit('register', row.item)" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus" aria-hidden="true" />
                    </button>
                </template>
                <template v-else>
                    <span @click="$emit('edit', row.item)" class="pointer text-center">

                        <i class="fa fa-edit fa-lg" aria-hidden="true" />
                        <!-- <img src="/img/ManagementDashboard/icon/edit.png" class="edit-icon-size pointer"> -->
                    </span>
                </template>
                <span @click="$emit('remove', row.item)" class="pointer text-center">
                    <i class="fa fa-trash fa-lg" aria-hidden="true" />
                    <!-- <img src="/img/ManagementDashboard/icon/delete.png" class="edit-icon-size pointer"> -->

                </span>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="totalItems" :per-page="perPage" align="center"
                      aria-controls="remote-lock-devices-table" v-if="showPagination" />
    </div>
</template>

<script>
/**
 * <System Name> iBMS
 *
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @since 2021.10.07
 */
export default {
    props: {
        items: Array,
    },

    data() {
        return {
            perPage: 5,
            currentPage: 1,
        }
    },

    computed: {
        /**
         * @name fields
         * @description Make the field names computed to support locale changes
         *
         * @returns {object[]}
         */
        fields: function () {
            return [
                {
                    key: 'DEVICE_SERIAL_NO',
                    label: this.$t('management.remotelock.fields.serialNo'),
                    isRowHeader: true,
                    stickyColumn: true,
                },
                {
                    key: 'DEVICE_NAME',
                    label: this.$t('management.remotelock.fields.name'),
                    class: 'text-center p-auto',
                },
                {
                    key: 'room.ROOM_NAME',
                    label: this.$t('management.remotelock.fields.room'),
                    class: 'text-center p-auto',
                },
                {
                    key: 'status',
                    label: this.$t('management.remotelock.fields.status'),
                    class: 'text-center p-auto',
                },
                {
                    key: 'action',
                    label: this.$t('management.remotelock.fields.action'),
                    class: 'text-center p-auto',
                },
            ]
        },

        /**
         * @name totalItems
         * @description Total number of items
         *
         * @returns {number}
         */
        totalItems: function () {
            return this.items.length
        },

        showPagination: function () {
            return this.items.length > this.perPage
        },
    },
}
</script>

<style scoped>
.edit-icon-size {
    width: 20px;
    height: 20px;
}
.tab-content {
    font-size: 18px;
}
.mt-3 {
    background-color: #595959;
}
</style>
