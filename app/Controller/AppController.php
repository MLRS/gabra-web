<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

  public $components = array(
    'Session',
    'Referer',
    'Search',
    'Auth' => array(
      'loginRedirect'  => '/',
      'logoutRedirect' => '/',
    ),
    'RequestHandler',
  );

  public $helpers = array(
    'Minify.Minify',
  );

  // Add debug toolbar when debug > 0
  // http://stackoverflow.com/a/6701414
  public function constructClasses() {
    if (Configure::read('debug') > 0) {
      $this->components[] = 'DebugKit.Toolbar';
    }
    parent::constructClasses();
  }

  private $alphabet = array(
    "a", "b", "ċ", "d", "e", "f", "ġ", "g", "għ", "h", "ħ", "i", "ie", "j", "k",
    "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "ż", "z");
  private $consonants = array(
    "b", "ċ", "d", "f", "ġ", "g", "għ", "h", "ħ", "j", "k",
    "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "ż", "z");
  private $parts_of_speech;
  private $root_types;

  private function enums() {
    $this->parts_of_speech = array(
      // Universal POS tags
      // http://universaldependencies.github.io/docs/u/pos/index.html
      'ADJ'   => __('adjective'),
      'ADP'   => __('adposition'),
      'ADV'   => __('adverb'),
      'AUX'   => __('auxiliary verb'),
      'CONJ'  => __('coordinating conjunction'),
      'DET'   => __('determiner'),
      'INTJ'  => __('interjection'),
      'NOUN'  => __('noun'),
      'NUM'   => __('numeral'),
      'PART'  => __('particle'),
      'PRON'  => __('pronoun'),
      'PROPN' => __('proper noun'),
      'PUNCT' => __('punctuation'),
      'SCONJ' => __('subordinating conjunction'),
      'SYM'   => __('symbol'),
      'VERB'  => __('verb'),
      'X'     => __('other'),
    );
    $this->root_types = array(
      'strong'       => __('strong'),
      'geminated'    => __('geminated'),
      'weak-initial' => __('weak initial'),
      'weak-medial'  => __('weak medial'),
      'weak-final'   => __('weak final'),
      'irregular'    => __('irregular'),
    );
  }

  public function beforeFilter() {

    // If site maintenance, then just die
    if (Configure::read('Site.status') === 0) {
      header('HTTP/1.0 503 Service Unavailable');
      header('Content-Type: text/html; charset=utf-8');
      die("Ġabra is down for maintenance, please try again later.");
    }

    // User
    // $this->Auth->allow();
    // $this->Auth->deny('add', 'edit', 'delete');
    $this->Auth->allow('index', 'view', 'display');

    // Language
    if (isset($this->request->query['lang'])) {
      $this->Session->write('Config.language', $this->request->query['lang']);
    }
    if (!$this->Session->read('Config.language')) {
      $this->Session->write('Config.language', 'eng'); // default is English
    }
    $language = $this->Session->read('Config.language');
    Configure::write('Config.language', $language);
    $this->set('language',  $language);

    // Localised web content
    $this->loadModel('Message');
    $this->set('content', $this->Message->webContent($language));

    // General stuff
    $this->enums();
    $common = array(
      'alphabet' => $this->alphabet,
      'consonants' => $this->consonants,
      'root_types' => $this->root_types,
      'parts_of_speech' => $this->parts_of_speech,
      'user' => $this->Auth->user(),
    );
    // Add dummy user if developing
    // if (defined('DEVELOPMENT_MODE')) {
    //   $common['user'] = array(
    //     'created' => 0,
    //     'modified' => 0,
    //     'role' => 'admin',
    //     'username' => 'DEVELOPMENT_MODE',
    //     'id' => '52132fec66ccf715abd4827b' // same as john.camilleri
    //   );
    // }
    $this->set('common', $common);

    // Save referrer in session for possible future redirect
    $this->Referer->set();
  }

  /* Wrappers over Session->setFlash */
  public function setMessageGood($message) {
    return $this->setMessage($message, 'good');
  }
  public function setMessageBad($message) {
    return $this->setMessage($message, 'bad');
  }
  public function setMessageInfo($message) {
    return $this->setMessage($message, 'info');
  }
  private function setMessage($message, $class) {
    return $this->Session->setFlash(
      $message,
      'default',
      array(),
      $class
    );
  }

}
