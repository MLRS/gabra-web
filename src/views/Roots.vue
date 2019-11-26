<template>
  <div>
    <h1 class="h3" v-show="results === null">{{ __('Root search') }}</h1>
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N, { __l, Language } from '@/components/I18N.ts'

interface Data {
  results: null | any[]
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      results: null
    }
  },
  mounted (): void {
    this.$emit('setTitle', __l(this.language as Language, 'Root search'))
  }
})
</script>
