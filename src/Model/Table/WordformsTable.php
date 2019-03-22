<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class WordformsTable extends Table {

  public function getForLexeme($lexeme_id) {
    $json = file_get_contents(API_SERVER_URL . 'lexemes/wordforms/' . $lexeme_id . '?pending=1');
    $wordforms = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $wordforms;
  }

}
