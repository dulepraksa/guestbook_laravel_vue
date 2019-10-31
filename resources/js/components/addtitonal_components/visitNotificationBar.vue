<template>
    <div class="notification-bar">
        <input v type="text">
    </div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
    props: {
        visitNotification: {
            type: Object,
            required: true
        }
    },
    created() {
        console.log(this.notification.id)
    },
    data() {
        return {
            timeout: null,

        }
    },
    mounted() {
        this.timeout = setTimeout(() => this.remove(this.notification), 30000)
    },
    beforeDestroy() {
        clearTimeout(this.timeout)
    },
    methods:{
        ...mapActions('visitNotification',['remove']),
        ...mapActions('')    
    } 
        
}
</script>
<style scoped>
    .notification-bar {

    }
</style>
