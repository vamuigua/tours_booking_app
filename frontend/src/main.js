import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from '@/lib/axios'
import router from './router'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import App from './App.vue'
import ValidationError from '@/components/ValidationError.vue'
import IconSpinner from '@/components/IconSpinner.vue'

import VueAwesomePaginate from "vue-awesome-paginate";

import './assets/main.css'
import "vue-awesome-paginate/dist/style.css";

window.axios = axios

const app = createApp(App)
const pinia = createPinia()

pinia.use(piniaPluginPersistedstate)

app.use(pinia)
app.use(router)
app.use(VueAwesomePaginate)

app.component('ValidationError', ValidationError)
app.component('IconSpinner', IconSpinner)

app.mount('#app')