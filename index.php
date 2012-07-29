<?php
  // include parsing lib
  include('lib/simple_html_dom.php');

  $company = 'wwe';

  // checking for the company name
  if(isset($_REQUEST['cp'])) {
    if($_REQUEST['cp'] === 'tna'){
      $company = $_REQUEST['cp'];
    }
  } 

  // checking total news to fetch
  if(isset($_REQUEST['ttl'])) {
    if(!is_nan($_REQUEST['ttl'])){
      $totalItems = $_REQUEST['ttl'];
    }  
  } else {
    $totalItems = 10;
  }

  $company = file_get_html('http://www.nodq.com/' . $company);
  // yes this is the where the news live
  $companyNews = $company->find('td[width=388] li a');
  $companyLinks = array();
  $companyHeaders = array();
  $j;

  // fetching the last 100 headers and links
  foreach($companyNews as $entry) {
    array_push($companyHeaders, $entry->plaintext);
    array_push($companyLinks, $entry->href);
  }
?>

<!DOCTYPE html> 
  <html>

  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <title>rasslin' news</title>
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
        $('center').remove();
        $('font').remove();
        $('span[class^=st_]').remove();
        content.find(' > li').remove();
        content.find(' > br').remove();
        content.find(' > b').remove();
        $('i a').parent().remove();
        $('b a').parent().remove();
        $('form').remove();
      }
    });
  </script>
  <!-- moved after tag removals to have a cleaner dom to work with -->
  <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
</head> 
<body> 

<?php for($i = 0; $i < $totalItems; $i++) {  ?>
    <article data-role="page" id="entry-<?php echo $i; ?>" class="news-entry" data-theme="a">
      <header data-role="header">
        <h1><a href="#entry-0"><img src="http://nodq.com/images/nodq2012.gif" alt="NODQ.com" /></a></h1>
      </header>
      <section data-role="content">
        <h2 style="margin-bottom: -2em;"><?php echo $companyHeaders[$i]; ?></h2>
        <?php
            // individual scrapping
            $singleEntry = file_get_html($companyLinks[$i]);
            $content = $singleEntry->find('td[width=388]');
            echo '<p class="news-content">';
              echo $content[0];
            echo '</p>';
        ?>
        </li><!-- a lot of crazy stuff going on -->
          <?php $j = $i; ?>
          <ul data-role="listview" data-inset="true">
            <?php if($j != ($totalItems - 1)){ ?>
              <li data-role="list-divider">More news</li>
            <?php } ?>
            <?php for($k = 0; $k < $totalItems; $k++ ) { ?>
              <?php if($j != ($totalItems - 1)) { ?>
                <li><a data-transition="turn" href="#entry-<?php $j++; echo $j; ?>"><?php echo $companyHeaders[$j]; ?></a></li>
              <?php } ?>
            <?php } ?>
          </ul>
      </section>
    </article>
  <?php } ?>

</body>
</html>
