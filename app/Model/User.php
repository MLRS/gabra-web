<?php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {

  public $name = 'User';

  public $validate = array(
    'username' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'A username is required'
      )
    ),
    'password' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'A password is required'
      )
    ),
    'role' => array(
      'valid' => array(
        'rule' => array('inList', array('admin', 'linguist')),
        'message' => 'Please enter a valid role',
        'allowEmpty' => false
      )
    )
  );

  public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
  }

}
