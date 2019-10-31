require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from './routes';
import {store} from './store';
import Master from './components/layouts/Master.vue';
import VisitChart from './components/Charts/VisitChart/VisitChart';

Vue.use(VueRouter);
Vue.component('visit-chart', VisitChart);

const router = new VueRouter({
    mode: 'history',
    routes,
    linkExactActiveClass: 'is-active',
});


let loggedIn = store.state.token;

if(loggedIn !== typeof 'undefined') {
    router.beforeEach((to, from, next) => {
        if (to.path.startsWith('/dashboard') && !loggedIn ) {
            next('/home');
        } else {
            next();
        }
    });
}

if (store.state.user !== null || store.state.user !== typeof 'undefined') {
    let userRoleName = store.state.user.roles[0].name;
    router.beforeEach((to, from, next) => {
        if (to.path.startsWith('/dashboard/users') && userRoleName == 'Visitor') {
            next('/dashboard');
        } else {
            next();
        }
    });
}


const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    components: {
        Master
    },
    template:'<Master/>'
});
