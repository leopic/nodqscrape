<?php
// include parsing lib
include('lib/simple_html_dom.php');

// WWE page parsing
// TODO: take company feed from URL
$wwe = file_get_html('http://www.nodq.com/wwe');
// yes this is the where the news live
$wweNews = $wwe->find('td[width=388] li a');
$wweLinks = array();
$wweHeaders = array();
// TODO: take this as a param from the URL
$totalItems = 5;
$j = 0;

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
  <!-- TODO: smart page title -->
  <title>WWE News</title> 
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
  <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var fbcrap = $('#fb-root');

      // TODO, cache this somehow?
      if(fbcrap.length) {
        fbcrap.remove();
        $('center').remove();
        $('font').remove();
        $('span[class^=st_]').remove();
        $('section[data-role] > li').remove();
        $('section[data-role] > br').remove();
        $('section[data-role] > b').remove();
        $('i a').parent().remove();
        $('b a').parent().remove();
        $('br + br + br').remove();
        $('form').remove();
      }
    });
  </script>
  <!-- moved after tag removals to have a cleaner html to work with -->
  <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
</head> 
<body> 

<?php
  for($i = 0; $i < $totalItems; $i++) { $j = $i; $j++; ?>
    <!-- TODO: add prev/next buttons, icons? homepage? company toggler? -->
    <article data-role="page" id="entry-<?php echo $i; ?>" class="news-entry" data-theme="a">
      <header data-role="header">
        <h1>NODQ.com - WWE News</h1>
      </header>
      <section data-role="content">
        <h2 style="margin-bottom: 0;"><?php echo $wweHeaders[$i]; ?></h2>
        <?php
            // individual scrapping
            $singleEntry = file_get_html($wweLinks[$i]);
            $content = $singleEntry->find('td[width=388]');
            echo '<p class="news-content">';
              echo $content[0];
            echo '</p>'; ?>
        </li><!-- a lot of crazy stuff going on -->
          <?php $j = 0; ?>
          <ul data-role="listview" data-inset="true">
            <li><a data-transition="turn" href="#entry-<?php echo $j; ?>"><?php echo $wweHeaders[$j]; ?></a></li>
            <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $wweHeaders[$j]; ?></a></li>
            <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $wweHeaders[$j]; ?></a></li>
            <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $wweHeaders[$j]; ?></a></li>
            <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $wweHeaders[$j]; ?></a></li>
          </ul>
      </section>
    </article>
  <?php } ?>

</body>
</html>
