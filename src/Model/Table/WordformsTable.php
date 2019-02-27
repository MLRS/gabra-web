<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class WordformsTable extends Table {

  public $belongsTo = 'Lexeme';

  public function beforeSave(Event $event, Entity $entity, ArrayObject $options) {
    parent::beforeSave($options);
    $this->data['Wordform']['lexeme_id'] = new MongoId($this->data['Wordform']['lexeme_id']);
    return true;
  }

}
