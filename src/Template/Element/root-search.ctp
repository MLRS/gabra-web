<?php
/**
 * Valid options:
 *   hide_help
 */

echo $this->Form->create('Root', array(
  'action' => 'index',
  'type' => 'get',
  'class' => 'form-inline'
));
?>

<div class="form-group">
  <div class="input-group">
    <?php echo $this->element('keyboard', array('search_id'=>'root-search', 'width'=>'16px')); ?>
    <?php
    // Actual query
    echo $this->Form->input('s', array(
        'type' => 'text',
        'label' => false,
        'value' => @$queryObj->raw_query,
        'div'=>false,
        'class'=>'form-control',
        'id' => 'root-search',
        'placeholder' => __('Search roots'),
        'autofocus' => true,
    ));
    ?>
  </div><!-- input-group -->
</div><!-- form-group -->

<div class="form-group">
  <label style="margin-right:0"><em><?php echo __('or') ?></em></label>
</div>

<div class="form-group">
  <?php
    $opts = array_combine($common['consonants'], $common['consonants']);
    $opts['(j|w)'] = __('j/w');
    $opts["'"] = "'";
    for ($i=1;$i<=4;$i++)
    echo $this->Form->control('c'.$i, array(
      'type' => 'select',
      'empty' => true,
      'options' => $i==4 ? array_merge(array('none'=>__('none')), $opts) : $opts,
      'value' => @$_GET['c'.$i],
      'label' => $i==1 ? __('Radicals') : false,
      'class'=>'form-control',
      'templates'=> ['inputContainer' => '{{content}}'], // do not wrap with div
    ));
    echo $this->Form->control('t', array(
      'type' => 'select',
      'label' => __('Class'),
      'empty' => true,
      'options' => $common['root_types'],
      'value' => @$_GET['t'],
      'class'=>'form-control',
      'templates'=> ['inputContainer' => '{{content}}'], // do not wrap with div
    ));
  ?>
</div> <!-- .form-group -->

<?php
echo $this->Form->input('r', array(
    'type' => 'hidden',
    'hiddenField' => false,
    'value' => '1',
    'checked' => true,
));

// Search
echo $this->Form->button(
  $this->UI->icon('search', __('Search roots')),
  array("class" => "btn btn-primary")
);

if (!@$hide_help) {
  echo $this->Html->link(
    __('Help').'&hellip;', '',
    array('id'=>'search-help-toggle', 'escape'=>false, 'class'=>'btn btn-link')
  );
  echo $this->Html->scriptBlock(<<<JS
  $('#search-help-toggle').click(function(){
     $('#search-help').slideToggle();
     return false;
  });
JS
  );
}
echo $this->Form->end();
?>

<div id="search-help" <?php if ($queryObj->query): ?>style="display:none"<?php endif ?>>
<div class="col-sm-6">
  <h3><?php echo __('Legend'); ?></h3>
  <p>
    <ul>
      <li><?php echo __('Superscript numbers {0} beside roots indicate multiple meanings', '<sup>1,2,3...</sup>'); ?></li>
      <li><?php echo __('Entries in {0}(parenthesis){1} indicate variants', '<span class="alt">', '</span>'); ?></li>
      <li><?php echo __('Items in {0}red{1} are hypothetical, unused or obsolete forms listed in dictionaries', '<span class="hypothetical surface_form">', '</span>'); ?></li>
    </ul>
  </p>
  <h3><?php echo __('Root search'); ?></h3>
  <p>
    <?php // echo __('Search covers both roots and verbal forms.'); ?>
    <?php echo __('Use the root search when you want to filter the roots by some specific criteria.'); ?>
    <?php echo __('It acts as a convenient shortcut for the more advanced search syntax (below).'); ?>
    <ul>
      <li><?php echo __('Selecting nothing from a dropdown will match anything in that position.'); ?></li>
      <li><?php echo __('Select {0} to match either a <em>j</em> or a <em>w</em>', '<code>j/w</code>'); ?></li>
      <li><?php echo __('The apostrophe {0} indicates a missing radical, in irregular roots.', '<code>\'</code>'); ?></li>
    </ul>
  </p>
</div><!-- col-sm-6 -->
<div class="col-sm-6">
  <h3><?php echo __('Advanced search syntax'); ?></h3>
  <p>
    <?php echo __('Queries follow regular expression syntax:'); ?>
    <ul>
      <li><?php echo __('{0} matches any single letter (including <em>ie</em> and <em>għ</em>)', '<code>.</code>'); ?></li>
      <li><?php echo __('{0} matches beginning of string', '<code>^</code>'); ?></li>
      <li><?php echo __('{0} matches the end of string', '<code>$</code>'); ?></li>
      <li><?php echo __('{0} matches 1 or more repetitions of the previous expression', '<code>+</code>'); ?></li>
      <li><?php echo __('{0} matches 0 or more repetitions of the previous expression', '<code>*</code>'); ?></li>
      <li><?php echo __('{0} matches 0 or 1 occurances of the previous expression', '<code>?</code>'); ?></li>
      <li><?php echo __('Character classes can be enclosed in {0}', '<code>[]</code>'); ?></li>
    </ul>
  </p>
  <h3><?php echo __('Examples'); ?></h3>
  <p>
    <ul>
      <?php
      if ($this->request->getParam('controller') == 'roots') {
        $search_suggestions = array(
          'k-.-b' => __('{0} matches <em>k</em> as first radical and <em>b</em> as third radical'),
          '-[wj]$' => __('{0} matches any weak-final root'),
          '^għar.*' => __('{0} matches any item beginning with <em>għar</em>')
        );
      } else {
        $search_suggestions = array(
          '^għar' => __('{0} matches any item beginning with <em>għar</em>'),
          'kit.*hom$' => __('{0} matches any item beginning with <em>kit</em> and ending with <em>hom</em>'),
          's[ae]ma\'' => __('{0} matches both <em>sama\'</em> and <em>sema\'</em>')
        );
      }
      foreach ($search_suggestions as $k=>$v):
        $link = $this->Html->tag('code', $this->Html->link($k, ['?' => ['s' => $k,'r' => '1']]));
        echo $this->Html->tag(
          'li',
          str_replace('{0}', $link, $v)
        );
      endforeach;
?>
    </ul>
  </p>
</div><!-- col-sm-6 -->
</div><!-- #search-help -->
