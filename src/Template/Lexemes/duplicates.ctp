<?php $this->assign('title', __('Duplicates')); ?>
<div class="lexemes duplicates">

  <h3>
    <?php echo __('Duplicates') ?>
  </h3>

  <p>
    <?php
    $cnt = count($duplicates);
    if ($cnt == 1) {
      echo __('{0} entries for this lemma', count($duplicates[0]['value']['ids']));
    } else {
      $total = 0;
      foreach ($duplicates as $d) {
        $total += count($d['value']['ids']);
      }
      echo __('{0} lemmas found with multiple entries', sprintf('<span class="badge">%d</span>', $cnt)).'; ';
      echo __('{0} lemmas total', sprintf('<span class="badge">%d</span>', $total)).'.';
    }
    echo '<br/>';

    if (@$_GET['s']) {
      echo $this->Html->link(
        $this->UI->icon('search').' '.__('Search as lexeme'),
        array('action'=>'index', '?'=>$_GET),
        array('escape'=>false)
      );
      echo '<br/>';
    }

    // Decide indexes to show
    $limit = 10;
    $indexes = array();
    if (@$queryObj->query) {
      $indexes = array_keys($duplicates);
    }
    else if ($page) {
      // we're paging (1+)
      $start = ($page-1)*$limit;
      $stop  = min($cnt, $start + $limit - 1);
      $indexes = range($start, $stop);
      //echo __('Showing entries {0} to {1}', $start+1, $stop+1) . '. ';
    }
    ?>
  </p>

  <div>
  <?php if (!@$queryObj->query): ?>
  <?php $page_last = ceil($cnt/$limit) ?>
  <ul class="pagination pull-left" style="margin-top:0;margin-right:1em;">

  <?php if ($page > 1): ?>
    <li>
      <?php
      echo $this->Html->link(
        $this->UI->icon('step-backward') .' '. __('first'),
        array('controller' => 'Lexemes', 'action' => 'duplicates', 1),
        array('escape'=>false)
      );
      ?>
    </li>
    <li>
      <?php
      echo $this->Html->link(
        $this->UI->icon('chevron-left') .' '. __('previous'),
        array('controller' => 'Lexemes', 'action' => 'duplicates', $page-1),
        array('escape'=>false)
      );
      ?>
    </li>
  <?php endif ?>
  <?php for ($i = max(1, $page-5); $i<=min($page_last, $page+5); $i++): ?>
    <li class="<?php if ($i==$page) echo 'active' ?>">
      <?php
      echo $this->Html->link(
        $i,
        array('controller' => 'Lexemes', 'action' => 'duplicates', $i)
      );
      ?>
    </li>
  <?php endfor ?>
  <?php if ($page < $page_last): ?>
    <li>
      <?php
      echo $this->Html->link(
        __('next') .' '. $this->UI->icon('chevron-right'),
        array('controller' => 'Lexemes', 'action' => 'duplicates', $page+1),
        array('escape'=>false)
      );
      ?>
    </li>
    <li>
      <?php
      echo $this->Html->link(
        __('last') .' '. $this->UI->icon('step-forward'),
        array('controller' => 'Lexemes', 'action' => 'duplicates', $page_last),
        array('escape'=>false)
      );
      ?>
    </li>
  <?php endif ?>
  </ul>
  <?php endif ?>

  <form role="search" class="form-inline" action="<?php echo $this->Url->build('/lexemes/duplicates') ?>">
    <div class="input-group">
      <input type="search" name="s" class="form-control" autofocus="true"
             value="<?php echo @$queryObj->raw_query ?>"
             placeholder="<?php echo __("Search in duplicates") ?>"
      />
      <div class="input-group-btn">
        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
        <a href="<?php echo $this->Url->build('/lexemes/duplicates') ?>" class="btn btn-link">Clear</a>
      </div><!-- input-group-btn -->
    </div><!-- input-group -->
  </form>
  </div>

  <table class="table table-hover">
    <thead>
    <tr>
      <th><?php echo __('Lemma'); ?></th>
      <th><?php echo __('Features'); ?></th>
      <th><?php echo __('Merge'); ?></th>
      <th><?php echo __('Actions'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($indexes as $i): ?>
    <?php if (!@$duplicates[$i]) break; ?>
    <?php $item = $duplicates[$i]; ?>
    <?php foreach ($item->value['ids'] as $id): ?>
    <tr id="<?php echo $id ?>" class="<?php if ($i%2==0) echo "striped" ?>">
      <td class="surface_form">
        <?php echo $this->Html->link($item['_id']['lemma'], array('action'=>'view',$id)); ?>
      </td>
      <td class="loading">
        <?php $this->Js->buffer("Gabra.loadLexeme('{$id}', '#{$id} td:eq(1)')"); ?>
      </td>
      <td class="text-nowrap">
        <label>
        <input type="checkbox" name="merge2" />
        <?php echo __('merge this') ?>
        </label>
        <?php echo $this->Html->link(__('into this'), array('action'=>'merge', $id, '?'=>array('id2s'=>'')), array('class'=>'merge')); ?>
      </td>
      <td>
      <?php
        echo $this->Html->link(
          $this->UI->icon('pencil').' '.__('Edit'),
          array('action'=>'edit',$id),
          array('escape'=>false, 'class'=>'text-warning')
        );
        echo ' ';
        echo $this->Html->link(
          $this->UI->icon('remove').' '.__('Delete'),
          array('action'=>'delete', $id),
          array('escape'=>false, 'class'=>'text-danger'),
          __('Are you sure you want to delete this entry?')
        );
      ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
  </table>

</div>

<script type="text/javascript">
$(document).ready(function(){

  // Click on tr checks box
  $('tr td:nth-child(2)').click(function(){
    $(this).parent().find('input[name="merge2"]').click();
  });

  // When clicking a radio to merge
  $('input[name="merge2"]').click(function(){
    var id2s = [];
    $('input[type=checkbox][name=merge2]:checked').each(function(){
      var id = $(this).parent().parent().parent().attr('id');
      id2s.push(id);
    });
    $('a.merge').each(function(){
      var obj = $(this);
      var url = obj.attr('href');
      var new_url = url.substring(0, url.lastIndexOf('?')+1) + "id2s=" + id2s.join(',');
      obj.attr('href', new_url);
    });
  });

  // Confirm merge glosses when more than one item selected
  $('a.merge').click(function(){
    var selected = $('input[type=checkbox][name=merge2]:checked');
    if (selected.length == 0) {
      alert(Gabra.i18n.localise('merge.no_selection'));
      return false;
    } else if (selected.length > 1) {
      return confirm(Gabra.i18n.localise('merge.confirm'));
    }
  });

  // AJAX delete
  $('a.delete').click(function(){

    if (!confirm(Gabra.i18n.delete_confirm)) return false;

    var obj = $(this);
    var tr = obj.parent().parent();
    var td = tr.find('td:eq(1)');
    var id = obj.parent().parent().attr('id');
    td.addClass('loading');
    $.ajax({
        url: BASE_URL+"lexemes/delete/"+id+".json",
        dataType: "json",
        type: "GET",
        success: function(data) {
          if (data.response == 'OK') {
            tr.remove();
          } else {
            console.log(data);
            td.removeClass('loading');
          }
        },
        error: function(err){
          console.log(err.responseJSON);
          td.removeClass('loading');
        }
    });
    return false;
  });
});
</script>
