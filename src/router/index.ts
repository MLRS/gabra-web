import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Lexemes from '../views/Lexemes.vue'
import LexemeView from '../views/LexemeView.vue'
import Roots from '../views/Roots.vue'
import RootView from '../views/RootView.vue'
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
    component: Lexemes
  },
  {
    path: '/lexemes/view/:id',
    name: 'lexeme',
    component: LexemeView
  },
  {
    path: '/roots',
    name: 'roots',
    component: Roots
  },
  {
    path: '/roots/view/:radicals/:variant?',
    name: 'root',
    component: RootView
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
