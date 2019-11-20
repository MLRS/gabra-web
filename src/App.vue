<template>
  <div id="app">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
      <router-link to="/" class="navbar-brand">Ġabra</router-link>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav mr-auto">
          <router-link to="/search" class="nav-item nav-link">Advanced search</router-link>
          <router-link to="/root-search" class="nav-item nav-link">Root search</router-link>
          <router-link to="/sources" class="nav-item nav-link">Sources</router-link>
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
// import Vue from 'vue'
import mixins from 'vue-typed-mixins'
import I18N, { Language } from '@/components/I18N.vue'

interface Data {
  language: Language
}

export default mixins(I18N).extend({
  data (): Data {
    return {
      language: 'en'
    }
  },
  methods: {
    setLanguage: function (lang: Language): void {
      this.language = lang
    }
  }
})
</script>

<style lang="scss">
@import 'assets/custom.scss';

</style>
