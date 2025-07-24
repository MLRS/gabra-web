
<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import { __ } from '@/components/I18N.ts'
import * as UI from '@/helpers/UI.ts'

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
const agrOptions: Array<'' | Agreement> = [
  '',
  { person: 'p1', number: 'sg' },
  { person: 'p2', number: 'sg' },
  { person: 'p3', number: 'sg', gender: 'm' },
  { person: 'p3', number: 'sg', gender: 'f' },
  { person: 'p1', number: 'pl' },
  { person: 'p2', number: 'pl' },
  { person: 'p3', number: 'pl' }
]

interface Wordform {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  [key: string]: any
}

const props = defineProps<{
  lexeme: Lexeme,
  wordforms: Wordform[]
}>()

// active filters
// stores "formatted" value: {person: 'p1', number: 'sg'} -> 'p1 sg'
// empty string means only match when field is missing
const filters = ref<{[key: string]: string}>({})

watch(
  () => props.lexeme,
  () => {
    let fs = {}
    switch (props.lexeme.pos) {
      case 'VERB':
        fs = {
          'dir_obj': '',
          'ind_obj': '',
          'polarity': 'pos'
        }
        break
    }
    filters.value = fs
  },
  {
    immediate: true
  }
)

const showFields = computed<string[]>(() => {
  if (!props.wordforms) return []
  const fields = new Set()
  for (const wf of props.wordforms) {
    for (const f in wf) {
      if (!ignoreFields.has(f)) {
        fields.add(f)
      }
    }
  }
  const fieldsArray = Array.from(fields) as string[]
  return fieldsArray.sort((a: string, b: string): number => {
    // Sort by order in knownFields
    const xa = knownFields.indexOf(a)
    const xb = knownFields.indexOf(b)
    if (xa > -1 && xb > -1) return xa - xb
    else if (xa === -1 && xb > -1) return 1
    else if (xa > -1 && xb === -1) return -1
    else {
      if (a < b) return -1
      else if (b > a) return 1
      else return 0
    }
  })
})

interface FilterFields {
  'dir_obj'?: typeof agrOptions,
  'ind_obj'?: typeof agrOptions,
  'polarity'?: ['pos', 'neg'],
  [key: string]: FilterFieldsOptions | undefined
}

type FilterFieldsOptions = typeof agrOptions | ['pos', 'neg']

const filterFields = computed<FilterFields>(() => {
  switch (props.lexeme.pos) {
    case 'VERB':
      return {
        'dir_obj': agrOptions,
        'ind_obj': agrOptions,
        'polarity': ['pos', 'neg']
      }
    default:
      return {}
  }
})

function isFilterField(field: string): boolean {
  return Object.keys(filterFields.value).includes(field)
}

function filterFieldOptions(field: string): FilterFieldsOptions | undefined {
  // assumes isFilterField(field)
  return filterFields.value[field]
}

const filteredWordforms = computed<Wordform[]>(() => {
  if (!props.wordforms) return []
  return (props.wordforms).filter((wf: Wordform): boolean => {
    for (const field in filters.value) {
      const filterValue = filters.value[field]
      const wf_field = wf[field] 
      if (!filterValue && wf_field) {
        return false
      }
      if (filterValue && format(field, wf_field) !== filterValue) {
        return false
      }
    }
    return true
  })
})

// eslint-disable-next-line @typescript-eslint/no-explicit-any
function format(field: string, value: any): any {
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
</script>

<template>
  <table class="table table-sm">
    <thead>
      <tr class="text-capitalize">
        <th>{{ __('surface_form') }}</th>
        <th v-for="f,ix in showFields" :key="ix">
          {{ __(f) }}
        </th>
      </tr>
      <tr v-if="Object.keys(filterFields).length > 0">
        <th></th>
        <th v-for="f,ix in showFields" :key="ix">
          <select class="form-select-sm" v-if="isFilterField(f)" v-model="filters[f]">
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
