<?php
/**
* Static content controller.
*
* This file will render views from views/pages/
*
* PHP 5
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
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
* Static content controller
*
* Override this controller by placing a copy in controllers directory of an application
*
* @package       app.Controller
* @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
*/
class PagesController extends AppController {

  /**
  * Controller name
  *
  * @var string
  */
  public $name = 'Pages';

  /**
  * This controller does not use a model
  *
  * @var array
  */
  public $uses = array();

  /**
  * Displays a view
  *
  * @param mixed What page to display
  * @return void
  */
  public function display() {
    $path = func_get_args();

    $count = count($path);
    if (!$count) {
      $this->redirect('/');
    }
    $page = $subpage = $title_for_layout = null;

    if (!empty($path[0])) {
      $page = $path[0];
    }
    if (!empty($path[1])) {
      $subpage = $path[1];
    }
    if (!empty($path[$count - 1])) {
      $title_for_layout = Inflector::humanize($path[$count - 1]);
    }
    $this->set(compact('page', 'subpage', 'title_for_layout'));

    if (method_exists($this, $page)) {
      $this->$page();
    }

    try {
      $this->render(implode('/', $path));
    } catch (MissingViewException $e) {
      if (Configure::read('debug')) {
        throw $e;
      }
      throw new NotFoundException();
    }
  }

  public function home() {
    // For stats
    $this->loadModel('Root');
    $this->loadModel('Lexeme');
    $this->loadModel('Wordform');
    $this->loadModel('Message');
    $this->set('stats', array(
      'lexemes' => $this->Lexeme->find('count', array('conditions'=>array('pending'=>array('$ne'=>true)))),
      'roots' => $this->Root->find('count'),
      'wordforms' =>  $this->Wordform->find('count'),
    ));
    $this->set(
      'news',
      $this->Message->find('all', array(
        'conditions'=> array('type'=>'news'),
        'order'=> array('created'=>'DESC'),
        'limit'=> 3,
      ))
    );
  }

  public function news() {
    $this->loadModel('Message');
    $this->set(
      'news',
      $this->Message->find('all', array(
        'conditions'=> array('type'=>'news'),
        'order'=> array('created'=>'DESC'),
      ))
    );
  }

}
