<?php $hidden = ($this->request->getRequestTarget() == $this->request->getAttribute('webroot') || $this->request->getParam('action') == 'search') ?>
<form role="search" class="navbar-form navbar-left <?php if ($hidden) echo 'hidden'?>" action="<?php echo $this->Url->build('/lexemes') ?>">
  <div class="input-group">

    <?php echo $this->element('keyboard', array('search_id'=>'navbar-search', 'width'=>'16px')); ?>

    <input type="search" name="s" class="form-control" id="navbar-search" autofocus="true"
           <?php if ($this->request->getParam('controller')!='roots'): ?>
           value="<?php echo @$queryObj->raw_query ?>"
           <?php endif ?>
           <?php if ($this->request->getParam('controller')=='roots'): ?>
           placeholder="<?php echo __("Search all of Ġabra") ?>"
         <?php elseif ($this->request->getParam('action')=='search'): ?>
           <?php else: ?>
           placeholder="<?php echo __("Search for a word") ?>"
           <?php endif ?>
    />
    <div class="input-group-btn">
      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
    </div><!-- input-group-btn -->

  </div><!-- input-group -->

</form>
