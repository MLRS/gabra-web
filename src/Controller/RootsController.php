<?php
namespace App\Controller;

use App\Controller\AppController;

class RootsController extends AppController {

  public $paginate = array(
    'limit' => 20,
    'order' => array(
      'radicals' => 'ASC',
      'variant' => 'ASC',
    )
  );

  public function beforeFilter(Event $event) {
    parent::beforeFilter($event);
  }

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
    $this->loadModel('Lexeme');
    $related = $this->Lexeme->find('all',array(
      'fields' => array('wordforms' => 0),
      'conditions' => array(
        'root.radicals' => $root->root['radicals'],
        'root.variant' => @$root->root['variant'],
      )
    ));
    $this->set('root', $root);
    $this->set('related', $related);
  }

}
