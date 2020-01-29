<template>
  <div>
    <h1 class="h3">{{ __('Sources') }}</h1>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>

    <div class="mt-3">
      <div class="row border-top py-2" v-for="s,ix in sources" :key="ix">
        <div class="col-md-2 font-weight-bold">{{ s.key }}</div>
        <div class="col-md-3">{{ s.title }}</div>
        <div class="col-md-2">{{ s.author }}</div>
        <div class="col-md-1">{{ s.year }}</div>
        <div class="col-md-4" style="word-wrap: break-word">{{ s.note }}</div>
      </div>
    </div>

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.ts'
import axios from 'axios'

interface Data {
  sources: Source[]
  working: boolean
}

export default mixins(I18N).extend({
  components: {
  },
  data (): Data {
    return {
      sources: [],
      working: false
    }
  },
  mounted (): void {
    this.$store.dispatch('setTitle', { key: 'Sources' })

    this.working = true
    axios.get(`${process.env.VUE_APP_API_URL}/sources`)
      .then(response => {
        this.sources = response.data
      })
      .catch(error => {
        this.$store.dispatch('addError', error)
      })
      .then(() => {
        this.working = false
      })
  }
})
</script>
