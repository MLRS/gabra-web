<script setup lang="ts">
import axios from 'axios'
import { ref, watch } from 'vue'

import { __ } from '@/components/I18N.ts'

import { useRootStore } from '@/stores/root'
const store = useRootStore()

const props = defineProps<{
  word: string,
  pos_options: string[]
}>()

const emit = defineEmits(['hide', 'done'])

const working = ref(false)
const lemma = ref<string | null>(null)
const gloss = ref<string | null>(null)
const pos = ref('NOUN')

function submitSuggestion (): void {
  store.clearMessages()
  if (!lemma.value || !gloss.value || !pos.value) {
    store.addError(__('suggest.invalid'))
    return
  }
  working.value = true
  axios.post(`${process.env.VUE_APP_API_URL}/feedback/suggest`, {
    lemma: lemma.value,
    gloss: gloss.value,
    pos: pos.value
  })
    .then(() => {
      emit('hide')
      emit('done')
    })
    .catch(error => {
      store.addError(error)
    })
    .then(() => {
      working.value = false
    })
}

watch(
  () => props.word,
  () => {
    lemma.value = props.word
    gloss.value = null
  },
  {
    immediate: true
  }
)
</script>

<template>
  <form @submit.prevent="submitSuggestion">
    <h3 class="h5 mb-3">{{ __('suggest.title') }}</h3>
    <div class="form-group row">
      <label for="SuggestLemma" class="col-sm-2 col-form-label">{{ __('suggest.lemma.label') }}</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="SuggestLemma" v-model="lemma">
        <small class="form-text text-muted">{{ __('suggest.lemma.description') }}</small>
      </div>
    </div>
    <div class="form-group row">
      <label for="SuggestGloss" class="col-sm-2 col-form-label">{{ __('suggest.gloss.label') }}</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="SuggestGloss" v-model="gloss">
        <small class="form-text text-muted">{{ __('suggest.gloss.description') }}</small>
      </div>
    </div>
    <div class="form-group row">
      <label for="SuggestPOS" class="col-sm-2 col-form-label">{{ __('suggest.pos.label') }}</label>
      <div class="col-sm-10">
        <select class="form-control" id="SuggestPOS" v-model="pos">
          <option v-for="p,ix in pos_options" :key="ix" :value="p">{{ __(`pos.${p}`) }}</option>
        </select>
        <small class="form-text text-muted">{{ __('suggest.pos.description') }}</small>
      </div>
    </div>
    <div class="form-group row mb-1">
      <div class="col text-right">
        <i class="fas fa-circle-notch fa-spin text-danger fa-2x mr-3 align-middle" v-show="working"></i>
        <button type="submit" class="btn btn-primary">{{ __('suggest.submit') }}</button>
        <button type="reset" class="btn btn-link ml-2" @click.prevent="$emit('hide')">{{ __('suggest.cancel') }}</button>
      </div>
    </div>
  </form>
</template>
