<?php
App::uses('AppModel', 'Model');
class Wordform extends AppModel {

  public $primaryKey = '_id';

  public $belongsTo = 'Lexeme';

  public function beforeSave($options=array()) {
    parent::beforeSave($options);
    $this->data['Wordform']['lexeme_id'] = new MongoId($this->data['Wordform']['lexeme_id']);
    return true;
  }

}
