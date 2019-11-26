<template>
  <router-link :to="{ name: 'root', params: { radicals: root.radicals, variant: root.variant } }" class="text-nowrap" v-if="root">
    {{ root.radicals }}
    <sup class="text-muted" v-if="root.variant">
      {{ root.variant }}
    </sup>
  </router-link>
</template>

<script lang="ts">
import Vue from 'vue'

interface Data {
}

export default Vue.extend({
  props: {
    root: Object // Root
  },
  data (): Data {
    return {
    }
  },
  methods: {
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

</style>
