<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  root: Root, // Root
  match?: string // Regex from search
}>()

const highlighted = computed(() => {
  if (props.match) {
    const re = new RegExp(`(${props.match})`)
    return props.root.radicals.replace(re, '<span class="highlight">$1</span>')
  } else {
    return props.root.radicals
  }
})
</script>

<template>
  <router-link :to="{ name: 'root', params: { radicals: root.radicals, variant: root.variant } }" class="text-nowrap" v-if="root">
    <span v-html="highlighted"></span>
    <sup class="text-muted" v-if="root.variant">
      {{ root.variant }}
    </sup>
  </router-link>
</template>
