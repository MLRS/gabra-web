<script setup lang="ts">
import { onMounted } from 'vue'
import { __ } from '@/components/I18N.ts'

import { useRootStore } from '@/stores/root'
const store = useRootStore()

onMounted(() => {
  store.setTitle({ key: 'not_found.title' })
  store.addError(__('not_found'))
})
</script>

<template>
  <div>
  </div>
</template>

