<template>
    <div class="visit-notification-container">
        <visitNotificationBar
        v-for="visitNotification in visitNotifications"
        :key="visitNotification.id"
        :notification="visitNotification"
        :visitId="visitId"
        />
    </div>
</template>
<script>
import visitNotificationBar from './visitNotificationBar'
import { mapState } from 'vuex'

export default {
    components: {
        visitNotificationBar
    },
    computed: mapState('visitNotification',['visitNotifications'])
}
</script>
<style scoped>
    .visit-notification-container {
        position: fixed;
        bottom: 5px;
        left: 5px;
        width: 200px;
        height: 200px;
        background-color: red;
        color: white;
        font-weight: 800;
        border-radius: 1%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    }
</style>