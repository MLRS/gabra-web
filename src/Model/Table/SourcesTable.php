<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class SourcesTable extends Table {

  // Assoc array for direct use in dropdowns
  public function options() {
    return $this->find('list', array(
      'fields' => array('key','key')
    ));
  }

}
