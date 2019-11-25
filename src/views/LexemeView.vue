<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>

    <div class="row" v-if="lexeme">

      <div class="col-12">
        <h1 class="surface_form">{{ lexeme.lemma }}</h1>
      </div>

      <!-- lexeme -->
      <div class="col-md-4">
        <dl>

          <dt>{{ __('Part of speech') }}</dt>
          <dd>{{ lexeme.pos }}</dd>

          <dt>{{ __('Gloss') }}</dt>
          <dd>
            <ul class="pl-4">
              <li v-for="g,ix in lexeme.glosses" :key="ix">
                {{ g.gloss }}
              </li>
            </ul>
          </dd>

          <template v-if="lexeme.root">
            <dt>{{ __('Root') }}</dt>
            <dd>
              <router-link :to="{ path: 'roots/' + lexeme.root.radicals }" class="text-nowrap">
                {{ lexeme.root.radicals }}
                {{ lexeme.root.variant }}
              </router-link>
            </dd>
          </template>

        </dl>
      </div>

      <!-- wordforms -->
      <div class="col-md-8">

        <table class="table table-sm">
          <tbody>
            <tr v-for="wf,ix in wordforms" :key="ix">
              <td class="surface_form">
                  {{ wf.surface_form }}
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
  lexeme: Lexeme | null
  wordforms: Wordform[]
  working: boolean
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
      lexeme: null,
      wordforms: [],
      working: false
    }
  },
  watch: {
    // Populate local search object whenever URL changes
    '$route.query': {
      handler: function (): void {
        this.loadLexeme()
      },
      immediate: true
    }
  },
  computed: {
  },
  methods: {
    // get lexeme and wordforms
    loadLexeme: function (): void {
      this.working = true // TODO might need to wait for browser render
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/${this.$route.params.id}`)
        .then(response => {
          this.lexeme = response.data
          axios.get(`${process.env.VUE_APP_API_URL}/lexemes/wordforms/${(this.lexeme as Lexeme)._id}`)
            .then(resp => {
              this.wordforms = resp.data
            })
        })
        .catch(error => {
          console.error(error)
        })
        .then(() => {
          this.working = false
        })
    }
  },
  mounted: function () {
  }
})
</script>
