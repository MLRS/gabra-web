<?php $item = $root; ?>
<?php $this->assign('title', __('Root').': '.$root->root['radicals']); ?>
<div class="roots view">

  <h2 class="bigword">
    <?php echo __('Root'); ?>:
    <?php echo $this->UI->root($item, array('include_link'=>false)); ?>

    <?php if (@$item->root['alternatives']): ?>
    <small class="alt root"><?php echo $this->UI->alternatives(@$item->root['alternatives'], array()); ?></small>
    <?php endif; ?>
  </h2>

  <dl>

    <dt><?php echo __('Class'); ?></dt>
    <dd>
      <?php
        if ($root->root['type'] != 'irregular')
        echo (($root->root['radical_count'] == 4) ? __('Quad.') : __('Tri.')).' ';
        echo $common['root_types'][$root->root['type']];
      ?>
    </dd>

    <?php if (@$item->root['sources']): ?>
    <dt><?php echo __('Source(s)'); ?></dt>
    <dd><?php
        foreach ($item->root['sources'] as $i=>$source) {
          $s = $source;
          echo $this->Html->link($s, array('controller' => 'Sources','action' => 'view',$s));
          if ($i < count($item->root['sources'])-1) echo ', ';
        }
    ?></dd>
    <?php endif; ?>

    <dt><?php echo __('Related'); ?></dt>
    <dd>
      <?php if (@$related): ?>
      <?php foreach ($related as $item): ?>
      <?php echo $this->Html->link($item->lexeme['lemma'], array('controller' => 'Lexemes','action' => 'view',$item->lexeme['_id']), array('class'=>'surface_form')); ?>
      <span class="text-muted">
        <?php echo $this->UI->posTag(@$item->lexeme['pos']); ?>
        <?php if (@$item->lexeme['derived_form']) echo $this->UI->derivedForm($item['Lexeme']); ?>
      </span>
      <br/>
      <?php endforeach; ?>
      <?php endif; ?>
    </dd>

  </dl>

</div>
