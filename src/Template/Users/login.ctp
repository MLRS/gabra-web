<?php $this->assign('title', __('Login')) ?>
<div class="users">
<?php echo $this->Flash->render('auth', array('element'=>'flash','params'=>array('class'=>'warning'))); ?>
<h2><?php echo __('Login') ?></h2>
<?php echo $this->Form->create('User', array('class'=>"form-inline")); ?>
    <p><?php echo __('Please enter your username and password'); ?></p>
    <?php
    echo $this->Form->input('username', array(
      'label'=> array('class'=>'sr-only'),
      'div'=> array('class'=>'form-group'),
      'class'=>'form-control',
      'placeholder'=>__('Username')
    ));
    echo ' ';
    echo $this->Form->input('password', array(
      'label'=> array('class'=>'sr-only'),
      'div'=> array('class'=>'form-group'),
      'class'=>'form-control',
      'placeholder'=>__('Password')
    ));
    echo ' ';
    echo $this->Form->submit(__('Login'), array(
      'div'=>false,
      'class'=>'btn btn-primary'
    ));
    echo $this->Form->end();
    ?>
</div>
