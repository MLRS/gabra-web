<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;

class RefererComponent extends Component {

  var $ignore_actions = array('add', 'edit', 'login', 'merge');

  public $components = array('Session');

  public function initialize(array $config) {
    // TODO
    // $this->controller = $controller;
  }

  public function set() {
    $ref = $this->controller->referer(null, true);
    foreach ($this->ignore_actions as $a) {
      if (stripos($ref, $a) !== false) return;
    }
    // else...
    $this->Session->write('referer', $ref);
  }

  public function get() {
    return $this->Session->read('referer');
  }

}
