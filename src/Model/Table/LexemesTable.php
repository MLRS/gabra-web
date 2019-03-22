<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class LexemesTable extends Table {

  public function getAll() {
    $json = file_get_contents(API_SERVER_URL . 'lexemes/');
    $lexemes = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $lexemes;
  }

  public function getById($id) {
    $json = file_get_contents(API_SERVER_URL . 'lexemes/' . $id);
    $lexeme = (array) json_decode($json);
    return $lexeme;
  }

  public function getByRoot($radicals, $variant=null) {
    $url = API_SERVER_URL . 'roots/lexemes/' . urlencode($radicals);
    if ($variant) $url .= '/' . $variant;
    $json = file_get_contents($url);
    $lexemes = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $lexemes;
  }

  public function getRelated($id) {
    $json = file_get_contents(API_SERVER_URL . 'lexemes/related/' . $id);
    $lexemes = array_map(function ($obj) { return (array) $obj; }, json_decode($json));
    return $lexemes;
  }

  // Used for sorting word forms
  public function compareWordForms($a, $b, $fields) {
    // get field to sort on
    $f = array_shift($fields);
    if (!$f) return 0;

    // get values
    $x = @$a[$f];
    $y = @$b[$f];

    if ($x===$y) return $this->compareWordForms($a,$b,$fields); // sort on next field
    if (empty($x)&&$y) return -1;
    if ($x&&empty($y)) return 1;

    if (is_array($x) && is_array($y)) {
      return $this->compareWordForms($x,$y,$fields);
    }
    if (is_object($x) && is_object($y)) {
      return $this->compareWordForms((array)$x,(array)$y,$fields);
    }

    $ranks = array(
      'P1 Sg' => 1,
      'P2 Sg' => 2,
      'P3 Sg Masc' => 3,
      'P3 Sg Fem' => 4,
      'P1 Pl' => 5,
      'P2 Pl' => 6,
      'P3 Pl' => 7,

      'p1' => 1,
      'p2' => 2,
      'p3' => 3,

      'perf' => 11,
      'impf' => 12,
      'imp' => 13,
      'pastpart' => 14,
      'prespart' => 15,

      'sg' => 21,
      'dl' => 22,
      'pl' => 23,
      'sp' => 24,
      'sgv' => 25,
      'coll' => 26,

      'm' => 31,
      'f' => 32,
      'mf' => 33,

      'pos' => 41,
      'neg' => 42,
    );
    $r1 = @$ranks[$x];
    $r2 = @$ranks[$y];
    if (empty($r1)&&$r2) return -1;
    if ($r1&&empty($r2)) return 1;
    if ($r1&&$r2) return ($r1>$r2) ? 1 : -1;

    return ($x>$y) ? 1 : -1;
  }

}
