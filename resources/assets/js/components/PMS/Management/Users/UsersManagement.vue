<template>
    <div class="container">

        <div class="row my-row" style="margin-top:10px;">
            <div class="col">
            <h3>{{ $t('management.users') }}</h3>
            </div>
            <div class="col" style="text-align:end">
                <i class="fa fa-bell fa-2x" aria-hidden="true"></i>
            </div>
        </div>
        <Users></Users>

    </div>
</template>

<script>
import Users from '../../Users/Users.vue'
export default {
    components: {
        Users
    },
    data() {
            return {
                link : '',
                apiInfo: []
            }

        },

    // created() {
    //     this.usersManagement();
    // },

    methods: {
        usersManagement() {
            axios.get('user-management', {
            })
            .then((response) => {
                console.log(response.data);
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }

}
</script>

<style>

</style>
