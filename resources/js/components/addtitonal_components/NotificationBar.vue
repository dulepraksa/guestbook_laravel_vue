<template>
    <div class="notification-bar">
        <p>{{ notification.message }}</p>
    </div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    props: {
        notification: {
            type: Object,
            required: true
        }
    },
    created() {
        
    },
    data() {
        return {
            timeout: null
        }
    },
    mounted() {
        this.timeout = setTimeout(() => this.remove(this.notification), 5000)
    },
    beforeDestroy() {
        clearTimeout(this.timeout)
    },
    computed: {
        notificationTypeClass() {
            return `-text-${this.notification.type}`
        }
    },
    methods: mapActions('notification',['remove'])
}
</script>
<style scoped>
    .notification-bar {

    }
</style>
