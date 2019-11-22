<template>
  <div class="input-group">
    <div class="input-group-prepend keyboard">
      <button type="button" class="btn" v-if="!showKeyboard" @click="toggleKeyboard">
        <i class="far fa-keyboard mr-2"></i>
        <i class="fas fa-caret-right"></i>
      </button>
      <button type="button" class="btn btn-default" v-show="showKeyboard"
        v-for="letter in ['ċ','ġ','ħ','ż']" :key="letter"
        @click="insert(letter)"
      >
        {{ letter }}
      </button>
    </div>
    <input type="search" name="s" class="form-control" autofocus="true"
      :placeholder="placeholder"
      @keydown.enter="$event.stopPropagation()"
      @keyup="updatePosition"
      @click="updatePosition"
      v-model="term"
      />
    <div class="input-group-append" v-if="showSubmit">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </div><!-- input-group -->
</template>

<script lang="ts">
import Vue from 'vue'

interface Data {
  term: string,
  showKeyboard: boolean,
  position: number
}

export default Vue.extend({
  props: {
    placeholder: String,
    showSubmit: Boolean
  },
  data (): Data {
    return {
      term: '',
      showKeyboard: false,
      position: 0
    }
  },
  methods: {
    toggleKeyboard (): void {
      this.showKeyboard = !this.showKeyboard
    },
    // Update cursor position on input
    updatePosition (e): void {
      this.position = e.target.selectionStart
    },
    insert (letter: string): void {
      this.term = this.term.slice(0, this.position) + letter + this.term.slice(this.position)
    }
  }
})
</script>

<style lang="scss">
@import '@/assets/custom.scss';

.keyboard button {
  @extend .border;
  background: #e9ecef;
  color: #666;
}
</style>
