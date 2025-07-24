<script setup lang="ts">
import axios from 'axios'
import { computed, reactive, onMounted, watch } from 'vue'

import { __, markdown } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'
import Root from '@/components/Root.vue'

import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

interface Data {
  types: string[] // for dropdown
  consonants: string[] // for dropdown
  search: { // linked to inputs
    s: string // free input query
    c1: string // consonant drop-downs
    c2: string
    c3: string
    c4: string
    t: string // type
  },
  term: string | null // actual term computed and returned from API
  page: number // last page retrieved
  working: boolean
  results: Result[]
  resultCount: number // as reported by server, only equal to resuls.length when all results loaded
}

interface Result {
  root: Root
  lexemes: Lexeme[]
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
function is (x: any): boolean {
  return x !== undefined && x !== null && x !== ''
}

const data = reactive<Data>({
  types: ['strong', 'geminated', 'weak-initial', 'weak-medial', 'weak-final', 'irregular'],
  consonants: ['b', 'ċ', 'd', 'f', 'ġ', 'g', 'għ', 'h', 'ħ', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'ż', 'z', '[jw]', '\''],
  search: {
    // These default values are overwritten by watch below
    s: '',
    c1: '',
    c2: '',
    c3: '',
    c4: '',
    t: ''
  },
  term: null,
  page: 0,
  working: false,
  results: [],
  resultCount: 0
})

const isSearching = computed<boolean>(() => {
  return is(route.query.s) || is(route.query.c1) || is(route.query.c2) || is(route.query.c3) || is(route.query.c4) || is(route.query.t)
})

const moreResults = computed<boolean>(() => {
  return data.results.length < data.resultCount
})

// Populate local search object whenever URL changes
watch(
  () => route.query,
  () => {
    data.search = {
      s: route.query.s as string || '',
      c1: route.query.c1 as string || '',
      c2: route.query.c2 as string || '',
      c3: route.query.c3 as string || '',
      c4: route.query.c4 as string || '',
      t: route.query.t as string || ''
    }
    if (isSearching.value) {
      data.results = []
      data.resultCount = 0
      data.page = 0
      loadResults()
      data.term = null
    } else {
      store.setTitle({ key: 'Root search' })
    }
  },
  {
    immediate: true
  }
)

watch(
  () => data.term,
  () => {
    if (data.term !== null) {
      store.setTitle({ key: 'title.search', replacements: [data.term] })
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
      c1: data.search.c1,
      c2: data.search.c2,
      c3: data.search.c3,
      c4: data.search.c4,
      t: data.search.t
    }
  })
}

// get results
function loadResults() {
  data.working = true
  axios.get(`${import.meta.env.VITE_API_URL}/roots/search`, {
    params: {
      s: data.search.s,
      c1: data.search.c1,
      c2: data.search.c2,
      c3: data.search.c3,
      c4: data.search.c4,
      t: data.search.t,
      page: ++data.page
    } })
    .then(response => {
      response.data.results.forEach((r: Result) => {
        data.results.push(r)
      })
      data.term = response.data.query.term || data.search.t
      data.resultCount = response.data.query.result_count
    })
    .catch(error => {
      store.addError(error)
    })
    .then(() => {
      data.working = false
    })
}

function lexemesByForm(item: Result, form: number): Lexeme[] {
  return item.lexemes.filter((l) => l.derived_form === form)
}

onMounted(() => {
  // https://renatello.com/check-if-a-user-has-scrolled-to-the-bottom-in-vue-js/
  window.onscroll = () => {
    const bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
    if (bottomOfWindow && !data.working && moreResults.value) {
      loadResults()
    }
  }
})
</script>

<template>
  <div>

    <div class="row" v-show="!isSearching">

      <div class="col-md-4 mb-4">
        <h1 class="h3">{{ __('Root search') }}</h1>

        <form @submit.prevent="submitSearch">
          <SearchInput
          :placeholder="__('search.placeholder.root')"
          :showSubmit="false"
          @update="(s: string) => { if (!isSearching) data.search.s = s }"
          class="my-4"
          ></SearchInput>

          <div class="text-center">
            <em>{{ __('or') }}</em>
          </div>

          <div class="mb-3">
            <label class="text-capitalize mb-2">{{ __('root.radicals') }}</label>
            <div class="d-flex gap-1">
              <select v-model="data.search.c1" class="form-select">
                <option value=""></option>
                <option v-for="c,ix in data.consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="data.search.c2" class="form-select">
                <option value=""></option>
                <option v-for="c,ix in data.consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="data.search.c3" class="form-select">
                <option value=""></option>
                <option v-for="c,ix in data.consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="data.search.c4" class="form-select">
                <option value=""></option>
                <option v-for="c,ix in data.consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="text-capitalize mb-2" for="RootClass">{{ __('root.type') }}</label>
            <select v-model="data.search.t" class="form-select" id="RootClass">
              <option value=""></option>
              <option v-for="p in data.types" :key="p" :value="p">{{ __(`root.type.${p}`) }}</option>
            </select>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search me-1"></i>
            {{ __('search.button') }}
          </button>
        </form>
      </div>
      <div class="col-md-8 row">
        <h2 class="h4 col-12">{{ __('search.help.title') }}</h2>

        <!-- <div class="col-md-6">
          <h3 class="h5">{{ __('search.roots.legend.title') }}</h3>
          <div v-html="markdown(__('search.roots.legend.body'))"></div>
        </div> -->

        <div class="col-md-8">
          <h3 class="h5">{{ __('search.roots.syntax.title') }}</h3>
          <div v-html="markdown(__('search.roots.syntax.body'))"></div>
        </div>

        <div class="col-md-4">
          <h3 class="h5">{{ __('search.roots.examples.title') }}</h3>
          <div v-html="markdown(__('search.roots.examples.body'))"></div>
        </div>

        <div class="col-md-12">
          <h3 class="h5">{{ __('search.roots.dropdowns.title') }}</h3>
          <div v-html="markdown(__('search.roots.dropdowns.body'))"></div>
        </div>

      </div>

    </div><!-- search form -->

    <div v-if="isSearching">
      <router-link :to="{ name: 'roots' }" class="btn btn-secondary float-end">
        {{ __('search.new') }}
      </router-link>

      <h3 class="h4" v-html="__('results.title', {
        term: `<span class='highlight'>${data.working ? '…' : data.term}</span>`,
        showing: data.working ? '…' : `${data.results.length}`,
        total: data.working ? '…' : `${data.resultCount}`
        })"></h3>

      <table class="table mt-3">
        <thead>
          <tr>
            <th class="text-capitalize">{{ __('root') }}</th>
            <th class="text-capitalize">{{ __('root.type') }}</th>
            <th>I</th>
            <th>II</th>
            <th>III</th>
            <th>V</th>
            <th>VI</th>
            <th>VII</th>
            <th>VIII</th>
            <th>IX</th>
            <th>X</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item,ix in data.results" :key="ix">
            <td>
              <Root :root="item.root" :match="data.term || undefined"></Root>
            </td>
            <td>{{ __(`root.type.${item.root.type}`) }}</td>
            <td v-for="form in [1,2,3,5,6,7,8,9,10]" :key="form">
              <div v-if="lexemesByForm(item, form).length === 0" class="text-lighter">-</div>
              <div v-for="lexeme,ix in lexemesByForm(item, form)" :key="ix">
                <div class="surface_form">
                  <router-link :to="{ name: 'lexeme', params: { 'id': lexeme._id }}">
                    {{ lexeme.lemma }}
                  </router-link>
                  <span div v-if="lexeme.alternatives" class="alternative">
                    ({{ lexeme.alternatives.join(', ') }})
                  </span>
                </div>
                <div class="text-lighter">
                  {{ lexeme.transitive ? __('transitive') : '' }}
                  {{ lexeme.intransitive ? __('intransitive') : '' }}
                  {{ lexeme.ditransitive ? __('ditransitive') : '' }}
                  {{ lexeme.hypothetical ? __('hypothetical') : '' }}
                </div>
                <div v-if="lexeme.glosses">
                  <div v-for="g,ix in lexeme.glosses.slice(0,5)" :key="ix">
                    {{ g.gloss }}
                  </div>
                  <div v-if="lexeme.glosses.length > 5">
                    ⋮
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="text-center mb-5">
        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="data.working"></i>
        <button class="btn btn-primary" @click="loadResults()" v-show="!data.working && moreResults">
          {{ __('search.button.more_results') }}
        </button>
      </div>

    </div><!-- search reuslts -->

  </div>
</template>
