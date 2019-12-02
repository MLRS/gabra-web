<template>
  <div>
    <h1 class="h3">{{ __('Sources') }}</h1>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>

    <table class="table mt-3">
      <tbody>
        <tr v-for="s,ix in sources" :key="ix">
          <th>{{ s.key }}</th>
          <td>{{ s.title }}<td>
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
        console.error(error)
      })
      .then(() => {
        this.working = false
      })
  }
})
</script>
