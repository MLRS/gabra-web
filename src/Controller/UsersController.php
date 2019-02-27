<?php
namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController {

  private $roleOptions = array(
    'admin' => 'Admin',
    'linguist' => 'Linguist'
  );

  public function beforeFilter(Event $event) {
    parent::beforeFilter($event);
    $this->Auth->deny(['index', 'view']);
    $this->Auth->allow('login');
  }

  public function login() {
    if ($this->Auth->loggedIn()) {
      $this->redirect('/');
    }

    if ($this->request->is('post')) {
      if ($this->Auth->identify()) {
        return $this->redirect($this->Auth->redirect());
      }
      $this->setMessageBad(__('Invalid username or password, try again'));
    }
  }

  public function logout() {
    return $this->redirect($this->Auth->logout());
  }

  public function index() {
    $this->Users->recursive = 0;
    $this->set('users', $this->paginate());
  }

  public function view($id = null) {
    $this->Users->id = $id;
    if (!$this->Users->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    $this->set('user', $this->Users->read(null, $id));
  }

  public function add() {
    if ($this->request->is('post')) {
      $user = $this->Users->newEntity($this->request->data);
      if ($this->Users->save($user)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    $this->set('roleOptions', $this->roleOptions);
  }

  public function edit($id = null) {
    $this->Users->id = $id;
    if (!$this->Users->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Users->save($user)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    } else {
      $this->request->data = $this->Users->read(null, $id);
      unset($this->request->data['User']['password']);
    }
    $this->set('roleOptions', $this->roleOptions);
  }

  public function delete($id = null) {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException();
    }
    $this->Users->id = $id;
    if (!$this->Users->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->Users->delete()) {
      $this->setMessageGood(__('User deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->setMessageBad(__('Error'));
    $this->redirect(array('action' => 'index'));
  }
}
