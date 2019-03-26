<?php $this->assign('title', __('Advanced search')); ?>
<div class="col-md-5">

  <h2><?php echo __('Advanced search'); ?></h2>

<?php
echo $this->Form->create('Lexeme', array(
    'type' => 'get',
    'url' => '/lexemes',
));
?>

  <div class="input-group">

    <?php echo $this->element('keyboard', array('search_id'=>'advanced-search', 'width'=>'16px')); ?>

<?php
// Actual query
echo $this->Form->input('s', array(
    'type' => 'text',
    'label' => false,
    'div'=>false,
    'class'=>'form-control',
    'id' => 'advanced-search',
    'placeholder' => __('Search'),
    'autofocus' => true,
));
?>
</div><!-- input-group -->
<?php
// Search lemma
echo $this->Form->input('l', array(
    'type' => 'checkbox',
    'label' => __('Search lemmas'),
    'hiddenField' => true,
    'checked' => true,
));

// Search wordforms
echo $this->Form->input('wf', array(
    'type' => 'checkbox',
    'label' => __('Search wordforms'),
    'hiddenField' => true,
    'checked' => true,
));

// Regex search
// echo $this->Form->input('r', array(
//     'type' => 'checkbox',
//     'label' => false,
//     'before' => '<label>',
//     'after' => __('Regex search in wordforms (slow)').'</label>',
//     'hiddenField' => true,
//     'checked' => false,
// ));

// Search gloss
echo $this->Form->input('g', array(
    'type' => 'checkbox',
    'label' => __('Search in English glosses'),
    'hiddenField' => true,
    'checked' => true,
));

// Part of speech
echo $this->Form->input('pos', array(
    'type' => 'select',
    'label' => __('Part of speech'),
    'empty' => __('All'),
    'options' => $common['parts_of_speech'],
    'class'=>'form-control',
    'div'=>array('class'=>'form-group'),
));

// Source
echo $this->Form->input('source', array(
    'type' => 'select',
    'label' => __('Source'),
    'empty' => __('All'),
    'options' => $sources,
    'class'=>'form-control',
    'div'=>array('class'=>'form-group'),
));

// Search
echo $this->Form->button(
  $this->UI->icon('search', __('Search')),
  array("class" => "btn btn-primary mt")
);

echo $this->Form->end();
?>
</div><!-- col-md-6 -->

<div class="col-md-7">

  <h3><?php echo __('Search help'); ?></h3>
  <p>
    <ul>
      <li><?php echo __('The search is sensitive to Maltese characters. This means that {0} is not the same as {1}.', '<em>hareg</em>', '<em>ħareġ</em>'); ?></li>
      <li><?php echo __('Use the buttons to the right of the search to insert Maltese characters into the search box.'); ?></li>
      <li><?php echo __('To search by English translation, check the <strong>Search in English glosses</strong> box.'); ?></li>
      <li><?php echo __('Currently, one can only search by substring from the start of a word. This means that {0} will not match {1}.', '<code>itbet</code>', '<em>kitbet</em>'); ?></li>
    </ul>
  </p>

</div><!-- col-md-6 -->
