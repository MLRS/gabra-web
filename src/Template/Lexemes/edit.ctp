<div class="alert alert-warning">
  You shouldn't use this outdated page.
  Use the <a class="" href="<?php echo API_URL ?>lexemes/view/<?php echo $this->request->data['Lexeme']['_id'] ?>">edit lexeme</a> page on the Ġabra API site.
</div>

<?php $this->assign('title', __('Edit lexeme')); ?>
<div class="lexemes edit">

  <div class="col-sm-6">
    <legend><?php echo __('Lexeme');?></legend>

  <?php echo $this->Form->create($lexeme);?>

    <?php
    echo $this->Form->hidden('_id');
    echo $this->Form->input('lemma', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));

    echo $this->Form->input('alternatives', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
      'value'=>@implode(', ', $this->request->data['Lexeme']['alternatives']),
      'after'=>$this->Html->tag('p', __("Use commas to separate multiple alternatives"), array('class'=>'help-block'))
    ));

    echo $this->Form->input('pos', array(
      'type'=>'select',
      'options' => $common['parts_of_speech'],
      'empty'=>'',
      'selected'=> @$this->request->data['Lexeme']['pos'],
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
    ));
    $root = @$this->request->data['Lexeme']['root']['radicals'];
    if (@$this->request->data['Lexeme']['root']['variant']) $root .= ' '.$this->request->data['Lexeme']['root']['variant'];
    echo $this->Form->input('root._id', array(
      'label'=>__('Root'),
      'type'=>'select',
      'options'=>$roots,
      'selected'=>array_search($root, $roots),
      'empty' => true,
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    echo $this->Form->input('gloss', array(
      'type'=>'textarea',
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control'
    ));
    echo $this->Form->input('sources', array(
      'div'=>array('class'=>'form-group'),
      'class'=>'form-control',
      'value'=>@implode(', ', $this->request->data['Lexeme']['sources']),
      'after'=>$this->Html->tag('p', __("Use commas to separate multiple sources"), array('class'=>'help-block'))
    ));
    ?>

    <div class="form-group btn-group">
    <?php
    $link_del = $this->Html->link(
      __('Delete'),
      array('controller' => 'Lexemes','action' => 'delete',$this->request->data['Lexeme']['_id']),
      array('class'=>'btn btn-danger'),
      __('Are you sure you want to delete this entry?')
    );
    $link_view = $this->Html->link(
      __('View'),
      array('controller' => 'Lexemes','action' => 'view',$this->request->data['Lexeme']['_id']),
      array('class'=>'btn btn-link')
    );
    echo $this->Form->submit('Save', array(
      'after' => $link_view . $link_del,
      'class' => 'btn btn-primary'
    ));
    ?>
    </div>

    <?php
    $remaining = array_diff_key($this->request->data['Lexeme'], array_flip(array(
      'id','_id','lemma','alternatives','pos','gloss','sources','Root','root','root_id','modified','created'
    )));
    foreach ($remaining as $k=>$v) {
      if (gettype($v) == 'boolean') {
        echo $this->Form->input($k, array(
          'type'=>'checkbox',
          'div'=>array('class'=>'checkbox'),
          'label'=>false,
          'before'=>'<label>',
          'after'=>$k.'</label>',
        ));
      } else {
        echo $this->Form->input($k, array(
          'div'=>array('class'=>'form-group'),
          'class'=>'form-control',
        ));
      }
    }
    ?>

    <div class="form-group" id="add-field">
      <?php echo __('New field with name:') ?>
      <input name="" />
      <a href="#"><?php echo __('Add') ?></a>
    </div>

    <?php echo $this->Form->end(); ?>

  </div>

  <?php /* ----------------------------------------------------------- */ ?>

  <!-- Right panel (the item being deleted) -->
  <div class="col-sm-6">
    <legend><?php echo __('Wordforms'); ?></legend>

    <?php if ($wordforms): ?>
    <?php echo $this->Form->create($wordform', array('action' => 'saveMany)); ?>
    <table class="table table-condensed table-hover" id="wordforms">
      <thead>
      <tr>
        <th><?php echo __('Surface form') ?></th>
        <th><?php echo __('Number') ?></th>
        <th><?php echo __('Gender') ?></th>
        <th><?php echo __('Form') ?></th>
        <th>&nbsp;</th>
      </tr>
      </thead>
      <tbody>
    <?php foreach ($wordforms as $i=>$wf): ?>
      <tr>
        <td>
          <?php echo $this->Form->input("Wordform.$i.surface_form", array('value'=>$wf->wordform['surface_form'], 'label'=>false)); ?>
          <?php
          $remaining = array_diff_key($wf['Wordform'], array_flip(array(
            'surface_form', 'number', 'gender', 'form'
          )));
          foreach ($remaining as $k=>$v) {
            echo $this->Form->hidden("Wordform.$i.$k", array('value'=>$wf->wordform[$k]));
          }
          ?>
        </td>
        <td><?php echo $this->Form->input("Wordform.$i.number", array('value'=>@$wf->wordform['number'], 'label'=>false)); ?></td>
        <td><?php echo $this->Form->input("Wordform.$i.gender", array('value'=>@$wf->wordform['gender'], 'label'=>false)); ?></td>
        <td><?php echo $this->Form->input("Wordform.$i.form",   array('value'=>@$wf->wordform['form'], 'label'=>false)); ?></td>
        <td><?php
            echo $this->Html->link(
              $this->UI->icon('remove'),
              array('controller' => 'Wordforms', 'action' => 'delete', $wf->wordform['_id']),
              array('escape'=>false, 'class'=>'text-danger'),
              __('Are your sure you want to delete this wordform?')
            );
        ?></td>
      <tr>
    <?php endforeach; ?>
      </tbody>
    </table>
    <?php
      echo $this->Form->submit(__('Save wordforms'), array('class'=>'btn btn-primary'));
      echo $this->Form->end();
    ?>
    <?php else: ?>
    (<?php echo __('No manual wordforms') ?>)
    <?php endif; ?>

    <?php /* ----------------------------------------------------------- */ ?>

    <legend style="margin-top:1em"><?php echo __('Add wordform'); ?></legend>
    <?php
      $copy_lemma = $this->Html->link(__('Copy lemma'), '#', array('id'=>'copy_lemma', 'class'=>'pull-right'));
    $this->Js->buffer(<<<JS
$('a#copy_lemma').click(function(){
  // Copy lemma
  var lemma = $('form#LexemeEditForm input#LexemeLemma').val();
  $('form#WordformAddForm input#WordformSurfaceForm').val(lemma);

  // Attempt to copy gender
  var gender = $('form#LexemeEditForm input#LexemeGender').val();
  if (gender)
    $('form#WordformAddForm select#WordformGender').val(gender);

  return false;
})
JS
      );

      echo $this->Form->create($wordform', array('action'=>'add));

      echo $this->Form->hidden('lexeme_id', array('value'=>$this->request->data['Lexeme']['_id']));
      echo $this->Form->input('surface_form', array(
        'div'=>array('class'=>'form-group'),
        'class'=>'form-control',
        'before'=> $copy_lemma,
      ));
      echo $this->Form->input('number', array(
        'type'=>'select',
        'options'=>array(
          'sg'=>__('Singular'),
          'dl'=>__('Dual'),
          'pl'=>__('Plural'),
          'sgv'=>__('Singulative'),
          'coll'=>__('Collective')
        ),
        'empty'=>'',
        'div'=>array('class'=>'form-group'),
        'class'=>'form-control',
      ));
      echo $this->Form->input('gender', array(
        'type'=>'select',
        'options'=>array(
          'm'=>__('Masc'),
          'f'=>__('Fem'),
          'mf'=>__('Masc/Fem')
        ),
        'empty'=>'',
        'div'=>array('class'=>'form-group'),
        'class'=>'form-control',
      ));
      echo $this->Form->input('form', array(
        'type'=>'select',
        'options'=>array(
          'diminutive' =>__('Diminutive'),
          'comparative'=>__('Comparative'),
          'superlative'=>__('Superlative')
        ),
        'empty'=>'',
        'div'=>array('class'=>'form-group'),
        'class'=>'form-control',
      ));
      echo $this->Form->submit(__('Add'), array(
        'class'=>'btn btn-primary',
      ));
      echo $this->Form->end()
      ?>
  </div>

</div>
