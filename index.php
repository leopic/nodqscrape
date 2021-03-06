<?php
  // include parsing lib
  include('lib/simple_html_dom.php');

  // checking for the company name
  if(isset($_REQUEST['cp']) && $_REQUEST['cp'] === 'tna') {
    $companyName = $_REQUEST['cp'];
  } else {
    $companyName = 'wwe';
  } 

  // checking total news to fetch
  if(isset($_REQUEST['ttl']) && !is_nan($_REQUEST['ttl']) && $_REQUEST['ttl'] < 101) {
    $totalItems = $_REQUEST['ttl'];
  } else {
    $totalItems = 10;
  }

  $company = file_get_html('http://www.nodq.com/' . $companyName);
  // getting all the links to individual pieces of news
  $companyNews = $company->find('td[width=388] li a');
  $companyLinks = array();
  $companyHeaders = array();
  $j;

  // fetching the last 100 headers and links
  foreach($companyNews as $entry) {
    array_push($companyHeaders, $entry->plaintext);
    array_push($companyLinks, $entry->href);
    if(count($companyLinks) == $totalItems) {
      break;
    }
  }
?>

<!DOCTYPE html> 
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title><?php echo strtoupper($companyName); ?> News</title>
    <link rel="shortcut icon" href="http://nodq.com/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/gif" href="http://nodq.com/favicon.gif">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        var fbcrap = $('#fb-root'),
            content = $('section[data-role]');

        // TODO, cache this somehow?
        if(fbcrap.length) {
          fbcrap.remove();
          $('center, font, form').remove();
          $('span[class^=st_]').remove();
          content.find(' > li').remove();
          content.find(' > br').remove();
          content.find(' > b').remove();
          $('i a, b a').parent().remove();
        }
      });
    </script>
    <style type="text/css">
      .news-header { margin-bottom: -2em; }
      ul.ui-listview.news-list { margin-top: -7em; }
      .logo-img { max-width: 100%; }
    </style>
  </head> 
  <body> 

  <?php for($i = 0; $i < $totalItems; $i++) {  ?>
    <article data-role="page" id="entry-<?php echo $i; ?>" class="news-entry" data-theme="a">
      <header data-role="header">
        <h1><a href="#entry-0"><img src="img/logo.gif" alt="NODQ.com" class="logo-img" /></a></h1>
      </header>

      <section data-role="content">
        <?php if($i != 0) { ?>
          <a data-rel="back" data-role="button" data-icon="back">Back</a>
        <?php } ?>

        <h2 class="news-header"><?php echo $companyHeaders[$i]; ?></h2>
        <?php
          // individual scrapping
          $singleEntry = file_get_html($companyLinks[$i]);
          $content = $singleEntry->find('td[width=388]');
          echo '<p class="news-content">';
          echo $content[0];
          echo '</p>';
        ?>

        </li><?php // a lot of crazy stuff going on ?>
        <?php $j = $i; ?>
        <?php if($j != ($totalItems - 1)){ ?>
        <ul data-role="listview" data-inset="true" class="news-list">
          <li data-role="list-divider">More <?php echo strtoupper($companyName); ?> news</li>
          <?php for($k = 0; $k < $totalItems; $k++ ) { ?>
          <?php if($j != ($totalItems - 1)) { ?>
          <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $companyHeaders[$j]; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </section>

    </article>
  <?php } ?>

  <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
</body>
</html>
