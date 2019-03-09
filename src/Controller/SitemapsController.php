<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

class SitemapsController extends AppController {

  public function index() {
    $this->layout = false;
    $this->loadModel('Roots');
    $this->loadModel('Lexemes');
    $this->set('roots', $this->Roots->find('all',array('fields' => array('radicals','variant','modified'))));
    $this->set('lexemes', $this->Lexemes->find('all',array('fields' => array('_id','lemma','modified'))));
    Configure::write ('debug', 0); //debug logs will destroy xml format, make sure were not in debug mode
  }

}
?>
