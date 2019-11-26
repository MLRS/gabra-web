<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="root === null"></i>

    <div class="row" v-if="root !== null && root.radicals">

      <div class="col-12">
        <h1 class="mb-3">
          <!-- {{ __('root.title') }}: -->
          <span class="surface_form">
            {{ root.radicals }}
            <sup class="text-muted" v-if="root.variant">
              {{ root.variant }}
            </sup>
          </span>
        </h1>
      </div>

      <!-- details -->
      <div class="col-md-4">
        <dl>

          <dt>{{ __('Class') }}</dt>
          <dd>...</dd>

          <dt>{{ __('Source(s)') }}</dt>
          <dd>
            ...
          </dd>

        </dl>
      </div>

      <!-- lexemes -->
      <div class="col-md-8">

        <h2 class="h6">{{ __('Lexemes') }}</h2>

        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexemes === null"></i>

        <table class="table table-sm">
          <tbody>
            <tr v-for="lexeme,ix in lexemes" :key="ix">
              <td class="surface_form">
                <router-link :to="{ name: 'lexeme', params: { id: lexeme._id } }" class="">
                  {{ lexeme.lemma }}
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>

      </div>

    </div><!-- row -->
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.ts'
import axios from 'axios'

interface Data {
  root: Root | null
  lexemes: Lexeme[] | null
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
      root: null,
      lexemes: []
    }
  },
  watch: {
    '$route.query': {
      handler: function (): void {
        this.load()
      },
      immediate: true
    }
  },
  computed: {
  },
  methods: {
    // get root and lexemes
    load: function (): void {
      let path = this.$route.params.variant ? `${this.$route.params.radicals}/${this.$route.params.variant}` : `${this.$route.params.radicals}`
      axios.get(`${process.env.VUE_APP_API_URL}/roots/${path}`)
        .then(response => {
          this.root = response.data
        })
        .catch(error => {
          console.error(error)
          this.root = {} as Root
        })
      axios.get(`${process.env.VUE_APP_API_URL}/roots/lexemes/${path}`)
        .then(response => {
          this.lexemes = response.data
        })
        .catch(error => {
          console.error(error)
          this.lexemes = []
        })
    }
  },
  mounted: function () {
  }
})
</script>
