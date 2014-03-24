<?php
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");


$S = new GranbyRotary;

// Get the makeup pages

$n = $S->query("select * from makeupinfo where makeupdate > '2011-07-01' order by makeupdate desc");
if($n) {
  while($row = $S->fetchrow()) {
    extract($row, EXTR_PREFIX_ALL, "makeup");

    $pastmeetings .= <<<EOF
<h2>Wednesday $makeup_makeupdate Meeting</h2>
$makeup_message
<hr>

EOF;
  }
}


$h->title = "Past Meetings Page";
$h->banner = "<h1>Past Meetings Summaries</h1>";

$top = $S->getPageTop($h);
$footer = $S->getFooter();

echo <<<EOF
$top
$pastmeetings
$footer
EOF;
?>
