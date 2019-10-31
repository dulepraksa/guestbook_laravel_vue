import Login from './components/auth/Login'
import Logout from './components/auth/Logout'
import Register from './components/auth/Register'
import Home from './components/layouts/Home.vue'
import Dashboard from './components/layouts/Dashboard.vue'
import MyProfile from './components/auth/MyProfile'
import UserProfile from './components/user/UserProfile.vue'
import Users from './components/user/Users.vue'
import NotFound from './components/addtitonal_components/NotFound'
import DashboardLanding from './components/DashLanding/DashboardLanding.vue'
import { Store } from 'vuex';

export const routes = [
    {
        path:'/404',
        name: '404',
        component: NotFound,
    },
    {
        path: '*',
        redirect: '/404',
        component: NotFound,
    },  
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/logout',
        name: 'logout',
        component:Logout, 
    },     
    {
        path: '/home',
        name: 'home',
        component: Home
    },
    {
        path:'/dashboard',
        name: 'dashboard',
        component: Dashboard,
        redirect: 'dashboard/myprofile',
        children: [
            {
                path: 'myprofile',
                name: 'my-profile',
                component: MyProfile
            },
            {
                path: 'home',
                name: 'dash-land',
                component: DashboardLanding
            },
            {
                path: 'users',
                name: 'user-list',
                component: Users,
            },
            {
                path: 'user/:id',
                name: 'user-profile',
                props: true,
                component: UserProfile
            }
    ]},

]