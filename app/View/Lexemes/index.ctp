<?php
if (!empty($queryObj->query))
  $this->assign('title', __('Search').': "'.$queryObj->raw_query.'"');
else
  $this->assign('title', __('Search'));
?>
<div class="lexemes index">

  <?php /* ---------- No search ---------- */ ?>
  <?php if (empty($queryObj->query)): ?>
  <div class="no-results">
    <?php echo __("Use the field above to search"); ?>
  </div>

  <?php /* ---------- Search ---------- */ ?>
  <?php else: ?>

  <h4>
    <?php echo __('Results: %s', '<span class="label label-default" id="result-count">...</span>') ?>
  </h4>

  <!-- Suggestions to diacritize -->
  <?php
//  if (!preg_match('/[ċħġż]/', $queryObj->query)):
    $q = addslashes($queryObj->query);
    $this->Js->buffer(<<<JS
      $(document).ready(function(){
        load_search_suggest('${q}');
      });
JS
    );
//  endif;
  ?>
  <div id="search-suggestions" class="lead" style="margin-bottom:0.2em"></div>

  <!-- Results go here by AJAX -->
  <div class="div-striped" id="results"></div>

  <!-- Load button -->
  <p style="height:2em" class="text-center" id="load-more">
  <button type="button" class="btn btn-default">
    <?php echo __('Load more results') ?>
  </button>
  </p>

  <?php
  $data = $queryObj->json;
  $term = addslashes($queryObj->raw_query);
  echo $this->Minify->script(array('search'));
  $this->Js->buffer(<<<JS
    $('#load-more button').click( load_results_function(${data}, '${term}') );
    $(document).ready(function(){
      $('#load-more button').trigger('click');
    });
JS
);
  ?>

  <?php endif; ?>

</div>
