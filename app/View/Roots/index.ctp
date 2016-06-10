<?php $this->assign('title', __('Root search')) ?>
<div class="roots index">

  <?php echo $this->element('root-search'); ?>

  <?php /* ---------- No search ---------- */ ?>
  <?php if (empty($queryObj->query)): ?>

  <?php /* ---------- Search ---------- */ ?>
  <?php else: ?>

  <h4>
    <?php echo __('Results: %s', '<span class="label label-default" id="result-count">...</span>') ?>
    &nbsp;
    <small id="search-suggestions"></small>
  </h4>

  <table class="table table-striped" id="results">
    <thead>
    <tr>
      <th><?php echo __('Radicals') ?></th>
      <th><?php echo __('Type') ?></th>
      <th>I</th>
      <th>II</th>
      <th>III</th>
      <th>V</th>
      <th>VI</th>
      <th>VII</th>
      <th>VIII</th>
      <th>IX</th>
      <th>X</th>
    </tr>
    </thead>

    <!-- Results go here by AJAX -->
    <tbody>
    </tbody>

  </table>

  <!-- Load button -->
  <p style="height:2em" class="text-center" id="load-more">
  <button type="button" class="btn btn-default">
    <?php echo __('Load more results') ?>
  </button>
  </p>

  <?php
  $data = $queryObj->json;
  $term = addslashes($queryObj->raw_query);
  echo $this->Minify->script(array('root-search'));
  $this->Js->buffer(<<<JS
    $('#load-more button').click( load_results_function(${data}, '${term}') );
    $(document).ready(function(){
      $('#load-more button').trigger('click');
    });
JS
);
  ?>

  <?php //if (count($roots) > 5) echo $this->CustomPaginator->links(true); ?>

  <?php endif; ?>

</div>
