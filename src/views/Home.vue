<template>
  <div id="home" class="row">

    <div class="jumbotron py-5 px-5">
      <h1 class="display-4 font-weight-normal text-shadow">{{ __('home.title') }}</h1>

      <p class="lead" v-html="__('home.1', {lexemes: stats.lexemes, wordforms: stats.wordforms})"></p>

      <form role="search" action="" @submit.prevent="submitSearch">
        <SearchInput
          :placeholder="__('home.search.placeholder', {maltese: 'ħarġa', english: 'outing'})"
          :showSubmit="true"
          class="input-group-lg"
          @update="(s) => { term = s }"
        ></SearchInput>
      </form>

    </div><!-- jumbotron -->

    <div class="col-sm-8">

      <div v-html="markdown(__('home.mistakes'))"></div>

      <div v-html="markdown(__('home.about'))"></div>

      <p v-html="__('home.citing')"></p>

      <p class="text-muted" v-html="__('home.license')"></p>

    </div><!-- /.col-sm-8 -->

    <div class="col-sm-4">

      <h3>
        {{ __('home.activity.title') }}
      </h3>

      <div id="log-chart"></div>

      <div v-for="item,x in latestNews" :key="x" class="mt-3">
        <h4 class="h6 mb-1">
          {{ item.date }}
        </h4>
        <div v-html="markdown(item[language])"></div>
      </div>

    </div><!-- /.col-sm-4 -->

    <!-- PHP
      echo $this->Html->script('https://www.gstatic.com/charts/loader.js', array('inline' => false, 'defer' => false));
      echo $this->Html->script(array('home','log-chart'), array('inline' => false, 'defer' => false)); -->

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'
import axios from 'axios'

interface Data {
  stats: {
    lexemes: string
    wordforms: string
    // TODO log
  }
  news: {date: string, en: string, mt: string}[]
  term: string
}

export default mixins(I18N).extend({
  name: 'home',
  components: {
    SearchInput
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      stats: {
        lexemes: '…',
        wordforms: '…'
      },
      news: require('@/assets/data/news.yaml'),
      term: ''
    }
  },
  computed: {
    latestNews (): {date: string, en: string, mt: string}[] {
      return this.news.slice(-3).reverse()
    }
  },
  methods: {
    submitSearch (): void {
      if (this.term) {
        this.$router.push({ name: 'lexemes', query: { s: this.term } })
      }
    }
  },
  mounted (): void {
    this.$emit('setTitle', '')

    axios.get(`${process.env.VUE_APP_API_URL}/lexemes/count`)
      .then(response => {
        this.stats.lexemes = `<span class="badge badge-dark">${response.data.toLocaleString()}</span>`
      })
      .catch(error => {
        console.error(error)
      })
    axios.get(`${process.env.VUE_APP_API_URL}/wordforms/count`)
      .then(response => {
        this.stats.wordforms = `<span class="badge badge-secondary">${response.data.toLocaleString()}</span>`
      })
      .catch(error => {
        console.error(error)
      })
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

.well {
  @extend .card-body, .bg-light, .rounded, .mt-3, .text-muted;
}
</style>
