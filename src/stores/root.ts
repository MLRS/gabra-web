import { defineStore } from 'pinia'
import { __l, type Language } from '@/components/I18N.ts'

interface I18NString {
  key: string,
  replacements?: {[key:string]: string} | string[]
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
}

// Get preferred language from browser settings
function preferredLanguage (): Language {
  if (window && window.navigator && window.navigator.language.startsWith('mt')) {
    return 'mt'
  } else {
    return 'en'
  }
}

export const useRootStore =  defineStore('root', {
  state: (): State => ({
    language: preferredLanguage(),
    title: {
      key: '',
      replacements: {}
    },
    messages: []
  }),
  actions: {
    setLanguage (lang: Language): void {
      this.language = lang
      this.setTitle(this.title)
    },
    clearTitle (): void {
      this.title = ''
    },
    setTitle (title: I18NString | string): void {
      if (title) {
        this.title = title
        let t: string
        if (typeof title === 'string') {
          t = title as string
        } else {
          t = __l(this.language, title.key, title.replacements)
        }
        document.title = `${t} · Ġabra`
      } else {
        this.title = ''
        document.title = 'Ġabra'
      }
    },
    addError (message: string): void {
      this.messages.push({ type: 'danger', text: message })
    },
    clearMessages (): void {
      this.messages = []
    }
  },
})
