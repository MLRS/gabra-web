<?php $item = $root; ?>
<?php $this->assign('title', __('Root').': '.$root['Root']['radicals']); ?>
<div class="roots view">

  <h2 class="bigword">
    <?php echo __('Root'); ?>:
    <?php echo $this->UI->root($item, array('include_link'=>false)); ?>

    <?php if (@$item['Root']['alternatives']): ?>
    <small class="alt root"><?php echo $this->UI->alternatives(@$item['Root']['alternatives'], array()); ?></small>
    <?php endif; ?>
  </h2>

  <dl>

    <dt><?php echo __('Class'); ?></dt>
    <dd>
      <?php
        if ($root['Root']['type'] != 'irregular')
        echo (($root['Root']['radical_count'] == 4) ? __('Quad.') : __('Tri.')).' ';
        echo $common['root_types'][$root['Root']['type']];
      ?>
    </dd>

    <?php if (@$item['Root']['sources']): ?>
    <dt><?php echo __('Source(s)'); ?></dt>
    <dd><?php
        foreach ($item['Root']['sources'] as $i=>$source) {
          $s = $source;
          echo $this->Html->link($s, array('controller'=>'sources','action'=>'view',$s));
          if ($i < count($item['Root']['sources'])-1) echo ', ';
        }
    ?></dd>
    <?php endif; ?>

    <dt><?php echo __('Related'); ?></dt>
    <dd>
      <?php if (@$related): ?>
      <?php foreach ($related as $item): ?>
      <?php echo $this->Html->link($item['Lexeme']['lemma'], array('controller'=>'lexemes','action'=>'view',$item['Lexeme']['_id']), array('class'=>'surface_form')); ?>
      <span class="text-muted">
        <?php echo $this->UI->posTag(@$item['Lexeme']['pos']); ?>
        <?php if (@$item['Lexeme']['derived_form']) echo $this->UI->derivedForm($item['Lexeme']); ?>
      </span>
      <br />
      <?php endforeach; ?>
      <?php endif; ?>
    </dd>

  </dl>

</div>
