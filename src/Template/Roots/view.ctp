<?php $this->assign('title', __('Root').': '.$root['radicals']); ?>
<div class="roots view">

  <h2 class="bigword">
    <?php echo __('Root'); ?>:
    <?php echo $this->UI->root($root, array('include_link'=>false)); ?>

    <?php if (@$root['alternatives']): ?>
    <small class="alt root"><?php echo $this->UI->alternatives(@$root['alternatives'], array()); ?></small>
    <?php endif; ?>
  </h2>

  <dl>

    <dt><?php echo __('Class'); ?></dt>
    <dd>
      <?php
        if ($root['type'] != 'irregular')
        echo (($root['radical_count'] == 4) ? __('Quad.') : __('Tri.')).' ';
        echo $common['root_types'][$root['type']];
      ?>
    </dd>

    <?php if (@$root['sources']): ?>
    <dt><?php echo __('Source(s)'); ?></dt>
    <dd><?php
        foreach ($root['sources'] as $i=>$source) {
          $s = $source;
          echo $this->Html->link($s, array('controller' => 'Sources','action' => 'view',$s));
          if ($i < count($root['sources'])-1) echo ', ';
        }
    ?></dd>
    <?php endif; ?>

    <dt><?php echo __('Related'); ?></dt>
    <dd>
      <?php if (@$related): ?>
      <?php foreach ($related as $lexeme): ?>
      <?php echo $this->Html->link($lexeme['lemma'], array('controller' => 'Lexemes','action' => 'view',$lexeme['_id']), array('class'=>'surface_form')); ?>
      <span class="text-muted">
        <?php echo $this->UI->posTag(@$lexeme['pos']); ?>
        <?php if (@$lexeme['derived_form']) echo $this->UI->derivedForm($lexeme); ?>
      </span>
      <br/>
      <?php endforeach; ?>
      <?php endif; ?>
    </dd>

  </dl>

</div>
