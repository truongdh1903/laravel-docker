import { createApp } from 'vue'
import './scss/styles.scss'
import * as bootstrap from 'bootstrap'
import router from '../router'
import store from '../store'
import App from './App.vue'
import jQuery from 'jquery'
import axios from 'axios'
window.myAxios = axios.create({
    baseURL: 'http://127.0.0.1/api/',
    headers: {Authorization: 'Bearer ' + window.localStorage.getItem('token')}
})
window.$ = jQuery
createApp(App).use(router).use(store).mount('#app')
