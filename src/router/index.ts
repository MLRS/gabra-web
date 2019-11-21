import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Lexemes from '../views/Lexemes.vue'
import Roots from '../views/Roots.vue'
import Sources from '../views/Sources.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/lexemes',
    name: 'lexemes',
    component: Lexemes,
    props: true
  },
  {
    path: '/roots',
    name: 'roots',
    component: Roots
  },
  {
    path: '/sources',
    name: 'sources',
    component: Sources
  }
]

const router = new VueRouter({
  routes
})

export default router
