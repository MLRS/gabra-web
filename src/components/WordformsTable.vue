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

const agrFields = [
  'subject',
  'dir_obj',
  'ind_obj'
]

export default mixins(I18N).extend({
  props: {
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
      return Array.from(fields)
    }
  },
  methods: {
    format (field, value): string {
      if (agrFields.includes(field) && value) {
        let s = ''
        s += value.person + ' '
        s += value.number + ' '
        if (value.gender) s += this.__(`gender.${value.gender}`)
        return s
      } else {
        return value
      }
    }
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

</style>
