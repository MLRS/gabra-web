<?php
namespace App\View\Helper;

use Cake\Core\App;

//App::import('Helper', 'Html');

class CustomPaginatorHelper extends PaginatorHelper {

  var $helpers = array('Html', 'UI');

  // Remove sort & direction params from URLs
  private function removeSortParams($s) {
    return preg_replace('/\/(sort|direction):[a-zA-Z0-9_.]+/', '', $s);
  }

  public function links($suppress_sorting=false, $show_msg=true, $show_first=true) {
    $this->options['url'] = array_merge($this->params->pass, array('?' => $this->params->query));
    $out = '<ul class="pagination">';

    if ($show_first)
    $out .= parent::first(
      $this->UI->icon('step-backward') .' '. __('first'),
      array('escape'=>false, 'class'=>'prev', 'tag'=>'li')
    );

    if (parent::hasPrev())
    $out .= parent::prev(
      $this->UI->icon('chevron-left') .' '. __('previous'),
      array('escape'=>false, 'class'=> (parent::hasPrev() ? '' : 'prev'), 'tag'=>'li', 'disabledTag'=>'a')
    );

    $out .= parent::numbers(
      array('separator'=>'', 'tag'=>'li', 'currentTag'=>'a', 'currentClass'=>'active', 'modulus'=>4)
    );

    if (parent::hasNext())
    $out .= parent::next(
      __('next') .' '. $this->UI->icon('chevron-right'),
      array('escape'=>false, 'class'=> (parent::hasNext() ? '' : 'next'), 'tag'=>'li', 'disabledTag'=>'a')
    );

    if ($show_first)
    $out .= parent::last(
      __('last') .' '. $this->UI->icon('step-forward'),
      array('escape'=>false, 'class'=>'next', 'tag'=>'li')
    );

    if ($show_msg)
    $out .= $this->Html->tag('li',$this->Html->tag('span',
      parent::counter(array(
        'format' => __('Page {:page} of {:pages}, showing records {:start} to {:end} out of {:count} total.')
      )))
    );

    $out .= '</ul>';

    return $suppress_sorting ? $this->removeSortParams($out) : $out;
  }

}
