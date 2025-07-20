import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import Lexemes from '../views/Lexemes.vue'
import LexemeView from '../views/LexemeView.vue'
import Roots from '../views/Roots.vue'
import RootView from '../views/RootView.vue'
import Sources from '../views/Sources.vue'
import NotFound from '../views/NotFound.vue'

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
  },
  // catch-all, see https://router.vuejs.org/guide/essentials/history-mode.html#caveat
  {
    path: '*',
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// const router = new VueRouter({
//   base: process.env.VUE_APP_BASE_URL,
//   mode: 'history',
//   routes,
//   scrollBehavior (_to, _from, savedPosition) {
//     if (savedPosition) {
//       return savedPosition
//     } else {
//       return { x: 0, y: 0 }
//     }
//   }
// })

export default router
