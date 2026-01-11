import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { configure } from 'vue-gtag'

import 'bootstrap/js/dist/collapse';

import App from './App.vue'
import router from './router'

if (import.meta.env.PROD) {
  configure({
    tagId: 'UA-34654961-2'
  })
}

const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.use(router)
app.mount('#app')
