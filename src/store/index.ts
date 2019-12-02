import Vue from 'vue'
import Vuex from 'vuex'

import { Language } from '@/components/I18N.ts'

Vue.use(Vuex)

interface State {
  language: Language
  // TODO: page title
  // TODO: loading spinnner
  // TODO: messages
}

// Get preferred language from browser settings
function preferredLanguage (): Language {
  if (window && window.navigator && window.navigator.language.startsWith('mt')) {
    return 'mt'
  } else {
    return 'en'
  }
}

export default new Vuex.Store({
  state: {
    language: preferredLanguage()
  },
  mutations: {
    SET_LANGUAGE (state: State, lang: Language): void {
      state.language = lang
    }
  },
  actions: {
    setLanguage (context, lang: Language): void {
      context.commit('SET_LANGUAGE', lang)
    }
  },
  modules: {
  },
  getters: {

  }
})
