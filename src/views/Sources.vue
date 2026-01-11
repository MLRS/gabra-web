<script setup lang="ts">
import axios from 'axios'
import { ref, onMounted } from 'vue'

import { __ } from '@/components/I18N.ts'

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const sources = ref<Source[]>([])
const working = ref(false)

onMounted(() => {
  store.setTitle({ key: 'Sources' })

  working.value = true
  axios.get(`${import.meta.env.VITE_API_URL}/sources`)
    .then(response => {
      sources.value = response.data
    })
    .catch(error => {
      store.addError(error)
    })
    .then(() => {
      working.value = false
    })
})
</script>

<template>
  <div>
    <h1 class="h3">{{ __('Sources') }}</h1>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>

    <div class="mt-3">
      <div class="row border-top py-2" v-for="s,ix in sources" :key="ix">
        <div class="col-md-2 fw-semibold">{{ s.key }}</div>
        <div class="col-md-3">{{ s.title }}</div>
        <div class="col-md-2">{{ s.author }}</div>
        <div class="col-md-1">{{ s.year }}</div>
        <div class="col-md-4" style="word-wrap: break-word">{{ s.note }}</div>
      </div>
    </div>

  </div>
</template>
