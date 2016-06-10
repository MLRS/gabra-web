<?php $this->assign('title', __('News')) ?>
<h2><?php echo __('News') ?></h2>

<div>

<?php foreach($news as $item): ?>
  <?php if (@$item['Message'][$language]): ?>
  <div class="row">
    <div class="col-sm-2">
      <p class="text-right">
        <strong>
          <?php echo $this->UI->date($item['Message']['created'], array('format'=>'Y-m-d')) ?>
        </strong>
      </p>
    </div>
    <div class="col-sm-10">
      <p>
        <?php echo h($item['Message'][$language]) ?>
      </p>
    </div>
  </div><!-- row -->
<?php endif ?>
<?php endforeach ?>

</div>