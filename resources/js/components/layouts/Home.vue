<template>
    <div class="main-wrapper">
            <nav class="nav-home">
                <ul class="nav-home-list">
                    <li>
                        <a v-if="loggedIn" >Hello, {{user.name}}</a>
                        <a v-else></a>
                    </li>
                    <li>
                        <img src="http://www.tiacgroup.com/wp-content/uploads/2015/06/TIAC-logo.png" alt="" width="100px" height="50px">
                    </li>
                    <li class="nav-button">
                        <router-link v-if="!loggedIn" :to="{ name:'login' }">Login</router-link>
                        <router-link v-else :to="{ name:'logout' }">Log out</router-link>
                    </li>
                </ul>
            </nav>
        <div class="home">
            <div class="clock-div">
                <div class="clock-div-clock">
                    <Clock></Clock>
                </div>
            </div>
            <div class="home-button">
                <a class="cursor-pointer" v-if="loggedIn" @click.prevent="openModal">Create guest</a>
                <router-link class="cursor-pointer" v-if="loggedIn" :to="{name: 'dash-land'}">Dashboard</router-link>
            </div>
            <CreateUserModal v-if="modal" @close-modal="closeModal"/>
            <on-going-visits></on-going-visits>
            <meetings></meetings>
        </div>
    </div>
</template>

<script>
import Clock from '../widgets/Clock'
import OnGoingVisits from '../guest/OnGoingVisits'
import Meetings from '../guest/Meetings'
import CreateUserModal from '../user/CreateUserModal'

export default {
    components: {
        Clock,OnGoingVisits,Meetings,CreateUserModal
    },
    data() {
        return {
            modal: false
        }
    },
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        },
        user() {
            return this.$store.state.user
        }
    },
    methods: {
        openModal() {
            this.modal = true
            console.log(this.modal)
        },
        closeModal() {
            this.modal = false
            console.log(this.modal)
        },
    }
}
</script>
<style>

</style>


 