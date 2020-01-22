<template>
  <div>
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.ts'

export default mixins(I18N).extend({
  mounted (): void {
    this.$store.dispatch('setTitle', { key: 'not_found.title' })
    this.$store.dispatch('addError', this.__('not_found'))
  }
})
</script>
