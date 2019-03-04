<?php
namespace App\Controller\Component;

class SearchQuery {
  var $query;            // query, post-processed
  var $raw_query;        // query as input by user

  var $regex_search;     // use regular expressions?
  var $search_lemma;     // should we search in lemma?
  var $search_wordforms; // should we search in word forms?
  var $search_gloss;     // should we search in English gloss?

  var $pos;              // specify a POS
  var $root_type;        // specify a root type/class
  var $source;           // specify a source

  var $page;             // Page offset for paging (starting from 1)
  var $results;          // Total number of results for query (or null if unknown)

  var $json;             // Query encoded as JSON, using in async loading
}
