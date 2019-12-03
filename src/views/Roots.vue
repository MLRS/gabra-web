<template>
  <div>

    <div class="row" v-show="!isSearching">

      <div class="col-md-4">
        <h1 class="h3">{{ __('Root search') }}</h1>

        <form @submit.prevent="submitSearch">
          <SearchInput
          :placeholder="__('search.placeholder.root')"
          :showSubmit="false"
          @update="(s) => { search.s = s }"
          class="my-4"
          ></SearchInput>

          <div class="text-center">
            <em>{{ __('or') }}</em>
          </div>

          <div class="form-group">
            <label class="text-capitalize">{{ __('root.radicals') }}</label>
            <div class="row m-0">
              <select v-model="search.c1" class="form-control col-3">
                <option value=""></option>
                <option v-for="c,ix in consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="search.c2" class="form-control col-3">
                <option value=""></option>
                <option v-for="c,ix in consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="search.c3" class="form-control col-3">
                <option value=""></option>
                <option v-for="c,ix in consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
              <select v-model="search.c4" class="form-control col-3">
                <option value=""></option>
                <option v-for="c,ix in consonants" :key="ix" :value="c">{{ c }}</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="text-capitalize" for="RootClass">{{ __('root.type') }}</label>
            <select v-model="search.t" class="form-control" id="RootClass">
              <option value=""></option>
              <option v-for="p in types" :key="p" :value="p">{{ __(`root.type.${p}`) }}</option>
            </select>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search mr-1"></i>
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
      <router-link :to="{ name: 'roots' }" class="btn btn-link float-right">
        {{ __('search.new') }}
      </router-link>

      <h3 class="h4" v-html="__('results.title', {
        term: `<span class='text-red'>${working ? '…' : term}</span>`,
        showing: working ? '…' : results.length,
        total: working ? '…' : resultCount
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
          <tr v-for="item,ix in results" :key="ix">
            <td>
              <Root :root="item.root"></Root>
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
                  <div v-if="lexeme.glosses > 5">
                    ⋮
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="text-center mb-5">
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
import Root from '@/components/Root.vue'

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
  types: string[] // for dropdown
  consonants: string[] // for dropdown
  search: {
    s: string
    c1: string
    c2: string
    c3: string
    c4: string
    t: string // type
  },
  term: string | null
  page: number // last page retrieved
  working: boolean
  results: Result[]
  resultCount: number // as reported by server, only equal to resuls.length when all results loaded
}

interface Result {
  root: Root
  lexemes: Lexeme[]
}

function is (x: any): boolean {
  return x !== undefined && x !== null && x !== ''
}

export default mixins(I18N).extend({
  components: {
    SearchInput,
    Root
  },
  data (): Data {
    return {
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
      term: null, // actual term computed and returned from API
      page: 0,
      working: false,
      results: [],
      resultCount: 0
    }
  },
  watch: {
    // Populate local search object whenever URL changes
    '$route.query': {
      handler (): void {
        this.search = {
          s: this.$route.query.s as string || '',
          c1: this.$route.query.c1 as string || '',
          c2: this.$route.query.c2 as string || '',
          c3: this.$route.query.c3 as string || '',
          c4: this.$route.query.c4 as string || '',
          t: this.$route.query.t as string || ''
        }
        if (this.isSearching) {
          this.results = []
          this.resultCount = 0
          this.page = 0
          this.loadResults()
          this.term = null
        } else {
          this.$store.dispatch('setTitle', { key: 'Root search' })
        }
      },
      immediate: true
    },
    term: {
      handler (): void {
        if (this.term !== null) {
          this.$store.dispatch('setTitle', { key: 'title.search', replacements: [this.term] })
        }
      },
      immediate: true
    }
  },
  computed: {
    isSearching (): boolean {
      return is(this.$route.query.s) || is(this.$route.query.c1) || is(this.$route.query.c2) || is(this.$route.query.c3) || is(this.$route.query.c4) || is(this.$route.query.t)
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
          c1: this.search.c1,
          c2: this.search.c2,
          c3: this.search.c3,
          c4: this.search.c4,
          t: this.search.t
        }
      })
    },
    // get results
    loadResults (): void {
      this.working = true
      axios.get(`${process.env.VUE_APP_API_URL}/roots/search`, {
        params: {
          s: this.search.s,
          c1: this.search.c1,
          c2: this.search.c2,
          c3: this.search.c3,
          c4: this.search.c4,
          t: this.search.t,
          page: ++this.page
        } })
        .then(response => {
          response.data.results.forEach((r: Result) => {
            this.results.push(r)
          })
          this.term = response.data.query.term || this.search.t
          this.resultCount = response.data.query.result_count
        })
        .catch(error => {
          this.$store.dispatch('addError', error)
        })
        .then(() => {
          this.working = false
        })
    },
    lexemesByForm (item: Result, form: number): Lexeme[] {
      return item.lexemes.filter((l) => l.derived_form === form)
    }
  },
  mounted (): void {
  }
})
</script>
