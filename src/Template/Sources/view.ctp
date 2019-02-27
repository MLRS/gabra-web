<?php $this->assign('title', __('Sources')) ?>
<div class="sources view">

  <h2>
    <?php echo __('Source'); ?>:
    <?php echo h($source->source['key']); ?>
  </h2>

  <dl>
    <dt><?php echo __('Title'); ?></dt>
    <dd><?php echo h($source->source['title']); ?>&nbsp;</dd>

    <dt><?php echo __('Authors'); ?></dt>
    <dd><?php echo h($source->source['author']); ?>&nbsp;</dd>

    <dt><?php echo __('Year'); ?></dt>
    <dd><?php echo h($source->source['year']); ?>&nbsp;</dd>

    <dt><?php echo __('Note'); ?></dt>
    <dd><?php echo h($source->source['note']); ?>&nbsp;</dd>

  </dl>

</div>
