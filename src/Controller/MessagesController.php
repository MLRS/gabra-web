<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class MessagesController extends AppController {

  public function beforeFilter(Event $event) {
    parent::beforeFilter($event);
    $this->Auth->deny(['index', 'add', 'edit']);
  }

  public function index() {
    $queryObj = $this->Search->getQuery(array(
      'replace_dot'=>false,
      'fix_case'=>false,
    ));
    $this->set('queryObj', $queryObj);
    if ($queryObj->query) {
      $this->set('messages', $this->Messages->find('all', [
        'conditions' => [
          '$or' => [
            [ 'type' => $queryObj->query ],
            [ 'key' => [ '$regex' => $queryObj->query ] ],
            [ 'eng' => [ '$regex' => $queryObj->query ] ],
            [ 'mlt' => [ '$regex' => $queryObj->query ] ],
          ]
        ]
      ]));
    } else {
      $this->set('messages', $this->Messages->find('all', [
        'order' => [
          'created' => 'DESC'
        ]
      ]));
    }
  }

  public function add() {
    if ($this->request->is('post')) {
      $message = $this->Messages->newEntity($this->request->data);
      if ($this->Messages->save($message)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
  }

  public function edit($id = null) {
    $this->Messages->id = $id;
    if (!$this->Messages->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Messages->save($message)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    } else {
      $this->request->data = $this->Messages->read(null, $id);
    }
  }

  public function delete($id = null) {
    $msg = '';
    if (!$id) {
      $this->setMessageBad(__('Invalid ID'));
    }
    if ($this->Messages->delete($message)) {
      $this->setMessageGood(__('Entry deleted'));
    } else {
      $this->setMessageBad(__('Error'));
    }
    $this->redirect(array('action' => 'index'));
  }

}
