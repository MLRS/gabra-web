<?php
class RootsController extends AppController {

  public $paginate = array(
    'limit' => 20,
    'order' => array(
      'radicals' => 'ASC',
      'variant' => 'ASC',
    )
  );

  public function beforeFilter() {
    parent::beforeFilter();
  }

  public function index($letter=null) {
    $queryObj = $this->Search->getQuery();
    $this->set('queryObj', $queryObj);
  }

  public function view($radicals=null, $variant=null) {
    $root = $this->Root->find('first', array(
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
        'root.radicals' => $root['Root']['radicals'],
        'root.variant' => @$root['Root']['variant'],
      )
    ));
    $this->set('root', $root);
    $this->set('related', $related);
  }

}
