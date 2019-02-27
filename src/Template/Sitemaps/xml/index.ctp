<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<!-- Static pages -->
<url>
  <loc><?php echo Router::url('/',true); ?></loc>
  <priority>1.0</priority>
</url>
<url>
  <loc><?php echo Router::url('/pages/news',true); ?></loc>
  <priority>0.8</priority>
  <changefreq>monthly</changefreq>
</url>
<url>
  <loc><?php echo Router::url('/sources',true); ?></loc>
  <priority>0.4</priority>
</url>

<!-- Lexemes -->
<url>
  <loc><?php echo Router::url('/lexemes/search',true); ?></loc>
  <priority>1.0</priority>
</url>
<?php foreach ($lexemes as $item):?>
<url>
  <loc><?php echo Router::url(array('controller' => 'Lexemes','action' => 'view',$item->lexeme['_id']),true); ?></loc>
  <?php if (@$item->lexeme['modified']): ?>
  <lastmod><?php echo date('c',$item->lexeme['modified']->sec); ?></lastmod>
  <?php endif ?>
  <priority>0.8</priority>
</url>
<?php endforeach; ?>

<!-- Roots -->
<url>
  <loc><?php echo Router::url('/roots',true); ?></loc>
  <priority>1.0</priority>
</url>
<?php foreach ($roots as $item):?>
<url>
  <loc><?php echo Router::url(array('controller' => 'Roots','action' => 'view',$item->root['radicals'],@$item->root['variant']),true); ?></loc>
  <?php if (@$item->root['modified']): ?>
  <lastmod><?php echo date('c',$item->root['modified']->sec); ?></lastmod>
  <?php endif ?>
  <priority>0.6</priority>
</url>
<?php endforeach; ?>

</urlset>
