<?php
// Get the GranbyRotary class
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOADNAME"));
ErrorClass::setDevelopment(true);
$S = new $_site->className($_site);

$h->title = "WebStats";
$h->banner = "<h1>Rotary Web Stats</h1>";
$h->css =<<<EOF
  <style>
div {
  width: 50rem;
  margin: auto;
}
td, th {
  padding: 0 1rem;
}
  </style>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);

$T = new dbTables($S);
$query = "select concat(FName, ' ', LName) as Name, office, visittime, visits from rotarymembers ".
         "where status='active' and otherclub='granby' ".
         "order by LName";

list($tbl) = $T->maketable($query, array('attr'=>array('id'=>'members','border'=>"1")));

$query = "select FName from rotarymembers where status='active' and otherclub='granby'";

$n = $S->query($query);

echo <<<EOF
$top
<div>
<h2>Active Members: $n</h2>
$tbl
</div>
<br>
$footer
EOF;

