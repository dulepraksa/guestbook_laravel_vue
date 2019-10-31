<template>
    <div id = "app">
        <transition name="fade" mode="out-in">
        <router-view></router-view>
        </transition>
        <NotificationContainer/>
    </div>
</template>
<script>
import NotificationContainer from '../addtitonal_components/NotificationContainer'
export default {
    components: {
        NotificationContainer
    }
}
</script>
