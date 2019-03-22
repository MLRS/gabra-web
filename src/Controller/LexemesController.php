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
   * Random
   */
  public function random() {
    $all_ids = $this->Lexemes->find('list', array('fields'=> array('id')));
    $random = array_rand($all_ids, 1);
    $this->redirect(array('action'=>'view', $random));
  }

  /**
   * View
   */
  public function view($id) {
    $_id = new \MongoDB\BSON\ObjectId($id);
    $lexeme = $this->Lexemes->find('first', array(
      'conditions' => array (
        '_id' => $_id,
      )
    ));
    if (!$lexeme) {
      throw new NotFoundException(__('Invalid ID'));
    }

    // Get, sort, add wordforms
    $wordforms = $this->Wordforms->getForLexeme($_id);

    $host = $this->Lexemes;
    usort($wordforms, function($a, $b) use ($host) {
      return $host->compareWordForms($a, $b, array('aspect', 'subject', 'dir_obj', 'ind_obj','number', 'person', 'gender', 'polarity'));
    });

    // Get entries with same root (using root.radicals and root.variant)
    if (@$lexeme['root']) {
      $conds = array(
        'root.radicals'=>$lexeme['root']->radicals,
        '_id'=>array('$ne'=>$lexeme['_id']),
      );
      if (@$lexeme['root']->variant) {
        $conds['root.variant'] = $lexeme['root']->variant;
      }
      $related = $this->Lexemes->find('all', array(
        'conditions' => $conds,
        'fields' => array('lemma','pos','derived_form'),
        'order' => array('pos'=>'ASC'),
      ));
      $this->set('related', $related);
    }

    $this->set('lexeme', $lexeme);
    $this->set('wordforms', $wordforms);
  }

}
