<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  text: string,
  match: string | null
}>()

const highlighted = computed(() => {
  if (props.match)
    return props.text.replace(props.match, `<span class="highlight">${props.match}</span>`)
  else
    return props.text
})
</script>

<template>
  <span v-html="highlighted"></span>
</template>
