<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

  private $alphabet = array(
    "a", "b", "ċ", "d", "e", "f", "ġ", "g", "għ", "h", "ħ", "i", "ie", "j", "k",
    "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "ż", "z");

  public function mt_compare($a, $b) {
    $a0 = mb_substr($a,0,1);
    $b0 = mb_substr($b,0,1);
    if ($a0 == $b0) {
      $as = substr($a, 1);
      $bs = substr($b, 1);
      if (!$as) return -1;
      if (!$bs) return 1;
      return $this->mt_compare($as, $bs);
    }
    $flip = array_flip($this->alphabet);
    $x = @$flip[$a0]; // $x = (int) array_search($a0, $this->alphabet);
    $y = @$flip[$b0]; // $y = (int) array_search($b0, $this->alphabet);
    if (!$x) $x = $a;
    if (!$y) $y = $b;
    if ($x < $y) return -1;
    else return 1;
  }

}
