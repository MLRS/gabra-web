<?php $this->assign('title', __('User').': '.h($user->user['username'])) ?>
<div class="users view">
  <h2><?php  echo __('User'); ?></h2>
  <dl>
    <dt><?php echo __('ID'); ?></dt>
    <dd>
      <?php echo h($user->user['id']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Username'); ?></dt>
    <dd>
      <?php echo h($user->user['username']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Role'); ?></dt>
    <dd>
      <?php echo h($user->user['role']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Created'); ?></dt>
    <dd>
      <?php echo $this->UI->date($user->user['created']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Modified'); ?></dt>
    <dd>
      <?php echo $this->UI->date($user->user['modified']); ?>&nbsp;
    </dd>
  </dl>
</div>
