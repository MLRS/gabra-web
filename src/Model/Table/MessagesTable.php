<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class MessagesTable extends Table {

  public function webContent($language) {
    return $this->find('list', array(
      'conditions' => array(
        'type' => 'web'
      ),
      'keyField' => 'key',
      'valueField' => $language,
    ));
  }

}
