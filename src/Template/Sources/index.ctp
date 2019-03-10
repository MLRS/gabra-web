<?php $this->assign('title', __('Sources')) ?>
<div class="sources index">

  <h2><?php echo __('Sources') ?></h2>

  <table class="table table-striped">
    <thead>
    <tr>
      <th><?php echo __('Key'); ?></th>
      <th><?php echo __('Title'); ?></th>
      <th><?php echo __('Author'); ?></th>
      <th><?php echo __('Year'); ?></th>
      <th><?php echo __('Note'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($sources as $source): ?>
    <tr>
      <td><?php echo h($source['key']); ?>&nbsp;</td>
      <td><?php echo h($source['title']); ?>&nbsp;</td>
      <td><?php echo h($source['author']); ?>&nbsp;</td>
      <td><?php echo h($source['year']); ?>&nbsp;</td>
      <td><?php echo h($source['note']); ?>&nbsp;</td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

</div>
