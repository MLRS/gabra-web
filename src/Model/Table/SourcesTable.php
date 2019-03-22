<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SourcesTable extends Table {

  public function getAll() {
    $json = file_get_contents(API_SERVER_URL . 'sources/');
    $sources = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $sources;
  }

  // Assoc array for direct use in dropdowns
  public function getList() {
    $json = file_get_contents(API_SERVER_URL . 'sources/');
    $sources = json_decode($json);
    $list = [];
    foreach ($sources as $source) {
      $list[$source->key] = $source->key;
    }
    return $list;
  }

  public function getByKey($key) {
    $json = file_get_contents(API_SERVER_URL . 'sources/' . urlencode($key));
    $source = (array) json_decode($json);
    return $source;
  }

}
