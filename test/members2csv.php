<?php
// Create a csv file from the rotarymembers table
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$S->query("select * from rotarymembers where status='active'");

$fp = fopen('/home/barton11/pub/file.csv', 'w');

while($row = $S->fetchrow('assoc')) {
  fputcsv($fp, $row);
}
?>
    