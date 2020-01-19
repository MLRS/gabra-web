<template>
  <table class="table table-sm">
    <thead>
      <tr class="text-capitalize">
        <th>{{ __('surface_form') }}</th>
        <th v-for="f,ix in showFields" :key="ix">
          {{ __(f) }}
        </th>
      </tr>
      <tr>
        <th></th>
        <th v-for="f,ix in showFields" :key="ix">
          <select v-if="isFilterField(f)" v-model="filters[f]">
            <option v-for="o,ix in filterFieldOptions(f)" :key="ix">{{ format(f, o) }}</option>
          </select>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="wf,ix in filteredWordforms" :key="ix">
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
import * as UI from '@/helpers/UI.ts'

/* eslint-disable camelcase */

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

// Other combinations may exist in data, any other fields not specified here are ignored
const agrOptions = [
  '',
  { person: 'p1', number: 'sg' },
  { person: 'p2', number: 'sg' },
  { person: 'p3', number: 'sg', gender: 'm' },
  { person: 'p3', number: 'sg', gender: 'f' },
  { person: 'p1', number: 'pl' },
  { person: 'p2', number: 'pl' },
  { person: 'p3', number: 'pl' }
]

interface Data {
  // active filters
  filters: {
    // stores "formatted" value: {person: 'p1', number: 'sg'} -> 'p1 sg'
    // empty string means only match when field is missing
    [key: string]: string
  },
}

export default mixins(I18N).extend({
  props: {
    lexeme: Object,
    wordforms: Array
  },
  data (): Data {
    return {
      filters: {
        'dir_obj': '',
        'ind_obj': '',
        'polarity': 'pos'
      }
    }
  },
  computed: {
    showFields (): string[] {
      if (!this.wordforms) return []
      let fields = new Set()
      for (let wf of this.wordforms as Wordform[]) {
        for (let f in wf) {
          if (!ignoreFields.has(f)) {
            fields.add(f)
          }
        }
      }
      let fieldsArray = Array.from(fields) as string[]
      return fieldsArray.sort((a: string, b: string): number => {
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
    },
    filterFields (): { [key: string]: any[] } {
      switch (this.lexeme.pos) {
        case 'VERB':
          return {
            'dir_obj': agrOptions,
            'ind_obj': agrOptions,
            'polarity': ['pos', 'neg']
          }
        default:
          return {}
      }
    },
    filteredWordforms (): Wordform[] {
      if (!this.wordforms) return []
      return (this.wordforms as Wordform[]).filter((wf: Wordform): boolean => {
        for (let field in this.filters) {
          let filterValue = this.filters[field]
          // @ts-ignore
          if (!filterValue && wf[field]) {
            return false
          }
          // @ts-ignore
          if (filterValue && this.format(field, wf[field]) !== filterValue) {
            return false
          }
        }
        return true
      })
    }
  },
  methods: {
    isFilterField (field: string): boolean {
      return Object.keys(this.filterFields).includes(field)
    },
    filterFieldOptions (field: string): any[] {
      // assumes isFilterField(field)
      return this.filterFields[field]
    },
    format (field: string, value: any): any {
      switch (field) {
        case 'subject':
        case 'dir_obj':
        case 'ind_obj':
          if (value) {
            return UI.agr(value)
          }
          break
        case 'phonetic':
          return value ? `/${value}/` : ''
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
