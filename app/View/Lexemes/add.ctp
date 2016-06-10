<div class="alert alert-warning">
  You shouldn't use this outdated page.
  Use the <a class="" href="<?php echo API_URL ?>lexemes/add">new lexeme</a> page on the Ġabra API site.
</div>

<?php $this->assign('title', __('Add lexeme')); ?>
<div class="lexemes add">
  <?php echo $this->Form->create('Lexeme', array('type' => 'post'));?>

    <legend><?php echo __('Add lexeme');?></legend>
    <?php
    echo $this->Form->input('lemma', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('alternatives', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
      'after'=>$this->Html->tag('p', __("Use commas to separate multiple alternatives"), array('class'=>'help-block'))
    ));
    echo $this->Form->input('pos', array(
      'type'=>'select',
      'options' => $common['parts_of_speech'],
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('root._id', array(
      'label'=>__('Root'),
      'type'=>'select',
      'options'=>$roots,
      'empty' => true,
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('gloss', array(
      'type'=>'textarea',
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('sources', array(
      'type'=>'text',
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    ?>

    <div id="add-field">
      <?php echo __('New field with name:') ?>
      <input name="" />
      <a href="#"><?php echo __('Add') ?></a>
    </div>

  <?php
    echo $this->Form->submit('Save', array(
      'class' => 'btn btn-primary'
    ));
    echo $this->Form->end();
  ?>
</div>
