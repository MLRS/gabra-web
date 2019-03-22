<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

class LexemesController extends AppController {

  public function beforeFilter(Event $event) {
    parent::beforeFilter($event);
    $this->loadModel('Wordforms');
    $this->loadModel('Roots');
    $this->loadModel('Sources');
  }

  /**
   * Index
   */
  public function index() {
    $queryObj = $this->Search->getQuery();
    $this->set('queryObj', $queryObj);
  }

  /**
   * Advanced search
   */
  public function search() {
    $this->set('sources', $this->Sources->getList()); // for dropdown
    $this->render('advanced-search');
  }

  /**
   * View
   */
  public function view($id) {
    $lexeme = $this->Lexemes->getById($id);
    if (!$lexeme) {
      throw new NotFoundException(__('Invalid ID'));
    }

    // Get, sort, add wordforms
    $wordforms = $this->Wordforms->getForLexeme($id);

    $host = $this->Lexemes;
    usort($wordforms, function($a, $b) use ($host) {
      return $host->compareWordForms($a, $b, array('aspect', 'subject', 'dir_obj', 'ind_obj','number', 'person', 'gender', 'polarity'));
    });

    // Get entries with same root (using root.radicals and root.variant)
    if (@$lexeme['root']) {
      $related = $this->Lexemes->getRelated($id);
      $this->set('related', $related);
    }

    $this->set('lexeme', $lexeme);
    $this->set('wordforms', $wordforms);
  }

}
