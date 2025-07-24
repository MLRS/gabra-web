<script setup lang="ts">
import axios from 'axios'
import { computed, reactive, onMounted, watch } from 'vue'

import { __, markdownInline } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'
import Suggest from '@/components/Suggest.vue'
import Root from '@/components/Root.vue'
import Highlight from '@/components/Highlight.vue'
import * as UI from '@/helpers/UI.ts'

import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

function query2bool (val?: string, def: boolean = true): boolean {
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
    pending: boolean
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

const data = reactive<Data>({
  pos: ['ADJ', 'ADP', 'ADV', 'AUX', 'CONJ', 'DET', 'INTJ', 'NOUN', 'NUM', 'PART', 'PRON', 'PROPN', 'PUNCT', 'SCONJ', 'SYM', 'VERB', 'X'],
  sources: [], // loaded async
  search: {
    // These default values are overwritten by watch below
    s: '',
    l: true,
    wf: true,
    g: true,
    pos: null,
    source: null,
    pending: false
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
})

const isSearching = computed<boolean>(() => {
  return route.query.s !== undefined && route.query.s !== null && route.query.s !== ''
})

const moreResults = computed<boolean>(() => {
  return data.results.length < data.resultCount
})

const searchSuggestionsFiltered = computed<string[]>(() => {
  return data.searchSuggestions.filter((s: string) =>
    !data.results.some((r: Result) => r.lexeme.lemma === s)
  )
})

// Populate local search object whenever URL changes
watch(
  () => route.query,
  () => {
    data.search = {
      s: route.query.s as string || '',
      l: query2bool(route.query.l as string | undefined, true),
      wf: query2bool(route.query.wf as string | undefined, true),
      g: query2bool(route.query.g as string | undefined, true),
      pos: route.query.pos as string || null,
      source: route.query.source as string || null,
      pending: query2bool(route.query.pending as string | undefined, false)
    }
    if (isSearching.value) {
      data.results = []
      data.resultCount = 0
      data.page = 0
      loadResults()
      searchSuggest()
      store.setTitle({ key: 'title.search', replacements: [data.search.s] })
    } else {
      store.setTitle({ key: 'Advanced search' })
    }
  },
  {
    immediate: true
  }
)

// the form is submitted
function submitSearch() {
  router.push({
    query: {
      s: data.search.s,
      l: bool2query(data.search.l),
      wf: bool2query(data.search.wf),
      g: bool2query(data.search.g),
      pos: data.search.pos,
      source: data.search.source,
      pending: bool2query(data.search.pending)
    }
  })
}

// get results
function loadResults() {
  data.working = true
  data.suggest.show = false
  data.suggest.done = false
  store.clearMessages()
  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/search`, {
    params: {
      s: data.search.s,
      l: bool2query(data.search.l),
      wf: bool2query(data.search.wf),
      g: bool2query(data.search.g),
      pos: data.search.pos,
      source: data.search.source,
      pending: bool2query(data.search.pending),
      page: ++data.page
    } })
    .then(response => {
      response.data.results.forEach((r: Result) => {
        r.wordforms = null // not loaded
        data.results.push(r)
        axios.get(`${import.meta.env.VITE_API_URL}/lexemes/wordforms/${r.lexeme._id}`)
          .then(resp => {
            r.wordforms = resp.data // TODO this doesn't update reactively
          })
          .catch(error => {
            store.addError(error)
            r.wordforms = []
          })
      })
      data.resultCount = response.data.query.result_count
    })
    .catch(error => {
      store.addError(error)
    })
    .then(() => {
      data.working = false
    })
}

// search suggestions
function searchSuggest() {
  data.searchSuggestions = []
  // axios.get(`${import.meta.env.VITE_API_URL}/lexemes/search_suggest`, {
  //   params: {
  //     s: this.search.s
  //   } })
  //   .then(response => {
  //     response.data.results.forEach((r: {lexeme: Lexeme}) => {
  //       if (!this.searchSuggestions.includes(r.lexeme.lemma)) {
  //         this.searchSuggestions.push(r.lexeme.lemma)
  //       }
  //     })
  //   })
  //   .catch(error => {
  //     console.error(error)
  //   })
  axios.get(`${import.meta.env.VITE_API_URL}/wordforms/search_suggest`, {
    params: {
      s: data.search.s
    } })
    .then(response => {
      response.data.results.forEach((r: {wordform: Wordform}) => {
        if (!data.searchSuggestions.includes(r.wordform.surface_form)) {
          data.searchSuggestions.push(r.wordform.surface_form)
        }
      })
    })
    .catch(error => {
      console.error(error)
    })
}

onMounted(() => {
  // https://renatello.com/check-if-a-user-has-scrolled-to-the-bottom-in-vue-js/
  window.onscroll = () => {
    const bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
    if (bottomOfWindow && !data.working && moreResults.value) {
      loadResults()
    }
  }

  axios.get(`${import.meta.env.VITE_API_URL}/sources`)
    .then(response => {
      data.sources = response.data.map((s: Source) => s.key)
    })
    .catch(error => {
      console.error(error)
    })
})
</script>

<template>
  <div>

    <div class="row" v-show="!isSearching">

      <div class="col-md-6 mb-4">
        <h1 class="h3">{{ __('Advanced search') }}</h1>

        <form @submit.prevent="submitSearch">
          <SearchInput
          :placeholder="__('search.placeholder')"
          :showSubmit="false"
          @update="(s) => { data.search.s = s }"
          class="my-4"
          ></SearchInput>

          <div class="mb-3 form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="data.search.l" id="LexemeL"/>
            <label for="LexemeL">{{ __('search.option.lemmas') }}</label>
          </div>

          <div class="mb-3 form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="data.search.wf" id="LexemeWf"/>
            <label for="LexemeWf">{{ __('search.option.wordforms') }}</label>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" v-model="data.search.g" id="LexemeG"/>
            <label for="LexemeG">{{ __('search.option.glosses') }}</label>
          </div>

          <div class="mb-3">
            <label class="mb-2" for="LexemePos">{{ __('search.option.pos') }}</label>
            <select v-model="data.search.pos" class="form-select" id="LexemePos">
              <option value=""></option>
              <option v-for="p in data.pos" :key="p" :value="p">{{ __(`pos.${p}`) }}</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="mb-2" for="LexemeSource">{{ __('search.option.source') }}</label>
            <select v-model="data.search.source" class="form-select" id="LexemeSource">
              <option value=""></option>
              <option v-for="s in data.sources" :key="s" :value="s">{{ s }}</option>
            </select>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" v-model="data.search.pending" id="LexemePending"/>
            <label for="LexemePending">{{ __('search.option.pending') }}</label>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search me-1"></i>
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
        term: `<span class='highlight'>${data.search.s}</span>`,
        showing: data.working ? '…' : `${data.results.length}`,
        total: data.working ? '…' : `${data.resultCount}`
        })"></h3>

      <p class="text-muted" v-show="searchSuggestionsFiltered.length > 0">
        {{ __('search.suggestion') }}
        <router-link v-for="s,ix in searchSuggestionsFiltered" :key="ix" :to="{ query: { s: s } }" class="">
          {{ s }}
        </router-link>
      </p>

      <button
        class="btn btn-link p-0"
        v-show="!data.working && data.results.length === 0 && !data.suggest.show && !data.suggest.done"
        @click="data.suggest.show = true">
        {{ __('suggest.link', [data.search.s]) }}
      </button>

      <p v-show="data.suggest.done">
        {{ __('suggest.done') }}
      </p>

      <div class="border shadow my-3 p-3 rounded-3" v-show="data.suggest.show">
        <suggest
          :word="data.search.s"
          :pos_options="data.pos"
          @hide="data.suggest.show = false; store.clearMessages()"
          @done="data.suggest.done = true"></suggest>
      </div>

      <div class="mt-3">
        <div v-for="item,ix in data.results" :key="ix" class="row mt-2 pt-2" :class="{ 'border-top': ix > 0 }">
          <div class="col-1 text-lighter text-center">{{ ix+1 }}.</div>
          <div class="col-11 col-sm-2 fw-normal surface_form">
            <router-link :to="{ name: 'lexeme', params: { id: item.lexeme._id } }">
              <highlight :text="item.lexeme.lemma" :match="data.search.l ? data.search.s : null" />
            </router-link>
            <div v-if="item.lexeme.alternatives" class="alternative">
              (<highlight :text="item.lexeme.alternatives.join(', ')" :match="data.search.l ? data.search.s : null" />)
            </div>
          </div>
          <div class="col-11 offset-1 col-sm-2 offset-sm-0">
            <div v-if="item.lexeme.pos">
              {{ __(`pos.${item.lexeme.pos }`) }}
              <!-- {{ UI.derivedForm(item.lexeme.derived_form) }} -->
            </div>
            <Root v-if="item.lexeme.root" :root="item.lexeme.root" class="d-block"></Root>
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
                <highlight :text="g.gloss" :match="data.search.g ? data.search.s : null" />
              </div>
              <div v-if="item.lexeme.glosses.length > 5">
                ⋮
              </div>
            </template>
          </div>
          <div class="col-11 offset-1 col-sm-4 offset-sm-0">
            <i class="fas fa-circle-notch fa-spin text-danger" v-show="item.wordforms === null"></i>
            <template v-if="item.wordforms !== null">
              <div v-for="wf,ix in item.wordforms.slice(0,5)" :key="ix">
                <span class="surface_form me-2">
                  <highlight :text="wf.surface_form" :match="data.search.wf ? data.search.s : null" />
                </span>
                <span class="text-lighter">
                  <!-- Noun -->
                  {{ wf.number }}
                  {{ wf.gender }}

                  <!-- Verb -->
                  {{ wf.aspect }}
                  {{ wf.subject ? UI.agr(wf['subject']) : '' }}
                  {{ wf.dir_obj ? `&middot; dir: `+UI.agr(wf['dir_obj']) : '' }}
                  {{ wf.ind_obj ? `&middot; ind: `+UI.agr(wf['ind_obj']) : '' }}
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
        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="data.working"></i>
        <button class="btn btn-primary" @click="loadResults()" v-show="!data.working && moreResults">
          {{ __('search.button.more_results') }}
        </button>
      </div>

    </div><!-- search results -->

  </div>
</template>
