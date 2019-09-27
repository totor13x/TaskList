require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

const settings = {
  apiKey: 'd435cde1-1c31-4cb4-82ad-cca85b41941f',
  lang: 'ru_RU',
  coordorder: 'latlong',
  version: '2.1'
}
import YmapPlugin from 'vue-yandex-maps'

Vue.use(VueRouter)
Vue.use(Buefy)
Vue.use(YmapPlugin, settings)

import App from './views/home/App'
import Home from './views/home/Home'

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
    components: { App,YmapPlugin },
    router,
    data: function() {
        return {
            token: undefined,
            defaultCoords: [54.79402948133831, 56.05672011904906],
            coords: [54.79402948133831, 56.05672011904906],
        }
    }, 
    watch: {
        token: function() {
            window.axios.defaults.headers.common['Authorization'] = this.token;
        },
    }
});