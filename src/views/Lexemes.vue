<template>
  <div>

    <div class="row" v-show="!isSearching">

      <div class="col-md-6">
        <h3>{{ __('Advanced search') }}</h3>

        <form @submit.prevent="submitSearch">
          <SearchInput
          :placeholder="__('Search')"
          :showSubmit="false"
          class="my-4"
          ></SearchInput>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="search.l" id="LexemeL"/>
            <label for="LexemeL">{{ __('Search lemmas') }}</label>
          </div>

          <div class="form-group form-check mb-0">
            <input type="checkbox" class="form-check-input" v-model="search.wf" id="LexemeWf"/>
            <label for="LexemeWf">{{ __('Search wordforms') }}</label>
          </div>

          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" v-model="search.g" id="LexemeG"/>
            <label for="LexemeG">{{ __('Search in English glosses') }}</label>
          </div>

          <div class="form-group">
            <label for="LexemePos">{{ __('Part of speech') }}</label>
            <select v-model="search.pos" class="form-control" id="LexemePos">
              <option value="">{{ __('All') }}</option>
              <option v-for="p in pos" :key="p" :value="p">{{ __(`pos.${p}`) }}</option>
            </select>
          </div>

          <div class="form-group">
            <label for="LexemeSource">{{ __('Source') }}</label>
            <select v-model="search.source" class="form-control" id="LexemeSource">
              <option value="">{{ __('All') }}</option>
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

    <div v-if="isSearching">
      <h3>{{ __('results', [results ? results.length : '...']) }}</h3>

      <div v-for="item,ix in results" :key="ix">
        {{ item }}
      </div>
    </div><!-- search reuslts -->

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.vue'
import SearchInput from '@/components/SearchInput.vue'

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
  search: {
    s: string
    l: boolean
    wf: boolean
    g: boolean
    pos: string | null
    source: string | null
  },
  results: null | any[]
  pos: string[]
  sources: string[]
}

export default mixins(I18N).extend({
  components: {
    SearchInput
  },
  props: {
    language: String
  },
  data: function (): Data {
    return {
      search: {
        // These default values are overwritten by watch below
        s: '',
        l: true,
        wf: true,
        g: true,
        pos: null,
        source: null
      },
      results: null,
      pos: ['ADJ', 'ADP', 'ADV', 'AUX', 'CONJ', 'DET', 'INTJ', 'NOUN', 'NUM', 'PART', 'PRON', 'PROPN', 'PUNCT', 'SCONJ', 'SYM', 'VERB', 'X'],
      sources: ['Spagnol2011', 'Ellul2013', 'Mayer2013', 'Falzon2013', 'Camilleri2013', 'UserFeedback', 'KelmaKelma', 'KelmetilMalti', 'Apertium2014', 'DM2015', 'IATE2016']
    }
  },
  watch: {
    // Populate local search object whenever URL changes
    '$route.query': {
      handler: function (): void {
        this.search = {
          s: this.$route.query.s as string || '',
          l: query2bool(this.$route.query.l),
          wf: query2bool(this.$route.query.wf),
          g: query2bool(this.$route.query.g),
          pos: this.$route.query.pos as string || null,
          source: this.$route.query.source as string || null
        }
        // this.search = JSON.parse(JSON.stringify(this.$route.query))
      },
      immediate: true
    }
  },
  computed: {
    // term: function (): string | null {
    //   return this.$route.query.s as string || null
    // },
    isSearching: function (): boolean {
      return this.search.s && this.search.s.length > 0 // TODO weak
    }
  },
  methods: {
    submitSearch: function (): void {
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
    }
  }
})
</script>
