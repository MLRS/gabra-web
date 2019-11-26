import Vue from 'vue'
import terms from '@/assets/data/i18n.yaml'

const MarkdownIt = require('markdown-it')({
  html: true
})

export type Language = 'en' | 'mt'

// Standalone i18n function
export function __l (lang: Language, key: string, replacements?: {[key:string]: string} | string[]): string {
  let f = terms.find((x: Entry) => {
    if (x.key) {
      return x.key === key
    } else {
      return x.en === key
    }
  })
  if (f) {
    let s = f[lang] // from mixed-in component
    if (replacements) {
      for (let key in replacements) { // works for both key-values and arrays
        s = s.replace(`{${key}}`, (replacements as {[key:string]:string})[key])
      }
    }
    return s
  } else {
    return key
  }
}

// The component mixing-in this mixin must have a language property
// which is read directly from inside the __ function
interface Mixer {
  language: Language
}

export default Vue.extend({
  methods: {

    // Get text for key
    __: function (this: Mixer, key: string, replacements?: {[key:string]: string} | string[]): string {
      return __l(this.language, key, replacements)
    },

    // Render as Markdown
    markdown: function (md: string): string {
      return MarkdownIt.render(md)
    },

    // Render as Markdown (inline)
    markdownInline: function (md: string): string {
      return MarkdownIt.renderInline(md)
    }

  }
})
