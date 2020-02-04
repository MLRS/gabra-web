<template>
  <div id="app">

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top bg-light border-bottom mb-2">
    <div class="container">
      <router-link to="/" class="navbar-brand text-red mr-4">Ġabra</router-link>

      <form role="search" action="" @submit.prevent="submitSearch" v-show="$route.name != 'home'" class="mr-auto">
        <SearchInput
          :placeholder="__('search.placeholder')"
          :showSubmit="true"
          @update="(s) => { term = s }"
        ></SearchInput>
      </form>

      <div class="collapse navbar-collapse ml-2 d-flex">
        <div class="navbar-nav mr-auto flex-row">
          <router-link to="/lexemes" class="nav-item nav-link">{{ __('Advanced search') }}</router-link>
          <router-link to="/roots" class="nav-item nav-link">{{ __('Root search') }}</router-link>
          <router-link to="/sources" class="nav-item nav-link">{{ __('Sources') }}</router-link>
        </div>
        <button type="button" class="btn btn-link pr-0 text-black-50" v-show="$store.state.language != 'en'" @click="$store.dispatch('setLanguage', 'en')">
          in English
        </button>
        <button type="button" class="btn btn-link pr-0 text-black-50" v-show="$store.state.language != 'mt'" @click="$store.dispatch('setLanguage', 'mt')">
          bil-Malti
        </button>
      </div>
    </div>
    </nav>

    <main class="container pt-3">
      <div v-for="m,ix in $store.state.messages" :key="ix" class="alert" :class="'alert-'+m.type">
        {{ m.text }}
      </div>

      <router-view></router-view>
    </main>

    <footer class="container mb-5">
    </footer>

  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N, { Language } from '@/components/I18N.ts'
import SearchInput from '@/components/SearchInput.vue'

interface Data {
  term: string
}

export default mixins(I18N).extend({
  components: {
    SearchInput
  },
  data (): Data {
    return {
      term: this.$route.query.s as string
    }
  },
  watch: {
    // Clear any errors when route changes
    '$route': {
      handler (): void {
        this.$store.dispatch('clearMessages')
      }
    }
  },
  methods: {
    submitSearch (): void {
      if (this.term) {
        this.$router.push({ name: 'lexemes', query: { s: this.term } })
          .catch((_) => { })
      }
    }
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

.navbar-nav a {
  @extend .mr-3;
}
</style>
