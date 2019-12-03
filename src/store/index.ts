import Vue from 'vue'
import Vuex from 'vuex'

import { __l, Language } from '@/components/I18N.ts'

Vue.use(Vuex)

interface I18NString {
  key: string,
  replacements: {[key:string]: string} | string[]
}

interface Message {
  text: string
  type: MessageType
}

type MessageType = 'success' | 'info' | 'warning' | 'danger'

interface State {
  language: Language
  title: I18NString | string
  messages: Message[]
  // TODO: loading spinnner
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
    },
    messages: []
  },
  mutations: {
    SET_LANGUAGE (state: State, lang: Language): void {
      state.language = lang
    },
    SET_TITLE (state: State, title?: I18NString | string): void {
      if (title) {
        state.title = title
        let t: string
        if (typeof title === 'string') {
          t = title as string
        } else {
          t = __l(state.language, title.key, title.replacements)
        }
        document.title = `${t} · Ġabra`
      } else {
        state.title = ''
        document.title = 'Ġabra'
      }
    },
    ADD_MESSAGE (state: State, message: Message): void {
      state.messages.push(message)
    },
    CLEAR_MESSAGES (state: State): void {
      state.messages = []
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
    setTitle (context, title: I18NString | string): void {
      context.commit('SET_TITLE', title)
    },
    addError (context, message: string): void {
      context.commit('ADD_MESSAGE', { type: 'danger', text: message })
    },
    clearMessages (context): void {
      context.commit('CLEAR_MESSAGES')
    }

  },
  modules: {
  },
  getters: {

  }
})
