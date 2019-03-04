<?php $this->assign('title', __('Users')) ?>
<div class="users index">

  <h3><?php echo __('Users'); ?></h3>

  <table class="table table-striped">
    <thead>
    <tr>
      <th><?php echo __('Username'); ?></th>
      <th><?php echo __('Role'); ?></th>
      <th><?php echo __('Created'); ?></th>
      <th><?php echo __('Modified'); ?></th>
      <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($users as $user): ?>
    <tr>
      <td><?php echo h($user['username']); ?>&nbsp;</td>
      <td><?php echo h($user['role']); ?>&nbsp;</td>
      <td><?php echo $this->UI->date($user['created']); ?>&nbsp;</td>
      <td><?php echo $this->UI->date($user['modified']); ?>&nbsp;</td>
      <td class="actions">
	<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['_id'])); ?>
	<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['_id'])); ?>
	<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['_id']), [], __('Are you sure you want to delete # {0}?', $user['id'])); ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

</div>
