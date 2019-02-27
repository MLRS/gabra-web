<?php
namespace App\Model\Table;

use Cake\ORM\Table;
class SourcesTable extends Table {

  public $primaryKey = '_id';

  // Assoc array for direct use in dropdowns
  public function options() {
    return $this->find('list', array(
      'fields' => array('key','key')
    ));
  }

}
