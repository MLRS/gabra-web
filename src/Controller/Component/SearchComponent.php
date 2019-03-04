<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class SearchComponent extends Component {

  public $components = array('RequestHandler');

  // workaround for the fact that . doesn't work on multibyte chars in MySQL regex
  private $dot = "('|a|b|ċ|d|e|f|ġ|g|għ|h|ħ|i|ie|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|ż|z)";

  public function hasQuery() {
    $q = $this->RequestHandler->request->getQueryParams();
    return isset($q) && !empty($q);
  }

  private function boolItem($key, $default) {
    if ($this->RequestHandler->request->getQuery($key))
      return (bool) $this->RequestHandler->request->getQuery($key);
    else
      return $default;
  }

  public function getQuery($options=array()) {
    $opts = array_merge(array(
      'fix_case' => true,
      'replace_dot' => true,
    ), $options);

    $q = $this->RequestHandler->request->getQueryParams();
    $query = null;

    if (@$q['c1'] || @$q['c2'] || @$q['c3'] || @$q['c4']) {
      $query = '^';
      $query .= @$q['c1'] ? $q['c1'] : '.' ;
      $query .= '-';
      $query .= @$q['c2'] ? $q['c2'] : '.' ;
      $query .= '-';
      $query .= @$q['c3'] ? $q['c3'] : '.' ;
      if (@$q['c4']=='none') {
        $query .= '$';
      } elseif (@$q['c4']) {
        $query .= '-'.$q['c4'];
      }
    } else {
      $query = @($q['s']);
    }

    // If someone has CAPS LOCK on...
    if ($opts['fix_case'] && $query == strtoupper($query)) {
      $query = strtolower($query);
    }

    $obj = new SearchQuery();
    if ($opts['replace_dot'])
      $obj->query = str_replace('.', $this->dot, $query);
    else
      $obj->query = $query;
    $obj->raw_query = $query;
    $obj->regex_search = $this->boolItem('r', false);
    $obj->search_lemma = $this->boolItem('l', true);
    $obj->search_wordforms = $this->boolItem('wf', true);
    $obj->search_gloss = $this->boolItem('g', true);
    $obj->pos = @$this->RequestHandler->request->getQuery('pos');
    $obj->root_type = @$this->RequestHandler->request->getQuery('t');
    $obj->source = @$this->RequestHandler->request->getQuery('source');

    $obj->page = @$this->RequestHandler->request->getQuery('page');
    $obj->results = null;

    $obj->json = json_encode($this->RequestHandler->request->getQueryParams());

    return $obj;
  }

}
