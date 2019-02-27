<?php $this->assign('title', __('Edit user')) ?>
<div class="users form">
  <?php echo $this->Form->create($user); ?>

  <legend><?php echo __('Edit user'); ?></legend>
  <?php
  echo $this->Form->input('id'); // hidden
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

  $link_cancel = $this->Html->link(
    __('Cancel'),
    array('action'=>'index'),
    array('class' => 'btn btn-link')
  );
  echo $this->Form->submit('Save', array(
    'class' => 'btn btn-primary',
    'after' => $link_cancel,
  ));

  echo $this->Form->end();
  ?>

</div>
