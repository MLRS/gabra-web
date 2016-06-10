<?php
App::uses('AppModel', 'Model');
class Source extends AppModel {

  public $primaryKey = '_id';

  // Assoc array for direct use in dropdowns
  public function options() {
    return $this->find('list', array(
      'fields' => array('key','key')
    ));
  }

}
