<?php $this->assign('title', __('User').': '.h($user['User']['username'])) ?>
<div class="users view">
  <h2><?php  echo __('User'); ?></h2>
  <dl>
    <dt><?php echo __('ID'); ?></dt>
    <dd>
      <?php echo h($user['User']['id']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Username'); ?></dt>
    <dd>
      <?php echo h($user['User']['username']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Role'); ?></dt>
    <dd>
      <?php echo h($user['User']['role']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Created'); ?></dt>
    <dd>
      <?php echo $this->UI->date($user['User']['created']); ?>&nbsp;
    </dd>
    <dt><?php echo __('Modified'); ?></dt>
    <dd>
      <?php echo $this->UI->date($user['User']['modified']); ?>&nbsp;
    </dd>
  </dl>
</div>
