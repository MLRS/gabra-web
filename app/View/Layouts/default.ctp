<?php
$title = $this->fetch('title');
if ($this->request->here != '/') {
  if ($title)
    $title .= " · Ġabra";
  else
    $title  = "Ġabra";
}
?>
<!DOCTYPE html>
<html lang="<?php echo ($language=='eng')?'en':'mt' ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <?php

    // Open graph, for Facebook
    echo $this->Html->meta(array('name' => 'og:title', 'content' => $title));
    echo $this->Html->meta(array('name' => 'og:type', 'content' => 'website'));
    echo $this->Html->meta(array('name' => 'og:image', 'content' => ''));
    echo $this->Html->meta(array('name' => 'og:url', 'content' => FULL_BASE_URL . $_SERVER['REQUEST_URI']));

    // Other meta
    echo $this->fetch('meta');
    echo $this->Html->meta(
      'favicon.ico',
      '/img/favicon-bold.ico',
      array('type' => 'icon')
    );

    ?>

    <script type="text/javascript">
      Gabra = {
        user : <?php echo @$common['user'] ? json_encode($common['user']) : 'null' ?>,
        base_url : "<?php echo $this->Html->url('/') ?>",
        api_url : "<?php echo API_URL ?>",
        minsel_url : "<?php echo MINSEL_URL ?>",
        corpus_url : "<?php echo CORPUS_URL ?>",
        i18n : {
          // key: string or array of nested keys
          // replacements: string or array, in order
          localise : function(key, replacements) {
            // Load val from key
            if (key instanceof Array) {
              // nothing
            } else if (typeof key === "string") {
              key = key.split('.');
            }
            var val = Gabra.i18n;
            for (let x in key) {
              if (val.hasOwnProperty(key[x])) {
                val = val[key[x]];
              } else {
                val = key[x];
                break;
              }
            }

            // Handle replacements
            if (replacements) {
              if (typeof replacements === "string") {
                replacements = [replacements];
              }
              else if (typeof replacements === "number") {
                replacements = [replacements.toString()];
              }
              for (let x in replacements) {
                val = val.replace('%s', replacements[x]);
              }
            }

            return val;
          },
          updates: "<?php echo h(__("Updates")); ?>",
          eg: "<?php echo h(__("e.g.")); ?>",
          x_more: "<?php echo h(__("%s more matches")); ?>",
          feedback_dialog_title: "<?php echo h(__("What is wrong with this entry?")); ?>",
          marked_as_incorrect: "<?php echo h(__("This item has been marked as incorrect")); ?>",
          merge: {
            no_selection: "<?php echo h(__("You must select at least one entry to merge")); ?>",
            confirm: "<?php echo h(__("This will ONLY merge glosses into this entry. Continue?")); ?>",
          },
          delete_confirm: "<?php echo h(__('Are you sure you want to delete this entry?')); ?>",
          did_you_mean: "<?php echo h(__('Did you mean:')); ?>",
          suggest: {
            submit: "<?php echo h(__('Submit')); ?>",
            cancel: "<?php echo h(__('Cancel')); ?>",
            link: "<?php echo h(__('Click here to suggest that %s is added to the database.')); ?>",
            dialog_title: "<?php echo h(__("Suggest a new entry")); ?>",
            lemma: "<?php echo h(__("Lemma")); ?>",
            lemma_help: "<?php echo h(__("Word in Maltese")); ?>",
            gloss: "<?php echo h(__("Gloss")); ?>",
            gloss_help: "<?php echo h(__("Translation in English")); ?>",
            pos: "<?php echo h(__("Part of speech")); ?>",
            pos_help: "<?php echo h(__("Noun, verb, adjective or other")); ?>",
            added: "<?php echo h(__("Your suggestion has been added.")); ?>",
          },
          etymology: {
             occurs_in: "<?php echo h(__("Occurs in %s languages, grouped into %s senses")); ?>",
             more_link: "<?php echo h(__("See the full etymological information at <a href=\"%s\" target=\"_blank\">Minsel <span class=\"glyphicon glyphicon-new-window\"></span></a>")); ?>",
          },
          error_occurred: "<?php echo h(__("An error occurred")); ?>",
          pos: <?php echo json_encode($common['parts_of_speech']) ?>,
          root_types: <?php echo json_encode($common['root_types']) ?>,
          filter: {
            any: "<?php echo h(__("Any")); ?>",
            empty: "<?php echo h(__("Empty")); ?>",
          },
        },
        page: 0, // "current page" used in AJAX paging
      };
    </script>

    <?php if (defined('PRODUCTION_MODE')): ?>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                             m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-34654961-2', 'um.edu.mt');
    ga('send', 'pageview');
    </script>
    <?php endif; ?>

    <?php
    if (defined('USE_CDN') && USE_CDN===true) {
      echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');
      echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css');
    } else {
      echo $this->Html->css('/bootstrap-3.2.0-dist/css/bootstrap.min.css');
      echo $this->Html->css('/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css');
    }

    echo $this->Minify->css(array('gabra'));
    echo $this->fetch('css');

    if (defined('USE_CDN') && USE_CDN===true) {
      echo $this->Html->script('//code.jquery.com/jquery-1.11.3.min.js');
      echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array('defer' => true));
    } else {
      echo $this->Html->script('jquery-1.11.0.min.js');
      echo $this->Html->script('/bootstrap-3.2.0-dist/js/bootstrap.min.js', array('defer' => true));
    }
    echo $this->Html->script('/js/bootbox.min.js', array('defer' => true));
    ?>

  </head>
  <body role="document">

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"><?php echo __('Toggle navigation') ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php echo $this->Html->link("Ġabra", '/', array('class'=>'navbar-brand red')) ?>
          <?php echo $this->element('navbar-search') ?>
        </div><!-- .navbar-header -->

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li <?php if ($this->params['controller'] == 'lexemes' && $this->params['action'] == 'search'): ?>class="active"<?php endif ?>>
                <?php echo $this->Html->link(__("Advanced search"), '/lexemes/search') ?>
            </li>
            <li <?php if ($this->params['controller'] == 'roots'): ?>class="active"<?php endif ?>>
              <?php echo $this->Html->link(__("Root search"), '/roots') ?>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('More') ?> <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><?php echo $this->Html->link(__('Sources'), '/sources') ?></li>
                <li><?php echo $this->Html->link(__('Random entry'), '/lexemes/random') ?></li>
                <li><?php echo $this->Html->link(__('API').' & '.__('Download'), API_URL); ?></li>

                <?php if (!@$common['user']): ?>
                <li class="divider"></li>
                <li><?php echo $this->Html->link(__('Login'), '/users/login'); ?></li>
                <?php endif ?>
              </ul>
            </li>

            <!-- User menu -->
            <?php if ($user = @$common['user']): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo $this->UI->icon('user'); ?>
                <?php echo h($user['username']); ?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><?php echo $this->Html->link(__("Pending"), '/lexemes/pending'); ?></li>
                <li><?php echo $this->Html->link(__("Flagged"), '/lexemes/flagged'); ?></li>
                <li><?php echo $this->Html->link(__("Duplicates"), '/lexemes/duplicates'); ?></li>
                <li class="divider"></li>
                <li><?php echo $this->Html->link($this->UI->icon('off').' '.__('Logout'), '/users/logout', array('escape'=>false)); ?></li>
              </ul>
            </li>
            <?php endif ?>

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="language-switcher">
            <?php
              if ($language=='eng') {
                echo $this->Html->link(
                  __("bil-Malti"),
                  array_merge($this->params->pass, array('?'=>array_merge($_GET, array('lang'=>'mlt'))))
                );
              } else {
                echo $this->Html->link(
                  __("in English"),
                  array_merge($this->params->pass, array('?'=>array_merge($_GET, array('lang'=>'eng'))))
                );
              }
            ?>
            </li>
          </ul>
        </div><!-- .navbar-collapse -->
      </div><!-- .container -->
    </div><!-- navbar -->
    <!-- End navbar -->

    <!-- Main -->
    <div class="container" role="main">
      <?php
        foreach (array('flash'=>'warning','good'=>'success','bad'=>'danger','info'=>'info') as $k=>$v) {
          $close = '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
          echo $this->Session->flash(
            $k,
            array(
              'element' => 'flash', // View/Elements/flash.ctp
              'params' => array('class'=>$v)
            )
          );
        }
      ?>
      <?php echo $this->fetch('content'); ?>
    </div>
    <!-- end main -->

    <?php
      echo $this->Minify->script(array('common', 'ui', 'async', 'filter', 'feedback', 'cursor', 'edit'), array('defer' => true));
      echo $this->fetch('script');
      echo $this->Js->writeBuffer();
    ?>
  </body>
</html>
