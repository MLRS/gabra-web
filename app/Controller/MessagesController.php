<?php
class MessagesController extends AppController {

  public $paginate = array(
    'limit' => 20,
    'order' => array(
      'created' => 'DESC',
    )
  );
  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->deny('index','add','edit');
  }

  public function index() {
    $queryObj = $this->Search->getQuery(array(
      'replace_dot'=>false,
      'fix_case'=>false,
    ));
    $this->set('queryObj', $queryObj);
    if ($queryObj->query) {
      $this->set('messages', $this->paginate('Message', array(
        '$or'=>array(
          array('Message.type'=>$queryObj->query),
          array('Message.key'=>array('$regex'=>$queryObj->query)),
          array('Message.eng'=>array('$regex'=>$queryObj->query)),
          array('Message.mlt'=>array('$regex'=>$queryObj->query)),
        ))));
    } else {
      $this->set('messages', $this->paginate());
    }
  }

  // public function view($id = null) {
  //   $this->Message->id = $id;
  //   if (!$this->Message->exists()) {
  //     throw new NotFoundException(__('Invalid ID'));
  //   }
  //   $this->set('message', $this->Message->read(null, $id));
  // }

  public function add() {
    if ($this->request->is('post')) {
      $this->Message->create();
      if ($this->Message->save($this->request->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
  }

  public function edit($id = null) {
    $this->Message->id = $id;
    if (!$this->Message->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Message->save($this->request->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    } else {
      $this->request->data = $this->Message->read(null, $id);
    }
  }

  public function delete($id = null) {
    $msg = '';
    if (!$id) {
      $this->setMessageBad(__('Invalid ID'));
    }
    if ($this->Message->delete($id)) {
      $this->setMessageGood(__('Entry deleted'));
    } else {
      $this->setMessageBad(__('Error'));
    }
    $this->redirect(array('action' => 'index'));
  }

}
