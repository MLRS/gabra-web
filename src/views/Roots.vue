<template>
  <div>
    <h3 v-show="results === null">{{ __('Root search') }}</h3>
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.vue'

interface Data {
  results: null | any[]
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
      results: null
    }
  }
})
</script>
