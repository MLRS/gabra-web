<?php
class LexemesController extends AppController {

  public $paginate = array(
    'fields' => array('wordforms'=>0), // wordforms are loaded asynchronously
    'limit' => 20,
    'order' => array(
      'lemma' => 'ASC',
    )
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('search', 'random');
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
    $all_ids = $this->Lexeme->find('list', array('fields'=> array('id')));
    $random = array_rand($all_ids, 1);
    $this->redirect(array('action'=>'view', $random));
  }

  /**
   * View
   */
  public function view($id = null) {
    $this->Lexeme->id = $id;
    if (!$this->Lexeme->exists()) {
      throw new NotFoundException(__('Invalid ID'));
    }
    $lexeme = $this->Lexeme->read();

    // Get, sort, add wordforms
    $wordforms = $this->Lexeme->Wordform->find('all', array(
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
    // if (@$lexeme['Lexeme']['root']['_id']) {
    //   $related = $this->Lexeme->find('all', array(
    //     'conditions' => array(
    //       'root._id'=>$lexeme['Lexeme']['root']['_id'],
    //       '_id'=>array('$ne'=>$lexeme['Lexeme']['_id']),
    //     ),
    //     'fields' => array('lemma','pos','derived_form'),
    //     'order' => array('pos'=>'ASC'),
    //   ));
    //   $lexeme['Related'] = $related;
    // }

    // Get entries with same root (using root.radicals and root.variant)
    if (@$lexeme['Lexeme']['root']) {
      $conds = array(
        'root.radicals'=>$lexeme['Lexeme']['root']['radicals'],
        '_id'=>array('$ne'=>$lexeme['Lexeme']['_id']),
      );
      if (@$lexeme['Lexeme']['root']['variant']) {
        $conds['root.variant'] = $lexeme['Lexeme']['root']['variant'];
      }
      $related = $this->Lexeme->find('all', array(
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
      $this->Lexeme->create();
      if ($this->Lexeme->save($this->request->data)) {
        $this->setMessageGood(__('Item saved'));
        $this->redirect(array('action' => 'view', $this->Lexeme->id));
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
      if ($this->Lexeme->save($this->data)) {
        $this->setMessageGood(__('Changes saved'));
        $this->redirect(array('action'=>'edit', $id));
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Lexeme->read(null, $id);
      $this->set('roots', $this->Root->options()); // for dropdown

      // Get, sort, add wordforms
      $wordforms = $this->Lexeme->Wordform->find('all', array(
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
    if ($this->Lexeme->delete($id)) {
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
    $ds = $this->Lexeme->getDataSource();
    $mr = array(
      'mapreduce' => 'lexemes',
      'map' => new MongoCode(<<<JS
function(){
    if (!this.not_duplicate)
        emit(this.lemma, this._id);
}
JS
),
      // 'reduce' => new MongoCode('function(keys, vals){ return {"ids":vals, "count":NumberInt(vals.length)}; }'),
      'reduce' => new MongoCode(<<<JS
function(keys, vals){
    var ret = { ids:[], count:0 };
    vals.forEach(function(v){
        if (v.ids) {
          ret.ids = ret.ids.concat(v.ids);
          ret.count+=v.ids.length;
        }
        else {
          ret.ids.push(v);
          ret.count++;
        }
    });
    ret.count = NumberInt(ret.count);
    return ret;
}
JS
),
      'out' => array('replace' => 'mr_dupes'),
    );

    if ($this->Search->hasQuery()) {
      $queryObj = $this->Search->getQuery();
      $mr['query'] = array('lemma' => array('$regex' => $queryObj->query));
      $this->set('queryObj', $queryObj);
    }
    $ds->mapreduce($mr);
    $cursor = $ds->getMongoDb()->selectCollection('mr_dupes')->find(array('value.count'=>array('$gt'=>1)))->sort(array('value.count'=>-1));
    $dupes = array_values(iterator_to_array($cursor));

    if ($this->Search->hasQuery() && count($dupes)==0) {
      $new_msg  = __('No duplicates found for: ') . $this->Search->getQuery()->query;
      $this->setMessageInfo($new_msg);
      $this->redirect(array('controller'=>'lexemes', 'action'=>'duplicates', $page));
    }

    $this->set('duplicates', $dupes);
    $this->set('page', $page);
  }

  /**
   * Merge (both view and callback)
   */
  public function merge($id1=null) {

    // Check first ID
    if (!$id1 || !$this->Lexeme->findById($id1)) {
      $this->setMessageBad(__('Invalid ID') . " ($id1)");
      throw new BadRequestException();
    }

    // Check all second IDs
    $id2s = explode(',', @$this->RequestHandler->request->query['id2s']);
    foreach($id2s as $id) {
      if (!$id || !$this->Lexeme->findById($id)) {
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
      if ($this->Lexeme->save($this->data)) {

        // Move wordforms
        $this->Lexeme->Wordform->updateAll(
          array('lexeme_id'=>$mid1),
          array('lexeme_id'=>$mid2)
        );

        // Delete old
        $this->Lexeme->delete($id2);
        $this->setMessageGood(__('Entries merged'));
        // $this->redirect(array('controller'=>'lexemes', 'action'=>'duplicates', '?'=>array('s'=>$this->data['Lexeme']['lemma'])));
        $this->redirect($this->Referer->get());
      } else {
        $this->setMessageBad(__('Error'));
      }
    }
    if (empty($this->data)) {
      $this->data = $this->Lexeme->read(null, $id1);
      $this->set('roots', $this->Root->options()); // for dropdown

      $this->set('other', $this->Lexeme->findById($id2));
      $this->set('other_wf', $this->Lexeme->Wordform->find('all', array('conditions'=>array('lexeme_id'=>$mid2))));

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
      $item = $this->Lexeme->findById($id2);
      $glosses[] = $item['Lexeme']['gloss'];
    }

    // Save
    $data = $this->Lexeme->read(null, $id1);
    $this->Lexeme->set('gloss', $data['Lexeme']['gloss'] . "\n" . implode("\n", $glosses));
    if ($this->Lexeme->save()) {
      // Delete old
      foreach($id2s as $id2) {
        $this->Lexeme->delete($id2);
      }
      $this->setMessageGood(__('Entries merged'));
    } else {
      $this->setMessageBad(__('Error'));
    }

    $this->redirect(array('controller'=>'lexemes', 'action'=>'duplicates', '?'=>array('s'=>$data['Lexeme']['lemma'])));
  }

  /**
   * Mark as non-duplicate
   */
  public function not_duplicate($id = null) {
    $ok = false;
    $msg = '';
    if (!$id) {
      $msg = __('Invalid ID');
    }

    $this->Lexeme->id = $id;
    $ok = $this->Lexeme->saveField('not_duplicate', true);
    if ($ok) {
      $msg = __('Entry marked as non-duplicate');
    } else {
      $msg = __('Error');
    }
    $this->complete($ok, $msg);
  }

}
