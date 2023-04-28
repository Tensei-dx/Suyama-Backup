<template>
    <div>
        <div class="pb-2">

            <!-- TABLE -->
            <b-table :fields="fields" :items="adminUsers" :current-page="table.currentPage" :per-page="table.perPage"
                     @filtered="onFilteredItems" :filter="table.filter" :filter-included-fields="table.filterOn"
                     :sort-by.sync="table.sortBy" :sort-direction="table.sortDirection" show-empty
                     :empty-filtered-text="$t('management.keyManagementPage.noUserAccountsRegister')"
                     :empty-text="$t('management.keyManagementPage.noUserAccountsRegister')">

                <!-- Custom column: UPDATE -->
                <template slot="UPDATE" slot-scope="data">
                    <span @click="openModal(constant.UPDATE_MODAL, data.item)" class="pointer text-center">
                        <i class="fa fa-edit fa-style fa-lg" aria-hidden="true" />
                    </span>
                </template>

                <!-- Custom column: DELETE -->
                <template slot="DELETE" slot-scope="data">
                    <span @click="openModal(constant.DELETE_MODAL, data.item)" class="pointer text-center">
                        <i class="fa fa-trash fa-style fa-lg" aria-hidden="true" />
                    </span>
                </template>

            </b-table>

            <!-- pagination for table navigation -->
            <div v-if="table.totalRows > '6'">
                <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="table.totalRows"
                              align="center" aria-controls="#info-logs-table" />
            </div>

        </div>

        <UpdateModal v-if="isModal(constant.UPDATE_MODAL)" :user="selectedUser" @close="closeModal" />
        <DeleteModal v-if="isModal(constant.DELETE_MODAL)" :user="selectedUser" @close="closeModal" />

    </div>
</template>

<script>
import UpdateModal from '../Users/Modals/UpdateUserAccountModal.vue'
import DeleteModal from '../Users/Modals/DeleteUserAccountModal.vue'

export default {
    components: {
        UpdateModal,
        DeleteModal,
    },

    data() {
        return {
            isLoading: false,
            adminUsers: [],
            selectedUser: null,
            selectedModal: null,
            table: {
                perPage: 6,
                currentPage: 1,
                totalRows: 1,
                filter: null,
                filterOn: [],
                sortBy: 'LAST_NAME',
                sortDesc: true,
                sortDirection: 'desc',
                number: 0,
            },
            // constants
            constant: {
                UPDATE_MODAL: 'update',
                DELETE_MODAL: 'delete',
            },
        }
    },

    created() {
        this.getUsers()
    },

    mounted() {
        this.$bus.on('refresh-users-table', e => this.getUsers())
    },

    methods: {
        /**
         * @name getUsers
         * @description Get the listing of the user resource
         *
         * @returns {void}
         */
        getUsers() {
            this.isLoading = true
            axios
                .get('/users', {
                    params: {
                        user_type: 1,
                    },
                })
                .then(response => (this.adminUsers = response.data))
                .catch(error => console.error(error.response.data))
                .then(() => (this.isLoading = false))
        },

        /**
         * @name openModal
         * @description Prepare modal data
         *
         * @param {string} modal
         * @param {object} user
         * @returns {void}
         */
        openModal(modal, user) {
            this.selectedUser = user
            this.selectedModal = modal
        },

        /**
         * @name isModal
         * @description Check if the modal is the selected data
         *
         * @param {string} modal
         * @returns {bool}
         */
        isModal(modal) {
            return modal === this.selectedModal
        },

        /**
         * @name closeModal
         * @description Close any modal
         *
         * @returns {void}
         */
        closeModal() {
            this.selectedModal = null
        },

        /**
         * @name onFilteredItems
         * @description Perform additional action when the table is being filtered
         *
         * @param {object[]} filteredItems
         * @returns {void}
         */
        onFilteredItems(filteredItems) {
            this.table.totalRows = filteredItems.length
            this.table.currentPage = 1
        },
    },

    computed: {
        fields: function () {
            const fields = this.$t('management.keyManagementPage')

            return [
                // Tentative column name. Column name must be changed according to DB
                {
                    key: 'USERNAME',
                    label: fields.username,
                    sortable: true,
                    class: 'text-center text-nowrap title-border',
                },
                {
                    key: 'FIRST_NAME',
                    label: fields.name,
                    sortable: true,
                    class: 'text-center text-nowrap title-border',
                },
                {
                    key: 'EMAIL',
                    label: fields.email,
                    sortable: true,
                    class: 'text-center text-nowrap title-border',
                },
                // {
                //     key: 'UPDATE',
                //     label: '',
                //     sortable: true,
                //     class: 'text-center text-nowrap title-border',
                // },
                // {
                //     key: 'DELETE',
                //     label: '',
                //     sortable: true,
                //     class: 'text-center text-nowrap title-border',
                // },
            ]
        },
    },
}
</script>

<style scoped>
.edit-icon-size {
    width: 30px;
    height: 30px;
}

.custom-table {
    width: 100%;
    background-color: #595959;
}
.font_header {
    font-size: 1rem;
}
.font_data {
    font-size: 1rem;
}
.fa-style {
    color: inherit !important;
}
</style>

