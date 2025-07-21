<script setup lang="ts">
import axios from 'axios'
import { ref, reactive, onMounted } from 'vue'

import { __, markdown } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'

import { useRouter } from 'vue-router'
const router = useRouter()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const stats = reactive({
  lexemes: '…',
  wordforms: '…'
})

const term = ref('')

function submitSearch (): void {
  if (term.value) {
    router.push({ name: 'lexemes', query: { s: term.value } })
  }
}

onMounted(() => {
  store.clearTitle()

  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/count`)
    .then(response => {
      stats.lexemes = `<span class="badge bg-dark">${response.data.toLocaleString()}</span>`
    })
    .catch(error => {
      console.error(error)
    })
  axios.get(`${import.meta.env.VITE_API_URL}/wordforms/count`)
    .then(response => {
      stats.wordforms = `<span class="badge bg-secondary">${response.data.toLocaleString()}</span>`
    })
    .catch(error => {
      console.error(error)
    })
})
</script>

<template>
  <div id="home" class="row">

    <div class="rounded-3 bg-light shadow p-5 mb-5">
      <h1 class="display-4 fw-normal text-shadow">{{ __('home.title') }}</h1>

      <p class="lead" v-html="__('home.1', {lexemes: stats.lexemes, wordforms: stats.wordforms})"></p>

      <form role="search" action="" @submit.prevent="submitSearch">
        <SearchInput
          :placeholder="__('home.search.placeholder')"
          :showSubmit="true"
          class="input-group-lg"
          @update="(s: string) => { term = s }"
        ></SearchInput>
      </form>

    </div><!-- jumbotron -->

    <div class="col-sm-6">

      <div v-html="markdown(__('home.about'))"></div>

      <div v-html="markdown(__('home.mistakes'))"></div>

    </div><!-- /.col-sm-6 -->

    <div class="col-sm-6">

      <div v-html="markdown(__('home.using'))"></div>

      <div v-html="markdown(__('home.citing'))"></div>

      <div v-html="__('home.license')"></div>

    </div><!-- /.col-sm-6 -->

  </div>
</template>

<style lang="scss">
@use '@/assets/custom.scss';

blockquote {
  @extend .card-body, .bg-light, .rounded, .my-3, .p-3, .text-muted;
}
</style>
