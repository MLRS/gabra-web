<script setup lang="ts">
import axios from 'axios'
import { ref, watch, onMounted } from 'vue'

import { __ } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'

import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const term = ref(route.query.s)
const randomWorking = ref(false)
const darkMode = ref(false)

watch(
  () => route.query.s,
  () => {
    store.clearMessages()
  }
)

function submitSearch (): void {
  if (term.value) {
    router.push({ name: 'lexemes', query: { s: term.value } })
      .catch(() => { })
  }
}

function clickRandom (): void {
  randomWorking.value = true
  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/random`)
    .then(response => {
      router.push({
        name: 'lexeme',
        params: {
          'id': response.data._id
        }
      })
    })
    .then(() => {
      randomWorking.value = false
    })
}

onMounted(() => {
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  darkMode.value = prefersDark
})

watch(darkMode, (newVal) => {
  const html = document.documentElement
  html.setAttribute('data-bs-theme', newVal ? 'dark' : 'light')
})
</script>

<template>
  <div id="app">

    <nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-body-tertiary border-bottom mb-2">
    <div class="container gap-3">
      <router-link to="/" class="navbar-brand text-red">Ġabra</router-link>

      <form role="search" action="" @submit.prevent="submitSearch" v-show="$route.name != 'home'" class="me-auto">
        <SearchInput
          :placeholder="__('search.placeholder')"
          :showSubmit="true"
          @update="(s: string) => { term = s }"
        ></SearchInput>
      </form>

      <button class="navbar-toggler p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars" />
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav me-auto">
          <router-link to="/lexemes" class="nav-item nav-link">{{ __('Advanced search') }}</router-link>
          <router-link to="/roots" class="nav-item nav-link">{{ __('Root search') }}</router-link>
          <router-link to="/sources" class="nav-item nav-link">{{ __('Sources') }}</router-link>
        </div>
        <div class="navbar-nav">
          <a href="" class="nav-item nav-link" v-show="store.language != 'en'" @click.prevent="store.setLanguage('en')">
            in English
          </a>
          <a href="" class="nav-item nav-link" v-show="store.language != 'mt'" @click.prevent="store.setLanguage('mt')">
            bil-Malti
          </a>
          <a href="" class="nav-item nav-link" @click.prevent="darkMode = true" v-show="!darkMode" :title="__('mode.dark')">
            <i class="fas fa-moon" />
            <span class="d-inline-block d-lg-none ms-2">{{ __('mode.dark') }}</span>
          </a>
          <a href="" class="nav-item nav-link" @click.prevent="darkMode = false" v-show="darkMode" :title="__('mode.light')">
            <i class="fas fa-sun" />
            <span class="d-inline-block d-lg-none ms-2">{{ __('mode.light') }}</span>
          </a>
        </div>
      </div>
    </div>
    </nav>

    <main class="container pt-3">
      <div v-for="m,ix in store.messages" :key="ix" class="alert" :class="'alert-'+m.type">
        {{ m.text }}
      </div>
      <router-view></router-view>
    </main>

    <footer class="container mb-5">
      <button class="btn btn-link position-fixed" style="opacity:0.1; bottom: 0; right: 0;" :title="__('random.button')" @click="clickRandom">
        <i class="fas fa-2x" :class="[randomWorking ? 'fa-circle-notch fa-spin' : 'fa-dice']" />
      </button>
    </footer>

  </div>
</template>
