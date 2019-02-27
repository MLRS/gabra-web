<div class="lexemes index">

  <?php if (@$page_title): ?>
  <?php $this->assign('title', $page_title); ?>
  <h3>
    <?php echo $page_title ?>
  </h3>
  <?php endif ?>

  <?php /* ---------- No search ---------- */ ?>
  <?php if (!isset($lexemes)): ?>
  <div class="no-results">
    <?php echo __("Use the field above to search"); ?>
  </div>

  <?php /* ---------- No results ---------- */ ?>
  <?php elseif (count(@$lexemes) == 0): ?>
  <div class="no-results">
    <?php echo __('No results found.'); ?><br/>
    <?php echo __('{0} to let us know that "{1}" should be added to the database.', $this->Html->link(__('Click here'), array('controller' => 'Lexemes', 'action' => 'suggest', $queryObj->query)), $queryObj->query); ?>
  </div>

  <?php /* -------- Search results -------- */ ?>
  <?php else: ?>

  <?php echo $this->CustomPaginator->links(true); ?>

  <div class="div-striped">

  <?php foreach ($lexemes as $item): ?>
  <div class="row" id="<?php echo $item->lexeme['_id'] ?>">
    <div class="col-sm-2">
      <span class="surface_form">
      <?php echo $this->Html->link(
        $item->lexeme['lemma'],
        array('action'=>'view',$item->lexeme['_id'])
      ); ?>
      <?php echo $this->UI->alternatives(@$item->lexeme['alternatives'], array('class'=>'alt')); ?>
      </span>
      <?php if ($common['user']): ?>
      <div class="small">
        <?php
        echo $this->Html->link(
          $this->UI->icon('search').' '.__('Search'),
          array('action'=>'index', '?'=>array('s'=>$item->lexeme['lemma'])),
          array('escape'=>false, 'class'=>'text-info')
        );
        echo ' ';
        echo $this->Html->link(
          $this->UI->icon('pencil').' '.__('Edit'),
          (API_URL . 'lexemes/view/' . $item->lexeme['_id']),
          // array('action'=>'edit',$item->lexeme['_id']),
          array('escape'=>false, 'class'=>'text-warning')
        );
        echo ' ';
        echo $this->Html->link(
          $this->UI->icon('remove').' '.__('Delete'),
          array('action'=>'delete', $item->lexeme['_id']),
          array('escape'=>false, 'class'=>'text-danger'),
          __('Are you sure you want to delete this entry?')
        );
        ?>
      </div>
      <?php endif ?>
    </div>
    <div class="col-sm-2">
      <?php echo $this->UI->posTag(@$item->lexeme['pos']); ?>
      <?php echo $this->UI->root($item['Lexeme']); ?>
      <?php echo $this->UI->derivedForm($item['Lexeme']); ?>
      <?php echo $this->UI->maybeField($item['Lexeme'], 'frequency'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'transitive', 'trans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'intransitive', 'intrans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'ditransitive', 'ditrans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'hypothetical', 'hyp.'); ?>
    </div>
    <div class="col-sm-4">
      <?php echo $this->UI->gloss($item['Lexeme']); ?>
    </div>
    <div class="col-sm-4">
      <?php
        if (@$item->lexeme['modified'])
          echo $this->UI->date(@$item->lexeme['modified']);
        else
          echo $this->UI->date(@$item->lexeme['created']);
      ?>    </div>
    <?php /*
    <div class="col-sm-2 loading">
      <?php $limit = max(3,substr_count(@$item->lexeme['gloss'], "\n")-2); ?>
      <?php $q = (@$queryObj->query && @!$queryObj->search_gloss) ? urlencode(@$queryObj->query) : ''; ?>
      <?php $this->Js->buffer("Gabra.loadWordForms('{$item->lexeme['_id']}', '{$q}', '#{$item->lexeme['_id']} div:eq(3)', {$limit})"); ?>
    </div>
    */ ?>
  </div>
  <?php endforeach; ?>

  </div><!-- div-striped -->

  <?php echo $this->CustomPaginator->links(true); ?>

  <?php endif; ?>

</div>
