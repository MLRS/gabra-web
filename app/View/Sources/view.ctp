<?php $this->assign('title', __('Sources')) ?>
<div class="sources view">

  <h2>
    <?php echo __('Source'); ?>:
    <?php echo h($source['Source']['key']); ?>
  </h2>

  <dl>
    <dt><?php echo __('Title'); ?></dt>
    <dd><?php echo h($source['Source']['title']); ?>&nbsp;</dd>

    <dt><?php echo __('Authors'); ?></dt>
    <dd><?php echo h($source['Source']['author']); ?>&nbsp;</dd>

    <dt><?php echo __('Year'); ?></dt>
    <dd><?php echo h($source['Source']['year']); ?>&nbsp;</dd>

    <dt><?php echo __('Note'); ?></dt>
    <dd><?php echo h($source['Source']['note']); ?>&nbsp;</dd>

  </dl>

</div>
