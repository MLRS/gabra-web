<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexeme === null"></i>

    <div class="row" v-if="lexeme !== null && lexeme.lemma">

      <div class="col-12">
        <h1 class="surface_form mb-3">{{ lexeme.lemma }}</h1>
      </div>

      <!-- lexeme -->
      <div class="col-md-4">
        <dl>

          <dt>{{ __('Part of speech') }}</dt>
          <dd>{{ __(`pos.${lexeme.pos }`) }}</dd>

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
              <Root :root="lexeme.root"></Root>
            </dd>
          </template>

        </dl>
      </div>

      <!-- wordforms -->
      <div class="col-md-8">

        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="wordforms === null"></i>

        <h2 class="h6">{{ __('Word forms') }}</h2>

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
import I18N, { __l, Language } from '@/components/I18N.ts'
import Root from '@/components/Root.vue'
import axios from 'axios'

interface Data {
  lexeme: Lexeme | null
  wordforms: Wordform[] | null
}

export default mixins(I18N).extend({
  components: {
    Root
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      lexeme: null,
      wordforms: null
    }
  },
  watch: {
    '$route.query': {
      handler (): void {
        this.load()
      },
      immediate: true
    }
  },
  computed: {
  },
  methods: {
    // get lexeme and wordforms
    load (): void {
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/${this.$route.params.id}`)
        .then(response => {
          this.lexeme = response.data
          this.$emit('setTitle', (this.lexeme as Lexeme).lemma)
        })
        .catch(error => {
          console.error(error)
          this.lexeme = {} as Lexeme
        })
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/wordforms/${this.$route.params.id}`)
        .then(response => {
          this.wordforms = response.data
        })
        .catch(error => {
          console.error(error)
          this.wordforms = []
        })
    }
  },
  mounted (): void {
    this.$emit('setTitle', __l(this.language as Language, 'Lexeme'))
  }
})
</script>
