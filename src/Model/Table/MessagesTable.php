<?php
namespace App\Model\Table;

use Hayko\Mongodb\ORM\Table;

class MessagesTable extends Table {

  // Messages in table use eng, mlt
  // whereas I18n uses en, mt
  private function convertLanguage($language) {
    switch ($language) {
      case 'mt': return 'mlt';
      default: return 'eng';
    }
  }

  // rename language keys
  public function find($type='all', $options=[]) {
    $results = parent::find($type, $options);
    if (is_array($results)) {
      foreach ($results as $result) {
        if (!is_object($result)) continue;
        if (@$result['eng']) {
          $result['en'] = $result['eng'];
          unset($result['eng']);
        }
        if (@$result['mlt']) {
          $result['mt'] = $result['mlt'];
          unset($result['mlt']);
        }
      }
    }
    return $results;
  }

  public function webContent($language) {
    return $this->find('list', array(
      'conditions' => array(
        'type' => 'web'
      ),
      'keyField' => 'key',
      'valueField' => $this->convertLanguage($language),
    ));
  }

}
