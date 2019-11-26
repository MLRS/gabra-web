<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="root === null"></i>

    <div class="row" v-if="root !== null && root.radicals">

      <div class="col-12">
        <h1 class="mb-3">
          <!-- {{ __('root.title') }}: -->
          <span class="surface_form">
            {{ root.radicals }}
            <sup class="text-muted" v-if="root.variant">
              {{ root.variant }}
            </sup>

            <span v-if="root.alternatives" class="text-muted">
              ({{ root.alternatives }})
            </span>
          </span>
        </h1>
      </div>

      <!-- details -->
      <div class="col-md-4">
        <dl>

          <dt>{{ __('Class') }}</dt>
          <dd>{{ rootClass }}</dd>

          <dt>{{ __('Source(s)') }}</dt>
          <dd>
            <router-link v-for="s,ix in root.sources" :key="ix" :to="{ name: 'sources' }" class="">
              {{ s }}
            </router-link>
          </dd>

        </dl>
      </div>

      <!-- lexemes -->
      <div class="col-md-8">

        <h2 class="h6">{{ __('Lexemes') }}</h2>

        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexemes === null"></i>

        <table class="table table-sm">
          <tbody>
            <tr v-for="lexeme,ix in lexemes" :key="ix">
              <td class="">
                <router-link :to="{ name: 'lexeme', params: { id: lexeme._id } }" class="surface_form">{{ lexeme.lemma }}</router-link>
                <span class="text-lighter ml-1">
                  {{ __(`pos.${lexeme.pos }`) }}
                  {{ derivedForm(lexeme.derived_form) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

      </div>

    </div><!-- row -->
  </div>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'
import I18N, { __l, Language } from '@/components/I18N.ts'
import * as UI from '@/helpers/UI.ts'

import axios from 'axios'

interface Data {
  root: Root | null
  lexemes: Lexeme[] | null
}

export default mixins(I18N).extend({
  components: {
  },
  props: {
    language: String
  },
  data (): Data {
    return {
      root: null,
      lexemes: []
    }
  },
  watch: {
    '$route.params': {
      handler (): void {
        this.load()
        let title = this.$route.params.radicals
        switch (this.$route.params.variant) {
          case '1': title += ' ¹'; break
          case '2': title += ' ²'; break
          case '3': title += ' ³'; break
          case '4': title += ' ⁴'; break
          case '5': title += ' ⁵'; break
        }
        this.$emit('setTitle', title)
      },
      immediate: true
    }
  },
  computed: {
    rootClass: function (this: any): string {
      if (!this.root) return ''
      let out = ''
      if (this.root.type !== 'irregular') {
        let radicalCount = this.root.radicals.split('-').length
        out += radicalCount === 4 ? this.__('Quad.') : this.__('Tri.')
      }
      out += ' ' + this.__(`type.${this.root.type}`)
      return out
    }
  },
  methods: {
    // get root and lexemes
    load (): void {
      let path = this.$route.params.variant ? `${this.$route.params.radicals}/${this.$route.params.variant}` : `${this.$route.params.radicals}`
      axios.get(`${process.env.VUE_APP_API_URL}/roots/${path}`)
        .then(response => {
          this.root = response.data
        })
        .catch(error => {
          console.error(error)
          this.root = {} as Root
        })
      axios.get(`${process.env.VUE_APP_API_URL}/roots/lexemes/${path}`)
        .then(response => {
          this.lexemes = response.data
        })
        .catch(error => {
          console.error(error)
          this.lexemes = []
        })
    },
    // Making helper functions available to template
    derivedForm: UI.derivedForm
  },
  mounted (): void {
  }
})
</script>
