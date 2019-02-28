<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class LexemesTable extends Table {

  // Used for sorting word forms
  public function compareWordForms($a, $b, $fields) {
    $f = array_shift($fields);
    if (!$f) return 0;
    // $x = @$a['Wordform'][$f];
    // $y = @$b['Wordform'][$f];
    $x = @$a[$f];
    $y = @$b[$f];

    if ($x===$y) return $this->compareWordForms($a,$b,$fields);
    if (empty($x)&&$y) return -1;
    if ($x&&empty($y)) return 1;

    if (is_array($x) && is_array($y)) {
      return $this->compareWordForms($x,$y,$fields);
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
