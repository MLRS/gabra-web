<?php $headline = $content['home.title'] ?>
<?php $this->assign('title', $headline) ?>
<?php
function __k($t, $k, $p=null, $o=array()) {
  return $t->UI->content($k, $p, $o);
}
?>

<?php /* --------------------------------------------------------------- */ ?>

<div class="jumbotron">
  <h1><?php echo $headline ?></h1>

  <p>
    <?php echo __k($this, 'home.1', array(
      '{lexemes}' => $this->Html->tag('span', number_format($stats['lexemes']), array('class'=>'label label-primary')),
      '{wordforms}' => $this->Html->tag('span', number_format($stats['wordforms']), array('class'=>'label label-info'))
    )) ?>
  </p>

  <p>
  <form role="search" class="" action="<?php echo $this->Html->url('/lexemes') ?>">
    <div class="input-group input-group-lg">
      <?php echo $this->element('keyboard', array('search_id'=>'home-search', 'width'=>'25px')); ?>
      <?php $placeholder = __k($this, 'home.search.placeholder', array('{maltese}' => 'ħarġa', '{english}' => 'outing')) ?>
      <input type="search" id="home-search" name="s" class="form-control" autofocus="true" placeholder="<?php echo h($placeholder) ?>" />
      <div class="input-group-btn">
        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
      </div>
    </div><!-- input-group -->
  </form>
  </p>

</div><!-- jumbotron -->

<?php /* --------------------------------------------------------------- */ ?>

<div class="col-sm-8">

  <?php $img = $this->UI->icon('comment') ?>
  <p><?php echo __k($this, 'home.mistakes', array('{img}' => $img), array('markdown' => true)) ?></p>

  <?php echo $this->UI->content('home.about', null, array('markdown'=>true)); ?>

  <p><?php echo __k($this, 'home.citing') ?></p>

  <p class="text-muted">
    <?php echo __k($this, 'home.license') ?>
  </p>

  <p class="text-muted">
    <?php echo __k($this, 'home.credits') ?>
  </p>

</div><!-- /.col-sm-8 -->

<?php /* --------------------------------------------------------------- */ ?>

<div class="col-sm-4">

  <h3>
    <?php echo __k($this,'home.activity.title') ?>
  </h3>

  <div id="log-chart"></div>

  <?php foreach($news as $item): ?>
  <?php if (@$item['Message'][$language]): ?>
  <h5>
    <?php echo $this->UI->date($item['Message']['created'], array('format'=>'jS F Y')) ?>
  </h5>
  <p>
    <?php echo h($item['Message'][$language]) ?>
  </p>
  <?php endif ?>
  <?php endforeach ?>

  <p class="text-right">
    <?php echo $this->Html->link(__k($this,'home.news.see_all'), '/pages/news') ?>
  </p>

</div><!-- /.col-sm-4 -->

<?php
  $this->Html->script('//www.google.com/jsapi', array('inline' => false, 'defer' => false));
  $this->Minify->script(array('log-chart'), array('inline' => false, 'defer' => false));
?>
