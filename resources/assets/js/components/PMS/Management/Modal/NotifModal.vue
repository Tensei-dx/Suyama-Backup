<template>
    <div class="modal" id="notifModal" tabindex="-1" role="dialog" aria-labelledby="Notification Modal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-black text-left">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Event Type</th>
                                <!-- <th scope="col">Object</th> -->
                                <th scope="col">Message</th>
                                <th scope="col">Room Name</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in notifications" :key="item.NOTIFICATION_ID">
                                <th scope="row">{{ index + 1 }}</th>
                                <!-- <td>{{ item.OBJECT_NAME }}</td> -->
                                <td>{{ item.SUBJECT }}</td>
                                <td>{{ item.ROOM_ID }}</td>
                                <td>{{ item.CREATED_AT }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        room_id: Number,
    },
    data() {
        return {
            notifications: [],
            notifCount: 0,
        }
    },
    methods: {
        /**
         *
         */
        getNotification() {
            axios
                .get('client/notifications/' + this.room_id)
                .then(response => {
                    this.notifications = response.data
                    this.notifCount = response.data.length
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    created() {
        this.getNotification()
    },
    mounted() {
        Echo.channel('notification-event').listen('NotificationEvent', value => {
            this.getNotification()
        })
    },
}
</script>
