import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import VueGtag from 'vue-gtag'

Vue.use(VueGtag, {
  config: { id: 'UA-34654961-2' },
  enabled: process.env.NODE_ENV === 'production'
}, router)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
