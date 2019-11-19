<script lang="ts">
import Vue from 'vue'
// import MarkdownIt from 'markdown-it'
const md = require('markdown-it')({
  html: true
})

const web = require('@/assets/data/web.yaml')

interface Entry {
  key: string
  en: string
  mt: string
}

export default {
  methods: {
    // Get text for key
    __: function (key: string) {
      let lang = 'en'
      let f = web.find((x: Entry) => x.key === key)
      if (f) {
        return f[lang]
      } else {
        return key
      }
    },
    // Render as Markdown
    __m: function (key: string) {
      return md.render(this.__(key))
    }
  }
}
</script>
