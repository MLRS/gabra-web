<template>
  <div>

    <div class="row" v-show="results === null">

      <div class="col-md-6">
        <h3>{{ __('Advanced search') }}</h3>

        <form @submit.prevent="submitSearch">
          <div class="form-group mt-3">
            <div class="input-group">
              <Keyboard></Keyboard>
              <input type="search" name="s" class="form-control" autofocus="true"
                :placeholder="__('Search')"
                @keydown.enter="$event.stopPropagation()"
                v-model="term"
                />
            </div><!-- input-group -->
          </div><!-- input-group -->

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" name="l" checked="checked" value="1" id="LexemeL"/>
            <label for="LexemeL">{{ __('Search lemmas') }}</label>
          </div>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" name="wf" checked="checked" value="1" id="LexemeWf"/>
            <label for="LexemeWf">{{ __('Search wordforms') }}</label>
          </div>

          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="g" checked="checked" value="1" id="LexemeG"/>
            <label for="LexemeG">{{ __('Search in English glosses') }}</label>
          </div>

          <div class="form-group">
            <label for="LexemePos">{{ __('Part of speech') }}</label>
            <select name="pos" class="form-control" id="LexemePos">
              <option value="">All</option>
              <option v-for="p in pos" :key="p" :value="p">{{ __(`pos.${p}`) }}</option>
            </select>
          </div>

          <div class="form-group">
            <label for="LexemeSource">{{ __('Source') }}</label>
            <select name="source" class="form-control" id="LexemeSource">
              <option value="">All</option>
              <option v-for="s in sources" :key="s" :value="s">{{ s }}</option>
            </select>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search mr-1"></i>
            {{ __('Search') }}
          </button>
        </form>
      </div>
      <div class="col-md-6">
        <h4>{{ __('search.help.title') }}</h4>
        <ul>
          <li v-html="markdownInline(__('search.help.1', ['hareg', 'ħareġ']))"></li>
          <li v-html="markdownInline(__('search.help.2'))"></li>
          <li v-html="markdownInline(__('search.help.3'))"></li>
          <li v-html="markdownInline(__('search.help.4', ['itbet', 'kitbet']))"></li>
        </ul>
      </div>

    </div><!-- search form -->

    <div v-if="results !== null">
      <h3>{{ __('results', { '0': results.length }) }}</h3>

      <div v-for="item,ix in results" :key="ix">
        {{ item }}
      </div>
    </div><!-- search reuslts -->

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.vue'
import Keyboard from '@/components/Keyboard.vue'

interface Data {
  results: null | any[]
  pos: string[]
  sources: string[]
}

export default mixins(I18N).extend({
  components: {
    Keyboard
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
      results: null,
      pos: ['ADJ', 'ADP', 'ADV', 'AUX', 'CONJ', 'DET', 'INTJ', 'NOUN', 'NUM', 'PART', 'PRON', 'PROPN', 'PUNCT', 'SCONJ', 'SYM', 'VERB', 'X'],
      sources: ['Spagnol2011', 'Ellul2013', 'Mayer2013', 'Falzon2013', 'Camilleri2013', 'UserFeedback', 'KelmaKelma', 'KelmetilMalti', 'Apertium2014', 'DM2015', 'IATE2016']
    }
  },
  computed: {
    term: function (): string | null {
      return this.$route.query.s as string || null
    }
  },
  methods: {
    submitSearch: function (): void {
      // if (this.term) {
      //   this.$router.push({ name: 'lexemes', query: { s: this.term } })
      // }
      this.results = ['hello', 'I\'m', 'a', 'result']
    }
  }
})
</script>
