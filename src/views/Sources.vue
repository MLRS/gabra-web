<template>
  <div>
    <h1 class="h3">{{ __('Sources') }}</h1>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>

    <table class="table table-striped mt-3">
      <thead>
        <tr>
          <th>{{ __('Key') }}</th>
          <th>{{ __('Title') }}</th>
          <th>{{ __('Author') }}</th>
          <th>{{ __('Year') }}</th>
          <th>{{ __('Note') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s,ix in sources" :key="ix">
          <td>{{ s.key }}</td>
          <td>{{ s.title }}</td>
          <td>{{ s.author }}</td>
          <td>{{ s.year }}</td>
          <td>{{ s.note }}</td>
        </tr>
      </tbody>
    </table>

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N, { __l, Language } from '@/components/I18N.ts'
import axios from 'axios'

interface Data {
  sources: Source[]
  working: boolean
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      sources: [],
      working: false
    }
  },
  mounted (): void {
    this.$emit('setTitle', __l(this.language as Language, 'Sources'))

    this.working = true // TODO might need to wait for browser render
    axios.get(`${process.env.VUE_APP_API_URL}/sources`)
      .then(response => {
        this.sources = response.data
      })
      .catch(error => {
        console.error(error)
      })
      .then(() => {
        this.working = false
      })
  }
})
</script>
