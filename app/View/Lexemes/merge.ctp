<?php $this->assign('title', __('Merge')); ?>
<div class="lexemes merge">

  <!-- Left panel (the item being merged into) -->
  <div class="col-sm-6">
    <h3><?php echo __('Merge into (keep this entry):');?></h3>

  <?php echo $this->Form->create('Lexeme', array('type' => 'post'));?>
  <fieldset>
    <?php
    echo $this->Form->hidden('_id');
    echo $this->Form->input('lemma', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));

    echo $this->Form->input('pos', array(
      'type'=>'select',
      'options' => $common['parts_of_speech'],
      'selected'=> @$this->request->data['Lexeme']['pos'],
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    $root = @$this->request->data['Lexeme']['root']['radicals'];
    if (@$this->request->data['Lexeme']['root']['variant']) $root .= ' '.$this->request->data['Lexeme']['root']['variant'];
    echo $this->Form->input('root._id', array(
      'label'=>__('Root'),
      'type'=>'select',
      'options'=>$roots,
      'selected'=>array_search($root, $roots),
      'empty' => true,
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    $_gloss = @$this->request->data['Lexeme']['gloss'] . (@$other['Lexeme']['gloss'] ? "\n".$other['Lexeme']['gloss'] : '');
    echo $this->Form->input('gloss', array(
      'type'=>'textarea',
      'rows'=> max(5, substr_count($_gloss, "\n")+2),
      'value'=> $_gloss,
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    $this->Js->buffer("$('textarea').first().focus()");

    $_source = @$this->request->data['Lexeme']['source'] . (stripos(@$this->request->data['Lexeme']['source'], @$other['Lexeme']['source'])===false ? ", ".$other['Lexeme']['source'] : '');
    echo $this->Form->input('source', array(
      'value'=> $_source,
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    ?>
    <div class="btn-toolbar">
    <div class="btn-group">
      <?php echo $this->Form->submit('Save', array('class'=>'btn btn-primary', 'div'=>false)); ?>
    </div>
    <div class="btn-group">
      <?php echo $this->Html->link(__('Swap'), array('action' => 'merge', $id2, $id1), array('class'=>'btn btn-default')); ?>
    </div>
    </div>
    <?php

    $remaining = array_diff_key($this->request->data['Lexeme'], array_flip(array(
      'id','_id','lemma','pos','gloss','Root','root','root_id','modified','created', 'source'
    )));
    foreach ($remaining as $k=>$v) {
      if (gettype($v) == 'boolean') {
        echo $this->Form->input($k, array(
          'type'=>'checkbox',
          'div'=>array('class'=>'checkbox'),
          'label'=>false,
          'before'=>'<label>',
          'after'=>$k.'</label>',
        ));
      } else {
        echo $this->Form->input($k, array(
          'div'=>array('class'=>'form-group'),
          'class'=>'form-control',
        ));
      }
    }
  ?>
  </fieldset>
  <?php echo $this->Form->end();?>
  </div>

  <!-- Right panel (the item being deleted) -->
  <div class="col-sm-6">
    <h3><?php echo __('Merge from (delete this entry):'); ?></h3>

    <dl>
      <!--dt><?php echo __('Lemma'); ?></dt>
      <dd><?php echo h($other['Lexeme']['lemma']); ?></dd-->

      <?php foreach ($other['Lexeme'] as $k=>$v): ?>
      <dt><?php echo $k; ?></dt>
      <dd><?php echo is_array($v) ? '<pre>'.print_r($v,true).'</pre>' : h($v?$v:'-'); ?></dd>
      <?php endforeach; ?>
    </dl>

    <?php if ($other_wf): ?>
    <h4><?php echo __('Wordforms') ?></h4>
    <?php echo __('On merging, these will be associated with the remaining entry') ?>
    <ul>
    <?php foreach ($other_wf as $wf): ?>
      <li><?php echo h($wf['Wordform']['surface_form']); ?></li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>

  </div>
</div>
