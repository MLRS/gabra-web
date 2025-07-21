<script setup lang="ts">
import axios from 'axios'
import { computed, ref, onMounted, watch } from 'vue'

import { __ } from '@/components/I18N.ts'
import Root from '@/components/Root.vue'
import WordformsTable from '@/components/WordformsTable.vue'
import * as UI from '@/helpers/UI.ts'

import { useRoute } from 'vue-router'
const route = useRoute()

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const lexeme = ref<Lexeme | null>(null)
const wordforms = ref<Wordform[] | null>(null)
const related = ref<Lexeme[] | null>(null)

watch(
  () => route.query,
  () => {
    load()
  },
  {
    immediate: true
  }
)

const examples = computed<string[]>(() => {
  if (!lexeme.value || !lexeme.value.glosses) return []
  const egs = []
  for (const g of lexeme.value.glosses) {
    for (const e of g.examples || []) {
      if (e.type === 'full') {
        egs.push(e.example)
      }
    }
  }
  return egs
})

// get lexeme and wordforms
function load() {
  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/${route.params.id}`)
    .then(response => {
      lexeme.value = response.data
      store.setTitle((lexeme.value as Lexeme).lemma)
    })
    .catch(error => {
      store.addError(error)
      lexeme.value = {} as Lexeme
    })
  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/wordforms/${route.params.id}?pending=1`)
    .then(response => {
      wordforms.value = response.data
    })
    .catch(error => {
      store.addError(error)
      wordforms.value = []
    })
  axios.get(`${import.meta.env.VITE_API_URL}/lexemes/related/${route.params.id}`)
    .then(response => {
      related.value = response.data
    })
    .catch(error => {
      store.addError(error)
      related.value = []
    })
}

onMounted(() => {
  store.setTitle({ key: 'title.lexeme' })
})
</script>

<template>
  <div>

    <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="lexeme === null"></i>

    <div class="row" v-if="lexeme !== null && lexeme.lemma">

      <div class="col-12">
        <h1 class="surface_form mb-3">
          <span class="text-shadow">{{ lexeme.lemma }}</span>
          <small v-if="lexeme.alternatives" class="alternative">
            ({{ lexeme.alternatives.join(', ') }})
          </small>
        </h1>
      </div>

      <!-- lexeme -->
      <div class="col-md-4">
        <dl>

          <template v-if="lexeme.phonetic">
            <dt>{{ __('phonetic') }}</dt>
            <dd>
              /{{ lexeme.phonetic }}/
            </dd>
          </template>

          <dt>{{ __('part_of_speech') }}</dt>
          <dd>
            {{ __(`pos.${lexeme.pos }`) }}
          </dd>

          <dt>{{ __('english_gloss') }}</dt>
          <dd>
            <ul class="pl-4">
              <li v-for="g,ix in lexeme.glosses" :key="ix">
                {{ g.gloss }}
              </li>
            </ul>
          </dd>

          <template v-if="lexeme.root">
            <dt>{{ __('root') }}</dt>
            <dd>
              <Root :root="lexeme.root"></Root>
            </dd>
          </template>

          <dt>{{ __('features') }}</dt>
          <dd>
            <div>{{ lexeme.derived_form ? __('derived_form') + ' ' + UI.derivedForm(lexeme.derived_form) : '' }}</div>
            <div>{{ lexeme.frequency }}</div>
            <div>{{ lexeme.onomastic_type }}</div>
            <div>{{ lexeme.transitive ? __('transitive') : '' }}</div>
            <div>{{ lexeme.intransitive ? __('intransitive') : '' }}</div>
            <div>{{ lexeme.ditransitive ? __('ditransitive') : '' }}</div>
            <div>{{ lexeme.hypothetical ? __('hypothetical') : '' }}</div>
          </dd>

          <dt>{{ __('source') }}</dt>
          <dd>
            <template v-for="s,ix in lexeme.sources" :key="ix">
              <router-link :to="{ name: 'sources' }" class="">{{ s }}</router-link>
              <span v-if="ix < lexeme.sources.length - 1" :key="ix+'comma'">, </span>
            </template>
          </dd>

          <dt v-if="related && related.length > 0">{{ __('related_entries') }}</dt>

          <dd>
            <div v-for="r,ix in related" :key="ix">
            <router-link :to="{ name: 'lexeme', params: { id: r._id } }" class="surface_form">{{ r.lemma }}</router-link>
            <span class="text-lighter ml-1">
              {{ __(`pos.${r.pos }`) }}
              {{ UI.derivedForm(r.derived_form || 0) }}
            </span>
          </div>
          </dd>

        </dl>
      </div>

      <div class="col-md-8">
        <!-- wordforms -->
        <i class="fas fa-circle-notch fa-2x fa-spin text-danger" v-show="wordforms === null"></i>
        <!-- <h2 class="h6 text-capitalize fw-bold">{{ __('word_forms') }}</h2> -->
        <wordforms-table :lexeme="lexeme" :wordforms="wordforms" v-if="wordforms !== null && wordforms.length > 0"></wordforms-table>

        <!-- examples -->
        <h2 v-if="examples.length > 0" class="h6 text-capitalize fw-bold">{{ __('examples') }}</h2>
        <ul>
          <li v-for="e, ix in examples" :key="ix" class="surface_form">
            “{{ e }}”
          </li>
        </ul>
      </div>

    </div><!-- row -->
  </div>
</template>

<style lang="scss">
@use '@/assets/custom.scss';

dt {
  @extend .text-capitalize;
}
</style>
