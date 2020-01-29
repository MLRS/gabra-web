<template>
  <span v-html="highlighted"></span>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  props: {
    text: String,
    match: String
  },
  computed: {
    highlighted (): string {
      return this.text.replace(this.match, `<span class="highlight">${this.match}</span>`)
    }
  }
})
</script>
