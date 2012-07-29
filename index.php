<?php
// include parsing lib
include('lib/simple_html_dom.php');

// WWE page parsing
$wwe = file_get_html('http://www.nodq.com/wwe');
// yes this is the where the news live
$wweNews = $wwe->find('td[width=388] li a');
$wweLinks = array();
$wweHeaders = array();

// fetching the last 100 headers and links
foreach($wweNews as $entry) {
  array_push($wweHeaders, $entry->plaintext);
  array_push($wweLinks, $entry->href);
}

// individual scrapping
$singleEntry = file_get_html($wweLinks[0]);
// html4? xhtml? semantics? who gives a shit!
$content = $singleEntry->find('td[width=388]');
echo '<article class="news-entry">';
  echo $content[0];
echo '</article>';
