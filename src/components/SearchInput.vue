<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
const route = useRoute()

defineProps<{
  placeholder: string,
  showSubmit: boolean
}>();

const emit = defineEmits<{
  (e: 'update', value: string): void
}>()

const term = ref('') // updated by watch
const showKeyboard = ref(false)
let position = 0

function toggleKeyboard (): void {
  showKeyboard.value = !showKeyboard.value
}

// Update cursor position on input
function updatePosition (e: Event): void {
  if (e.target) {
    position = (e.target as HTMLInputElement).selectionStart || 0
  }
}

function insert (letter: string): void {
  term.value = term.value.slice(0, position) + letter + term.value.slice(position)
  position += letter.length
}

watch(
  () => route.query.s,
  () => {
    if (route.name === 'lexemes') {
      term.value = route.query.s as string || ''
    } else {
      term.value = ''
    }
  },
  { immediate: true }
)

watch(term, () => {
  emit('update', term.value)
})
</script>

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

<style lang="scss">
@use '@/assets/custom.scss';

.keyboard button {
  @extend .border;
  @extend .btn-outline-secondary;
}
</style>
