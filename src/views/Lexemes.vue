<template>
  <div>

    <div class="row" v-show="!isSearching">

      <div class="col-md-6">
        <h1 class="h3">{{ __('Advanced search') }}</h1>

        <form @submit.prevent="submitSearch">
          <SearchInput
          :placeholder="__('search.placeholder')"
          :showSubmit="false"
          @update="(s) => { search.s = s }"
          class="my-4"
          ></SearchInput>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="search.l" id="LexemeL"/>
            <label for="LexemeL">{{ __('search.option.lemmas') }}</label>
          </div>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="search.wf" id="LexemeWf"/>
            <label for="LexemeWf">{{ __('search.option.wordforms') }}</label>
          </div>

          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" v-model="search.g" id="LexemeG"/>
            <label for="LexemeG">{{ __('search.option.glosses') }}</label>
          </div>

          <div class="form-group">
            <label for="LexemePos">{{ __('search.option.pos') }}</label>
            <select v-model="search.pos" class="form-control" id="LexemePos">
              <option value=""></option>
              <option v-for="p in pos" :key="p" :value="p">{{ __(`pos.${p}`) }}</option>
            </select>
          </div>

          <div class="form-group">
            <label for="LexemeSource">{{ __('search.option.source') }}</label>
            <select v-model="search.source" class="form-control" id="LexemeSource">
              <option value=""></option>
              <option v-for="s in sources" :key="s" :value="s">{{ s }}</option>
            </select>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search mr-1"></i>
            {{ __('search.button') }}
          </button>
        </form>
      </div>
      <div class="col-md-6">
        <h2 class="h4">{{ __('search.help.title') }}</h2>
        <ul>
          <li v-html="markdownInline(__('search.help.1', ['hareg', 'ħareġ']))"></li>
          <li v-html="markdownInline(__('search.help.2'))"></li>
          <li v-html="markdownInline(__('search.help.3'))"></li>
          <li v-html="markdownInline(__('search.help.4', ['itbet', 'kitbet']))"></li>
        </ul>
      </div>

    </div><!-- search form -->

    <div v-if="isSearching">
      <h3 class="h4" v-html="__('results.title', {
        term: `<span class='text-red'>${search.s}</span>`,
        showing: working ? '…' : results.length,
        total: working ? '…' : resultCount
        })"></h3>

      <p class="text-muted" v-show="searchSuggestions.length > 0">
        {{ __('search.suggestion') }}
        <router-link v-for="s,ix in searchSuggestions" :key="ix" :to="{ query: { s: s } }" class="">
          {{ s }}
        </router-link>
      </p>

      <button
        class="btn btn-link p-0"
        v-show="!working && results.length === 0 && !suggest.show && !suggest.done"
        @click="suggest.show = true">
        {{ __('suggest.link', [search.s]) }}
      </button>

      <p v-show="suggest.done">
        {{ __('suggest.done') }}
      </p>

      <div class="border shadow my-3 p-3 rounded-lg" v-show="suggest.show">
        <suggest
          :word="search.s"
          :pos_options="pos"
          @hide="suggest.show = false; $store.dispatch('clearMessages')"
          @done="suggest.done = true"></suggest>
      </div>

      <div class="mt-3">
        <div v-for="item,ix in results" :key="ix" class="row mt-2 pt-2" :class="{ 'border-top': ix > 0 }">
          <div class="col-1 text-lighter text-center">{{ ix+1 }}.</div>
          <div class="col-11 col-sm-2 font-weight-normal surface_form">
            <router-link :to="{ name: 'lexeme', params: { id: item.lexeme._id } }" class="font-size-large">
              {{ item.lexeme.lemma }}
            </router-link>
            <div v-if="item.lexeme.alternatives" class="alternative">
              ({{ item.lexeme.alternatives.join(', ') }})
            </div>
          </div>
          <div class="col-11 offset-1 col-sm-2 offset-sm-0">
            <div v-if="item.lexeme.pos">
              {{ __(`pos.${item.lexeme.pos }`) }}
              <!-- {{ derivedForm(item.lexeme.derived_form) }} -->
            </div>
            <Root :root="item.lexeme.root" class="d-block"></Root>
            <!-- <div class="text-lighter">
              {{ item.lexeme.transitive ? __('transitive') : '' }}
              {{ item.lexeme.intransitive ? __('intransitive') : '' }}
              {{ item.lexeme.ditransitive ? __('ditransitive') : '' }}
              {{ item.lexeme.hypothetical ? __('hypothetical') : '' }}
            </div> -->
            <div>
              {{ item.lexeme.frequency }}
            </div>
          </div>
          <div class="col-11 offset-1 col-sm-3 offset-sm-0 my-2 my-sm-0">
            <template v-if="item.lexeme.glosses">
              <div v-for="g,ix in item.lexeme.glosses.slice(0,5)" :key="ix">
                {{ g.gloss }}
              </div>
              <div v-if="item.lexeme.glosses > 5">
                ⋮
              </div>
            </template>
          </div>
          <div class="col-11 offset-1 col-sm-4 offset-sm-0">
            <i class="fas fa-circle-notch fa-spin text-danger" v-show="item.wordforms === null"></i>
            <template v-if="item.wordforms !== null">
              <div v-for="wf,ix in item.wordforms.slice(0,5)" :key="ix">
                <span class="surface_form mr-2">
                  {{ wf.surface_form }}
                </span>
                <span class="text-lighter">
                  <!-- Noun -->
                  {{ wf.number }}
                  {{ wf.gender }}

                  <!-- Verb -->
                  {{ wf.aspect }}
                  {{ wf.subject ? agr(wf['subject']) : '' }}
                  {{ wf.dir_obj ? `&middot; dir: `+agr(wf['dir_obj']) : '' }}
                  {{ wf.ind_obj ? `&middot; ind: `+agr(wf['ind_obj']) : '' }}
                  {{ wf.polarity ? `&middot; ${wf.polarity}` : '' }}
                </span>
              </div>
              <div v-if="item.wordforms.length > 5" class="small">
                <router-link :to="{ name: 'lexeme', params: { id: item.lexeme._id } }">
                  {{ __('more_word_forms') }}…
                </router-link>
              </div>
            </template>
          </div>
        </div><!-- row -->
      </div>

      <div class="text-center my-5">
        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="working"></i>
        <button class="btn btn-primary" @click="loadResults()" v-show="!working && moreResults">
          {{ __('search.button.more_results') }}
        </button>
      </div>

    </div><!-- search reuslts -->

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'

import I18N from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'
import Suggest from '@/components/Suggest.vue'
import Root from '@/components/Root.vue'
import * as UI from '@/helpers/UI.ts'

import axios from 'axios'

function query2bool (val: any, def: boolean = true): boolean {
  if (val !== undefined) {
    return val === '1' || val === 'true'
  } else {
    return def
  }
}

function bool2query (val: boolean): string {
  return val ? '1' : '0'
}

interface Data {
  pos: string[] // for dropdown
  sources: string[] // for dropdown
  search: {
    s: string
    l: boolean
    wf: boolean
    g: boolean
    pos: string | null
    source: string | null
  },
  page: number // last page retrieved
  working: boolean
  results: Result[]
  resultCount: number // as reported by server, only equal to resuls.length when all results loaded
  searchSuggestions: string[]
  suggest: {
    show: boolean // is suggest modal showing
    done: boolean // successfully submitted
  }
}

interface Result {
  lexeme: Lexeme
  wordforms: Wordform[] | null
}

export default mixins(I18N).extend({
  components: {
    SearchInput,
    Suggest,
    Root
  },
  data (): Data {
    return {
      pos: ['ADJ', 'ADP', 'ADV', 'AUX', 'CONJ', 'DET', 'INTJ', 'NOUN', 'NUM', 'PART', 'PRON', 'PROPN', 'PUNCT', 'SCONJ', 'SYM', 'VERB', 'X'],
      sources: [], // loaded async
      search: {
        // These default values are overwritten by watch below
        s: '',
        l: true,
        wf: true,
        g: true,
        pos: null,
        source: null
      },
      page: 0,
      working: false,
      results: [],
      resultCount: 0,
      searchSuggestions: [],
      suggest: {
        show: false,
        done: false
      }
    }
  },
  watch: {
    // Populate local search object whenever URL changes
    '$route.query': {
      handler (): void {
        this.search = {
          s: this.$route.query.s as string || '',
          l: query2bool(this.$route.query.l),
          wf: query2bool(this.$route.query.wf),
          g: query2bool(this.$route.query.g),
          pos: this.$route.query.pos as string || null,
          source: this.$route.query.source as string || null
        }
        if (this.isSearching) {
          this.results = []
          this.resultCount = 0
          this.page = 0
          this.loadResults()
          this.searchSuggest()
          this.$store.dispatch('setTitle', { key: 'title.search', replacements: [this.search.s] })
        } else {
          this.$store.dispatch('setTitle', { key: 'Advanced search' })
        }
      },
      immediate: true
    }
  },
  computed: {
    isSearching (): boolean {
      return this.$route.query.s !== undefined && this.$route.query.s !== null && this.$route.query.s !== ''
    },
    moreResults (): boolean {
      return this.results.length < this.resultCount
    }
  },
  methods: {
    // the form is submitted
    submitSearch (): void {
      this.$router.push({
        query: {
          s: this.search.s,
          l: bool2query(this.search.l),
          wf: bool2query(this.search.wf),
          g: bool2query(this.search.g),
          pos: this.search.pos,
          source: this.search.source
        }
      })
    },
    // get results
    loadResults (): void {
      this.working = true
      this.suggest.show = false
      this.suggest.done = false
      this.$store.dispatch('clearMessages')
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/search`, {
        params: {
          s: this.search.s,
          page: ++this.page
        } })
        .then(response => {
          response.data.results.forEach((r: Result) => {
            r.wordforms = null // not loaded
            this.results.push(r)
            axios.get(`${process.env.VUE_APP_API_URL}/lexemes/wordforms/${r.lexeme._id}`)
              .then(resp => {
                r.wordforms = resp.data
              })
              .catch(error => {
                this.$store.dispatch('addError', error)
                r.wordforms = []
              })
          })
          this.resultCount = response.data.query.result_count
        })
        .catch(error => {
          this.$store.dispatch('addError', error)
        })
        .then(() => {
          this.working = false
        })
    },
    // search suggestions
    searchSuggest (): void {
      axios.get(`${process.env.VUE_APP_API_URL}/lexemes/search_suggest`, {
        params: {
          s: this.search.s
        } })
        .then(response => {
          this.searchSuggestions = response.data.results.map((r: Lexeme) => r.lemma)
        })
        .catch(error => {
          console.error(error)
        })
    },
    // Making helper functions available to template
    derivedForm: UI.derivedForm,
    agr: UI.agr
  },
  mounted (): void {
    // https://renatello.com/check-if-a-user-has-scrolled-to-the-bottom-in-vue-js/
    window.onscroll = () => {
      let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
      if (bottomOfWindow && !this.working && this.moreResults) {
        this.loadResults()
      }
    }

    axios.get(`${process.env.VUE_APP_API_URL}/sources`)
      .then(response => {
        this.sources = response.data.map((s: Source) => s.key)
      })
      .catch(error => {
        console.error(error)
      })
  }
})
</script>
