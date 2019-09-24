require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

Vue.use(VueRouter)
Vue.use(Buefy)

import App from './views/admin/App'
import Upload from './views/admin/Upload'
import Home from './views/admin/Home'

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/admin',
      name: 'admin.home',
      component: Home
    },
      /*
    {
      path: '/admin',
      name: 'home',
      component: Home
    },
    
    {
      path: '/admin/u',
      name: 'auth',
    },
    */
    {
      path: '/admin/upload',
      name: 'admin.upload',
      component: Upload
    },
  ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});