<script setup lang="ts">
import axios from 'axios'
import { computed, ref, watch } from 'vue'

import { __ } from '@/components/I18N.ts'
import * as UI from '@/helpers/UI.ts'

import { useRoute } from 'vue-router'
const route = useRoute()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const root = ref<Root | null>(null)
const lexemes = ref<Lexeme[] | null>(null)

watch(
  () => route.params,
  () => {
    load()
    let title = route.params.radicals
    switch (route.params.variant) {
      case '1': title += ' ¹'; break
      case '2': title += ' ²'; break
      case '3': title += ' ³'; break
      case '4': title += ' ⁴'; break
      case '5': title += ' ⁵'; break
    }
    store.setTitle(title as string)
  },
  {
    immediate: true
  }
)

const rootClass = computed<string>(() => {
  if (!root.value) return ''
  let out = ''
  if (root.value.type !== 'irregular') {
    const radicalCount = root.value.radicals.split('-').length
    out += radicalCount === 4 ? __('root.quad') : __('root.tri')
  }
  out += ' ' + __(`root.type.${root.value.type}`)
  return out
})

// get root and lexemes
function load() {
  const path = route.params.variant ? `${route.params.radicals}/${route.params.variant}` : `${route.params.radicals}`
  axios.get(`${process.env.VUE_APP_API_URL}/roots/${path}`)
    .then(response => {
      root.value = response.data
    })
    .catch(error => {
      store.addError(error)
      root.value = {} as Root
    })
  axios.get(`${process.env.VUE_APP_API_URL}/roots/lexemes/${path}`)
    .then(response => {
      lexemes.value = response.data
    })
    .catch(error => {
      store.addError(error)
      lexemes.value = []
    })
}
</script>

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

            <span v-if="root.alternatives" class="alternative">
              ({{ root.alternatives }})
            </span>
          </span>
        </h1>
      </div>

      <!-- details -->
      <div class="col-md-4">
        <dl>

          <dt>{{ __('root.type') }}</dt>
          <dd>{{ rootClass }}</dd>

          <dt>{{ __('source') }}</dt>
          <dd>
            <template v-for="s,ix in root.sources" :key="ix">
              <router-link :to="{ name: 'sources' }" class="">
                {{ s }}
              </router-link>
              <span v-if="ix < root.sources.length - 1" :key="ix">,</span>
            </template>
          </dd>

        </dl>
      </div>

      <!-- lexemes -->
      <div class="col-md-8">

        <h2 class="h6 text-capitalize">{{ __('lexemes') }}</h2>

        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexemes === null"></i>

        <table class="table table-sm">
          <tbody>
            <tr v-for="lexeme,ix in lexemes" :key="ix">
              <td class="">
                <router-link :to="{ name: 'lexeme', params: { id: lexeme._id } }" class="surface_form">{{ lexeme.lemma }}</router-link>
                <span class="text-lighter ml-1">
                  {{ __(`pos.${lexeme.pos }`) }}
                  {{ UI.derivedForm(lexeme.derived_form || 0) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

      </div>

    </div><!-- row -->
  </div>
</template>

<style lang="scss">
@import '@/assets/custom.scss';

dt {
  @extend .text-capitalize;
}
</style>
