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
        <button type="cancel" class="btn btn-link ml-2" @click.prevent="$emit('hide')">{{ __('suggest.cancel') }}</button>
      </div>
    </div>
  </form>
</template>

<script lang="ts">
import mixins from 'vue-typed-mixins'

import I18N from '@/components/I18N.ts'

import axios from 'axios'

interface Data {
  working: boolean,
  lemma: string | null,
  gloss: string | null,
  pos: string | null
}

export default mixins(I18N).extend({
  props: {
    word: String,
    pos_options: Array
  },
  data (): Data {
    return {
      working: false,
      lemma: null,
      gloss: null,
      pos: 'NOUN'
    }
  },
  methods: {
    submitSuggestion (): void {
      this.$store.dispatch('clearMessages')
      if (!this.lemma || !this.gloss || !this.pos) {
        this.$store.dispatch('addError', this.__('suggest.invalid'))
        return
      }
      this.working = true
      axios.post(`${process.env.VUE_APP_API_URL}/feedback/suggest`, {
        lemma: this.lemma,
        gloss: this.gloss,
        pos: this.pos
      })
        .then(response => {
          this.$emit('hide')
          this.$emit('done')
        })
        .catch(error => {
          this.$store.dispatch('addError', error)
        })
        .then(() => {
          this.working = false
        })
    }
  },
  watch: {
    word: {
      handler (): void {
        this.lemma = this.word
        this.gloss = null
      },
      immediate: true
    }
  }
})
</script>
