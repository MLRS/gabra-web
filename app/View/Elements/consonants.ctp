<div class="paging">
<?php
foreach ($common['consonants'] as $l) {
  $class = ($l=='b')?' prev':(($l=='z')?' next':'');
  if (@$letter==$l) {
    echo $this->Html->tag(
      'span',
      $l,
      array('class'=>'letter current'.$class)
    );
  } else {
    echo $this->Html->tag(
      'span',
      $this->Html->link(
        $l,
        array('action'=>$this->action,$l)
      ),
      array('class'=>'letter'.$class)
    );
  }
}
?>
</div>
