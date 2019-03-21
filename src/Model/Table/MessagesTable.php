<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MessagesTable extends Table {

  // get all web content for given language as associative array
  public function webContent($language) {
    $data = yaml_parse_file(ROOT . '/data/web.yaml');
    $list = [];
    foreach ($data as $item) {
      $list[$item['key']] = $item[$language];
    }
    return $list;
  }

  // get all news items, newest first
  public function getNews() {
    $news = yaml_parse_file(ROOT . '/data/news.yaml');
    usort($news, function($a, $b) {
      return $a['date'] < $b['date'];
    });
    return $news;
  }

}
