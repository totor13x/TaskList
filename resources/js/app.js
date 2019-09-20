require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

Vue.use(VueRouter)
Vue.use(Buefy)

import App from './views/App'
import Home from './views/Home'

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/auth',
      name: 'auth',
    },
  ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    data: function() {
        return {
            token: undefined,
        }
    }, 
    watch: {
        token: function() {
            window.axios.defaults.headers.common['Authorization'] = this.token;
        }
    }
});