<?php
App::uses('AppModel', 'Model');
class Message extends AppModel {

  public $primaryKey = '_id';

  public function webContent($language) {
    return $this->find('list', array(
      'conditions' => array(
        'type' => 'web'
      ),
      'fields' => array('key', $language),
    ));
  }

}
