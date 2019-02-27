<div class="messages form">
<?php echo $this->Form->create($message); ?>
  <fieldset>
    <legend><?php echo __('Add message'); ?></legend>
    <?php
    echo $this->Form->input('key', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('type', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    echo $this->Form->input('eng', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
      'type'=>'textarea',
      'maxlength'=>null,
    ));
    echo $this->Form->input('mlt', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
      'type'=>'textarea',
      'maxlength'=>null,
    ));
    ?>
  </fieldset>
  <?php
  echo $this->Form->submit(__('Add'), array(
    'class'=>'btn btn-primary',
    'after'=>$this->Html->link(__('Cancel'), array('action'=>'index'), array('class'=>'btn btn-link'))
  ));
  echo $this->Form->end();
  ?>
</div>
