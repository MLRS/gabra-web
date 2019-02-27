<?php /*

Variables:
  search_id
  width

*/ ?>
<div class="input-group-btn keyboard">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <!-- ċġħż -->
    <?php echo $this->Html->image('icon_36974_keyboard.svg', array('width'=>$width)) ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
  <?php foreach (array('ċ','ġ','għ','ħ','ż') as $l): ?>
    <li><a href="#" class="btn btn-default" onclick="$('input#<?php echo $search_id ?>').insertAtCursor('<?php echo $l ?>').focus(); return false;")"><?php echo $l ?></a></li>
  <?php endforeach ?>
  </ul>
</div><!-- input-group-btn -->
