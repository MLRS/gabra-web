<?php $this->assign('title', __('Add user')) ?>
<div class="users form">
  <?php echo $this->Form->create($user); ?>

  <legend><?php echo __('Add user'); ?></legend>
  <?php
  echo $this->Form->input('username', array(
    'div'=>array('class'=>'form-group'),
    'class'=>'form-control',
  ));
  echo $this->Form->input('password', array(
    'div'=>array('class'=>'form-group'),
    'class'=>'form-control',
  ));
  echo $this->Form->input('role', array(
    'options' => $roleOptions,
    'div'=>array('class'=>'form-group'),
    'class'=>'form-control',
  ));

  echo $this->Form->submit('Save', array(
    'class' => 'btn btn-primary'
  ));
  echo $this->Form->end();
  ?>
</div>
