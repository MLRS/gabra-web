<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { __, markdown } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'
import axios from 'axios'

import { useRootStore } from '@/stores/root'
const store = useRootStore()

import { useRouter } from 'vue-router'
const router = useRouter()

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

  axios.get(`${process.env.VUE_APP_API_URL}/lexemes/count`)
    .then(response => {
      stats.lexemes = `<span class="badge badge-dark">${response.data.toLocaleString()}</span>`
    })
    .catch(error => {
      console.error(error)
    })
  axios.get(`${process.env.VUE_APP_API_URL}/wordforms/count`)
    .then(response => {
      stats.wordforms = `<span class="badge badge-secondary">${response.data.toLocaleString()}</span>`
    })
    .catch(error => {
      console.error(error)
    })
})
</script>

<template>
  <div id="home" class="row">

    <div class="jumbotron bg-light shadow-sm py-5 px-5">
      <h1 class="display-4 font-weight-normal text-shadow">{{ __('home.title') }}</h1>

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
@import '@/assets/custom.scss';

.well {
  @extend .card-body, .bg-light, .rounded, .my-3, .text-muted;
}
</style>
