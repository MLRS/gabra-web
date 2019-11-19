<script lang="ts">
import Vue from 'vue'

const md = require('markdown-it')({
  html: true
})

const web = require('@/assets/data/web.yaml')

interface Entry {
  key: string
  en: string
  mt: string
}

export default Vue.extend({
  methods: {
    // Get text for key
    __: function (key: string, replacements?: {[key:string]: string}) {
      let f = web.find((x: Entry) => x.key === key)
      if (f) {
        let lang = 'en' // TODO
        let s = f[lang]
        if (replacements) {
          for (let key in replacements) {
            s = s.replace(`{${key}}`, replacements[key])
          }
        }
        return s
      } else {
        return key
      }
    },
    // Render as Markdown
    __m: function (key: string, replacements?: {[key:string]: string}) {
      return md.render(this.__(key, replacements))
    }
  }
})
</script>
