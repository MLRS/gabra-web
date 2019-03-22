<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RootsTable extends Table {

  public function getAll() {
    $json = file_get_contents(API_URL . 'roots/');
    $roots = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $roots;
  }

  public function getByRadicals($radicals, $variant=null) {
    $url = API_URL . 'roots/' . urlencode($radicals);
    if ($variant) $url .= '/' . $variant;
    $json = file_get_contents($url);
    $root = (array) json_decode($json);
    return $root;
  }

}
