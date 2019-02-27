<?php
namespace App\Model\Table;

use Cake\ORM\Table;
class MessagesTable extends Table {

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
