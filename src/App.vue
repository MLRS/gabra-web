<template>
  <div id="app">

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-2">
      <div class="container">
      <router-link to="/" class="navbar-brand">Ġabra</router-link>

      <form role="search" action="" @submit.prevent="submitSearch" v-show="$route.name != 'home'" class="mx-2">
        <SearchInput
          :placeholder="__('Search for a word')"
          :showSubmit="true"
          @update="(s) => { term = s }"
        ></SearchInput>
      </form>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
          <router-link to="/lexemes" class="nav-item nav-link">{{ __('Advanced search') }}</router-link>
          <router-link to="/roots" class="nav-item nav-link">{{ __('Root search') }}</router-link>
          <router-link to="/sources" class="nav-item nav-link">{{ __('Sources') }}</router-link>
        </div>

        <button type="button" class="btn btn-link pr-0 text-red" v-show="language != 'en'" @click="setLanguage('en')">
          in English
        </button>
        <button type="button" class="btn btn-link pr-0 text-red" v-show="language != 'mt'" @click="setLanguage('mt')">
          bil-Malti
        </button>

          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Dropdown
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li> -->
        <!-- <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> -->
      </div>
      </div>
    </nav>

    <main class="container pt-3">
      <router-view :language="language"/>
    </main>

    <footer class="container">
    </footer>

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N, { Language } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'

interface Data {
  language: Language,
  term: string
}

function preferredLanguage () : Language {
  if (window && window.navigator && window.navigator.language.startsWith('mt')) {
    return 'mt'
  } else {
    return 'en'
  }
}

export default mixins(I18N).extend({
  components: {
    SearchInput
  },
  data (): Data {
    return {
      language: preferredLanguage(),
      term: this.$route.query.s as string
    }
  },
  methods: {
    setLanguage: function (lang: Language): void {
      this.language = lang
    },
    submitSearch: function (): void {
      if (this.term) {
        this.$router.push({ name: 'lexemes', query: { s: this.term } })
      }
    }
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

</style>
