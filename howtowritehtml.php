<?php
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOAD"). "/siteload.php");
$S = new $_site->className($_site);

$h->title = "How to write HTML";
$h->banner = "<h1>Tutorial: How to write HTML</h1>";

list($top, $footer) = $S->getPageTopBottom($h, "<hr>");

echo $top;
// Use the howtowritehtml.php from my home site, bartonphillips.com.
// This php will either use an already instantiated SiteClass ($S) or if one does not already exist
// it uses Blp.
//include(TOP ."/www/howtowritehtml.php");
include("../bartonphillips/howtowritehtml.php");
echo $footer;
