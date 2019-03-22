<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;

class RootsController extends AppController {

  public function index() {
    $queryObj = $this->Search->getQuery();
    $this->set('queryObj', $queryObj);
  }

  public function view($radicals=null, $variant=null) {
    $root = $this->Roots->getByRadicals($radicals, $variant);
    if (!$root) {
      $this->setMessageBad(__('Invalid ID'));
      $this->redirect(array('action'=>'index'));
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
