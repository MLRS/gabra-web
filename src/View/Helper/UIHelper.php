<?php
namespace App\View\Helper;

use Cake\Core\App;
use Cake\View\Helper;

class UIHelper extends Helper {

  public $helpers = [
    'Html',
    'Tanuck/Markdown.Markdown'
  ];

  public function content($key, $replacements=null, $options=array()) {
    $opts = array_merge(array(
      'markdown' => false,
    ), $options);

    $raw = @$this->_View->viewVars['content'][$key];
    if (empty($raw)) return '';
    if (is_array($replacements)) {
      $raw = str_replace(array_keys($replacements), array_values($replacements), $raw);
    }
    if ($opts['markdown'])
      return $this->Markdown->transform($raw);
    else
      return $raw;
  }

  public function knownFields() {
    return array(
      'surface_form' => __('Surface form'),
      'gender' => __('Gender'),
      'number' => __('Number'),
      'phonetic' => __('Phonetic'),
      'pattern' => __('Pattern'),
      'derived_form' => __('Derived form'),
      'form' => __('Form'),
      'aspect' => __('Aspect'),
      'subject' => __('Subject'),
      // 'do' => __('Direct Object'),
      // 'io' => __('Indirect Object'),
      'dir_obj' => __('Direct Object'),
      'ind_obj' => __('Indirect Object'),
      'polarity' => __('Polarity'),
    );
  }
  public function knownValues() {
    return array(
      'm' => __('Masc'),
      'f' => __('Fem'),
      'mf'=>__('Masc/Fem'),
      'sg' => __('Singular'),
      'dl' => __('Dual'),
      'pl' => __('Plural'),
      'sp' => __('Singular/Plural'),
      'sgv' => __('Singulative'),
      'coll' => __('Collective'),
      'perf' => __('Perfective'),
      'impf' => __('Imperfective'),
      'imp' => __('Imperative'),
      'pastpart' => __('Past Participle'),
      'prespart' => __('Present Participle'),
      'pos' => __('Positive'),
      'neg' => __('Negative'),
      'diminutive' => __('Diminutive'),
      'comparative' => __('Comparative'),
      'superlative' => __('Superlative'),
    );
  }

  public function icon($name, $text=null, $options=array()) {
    $opts = array_merge(array(
      'title' => null,
      'sep' => ' ',
    ), $options);
    $icon = $this->Html->tag('span', '', array('title'=>$opts['title'], 'class'=>"glyphicon glyphicon-$name"));
    if ($text)
      return $icon.$opts['sep'].$text;
    else
      return $icon;
  }

  public function date($date, $options=array()) {
    $opts = array_merge(array(
      'format' => 'Y-m-d H:i O',
    ), $options);
    if (is_a($date, 'DateTime')) {
      return $date->format($opts['format']);
    } else {
      return date($opts['format'],$date->sec);
    }
  }

  public function corpusLink($query) {
    $base = CORPUS_URL;
    $urls = array(
      // Word lookup
      'lookup'      => "{$base}redirect.php?lookupString=".urlencode($query)."&lookupType=begin&lookupShowWithTags=1&pp=50&redirect=lookup&uT=y",
      // Concordance
      'concordance' => "{$base}concordance.php?theData=".urlencode($query)."&qmode=sq_nocase&pp=50&del=begin&del=end&uT=y",
      // Frequency (begins with)
      'frequency'   => "{$base}freqlist.php?flTable=__entire_corpus&flAtt=word&flFilterType=begin&flFilterString=".urlencode($query)."&pp=50&flOrder=desc&uT=y",
    );
    // $out = $this->icon('new-window').' '.__('MLRS corpus').':';
    // $out .= ' '.$this->Html->link(
    //   __('word lookup'),
    //   $urls['lookup'],
    //   array('target' => '_blank', 'escape'=>false)
    // );
    // $out .= ' / '.$this->Html->link(
    //   __('concordance'),
    //   $urls['concordance'],
    //   array('target' => '_blank', 'escape'=>false)
    // );
    // $out .= ' / '.$this->Html->link(
    //   __('frequency'),
    //   $urls['frequency'],
    //   array('target' => '_blank', 'escape'=>false)
    // );
    $out = $this->Html->link(
      $this->icon('new-window').' '.__('Look up in corpus'),
      $urls['lookup'],
      array('target' => '_blank', 'escape' => false)
    );
    return $out;
  }

  // Check if fields exist, and if so display them
  // public function maybeFields($item, $fields, $opts=array()) {
  //   $opts = array_merge(array('field_tag'=>'dt','value_tag'=>'dd','between'=>'<br/>'), $opts);
  //   $out = '';
  //   foreach ($fields as $field) {
  //     if (@$item[$field]) {
  //       $out .= "<{$opts['field_tag']}>".h($field)."</{$opts['field_tag']}>";
  //       $out .= "<{$opts['value_tag']}>".h($item[$field])."</{$opts['value_tag']}>";
  //       $out .= $opts['between'];
  //     }
  //   }
  //   return $out;
  // }

  // Check if field exists, and if so display it
  public function maybeField($item, $field, $formatstring="%s") {
    $out = '';
    if (@$item[$field]) {
      $out .= sprintf($formatstring, h($item[$field]));
    }
    return $out;
  }

  // Check if a boolean field exists and is true, and if so display its name
  public function booleanField($item, $field, $iftrue=null) {
    $out = '';
    if (@$item[$field]) {
      $out .= (is_null($iftrue)) ? h($field) : $iftrue ;
    }
    return $out;
  }

  // Root
  public function root($root, $options=array()) {
    $opts = array_merge(array(
        'include_link' => true,
        'include_variant' => true,
      ), $options);
    $r = is_a($root,'stdClass') ? (array) $root : $root;
    if (!@$r) return '';
    $display = h($r['radicals']);
    if ($opts['include_variant'] && @$r['variant']){
      $display .= $this->Html->tag('sup', h($r['variant']));
    }
    // $display = '√'.(strtoupper(str_replace('-','',$r['radicals'])));
    if ($opts['include_link']) {
       return $this->Html->link($display, array(
         'controller'=>'roots',
         'action'=>'view',
         $r['radicals'], @$r['variant'],
       ), array('class'=>'root', 'escape'=>false));
    } else {
       return $this->Html->tag('span', $display, array('class'=>'root'));
    }
  }

  // POS tag
  public function posTag($tag, $options=array()) {
    $opts = array_merge(array(
        'capitalise' => false,
      ), $options);
    $pos = $this->_View->viewVars['common']['parts_of_speech']; // is this hackey?
    $s = (array_key_exists($tag, $pos)) ? $pos[$tag] : h($tag);
    return ($opts['capitalise'])? ucfirst($s) : $s;
  }

  // Derived form in Roman numerals
  public function derivedForm($item, $options=array()) {
    $forms = array(
      1 => 'I',
      2 => 'II',
      3 => 'III',
      4 => 'IV',
      5 => 'V',
      6 => 'VI',
      7 => 'VII',
      8 => 'VIII',
      9 => 'IX',
      10 => 'X'
    );
    if (isset($item['derived_form']) && array_key_exists((int)$item['derived_form'], $forms))
      return $forms[(int)$item['derived_form']];
    else
      return '';
  }

  // gloss
  public function gloss($item, $expanded=false) {
    $repl = $expanded ? '<br/>' : ', ';
    return str_replace(array("\r\n","\n"), $repl, h(@$item['gloss']));
  }

  // A variant of a surface form
  // This should really be renamed to "alternative"
  public function alternatives($s, $options=array()) {
    if (!$s) return '';
    if (is_array($s)) {
      $s = implode($s, ', ');
    }
    // Special case for p-x-p-x / p-x-x ...
    // if (preg_match('/(see|cf\.?)\s*(.+)/', $s, $match)) {
    //   $s = 'cf. ' . $this->Html->link(
    //     $match[2],
    //     array(
    //       'controller' => 'roots',
    //       'action' => 'table',
    //       '?' => array(
    //         's' => $match[2]
    //       )
    //     )
    //   );
    // }
    return $this->Html->tag(
      'span',
      '('.h($s).')',
      array_merge(array(), $options)
    );
  }

  // Build a whole table of word forms dynamically, based on the fields available
  public function wordFormTable($wordforms, $options=array()) {
    $opts = array_merge(array(
      'fields' => null,
      'filter_fields' => null,
      'show_generated' => true,
    ), $options);

    // If no specific fields, search for all
    if ($opts['fields']) {
      $cols = $opts['fields'];
    } else {
      $cols = array();
      foreach ($wordforms as $wf) {
        // $keys = array_keys($wf['Wordform']);
        $keys = array();
        foreach ($wf->toArray() as $k=>$v) {
          if ($v) $keys[] = $k;
        }
        // $cols += $keys; // array union
        $cols = array_merge($cols,$keys);
      }
      $cols = array_unique($cols);
      $ignore = array(
        'surface_form', // included manually
        'alternatives', // included manually
        '_id',
        'lexeme_id',
        'sources',
        'modified',
        'created',
        'generated',
        'full',
      );
      $cols = array_diff($cols, $ignore);
    }
    $known_cols = $this->knownFields();
    $known_vals = $this->knownValues();
    ob_start();
?>
  <table class="table table-condensed table-hover">
  <thead>
    <tr>
      <th><?php echo __('Surface form') ?></th>
      <?php foreach ($cols as $col): ?>
      <th><?php echo h(@$known_cols[$col]?$known_cols[$col]:$col) ?></th>
      <?php endforeach; ?>
    </tr>
    <?php if (is_array($opts['filter_fields'])): ?>
    <tr>
      <th>&nbsp;</th>
    <?php foreach ($cols as $col): ?>
    <?php if (in_array($col, $opts['filter_fields'])): ?>
      <th><select class="filter" data-filter="<?php echo $col ?>" /></th>
    <?php else: ?>
      <th>&nbsp;</th>
    <?php endif; ?>
    <?php endforeach; ?>
    </tr>
    <?php endif; ?>
  </thead>
  <tbody>
  <?php foreach ($wordforms as $wordform): ?>
    <tr>
      <td>
        <span class="surface_form <?php if ($opts['show_generated'] && @$wordform['generated']) echo 'generated'; ?>">
          <?php echo h($wordform['surface_form']) ?>
          <?php echo $this->alternatives(@$wordform['alternatives'], array('class'=>'alt')); ?>
        </span>
      </td>
    <?php foreach ($cols as $col): ?>
      <?php $val = @$wordform[$col] ?>
      <td class="text-muted">
        <?php if (is_array($val) || is_object($val)): ?>
        <?php echo $this->agr($val); ?>
        <?php else: ?>
        <?php echo h(@$known_vals[$val]?$known_vals[$val]:$val) ?>
        <?php endif; ?>
      </td>
    <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
<?php
    return ob_get_clean();
  }

  // Agreemnet object
  public function agr($agr) {
    $agr = (array) $agr;
    $known_vals = $this->knownValues();
    $out = '';
    $out .= ucfirst(@$agr['person']).' ';
    $out .= ucfirst(@$agr['number']).' ';
    if (@$agr['gender'] && $agr['gender']!='mf')
      $out .= $known_vals[$agr['gender']];
    return h($out);
  }

  public function highlight($haystack, $needle) {
    return ($needle ? preg_replace("/($needle)/i","<mark>\${1}</mark>",$haystack) : $haystack);
  }

  // List params for a VerbWordForm, with links to vary it
  // public function verbWordFormParams($options, $verbWordForm, $field) {
  //   $s = '';
  //   foreach ($options as $key=>$lin) {
  //     if ($key == $verbWordForm->verbWordForm[$field]) {
  //       $s .= $this->Html->tag('strong', $options[$verbWordForm->verbWordForm[$field]]);
  //     } elseif (!$this->isValidVerbParamCombo($verbWordForm, array($field=>$key))) {
  //       $s .= $lin;
  //     } else {
  //       $s .= $this->Html->link($lin, array(
  //         'action'=>'vary',
  //         $verbWordForm->verbWordForm['id'],
  //       			      '?' => array(
  //       				$field => $key
  //       			      )
  //       ));
  //     }
  //     $s .= " ";
  //   }
  //   return $s;
  // }
  // private function isValidVerbParamCombo($verbWordForm, $new_fields) {
  //   $t = array_merge($verbWordForm['VerbWordForm'], $new_fields);

  //   $tense = $t['tense'];
  //   $sub = substr($t['agr_sub'], 0, 2);
  //   $dir = substr(@$t['agr_dir_obj'], 0, 2);
  //   $ind = substr(@$t['agr_ind_obj'], 0, 2);

  //   // Imperative only makes sense for P2
  //   if ($tense=='imperative' && $sub!='P2')
  //   return false;

  //   // Ind only when dir==none or P3
  //   if ($ind && !(!$dir || $dir=='P3')) {
  //     return false;
  //   }

  //   // Identical combinations of dir/ind
  //   if (in_array($sub, array('P1','P2'))) {
  //     if ($dir==$sub && !$ind) return false;
  //     if ($ind==$sub) return false; // for all cases of dir, including none
  //   }

  //   // Guess we're ok then
  //   return true;
  // }

}
