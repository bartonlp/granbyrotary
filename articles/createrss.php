<?php
// Add the information from the article template to the database
require_once("/var/www/includes/siteautoload.class.php");

$s = new stdClass;
$s->count = false;
$S = new GranbyRotary($s);  // don't count

$S->feedCount();

date_default_timezone_set(GMT);

$lastBuildDate = date(DATE_RSS); // default to todays date

$items =<<<EOF
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
 <channel>
  <title>Granby Rotary Club</title>
  <link>http://www.granbyrotary.org</link>
  <description>Granby Rotary Club News</description>
  <lastBuildDate>$lastBuildDate</lastBuildDate>
  <generator>createrss.php by Barton Phillips</generator>

EOF;

header('Content-Type: application/xml');

ob_start();
include("../upcomingmeetings.php");
$upcoming = ob_get_clean();
//echo "upcoming: $upcoming<br>";

if(preg_match("|<h2>(.*?)</h2>|", $upcoming, $m)) {
  $title = $m[1];
  $upcoming = preg_replace("|$m[0]|", "", $upcoming);
} else {
  $title = "<h2>Didn't find title</h2>";
}

$upcoming = htmlencode($upcoming);
$upcoming = preg_replace("/<ul>/", "<ul style='margin-left: -20px'>", $upcoming);
$upcoming = escapeltgt("<div style='font-size: 9pt'>$upcoming</div>");

$title = escapeltgt($title);
//Sat, 16 May 2009 17:12:15 +0000
$today = date("r");

// Include the upcoming meetings
$items .= <<<EOF
<item>
  <title>$title</title>
  <link>http://www.granbyrotary.org/news.php#upcoming</link>
  <description>$upcoming</description>
  <pubDate>$today</pubDate>
</item>

EOF;
$S->query("select * from rssfeeds where expired > now() order by feedorder, date desc");

while($row = $S->fetchrow('assoc')) {
  extract($row);

  $rsstitle = htmlencode($rsstitle);
  $rsslink  = htmlencode($rsslink);
  $rssdesc = htmlencode($rssdesc);

  //$rssdesc = preg_replace("/\n/sm", " ", $rssdesc);
  
  $items .= <<<EOF
  <item>
    <title>$rsstitle</title>
    <link>$rsslink</link>
    <description>$rssdesc</description>
    <pubDate>$rssdate</pubDate>
  </item>

EOF;
}

$items .= <<<EOF
 </channel>
</rss>

EOF;

echo "$items";

function htmlencode($text) {
   $t = preg_replace("/\"/sm", "&quot;", $text);
   $t = preg_replace("/\&/sm", "&amp;", $text);
   $t = preg_replace("/&lt;/sm", "&amp;lt;", $t);
   $t = preg_replace("/&gt;/sm", "&amp;gt;", $t);
   $t = preg_replace("/<(.*?)>/sm", "&lt;$1&gt;", $t);
   return $t;
}
