<?php
namespace App\Controller;

use App\Controller\AppController;

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
    $this->loadModel('Root');
    $this->loadModel('Source');
  }

  /**
   * Complete an action, correctly handling AJAX requests
   */
  private function complete($ok, $msg, $redirect=null) {
    if($this->RequestHandler->isAjax()) {
      $this->set('response', $ok ? 'OK' : 'ERROR');
      $this->set('message', $msg);
      $this->set('_serialize', array('response', 'message'));
    } else {
      if ($ok)
        $this->setMessageGood($msg);
      else
        $this->setMessageBad($msg);
      if ($redirect)
        $this->redirect($redirect);
      else
        $this->redirect($this->Referer->get());
    }
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
    $this->set('sources', $this->Source->options()); // for dropdown
    $this->render('advanced-search');
  }

  /**
   * Pending suggestions
   */
  public function pending() {
    $this->loadModel('Wordform');
    $conditions['$or'] = array(
      array('pending' => true),
      // array('_id' => array('$in' => $ids)),
    );
    $this->paginate['order'] = array(
      'created' => 'DESC'
    );
    $data = $this->paginate('Lexeme', $conditions);
    $this->set('page_title', __('Pending'));
    $this->set('lexemes', $data);
    $this->render('admin-index');
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
    $this->Lexemes->id = $id;
    if (!$this->Lexemes->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    $lexeme = $this->Lexemes->read();

    // Get, sort, add wordforms
    $wordforms = $this->Lexemes->Wordform->find('all', array(
      'conditions' => array(
        'lexeme_id' => new MongoId($id),
        // 'dir_obj' => null, 'ind_obj' => null, 'polarity' => 'pos' // minimised table
      ),
    ));
    $host = $this->Lexeme;
    usort($wordforms, function($a, $b) use ($host) {
      return $host->compareWordForms($a['Wordform'], $b['Wordform'], array('aspect', 'subject', 'dir_obj', 'ind_obj','number', 'person', 'gender', 'polarity'));
    });
    $lexeme['Wordforms'] = $wordforms;

    // Get entries with same root (using root._id)
    // if (@$lexeme->lexeme['root']['_id']) {
    //   $related = $this->Lexemes->find('all', array(
    //     'conditions' => array(
    //       'root._id'=>$lexeme->lexeme['root']['_id'],
    //       '_id'=>array('$ne'=>$lexeme->lexeme['_id']),
    //     ),
    //     'fields' => array('lemma','pos','derived_form'),
    //     'order' => array('pos'=>'ASC'),
    //   ));
    //   $lexeme['Related'] = $related;
    // }

    // Get entries with same root (using root.radicals and root.variant)
    if (@$lexeme->lexeme['root']) {
      $conds = array(
        'root.radicals'=>$lexeme->lexeme['root']['radicals'],
        '_id'=>array('$ne'=>$lexeme->lexeme['_id']),
      );
      if (@$lexeme->lexeme['root']['variant']) {
        $conds['root.variant'] = $lexeme->lexeme['root']['variant'];
      }
      $related = $this->Lexemes->find('all', array(
        'conditions' => $conds,
        'fields' => array('lemma','pos','derived_form'),
        'order' => array('pos'=>'ASC'),
      ));
      $lexeme['Related'] = $related;
    }

    $this->set('lexeme', $lexeme);
    $this->set('_serialize', array('lexeme')); // used by duplicates view
  }

  // Prepare data from add/edit
  private function prepareData() {
    // Make alternatives an array
    if ($alts=$this->request->data['Lexeme']['alternatives']) {
      $alts = explode(',', $alts);
      foreach ($alts as &$a) { $a = trim($a); }
      $this->request->data['Lexeme']['alternatives'] = $alts;
    }

    // Make sources an array
    if ($srcs=$this->request->data['Lexeme']['sources']) {
      $srcs = explode(',', $srcs);
      foreach ($srcs as &$a) { $a = trim($a); }
      $this->request->data['Lexeme']['sources'] = $srcs;
    }

    // Lookup root
    $root = $this->Root->resolveID($this->request->data['root']['_id']);
    if (@$root) {
      $this->request->data['Lexeme']['root'] = $root;
    } else {
      $this->request->data['Lexeme']['root'] = null;
    }
    unset($this->request->data['root']);

    // Derived form should be int
    if (@$this->request->data['Lexeme']['derived_form']) {
      $this->request->data['Lexeme']['derived_form'] = (int) $this->request->data['Lexeme']['derived_form'];
    } else {
      unset($this->request->data['Lexeme']['derived_form']);
    }

    // Boolean fields
    $boolean_fields = array(
      'transitive',
      'ditransitive',
      'intransitive',
      'hypothetical'
    );
    foreach($boolean_fields as $k)
    if (null !== @$this->request->data['Lexeme'][$k]) {
      $this->request->data['Lexeme'][$k] = (bool) $this->request->data['Lexeme'][$k];
    }

  }

  /**
   * Add
   */
  public function add() {
    if ($this->request->is('post')) {
      $this->prepareData();
      $lexeme = $this->Lexemes->newEntity($this->request->data);
      if ($this->Lexemes->save($lexeme)) {
        $this->setMessageGood(__('Item saved'));
        $this->redirect(array('action' => 'view', $this->Lexemes->id));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }

    $this->set('roots', $this->Root->options()); // for dropdown
  }

  /**
   * Edit
   */
  public function edit($id = null) {
    if (!$id && empty($this->data)) {
      $this->setMessageBad(__('Invalid ID'));
    }
    if (!empty($this->data)) {
      $this->prepareData();
      if ($this->Lexemes->save($this->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action'=>'edit', $id));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Lexemes->read(null, $id);
      $this->set('roots', $this->Root->options()); // for dropdown

      // Get, sort, add wordforms
      $wordforms = $this->Lexemes->Wordform->find('all', array(
        'conditions' => array(
          'lexeme_id' => new MongoId($id),
          'generated' => array('$ne'=>true), // only non-generated forms
        ),
      ));
      $host = $this->Lexeme;
      usort($wordforms, function($a, $b) use ($host) {
        return $host->compareWordForms($a, $b, array('aspect', 'subject', 'dir_obj', 'ind_obj', 'polarity', 'number', 'gender'));
      });
      $this->set('wordforms', $wordforms);
    }

  }

  /**
   * Delete
   */
  public function delete($id = null) {
    $ok = false;
    $msg = '';
    if (!$id) {
      $msg = __('Invalid ID');
    }
    if ($this->Lexemes->delete($lexeme)) {
      $ok = true;
      $msg = __('Entry deleted');
    } else {
      $msg = __('Error');
    }
    $this->complete($ok, $msg);
  }

  /**
   * Show duplicates
   */
  public function duplicates($page=1) {
    $MR_COLL = 'duplicate_lemmas'; // formerly 'mr_dupes'
    $ds = $this->Lexemes->getDataSource();
    $mr = array(
      'mapreduce' => 'lexemes',
      'map' => new MongoCode(<<<JS
function(){
  if (this.pending) return
  var obj = {
    lemma: this.lemma,
    pos: this.pos, // could be null
    root: null
  }
  if (this.root) {
    obj.root = {
      radicals: this.root.radicals,
      variant: this.root.variant // could be null
    }
  }
  emit(obj, this._id)
}
JS
),
      // 'reduce' => new MongoCode('function(keys, vals){ return {"ids":vals, "count":NumberInt(vals.length)}; }'),
      'reduce' => new MongoCode(<<<JS
function(keys, vals){
  var ret = {
    ids:[],
    count:0
  }
  vals.forEach(function(v) {
    if (v.ids) {
      ret.ids = ret.ids.concat(v.ids)
      ret.count += v.ids.length
    }
    else {
      ret.ids.push(v)
      ret.count++
    }
  })
  ret.count = NumberInt(ret.count)
  return ret
}
JS
),
      'out' => array('replace' => $MR_COLL),
    );

    if ($this->Search->hasQuery()) {
      $queryObj = $this->Search->getQuery();
      $mr['query'] = array('lemma' => array('$regex' => $queryObj->query));
      $this->set('queryObj', $queryObj);
    }
    $ds->mapreduce($mr);
    $cursor = $ds->getMongoDb()->selectCollection($MR_COLL)->find(array('value.count'=>array('$gt'=>1)))->sort(array('value.count'=>-1));
    $dupes = array_values(iterator_to_array($cursor, false));

    if ($this->Search->hasQuery() && count($dupes)==0) {
      $new_msg  = __('No duplicates found for: ') . $this->Search->getQuery()->query;
      $this->setMessageInfo($new_msg);
      $this->redirect(array('controller' => 'Lexemes', 'action' => 'duplicates', $page));
    }

    $this->set('duplicates', $dupes);
    $this->set('page', $page);
  }

  /**
   * Merge (both view and callback)
   */
  public function merge($id1=null) {

    // Check first ID
    if (!$id1 || !$this->Lexemes->findById($id1)) {
      $this->setMessageBad(__('Invalid ID') . " ($id1)");
      throw new BadRequestException();
    }

    // Check all second IDs
    $id2s = explode(',', @$this->RequestHandler->request->query['id2s']);
    foreach($id2s as $id) {
      if (!$id || !$this->Lexemes->findById($id)) {
        $this->setMessageBad(__('Invalid ID') . " ($id)");
        throw new BadRequestException();
      }
    }

    // Delegate
    $id2 = null;
    switch (count($id2s)) {
      case 0:
        $this->setMessageBad(__('Invalid ID') . " (2)");
        throw new BadRequestException();
      case 1:
        //just continue
        $id2 = $id2s[0];
        break;
      default:
        $this->mergeGlosses($id1, $id2s);
        return;
    };

    $mid1 = new MongoID($id1);
    $mid2 = new MongoID($id2);
    if (!empty($this->data)) {
      $this->prepareData();
      if ($this->Lexemes->save($this->data)) {

        // Move wordforms
        $this->Lexemes->Wordform->updateAll(
          array('lexeme_id'=>$mid1),
          array('lexeme_id'=>$mid2)
        );

        // Delete old
        $this->Lexemes->delete($id2);
        $this->setMessageGood(__('Entries merged'));
        // $this->redirect(array('controller' => 'Lexemes', 'action' => 'duplicates', '?'=>array('s'=>$this->data['Lexeme']['lemma'])));
        $this->redirect($this->Referer->get());
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Lexemes->read(null, $id1);
      $this->set('roots', $this->Root->options()); // for dropdown

      $this->set('other', $this->Lexemes->findById($id2));
      $this->set('other_wf', $this->Lexemes->Wordform->find('all', array('conditions'=>array('lexeme_id'=>$mid2))));

      $this->set('id1', $id1);
      $this->set('id2', $id2);
    }
  }

  /**
   * Merge glosses (can only be called from merge)
   * Here we can assume that all IDs have been checked
   */
  private function mergeGlosses($id1, $id2s) {

    // Join glosses
    $glosses = array();
    foreach($id2s as $id2) {
      $item = $this->Lexemes->findById($id2);
      $glosses[] = $item->lexeme['gloss'];
    }

    // Save
    $data = $this->Lexemes->read(null, $id1);
    $this->Lexemes->set('gloss', $data->lexeme['gloss'] . "\n" . implode("\n", $glosses));
    if ($this->Lexemes->save()) {
      // Delete old
      foreach($id2s as $id2) {
        $this->Lexemes->delete($id2);
      }
      $this->setMessageGood(__('Entries merged'));
    } else {
      $this->setMessageBad(__('Error'));
    }

    $this->redirect(array('controller' => 'Lexemes', 'action' => 'duplicates', '?'=>array('s'=>$data->lexeme['lemma'])));
  }

}
