<?php
require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;
$h->title = "Old News Letters";
$h->banner = "<h2>Old News Letters</h2>";
list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
<p>These are the News Letters from 2009. After 2009 we stopped sending out news letters and instead the <a href="news.php">
News Page</a> has all of the current information.</p>

<h2><a href='newsletters/aug7-2009.php'>August 7 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/july15-2009.php'>July 15 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/july9-2009.php'>July 9 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/jun14-2009.php'>June 14 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/jun8-2009.php'>June 8 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/may19-2009.php'>May 19 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/may5-2009.php'>May 5 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/apr4-2009.php'>April 4 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/mar26-2009.php'>March 26 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/mar16-2009.php'>March 16 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/mar2009.php'>March 2 Granby Rotary News
   Letter</a></h2>

<h2><a href='newsletters/feb2009.php'>February Granby Rotary
   News Letter</a></h2>

<h2><a href='newsletters/jan2009.php'>Janruary Granby Rotary
   News Letter</a></h2>


<!-- End Body items -->

<hr/>
$footer
EOF;
?>
