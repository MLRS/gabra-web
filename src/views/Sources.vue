<template>
  <div>
    <h3>{{ __('Sources') }}</h3>
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.ts'

interface Data {
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
    }
  }
})
</script>
