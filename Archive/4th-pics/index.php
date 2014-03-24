<?php
// GranbyRotary Class 
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;
$h->title = "Fourth of July 2009 Picnic";
$h->banner = "<h1>Fourth of July 2009 Picnic</h1>";
$top = $S->getPageTop($h);
$footer = $S->getFooter();
$ar = array(
"P1010027.JPG",
"P1010028.JPG",
"P1010029.JPG",
"P1010030.JPG",
"P1010031.JPG",
"P1010032.JPG",
"P1010033.JPG",
"P1010034.JPG",
"P1010035.JPG",
"P1010036.JPG",
"P1010037.JPG",
"P1010038.JPG",
"P1010039.JPG",
"P1010040.JPG",
"P1010041.JPG",
"P1010042.JPG",
"P1010043.JPG",
"P1010044.JPG",
"P1010045.JPG",
"P1010046.JPG",
"P1010047.JPG",
"P1010048.JPG",
"P1010049.JPG",
"P1010050.JPG",
"P1010051.JPG",
"P1010052.JPG",
"P1010053.JPG",
"P1010054.JPG",
"P1010055.JPG",
"P1010056.JPG",
"P1010057.JPG",
"P1010058.JPG",
"P1010059.JPG");

$pics = "";
foreach($ar as $pic) {
  $pics .= "<a href='$pic'><img src='resizeimg.php?image=$pic&width=300' alt='$pic' /></a>\n";
}

echo <<<EOF
$top
<div id="pictures">
$pics
</div>
$footer
EOF;
?>


