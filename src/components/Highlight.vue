<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  text: string,
  match: string
}>()

const highlighted = computed(() => props.text.replace(props.match, `<span class="highlight">${props.match}</span>`))
</script>

<template>
  <span v-html="highlighted"></span>
</template>
