<?php
class UsersController extends AppController {

  private $roleOptions = array(
    'admin' => 'Admin',
    'linguist' => 'Linguist'
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->deny('index','view');
    $this->Auth->allow('login');
  }

  public function login() {
    if ($this->Auth->loggedIn()) {
      $this->redirect('/');
    }

    if ($this->request->is('post')) {
      if ($this->Auth->login()) {
        return $this->redirect($this->Auth->redirect());
      }
      $this->setMessageBad(__('Invalid username or password, try again'));
    }
  }

  public function logout() {
    return $this->redirect($this->Auth->logout());
  }

  public function index() {
    $this->User->recursive = 0;
    $this->set('users', $this->paginate());
  }

  public function view($id = null) {
    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    $this->set('user', $this->User->read(null, $id));
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->User->create();
      if ($this->User->save($this->request->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    $this->set('roleOptions', $this->roleOptions);
  }

  public function edit($id = null) {
    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->User->save($this->request->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->setMessageBad(__('Error'));
      }
    } else {
      $this->request->data = $this->User->read(null, $id);
      unset($this->request->data['User']['password']);
    }
    $this->set('roleOptions', $this->roleOptions);
  }

  public function delete($id = null) {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException();
    }
    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    if ($this->User->delete()) {
      $this->setMessageGood(__('User deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->setMessageBad(__('Error'));
    $this->redirect(array('action' => 'index'));
  }
}
