<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

class LexemesController extends AppController {

  public $paginate = array(
    'fields' => array('wordforms'=>0), // wordforms are loaded asynchronously
    'limit' => 20,
    'order' => array(
      'lemma' => 'ASC',
    )
  );

  public function beforeFilter(Event $event) {
    parent::beforeFilter($event);
    $this->Auth->allow(['search', 'random']);
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
    $this->set('sources', $this->Sources->options()); // for dropdown
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
  public function view($id = null) {
    $lexeme = $this->Lexemes->find('first', ['id' => $id]);
    if (!$lexeme) {
      throw new NotFoundException(__('Invalid ID'));
    }

    // Get, sort, add wordforms
    // TODO resturns empty
    $wordforms = $this->Wordforms->find('all', array(
      'conditions' => array(
        'lexeme_id' => new \MongoDB\BSON\ObjectId($id),
        // 'dir_obj' => null, 'ind_obj' => null, 'polarity' => 'pos' // minimised table
      ),
    ));

    // TODO
    // $host = $lexeme;
    // usort($wordforms, function($a, $b) use ($host) {
    //   return $host->compareWordForms($a['Wordform'], $b['Wordform'], array('aspect', 'subject', 'dir_obj', 'ind_obj','number', 'person', 'gender', 'polarity'));
    // });

    // Get entries with same root (using root._id)
    // if (@$lexeme['root']['_id']) {
    //   $related = $this->Lexemes->find('all', array(
    //     'conditions' => array(
    //       'root._id'=>$lexeme['root']['_id'],
    //       '_id'=>array('$ne'=>$lexeme['_id']),
    //     ),
    //     'fields' => array('lemma','pos','derived_form'),
    //     'order' => array('pos'=>'ASC'),
    //   ));
    //   $lexeme['Related'] = $related;
    // }

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
