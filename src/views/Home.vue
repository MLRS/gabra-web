<template>
  <div id="home" class="row">

    <div class="jumbotron py-5">
      <h1 class="display-4">{{ __('home.title') }}</h1>

      <p class="lead" v-html="__('home.1')"></p>

      <form role="search" class="" action="PHP echo $this->Url->build('/lexemes') ">
        <div class="input-group input-group-lg">
          <Keyboard></Keyboard>
          <input type="search" id="home-search" name="s" class="form-control" autofocus="true" :placeholder="__('home.search.placeholder', { maltese: 'ħarġa', english: 'outing'})" />
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div><!-- input-group -->
      </form>

    </div><!-- jumbotron -->

    <div class="col-sm-8">

      <div v-html="__m('home.mistakes')"></div>

      <div v-html="__m('home.about')"></div>

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
        {{ item[language] }}
      </div>

    </div><!-- /.col-sm-4 -->

    <!-- PHP
      echo $this->Html->script('https://www.gstatic.com/charts/loader.js', array('inline' => false, 'defer' => false));
      echo $this->Html->script(array('home','log-chart'), array('inline' => false, 'defer' => false)); -->

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N from '@/components/I18N.vue'
import Keyboard from '@/components/Keyboard.vue'

interface Data {
  news: {date: string, en: string, mt: string}[]
}

export default mixins(I18N).extend({
  name: 'home',
  components: {
    Keyboard
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      news: require('@/assets/data/news.yaml')
    }
  },
  computed: {
    latestNews: function () {
      return this.news.slice(-3).reverse()
    }
  }
})
</script>
