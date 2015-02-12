<?php
require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;

// We make this page from the articles database table

$page = "";
$hdr = "";

$S->query("select * from articles where expired < now() order by created desc");

while($row = $S->fetchrow(s)) {
  extract($row);
  switch($articleInclude ) {
    case "article":
      $story = $article;
      break;
    case "rss":
      $story = $rssfeed;
      break;
    case "both":
      $story = "$rssfeed\n$article";
      break;
  }

  $page .= <<<EOF
<div>
$story 
</div>
<p style="color: brown; font-size: 10px; font-style: italic">Creation date: $created</p>
<hr>

EOF;

  $hdr .= "$header"; // $header is from table
}

ob_start();
eval('?>' . $page . '<?');
$data = ob_get_clean();

$h->extra = <<<EOF
   <style type="text/css">
blockquote {
        background-color: #FEF0C9;
        padding: 2px 20px;
}
   </style>
$hdr

EOF;

$h->title = "Old News";
$h->banner = "<h2>Old News</h2>";
$h->nohasjs = true;

list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
<!-- DISCLAIMER!!! -->
<div id='disclaimer'
  style='background-color: yellow; width: 30%; border: 1px solid black;
     padding: 10px; margin: 0 auto;'>
   <p>Please note that links on this page may be out of date and may
      not work. While I try to keep the links active, as this is
      old, I may have let some slip. If you encounter broken links I'm
      sorry.</p>
</div>
<hr>
$data
$footer
EOF;
?>
