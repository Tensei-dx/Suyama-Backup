<!-- UPDATED: TP Shannie SPRINT_12 TASK233 20211015 -->

<template>
    <div class="container key-management-width key-management-scroll">
        <div class="text-white row mx-0" style="background-color: black;">
            <div class="col-12 text-center">
                <i class="fa fa-key fa-3x" aria-hidden="true"></i>
                <!-- = SPRINT_09 Task192 -->
                <!-- <span class="font-weight-bold" style="font-size:3rem;">Create New Account</span> -->
                <span class="font-weight-bold"
                      style="font-size:3rem;">{{ $t('keyManagementPage.createNewAccount') }}</span>
                <!-- = SPRINT_09 Task192 -->
            </div>

            <div class="col-12 mb-3">
                <form class="font-weight-bold px-2">
                    <!-- <div class="form-group row"> -->
                    <!-- <label for="accountType" class="col col-form-label">ACCOUNT TYPE:</label> -->
                    <!-- <div class="col-12"> -->
                    <!-- <select class="custom-select" v-model="form_input.usertype"> -->
                    <!-- loop the rooms data -->
                    <!-- <option value="null" disabled selected>---</option>
                                <option value="1">User</option>
                                <option value="2">Guest</option>
                            </select> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- <div v-if="form_input.usertype == 1">
                        <NewUserAccount></NewUserAccount>
                    </div>
                    <div v-if="form_input.usertype == 2">
                        <NewGuestAccount></NewGuestAccount>
                    </div> -->
                    <div>
                        <NewUserAccount></NewUserAccount>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
//import NewGuestAccount from "../NewAccount/NewGuestAccount.vue";
import NewUserAccount from '../NewAccount/NewUserAccount.vue'

export default {
    components: {
        //NewGuestAccount,
        NewUserAccount,
    },

    data() {
        return {
            form_input: {
                usertype: 1,
            },
        }
    },
}
</script>

<style>
.btn-custom-color-rl {
    background-color: #b4c7e7;
}
.key-management-width {
    max-width: 895px !important;
}
</style>

