<?php
App::uses('AppModel', 'Model');
class Root extends AppModel {

  public $primaryKey = '_id';

  public $hasMany = array('Lexeme');

  public $displayField = 'radicals';

  // Add virtual fields
  public function afterFind($results, $primary=false) {
    $results = parent::afterFind($results);
    foreach ($results as &$item) {
      if (@$item['Root']['variant'])
        $item['Root']['radicals_with_variant'] = $item['Root']['radicals'] . ' ' . $item['Root']['variant'];
      $item['Root']['radical_count'] = 1+substr_count(@$item['Root']['radicals'], '-');
    }
    return $results;
  }

  // Get conditions for search/pagination
  public function searchConditions($queryObj) {
    $conditions = array(
      '$or' => array()
    );

    // Search in lexemes
    $conditions_2 = array('$or' => array(
      array('lemma' => array('$regex' => $queryObj->query)),
      array('alternatives' => array('$regex' => $queryObj->query)),
    ));
    if ($queryObj->search_gloss) {
      $conditions_2['$or'][] = array('gloss' => array('$regex' => $queryObj->query));
    }

    $lexeme_roots = $this->Lexeme->find('list', array(
      'fields' => array('root'),
      'conditions' => $conditions_2,
    ));
    $conditions_roots = array_map(function($i){
      if ($i)
        return array('$and'=>array(array_diff_key($i, array('_id'=>1))));
    }, $lexeme_roots);
    $conditions_roots = array_filter(array_values($conditions_roots), function($i){return ($i!=null);});

    // Then include as conditions for root
    $conditions['$or'] = array_merge(array(
      array('radicals' => array('$regex' => $queryObj->query)),
      array('alternatives' => array('$regex' => $queryObj->query)),
    ), $conditions_roots);

    // Root type
    if (@$queryObj->root_type) {
      $conditions['type'] = $queryObj->root_type;
    }

    return $conditions;
  }

  // Assoc array for direct use in dropdowns
  public function options() {
    $roots = $this->find('all', array(
      'fields' => array('_id','radicals','variant')
    ));
    $roots_list = array();
    foreach($roots as $r) {
      if (@$r['Root']['variant']) {
        $roots_list[$r['Root']['_id']] = $r['Root']['radicals'] . ' ' . @$r['Root']['variant'];
      } else {
        $roots_list[$r['Root']['_id']] = $r['Root']['radicals'];
      }
    }
    uasort($roots_list, array($this,'mt_compare'));
    return $roots_list;
  }

  public function resolveID($id) {
    if (!$id) return null;
    $root = $this->find('first', array(
      'conditions' => array('_id' => $id),
      'fields' => array('radicals', 'variant')
    ));
    // unset($root['Root']['_id']);
    unset($root['Root']['radicals_with_variant']);
    unset($root['Root']['radical_count']);
    return $root['Root'];
  }

}
