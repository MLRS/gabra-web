<?php $this->assign('title', __('News')) ?>
<h2><?php echo __('News') ?></h2>

<div>

<?php foreach($news as $item): ?>
  <?php if (@$item->message[$language]): ?>
  <div class="row">
    <div class="col-sm-2">
      <p class="text-right">
        <strong>
          <?php echo $this->UI->date($item->message['created'], array('format'=>'Y-m-d')) ?>
        </strong>
      </p>
    </div>
    <div class="col-sm-10">
      <?php echo $this->Markdown->transform($item->message[$language]) ?>
    </div>
  </div><!-- row -->
<?php endif ?>
<?php endforeach ?>

</div>
