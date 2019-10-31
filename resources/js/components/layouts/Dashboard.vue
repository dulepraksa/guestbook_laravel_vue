<template>
    <div id=app>
        <aside class="sidebar">
            <header>
                <a href="/home">
                    <img src="http://www.tiacgroup.com/wp-content/uploads/2015/06/TIAC-logo.png" alt="">
                </a>
            </header>
            <nav class="menu">
                <ul>
                    <router-link  :to="{name: 'dash-land'}">
                        <li>
                            <a :class="{active: redactive}"  class="menu-item">
                                <span class="fa fa-tachometer-alt menu-icon"></span>
                                <span class="menu-label">Dashboard</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link  :to="{ path:'/home'}">
                        <li>
                            <a :class="{active: redactive}"  class="menu-item">
                                <span class="fas fa-home menu-icon"></span>
                                <span class="menu-label">Home</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link :to="{ name:'my-profile' }">
                        <li>
                            <a  class="menu-item">
                                <span class="fas fa-id-card menu-icon"></span>
                                <span class="menu-label">Profile</span>
                            </a>
                        </li>
                    </router-link>
                    <router-link :to="{ name:'user-list' }" v-if="userRoles[0].name !='Visitor'">
                        <li>
                            <a :class="{active: redactive}" class="menu-item">
                                <span class="fas fa-book menu-icon"></span>
                                <span class="menu-label">Users</span>
                            </a>
                        </li>
                    </router-link>
                </ul>
            </nav>
        </aside>
        <main class="main-wrapper">
            <header class="site-header">
                <div class="searchbar">
                    <span class="fa fa-search"></span>
                    <input type="text" placeholder="Search..." name="" id=""  v-model="query">
                        <div v-if ="results.length > 0">
                            <a v-for="result in results" :key="result.id">
                                <a href="" @click="pushToProfile(result.id)">
                                    <img width="35px" height="35px" :src="getAvatar(result.image)">
                                    <a>
                                        {{result.name}}
                                        {{result.surname}}                                        
                                    </a>
                                </a>
                            </a>
                        </div>
                </div>
                <div class="d-flex align-items-center">                 
                    <div class="user-info">
                        <span class="user-avatar"> {{this.nameFirst()}}{{this.surnameFirst()}} </span>
                        <span class="user-name">{{this.usersName()}} {{this.usersSurname()}}</span>
                        <i class="fas fa-sort-down show-options" aria-hidden="true" @click="showMoreData=!showMoreData"></i>
                            <div v-bind:class="{active: showMoreData}" class="more-options">
                                <div class="drop-items">
                                    <router-link :to="{ name: 'logout' }">Logout</router-link>
                                </div>
                            </div>
                    </div>
                </div>
            </header>
            <div class="router-view-wrapper">
                <div class="router-view-container">
                    <transition name="fade" mode="out-in">
                        <router-view></router-view>
                    </transition>
                </div>
            </div>
        </main>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {

    name: 'dashboard',
    data() {
        return {
            query: null,
            results: [],
            noData: '',
            showMoreData: false,
            redactive: false,
        }
    },
    created() {
        this.$forceUpdate()
    },
    watch: {
        query(after, before) {
            this.fetch();
        }
    },
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn
        },
        user() {
            return this.$store.state.user
        },
        token() {
            return this.$store.state.token
        },
        userRoles() {
            return this.$store.state.user_roles
        }
    },
    methods: {
        fetch() {
            axios.get('/api/searchUsers?q=' + this.query,{
                headers: {
                    'Authorization': 'Bearer ' + this.token
                }
            })
                .then(response => this.results = response.data)
                .then(response => console.log(response))
                .catch(error => {});
        },
        usersName() {
            const name = this.user.name
            const nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1)
            return nameCapitalized
        },
        usersSurname() {
            const surname = this.user.surname
            const surnameCapitalized = surname.charAt(0).toUpperCase() + surname.slice(1)
            return surnameCapitalized
        },  
        nameFirst() {
            let x = this.user.name
            let y = x.substring(0, 1)
            return y
        },
        surnameFirst() {
            let x = this.user.surname
            let y = x.substring(0, 1)
            return y
        },
        getAvatar(image) {
            if(image != null) {
                return "/images/" + image
            } else {
                return "/images/default.png"
            }
        },
        pushToProfile(userId, gname, gsurname) {
            this.query = uname + ' ' + usurname
            this.$router.push({ name: 'user-profile', params: { id: JSON.stringify(userId)}})
        }
    }
}
</script>
<style scoped>
    #app{
        background: white;
    }
</style>