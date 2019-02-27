<?php
namespace App\Model\Table;

use Cake\ORM\Table;
class LexemesTable extends Table {

  public $primaryKey = '_id';

  public $belongsTo = 'Root';
  public $hasMany = 'Wordform';

  // Get conditions for search/pagination
  public function searchConditions($queryObj) {
    $conditions = array(
      '$or' => array()
    );

    if (@$queryObj->query) {
      $q = $queryObj->query;
      $qr = array('$regex' => $queryObj->query);

      function add(&$conditions, $field, $q) {
        $conditions['$or'][] = array($field => array('$regex' => $q));
        $q_lower = mb_strtolower($q,'utf-8');
        if ($q != $q_lower) {
          $conditions['$or'][] = array($field => array('$regex' => $q_lower));
        }
      }

      // Search in lexemes.gloss (always regex)
      if ($queryObj->search_gloss) {
        // $conditions['$or'][] = array('gloss' => $qr);
        add($conditions, 'gloss', $q);
      }

      // Search in lexemes.lemma (always regex)
      if ($queryObj->search_lemma) {
        // $conditions['$or'][] = array('lemma' => $qr);
        // $conditions['$or'][] = array('alternatives' => $qr);
        add($conditions, 'lemma', $q);
        add($conditions, 'alternatives', $q);
      }

      // Search in wordforms.surface_form (only regex is specified)
      if ($queryObj->search_wordforms) {
        $conds2 = array('$or'=>array());
        if ($queryObj->regex_search) {
          add($conds2, 'surface_form', $q);
          // add($conds2, 'alternatives', $q);
        } else {
          add($conds2, 'surface_form', '^'.$q);
          // add($conds2, 'alternatives', '^'.$q);
        }
        $ids = $this->Wordform->find('list', array(
          'fields' => array('lexeme_id'),
          'conditions' => $conds2,
        ));
        $conditions['$or'][] = array('_id' => array('$in' => array_values($ids)));
      }
    }

    // Specify POS
    if (@$queryObj->pos) {
      $conditions['pos'] = $queryObj->pos;
    }

    // Specify source
    if (@$queryObj->source) {
      $conditions['sources'] = array('$elemMatch'=>array('$eq'=>$queryObj->source));
    }

    // Ignore pending
    $conditions['pending'] = array('$ne'=>true);

    return $conditions;
  }

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
