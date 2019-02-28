<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class RootsTable extends Table {

  public $displayField = 'radicals';

  // Add virtual fields
  // TODO not sure if this actually works
  public function afterFind($results, $primary=false) {
    $results = parent::afterFind($results);
    foreach ($results as &$item) {
      if (@$item['Root']['variant'])
        $item['Root']['radicals_with_variant'] = $item['Root']['radicals'] . ' ' . $item['Root']['variant'];
      $item['Root']['radical_count'] = 1+substr_count(@$item['Root']['radicals'], '-');
    }
    return $results;
  }

}
