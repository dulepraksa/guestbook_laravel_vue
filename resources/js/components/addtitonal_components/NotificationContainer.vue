<template>
    <div class="notification-container">
        <NotificationBar
        v-for="notification in notifications"
        :key="notification.id"
        :notification="notification"
        />
    </div>
</template>
<script>
import NotificationBar from './NotificationBar'
import { mapState } from 'vuex'

export default {
    components: {
        NotificationBar
    },
    computed: mapState('notification',['notifications'])
}
</script>
<style scoped>
    .notification-container {
        position: fixed;
        bottom: 5px;
        right: 5px;

        background-color: red;
        color: white;
        font-weight: 800;
        border-radius: 1%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    }
</style>
