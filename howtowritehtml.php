<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;
$h->title = "How to write HTML";
$h->banner = "<h1>Tutorial: How to write HTML</h1>";
$top = $S->getPageTop($h);
$footer = $S->getFooter("<hr/>");
echo $top;
// Use the howtowritehtml.php from my home site, bartonphillips.com.
// This php will either use an already instantiated SiteClass ($S) or if one does not already exist
// it uses Blp.
include(TOP ."/www/howtowritehtml.php");
echo $footer;
?>
   