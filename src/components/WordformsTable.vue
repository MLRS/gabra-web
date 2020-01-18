<template>
  <table class="table table-sm">
    <thead>
      <tr class="text-capitalize">
        <th>{{ __('surface_form') }}</th>
        <th v-for="f,ix in showFields" :key="ix">
          {{ __(f) }}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="wf,ix in wordforms" :key="ix">
        <td class="surface_form">
          <span :class="{ 'generated': wf.generated }">{{ wf.surface_form }}</span>
          <div v-if="wf.alternatives" class="alternative">
            ({{ wf.alternatives.join(', ') }})
          </div>
        </td>
        <td v-for="f,ix in showFields" :key="ix">
          {{ format(f, wf[f]) }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'

import I18N from '@/components/I18N.ts'

interface Data {
}

// Fields to ignore from table
const ignoreFields = new Set([
  'surface_form', // included manually
  'alternatives', // included manually
  '_id',
  'lexeme_id',
  'sources',
  'modified',
  'created',
  'generated',
  'full',
  'pending'
])

// Enforce this order, all unknown fields come after
const knownFields = [
  'surface_form',
  'gender',
  'number',
  'phonetic',
  'pattern',
  'derived_form',
  'form',
  'aspect',
  'subject',
  'dir_obj',
  'ind_obj',
  'polarity'
]

// TODO
const filterFields = {
  'VERB': [
    'dir_obj',
    'ind_obj',
    'polarity'
  ]
}

export default mixins(I18N).extend({
  props: {
    lexeme: Object,
    wordforms: Array
  },
  data (): Data {
    return {
    }
  },
  computed: {
    showFields (): string[] {
      if (!this.wordforms) return []
      let fields = new Set()
      for (let wf of this.wordforms) {
        for (let f in wf) {
          if (!ignoreFields.has(f)) {
            fields.add(f)
          }
        }
      }
      return Array.from(fields).sort((a, b) => {
        // Sort by order in knownFields
        let xa = knownFields.indexOf(a)
        let xb = knownFields.indexOf(b)
        if (xa > -1 && xb > -1) return xa - xb
        else if (xa === -1 && xb > -1) return 1
        else if (xa > -1 && xb === -1) return -1
        else {
          if (a < b) return -1
          else if (b > a) return 1
          else return 0
        }
      })
    }
  },
  methods: {
    format (field, value): string {
      switch (field) {
        case 'subject':
        case 'dir_obj':
        case 'ind_obj':
          if (value) {
            let s = ''
            s += value.person + ' '
            s += value.number + ' '
            if (value.gender) s += this.__(`gender.${value.gender}`)
            return s
          }
          break
        default:
          return value
      }
    }
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

</style>
