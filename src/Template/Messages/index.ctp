<div class="messages index">

  <h3>
    <?php echo __('Messages'); ?>
    <?php echo $this->Html->link($this->UI->icon('plus',__('New message')), 'add', array('class'=>'btn btn-success', 'escape'=>false)); ?>

    <form role="search" class="form-inline" action="<?php echo $this->Url->build('/messages') ?>" type="get" style="display: inline-block">
      <div class="input-group">
        <input type="search" name="s" class="form-control" id="message-search" autofocus="true"
               value="<?php echo @$queryObj->raw_query ?>"
               placeholder="<?php echo __("Search messages") ?>"
        />
        <div class="input-group-btn">
          <button type="submit" class="btn btn-primary"><?php echo $this->UI->icon('search') ?></button>
        </div><!-- input-group-btn -->

      </div><!-- input-group -->
    </form>

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
      <td><?php echo h($message->message['type']); ?>&nbsp;</td>
      <td><?php echo h($message->message['key']); ?>&nbsp;</td>
      <td><?php echo h($message->message['eng']); ?>&nbsp;</td>
      <td><?php echo h($message->message['mlt']); ?>&nbsp;</td>
      <td class="actions">
	<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $message->message['_id'])); ?>
	<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $message->message['_id']), array('class'=>'text-danger'), __('Are you sure you want to delete # {0}?', $message->message['_id'])); ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <?php echo $this->CustomPaginator->links(); ?>

</div>
