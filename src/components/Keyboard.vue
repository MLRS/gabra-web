<template>
  <div class="input-group-prepend keyboard">
    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <i class="far fa-keyboard"></i>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li v-for="letter in ['ċ','ġ','għ','ħ','ż']" :key="letter">
        <a href="#" class="btn btn-default">{{ letter }}</a>
        <!--  onclick="$('input#<?php echo $search_id ?>').insertAtCursor('<?php echo $l ?>').focus(); return false;")"><? -->
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
export default {
  methods: {
  }
}
</script>
