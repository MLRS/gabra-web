<script lang="ts">
import Vue from 'vue'

const web = require('@/assets/data/web.yaml')

interface Entry {
  key: string
  en: string
  mt: string
}

export default {
  methods: {
    __: function (key: string) {
      console.log('web', web)
      return key
      // let lang = 'en'
      // let f = web.find((x: Entry) => x.key === key)
      // if (f) {
      //   return f[lang]
      // } else {
      //   return key
      // }
    }
  }
}
</script>
