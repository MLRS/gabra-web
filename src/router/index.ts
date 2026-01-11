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
  {
    path: '/:pathMatch(.*)',
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.VITE_BASE_URL),
  routes,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

export default router
