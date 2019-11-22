<script lang="ts">
import Vue from 'vue'

const MarkdownIt = require('markdown-it')({
  html: true
})

const terms = require('@/assets/data/i18n.yaml')

interface Entry {
  key?: string
  en: string
  mt: string
}

export type Language = 'en' | 'mt'

// The component mixing-in this mixin must have a language property
// which is read directly from inside the __ function
interface Mixer {
  language: Language
}

export default Vue.extend({
  methods: {
    // Get text for key
    __: function (this: Mixer, key: string, replacements?: {[key:string]: string} | string[]): string {
      let f = terms.find((x: Entry) => {
        if (x.key) {
          return x.key === key
        } else {
          return x.en === key
        }
      })
      if (f) {
        let s = f[this.language] // from mixed-in component
        if (replacements) {
          for (let key in replacements) { // works for both key-values and arrays
            s = s.replace(`{${key}}`, (replacements as {[key:string]:string})[key])
          }
        }
        return s
      } else {
        return key
      }
    },
    // Render as Markdown
    markdown: function (md: string): string {
      return MarkdownIt.render(md)
    },
    // Render as Markdown
    markdownInline: function (md: string): string {
      return MarkdownIt.renderInline(md)
    }
  }
})
</script>
