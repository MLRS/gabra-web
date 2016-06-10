<?php $this->assign('title', __('Sources')) ?>
<div class="sources index">

  <h2><?php echo __('Sources') ?></h2>

  <table class="table table-striped">
    <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('key'); ?></th>
      <th><?php echo $this->Paginator->sort('title'); ?></th>
      <th><?php echo $this->Paginator->sort('author'); ?></th>
      <th><?php echo $this->Paginator->sort('year'); ?></th>
      <th><?php echo __('Note'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($sources as $source): ?>
    <tr>
      <td><?php echo h($source['Source']['key']); ?>&nbsp;</td>
      <td><?php echo h($source['Source']['title']); ?>&nbsp;</td>
      <td><?php echo h($source['Source']['author']); ?>&nbsp;</td>
      <td><?php echo h($source['Source']['year']); ?>&nbsp;</td>
      <td><?php echo h($source['Source']['note']); ?>&nbsp;</td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

</div>
