<div class="messages index">

  <h3>
    <?php echo __('Messages'); ?>
    <?php echo $this->Html->link(__('New message'), 'add', array('class'=>'btn btn-default')); ?>
  </h3>

  <?php echo $this->CustomPaginator->links(); ?>

  <table class="table table-striped table-condensed table-hover">
    <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('type'); ?></th>
      <th><?php echo $this->Paginator->sort('key'); ?></th>
      <th><?php echo __('English');?></th>
      <th><?php echo __('Maltese');?></th>
      <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($messages as $message): ?>
    <tr>
      <td><?php echo h($message['Message']['type']); ?>&nbsp;</td>
      <td><?php echo h($message['Message']['key']); ?>&nbsp;</td>
      <td><?php echo h($message['Message']['eng']); ?>&nbsp;</td>
      <td><?php echo h($message['Message']['mlt']); ?>&nbsp;</td>
      <td class="actions">
	<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $message['Message']['_id'])); ?>
	<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $message['Message']['_id']), array('class'=>'text-danger'), __('Are you sure you want to delete # %s?', $message['Message']['_id'])); ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <?php echo $this->CustomPaginator->links(); ?>

</div>
