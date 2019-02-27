<?php $this->assign('title', __('Users')) ?>
<div class="users index">

  <h3><?php echo __('Users'); ?></h3>

  <table class="table table-striped">
    <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('username'); ?></th>
      <th><?php echo $this->Paginator->sort('role'); ?></th>
      <th><?php echo $this->Paginator->sort('created'); ?></th>
      <th><?php echo $this->Paginator->sort('modified'); ?></th>
      <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($users as $user): ?>
    <tr>
      <td><?php echo h($user->user['username']); ?>&nbsp;</td>
      <td><?php echo h($user->user['role']); ?>&nbsp;</td>
      <td><?php echo $this->UI->date($user->user['created']); ?>&nbsp;</td>
      <td><?php echo $this->UI->date($user->user['modified']); ?>&nbsp;</td>
      <td class="actions">
	<?php echo $this->Html->link(__('View'), array('action' => 'view', $user->user['id'])); ?>
	<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user->user['id'])); ?>
	<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user->user['id']), null, __('Are you sure you want to delete # {0}?', $user->user['id'])); ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <?php echo $this->CustomPaginator->links(); ?>

</div>
