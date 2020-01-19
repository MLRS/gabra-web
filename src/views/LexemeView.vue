<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexeme === null"></i>

    <div class="row" v-if="lexeme !== null && lexeme.lemma">

      <div class="col-12">
        <h1 class="surface_form text-shadow mb-3">
          {{ lexeme.lemma }}
          <small v-if="lexeme.alternatives" class="alternative">
            ({{ lexeme.alternatives.join(', ') }})
          </small>
        </h1>
      </div>

      <!-- lexeme -->
      <div class="col-md-4">
        <dl>

          <template v-if="lexeme.phonetic">
            <dt>{{ __('phonetic') }}</dt>
            <dd>
              /{{ lexeme.phonetic }}/
            </dd>
          </template>

          <dt>{{ __('part_of_speech') }}</dt>
          <dd>
            {{ __(`pos.${lexeme.pos }`) }}
          </dd>

          <dt>{{ __('english_gloss') }}</dt>
          <dd>
            <ul class="pl-4">
              <li v-for="g,ix in lexeme.glosses" :key="ix">
                {{ g.gloss }}
              </li>
            </ul>
          </dd>

          <template v-if="lexeme.root">
            <dt>{{ __('root') }}</dt>
            <dd>
              <Root :root="lexeme.root"></Root>
            </dd>
          </template>

          <dt>{{ __('features') }}</dt>
          <dd>
            <div>{{ lexeme.derived_form ? __('derived_form') + ' ' + derivedForm(lexeme.derived_form) : '' }}</div>
            <div>{{ lexeme.frequency }}</div>
            <div>{{ lexeme.onomastic_type }}</div>
            <div>{{ lexeme.transitive ? __('transitive') : '' }}</div>
            <div>{{ lexeme.intransitive ? __('intransitive') : '' }}</div>
            <div>{{ lexeme.ditransitive ? __('ditransitive') : '' }}</div>
            <div>{{ lexeme.hypothetical ? __('hypothetical') : '' }}</div>
          </dd>

          <dt>{{ __('source') }}</dt>
          <dd>
            <template v-for="s,ix in lexeme.sources">
              <router-link :to="{ name: 'sources' }" class="" :key="ix">{{ s }}</router-link>
              <span v-if="ix < lexeme.sources.length - 1" :key="ix+'comma'">, </span>
            </template>
          </dd>

          <dt>{{ __('related_entries') }}</dt>

          <dd>
            <div v-for="r,ix in related" :key="ix">
            <router-link :to="{ name: 'lexeme', params: { id: r._id } }" class="surface_form">{{ r.lemma }}</router-link>
            <span class="text-lighter ml-1">
              {{ __(`pos.${r.pos }`) }}
              {{ derivedForm(r.derived_form) }}
            </span>
          </div>
          </dd>

        </dl>
      </div>

      <!-- wordforms -->
      <div class="col-md-8">

        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="wordforms === null"></i>

        <!-- <h2 class="h6 text-capitalize font-weight-bold">{{ __('word_forms') }}</h2> -->

        <wordforms-table :lexeme="lexeme" :wordforms="wordforms"></wordforms-table>

      </div>

    </div><!-- row -->
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'

import I18N from '@/components/I18N.ts'
import Root from '@/components/Root.vue'
import WordformsTable from '@/components/WordformsTable.vue'
import * as UI from '@/helpers/UI.ts'

import axios from 'axios'

interface Data {
  lexeme: Lexeme | null
  wordforms: Wordform[] | null
  related: Lexeme[] | null
}

export default mixins(I18N).extend({
  components: {
    Root,
    WordformsTable
  },
  data (): Data {
    return {
      lexeme: null,
      wordforms: null,
      related: null
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
          this.$store.dispatch('addError', error)
          this.lexeme = {} as Lexeme
        })
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/wordforms/${this.$route.params.id}?pending=1`)
        .then(response => {
          this.wordforms = response.data
        })
        .catch(error => {
          this.$store.dispatch('addError', error)
          this.wordforms = []
        })
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/related/${this.$route.params.id}`)
        .then(response => {
          this.related = response.data
        })
        .catch(error => {
          this.$store.dispatch('addError', error)
          this.related = []
        })
    },
    // Making helper functions available to template
    derivedForm: UI.derivedForm,
    agr: UI.agr
  },
  mounted (): void {
    this.$store.dispatch('setTitle', { key: 'title.lexeme' })
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

dt {
  @extend .text-capitalize;
}
</style>
