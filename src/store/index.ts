import Vue from 'vue'
import Vuex from 'vuex'

import { __l, Language } from '@/components/I18N.ts'

Vue.use(Vuex)

// TODO more general name like "LocalisationUnit"
interface Title {
  key: string,
  replacements: {[key:string]: string} | string[]
}

interface State {
  language: Language
  title: Title | null
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
    language: preferredLanguage(),
    title: {
      key: '',
      replacements: {}
    }
  },
  mutations: {
    SET_LANGUAGE (state: State, lang: Language): void {
      state.language = lang
    },
    SET_TITLE (state: State, title?: Title): void {
      if (title) {
        state.title = title
        let t = __l(state.language, title.key, title.replacements)
        document.title = `${t} · Ġabra`
      } else {
        state.title = null
        document.title = 'Ġabra'
      }
    }
  },
  actions: {
    setLanguage (context, lang: Language): void {
      context.commit('SET_LANGUAGE', lang)
      context.commit('SET_TITLE', context.state.title)
    },
    clearTitle (context): void {
      context.commit('SET_TITLE')
    },
    setTitle (context, title: Title): void {
      context.commit('SET_TITLE', title)
    }
  },
  modules: {
  },
  getters: {

  }
})
