import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// import VueGtag from 'vue-gtag'

// Vue.use(VueGtag, {
//   config: { id: 'UA-34654961-2' },
//   enabled: import.meta.env.PROD,
// }, router)

// Vue.config.productionTip = false

const app = createApp(App)
const pinia = createPinia()
app.use(pinia)
app.use(router)
app.mount('#app')
