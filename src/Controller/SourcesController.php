<?php
namespace App\Controller;

use App\Controller\AppController;

class SourcesController extends AppController {

  public function index() {
    $sources = $this->Sources->getAll();
    $this->set('sources', $sources);
  }

  public function view($key = null) {
    $source = $this->Sources->getByKey($key);
    if (!$key || !$source) {
      $this->setMessageBad(__('Invalid ID'));
      $this->redirect(array('action'=>'index'));
      return;
    }
    $this->set('source', $source);
  }

}
