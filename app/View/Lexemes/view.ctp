<?php $item = $lexeme; ?>
<?php $this->assign('title', h($item['Lexeme']['lemma'])) ?>
<div class="lexemes view" id="<?php echo $item['Lexeme']['_id'] ?>">

<div class="col-md-12">
  <h2 class="surface_form bigword">
    <?php echo h($item['Lexeme']['lemma']); ?>

    <?php if (@$item['Lexeme']['alternatives']): ?>
    <small class="alt"><?php echo $this->UI->alternatives(@$item['Lexeme']['alternatives'], array()); ?></small>
    <?php endif; ?>
  </h2>
</div>

<div class="col-md-4">

  <?php if (@$item['Lexeme']['pending'] || @$item['Lexeme']['feedback']): ?>
  <div class="alert alert-warning" role="alert">
  <?php echo $this->UI->icon('exclamation-sign') ?>
  <?php echo __('This entry has been flagged and may contain errors.'); ?>
  </div>
  <?php endif; ?>

  <dl>
    <dt><?php echo __('Part of speech'); ?></dt>
    <dd>
      <?php echo $this->UI->posTag(@$item['Lexeme']['pos'], array('capitalise'=>true)); ?>
      <?php echo $this->UI->derivedForm($item['Lexeme']); ?>
      &nbsp;
    </dd>

    <?php if (@$item['Lexeme']['gloss']): ?>
    <dt><?php echo __('English gloss'); ?></dt>
    <dd><div><?php echo $this->UI->gloss($item['Lexeme'], true); ?>&nbsp;</div></dd>
    <?php endif; ?>

    <?php if (@$item['Lexeme']['root']): ?>
    <dt><?php echo __('Root'); ?></dt>
    <dd><?php echo $this->UI->root($item['Lexeme']); ?>&nbsp;</dd>
    <?php endif; ?>

    <dt><?php echo __('Features'); ?></dt>
    <dd>
      <?php echo $this->UI->maybeField($item['Lexeme'], 'frequency'); ?>
      <?php echo $this->UI->maybeField($item['Lexeme'], 'onomastic_type'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'transitive', 'trans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'intransitive', 'intrans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'ditransitive', 'ditrans.'); ?>
      <?php echo $this->UI->booleanField($item['Lexeme'], 'hypothetical', 'hyp.'); ?>
      &nbsp;
    </dd>

    <?php if (@$item['Lexeme']['sources']): ?>
    <dt><?php echo __('Source(s)'); ?></dt>
    <dd><?php
        foreach ($item['Lexeme']['sources'] as $i=>$source) {
          $s = $source;
          echo $this->Html->link($s, array('controller'=>'sources','action'=>'view',$s));
          if ($i < count($item['Lexeme']['sources'])-1) echo ', ';
        }
    ?></dd>
    <?php endif; ?>

    <?php if (@$item['Lexeme']['created']): ?>
    <dt><?php echo __('Created'); ?></dt>
    <dd><?php echo $this->UI->date($item['Lexeme']['created']); ?></dd>
    <?php endif; ?>
    <?php if (@$item['Lexeme']['modified'] && @$item['Lexeme']['modified'] != @$item['Lexeme']['created']): ?>
    <dt><?php echo __('Modified'); ?></dt>
    <dd><?php echo $this->UI->date($item['Lexeme']['modified']); ?></dd>
    <?php endif; ?>

  </dl>

  <div class="entry-meta">

    <?php /* if (@$item['Lexeme']['pending'] || @$item['Lexeme']['feedback']): ?>
    <div class="text-warning">
    <?php echo $this->UI->icon('exclamation-sign') ?>
    <?php echo __('This entry is pending review'); ?>
    </div>
    <?php endif; */ ?>

    <div class="lexeme feedback">
      <?php echo $this->Html->link(
        $this->UI->icon('comment').' '.__('Provide feedback about this entry'),
        array('controller'=>'lexemes', 'action'=>'feedback', $item['Lexeme']['_id']),
        array('escape'=>false, 'rel'=>'nofollow')) ?>
      <span class="message"></span>
    </div>

    <div>
      <?php echo $this->UI->corpusLink($item['Lexeme']['lemma']); ?>
    </div>

    <?php
      if ($common['user']):
        echo $this->UI->link(
          $this->UI->icon('pencil').' '.__('Edit entry'),
          (API_URL . 'lexemes/view/' . $item['Lexeme']['_id']),
          //array('action'=>'edit', $item['Lexeme']['_id']),
          array('class'=>'edit','escape'=>false)
        );
      endif;
    ?>
  </div><!-- entry-meta -->

  <?php if (@$item['Related']): ?>
  <h4 class="text-muted"><?php echo __('Related entries'); ?></h4>
  <ul class="list-unstyled">
    <?php foreach (@$item['Related'] as $link): ?>
    <li>
      <?php echo $this->Html->link($link['Lexeme']['lemma'], '/lexemes/view/'.$link['Lexeme']['_id'], array('class'=>'surface_form')); ?>
      <span class="text-muted">
        &nbsp;
        <?php echo $this->UI->posTag(@$link['Lexeme']['pos']); ?>
        <?php if (@$link['Lexeme']['derived_form']) echo $this->UI->derivedForm($link['Lexeme']); ?>
      </span>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>

</div>
<div class="col-md-8">

  <?php if (@$item['Wordforms']): ?>
  <h4 class="text-muted">
    <?php echo __('Word forms'); ?>
    <?php if ($item['Lexeme']['pos'] == 'VERB') : ?>
    <small>
      (<a href="#" onclick="$('tr:hidden').show(); return false;";><?php echo __('Show all forms') ?></a>)
    </small>
    <?php endif; ?>
  </h4>

  <?php
  if ($item['Lexeme']['pos'] == 'VERB') {
    echo $this->UI->wordFormTable(
      $item['Wordforms'],
      array(
        'fields'=>array('aspect','subject','dir_obj','ind_obj','polarity'),
        'filter_fields'=>array('dir_obj','ind_obj','polarity'),
      )
    );
  } else {
    echo $this->UI->wordFormTable($item['Wordforms']);
  } /* if V else */
  ?>

  <?php endif; /* if wordforms */ ?>

</div><!-- col 6 -->
</div><!-- lexemes view -->
