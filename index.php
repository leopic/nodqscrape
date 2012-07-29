<?php
// include parsing lib
include('lib/simple_html_dom.php');

// WWE page parsing
$wwe = file_get_html('http://www.nodq.com/wwe');
// yes this is the where the news live
$wweNews = $wwe->find('td[width=388] li a');
$wweLinks = array();
$wweHeaders = array();
$totalItems;
$j = 1;

// fetching the last 100 headers and links
foreach($wweNews as $entry) {
  array_push($wweHeaders, $entry->plaintext);
  array_push($wweLinks, $entry->href);
}
?>

<!DOCTYPE html> 
  <html>

  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <title>Multi-page template</title> 
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
  <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var fbcrap = $('#fb-root');

      if(fbcrap.length) {
        fbcrap.remove();
        $('center').remove();
        $('font').remove();
        $('span[class^=st_]').remove();
        $('div[data-role] > li').remove();
        $('div[data-role] > br').remove();
        $('div[data-role] > b').remove();
        $('i a').parent().remove();
        $('b a').parent().remove();
      }
    });
  </script>
  <!-- moved after tag removals to have a cleaner html to work with -->
  <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
</head> 
<body> 

<?php
if(count($wweHeaders) == count($wweLinks)) {
  $totalItems = count($wweHeaders); }
  // only if there are as many headers as links
  for($i = 0; $i < 10; $i++) { ?>

<div data-role="page" id="entry-<?php echo $i; ?>">
  <div data-role="header">
    <h1><?php echo $wweHeaders[$i]; ?></h1>
  </div>

  <div data-role="content" >
    <p><a href="#entry-<?php echo $j; $j++; ?>" data-role="button"><?php echo $wweHeaders[$j]; ?></a></p>
    <?php
        // individual scrapping
        $singleEntry = file_get_html($wweLinks[$i]);
        $content = $singleEntry->find('td[width=388]');
        echo '<p class="news-entry">';
          echo $content[0];
        echo '</p>'; ?>
  </div>
</div>

<?php } ?>

</body>
</html>
