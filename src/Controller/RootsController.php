<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;

class RootsController extends AppController {

  public function index($letter=null) {
    $queryObj = $this->Search->getQuery();
    $this->set('queryObj', $queryObj);
  }

  public function view($radicals=null, $variant=null) {
    $root = $this->Roots->find('first', array(
      'conditions' => array(
        'radicals' => $radicals,
        'variant' => $variant ? (int)$variant : null,
      )
    ));
    if (!$root) {
      throw new NotFoundException(__('Invalid ID'));
    }
    $this->loadModel('Lexemes');
    $related = $this->Lexemes->find('all',array(
      'conditions' => array(
        'root.radicals' => $root['radicals'],
        'root.variant' => @$root['variant'],
      )
    ));
    $this->set('root', $root);
    $this->set('related', $related);
  }

}
