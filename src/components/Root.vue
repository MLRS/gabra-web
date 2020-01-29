<template>
  <router-link :to="{ name: 'root', params: { radicals: root.radicals, variant: root.variant } }" class="text-nowrap" v-if="root">
    <span v-html="highlighted"></span>
    <sup class="text-muted" v-if="root.variant">
      {{ root.variant }}
    </sup>
  </router-link>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  props: {
    root: Object, // Root
    match: String // Regex from search
  },
  computed: {
    highlighted (): string {
      if (this.match) {
        let re = new RegExp(`(${this.match})`)
        return this.root.radicals.replace(re, '<span class="highlight">$1</span>')
      } else {
        return this.root.radicals
      }
    }
  }
})
</script>
