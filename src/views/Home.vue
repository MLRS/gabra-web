<template>
  <div id="home" class="row">

    <div class="jumbotron">
      <h1>{{ __('home.title') }}</h1>

      <p v-html="__('home.1')"></p>

      <form role="search" class="" action="PHP echo $this->Url->build('/lexemes') ">
        <div class="input-group input-group-lg">
          PHP echo $this->element('keyboard', array('search_id'=>'home-search', 'width'=>'25px'));

          <i class="far fa-keyboard"></i>

          <!-- PHP $placeholder = __k($this, 'home.search.placeholder', array('{maltese}' => 'ħarġa', '{english}' => 'outing')) -->
          <input type="search" id="home-search" name="s" class="form-control" autofocus="true" :placeholder="__('home.search.placeholder')" />
          <div class="input-group-btn">
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

      PHP foreach($news as $message):
      PHP if (@$message[$language]):
      <h5>
        PHP echo $message['date']
      </h5>
      PHP echo $this->Markdown->transform($message[$language])
      PHP endif
      PHP endforeach

      <!-- <p class="text-right">
        PHP echo $this->Html->link(__k($this,'home.news.see_all'), '/pages/news')
      </p> -->

    </div><!-- /.col-sm-4 -->

    <!-- PHP
      echo $this->Html->script('https://www.gstatic.com/charts/loader.js', array('inline' => false, 'defer' => false));
      echo $this->Html->script(array('home','log-chart'), array('inline' => false, 'defer' => false)); -->

  </div>
</template>

<script>
import I18N from '@/components/I18N.vue'
import mixins from 'vue-typed-mixins'

export default mixins(I18N).extend({
  name: 'home',
  components: {
  }
})
</script>
