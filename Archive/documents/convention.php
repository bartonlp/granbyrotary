<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;

$h->title = "Rotary International Convention";
$h->banner = "<h1>Rotary International Convention</h1>";

list($top, $footer) = $S->getPageTopBottom($h, "<hr>");

// Replace utf-8 with ISO-8859-1 so the strange little Windows junk look OK

$top = preg_replace("/charset=UTF-8/", "charset=ISO-8859-1", $top);

echo <<<EOF
$top
<p>Dear Rotarians,</p>
<p>The Early Bird deadline for the Rotary International Convention is fast approaching!  If you register before December 15, you
will save \$40 on an individual registration!  The New Orleans convention will be the last Rotary convention in North America for
five years, so plan to join fellow Rotarians in New Orleans May 21 -21, 2011 to:</p>
<ul>
<li>Hear inspirational Rotary speakers
<li>Make new Rotary friends in the House of Friendship
<li>Learn about new Rotary projects from around the world
<li>Explore the world famous French Quarter
<li>Have a Hurricane at Pat O’Brien’s or coffee and beignets at Café du Monde
<li>Listen to music in the “Birthplace of Jazz”
</ul> 

<p>Take advantage of special opportunities to enjoy the surrounding area which has so much to offer or to help New Orleans
Rotarians as they continue to rebuild the city after Hurricane Katrina.</p> 

<p>Let’s help President Ray and Judie celebrate a great year of “Building Communities and Bridging Continents” as we
<b>Let the Good Times Roll… Again!</b></p> 

<p>This will be a special convention, so don’t miss it!  For more information or to register, go to
<a href="http://www.rotary.org/en/Members/Events/Convention/Pages/ridefault.aspx">
http://www.rotary.org/en/Members/Events/Convention/Pages/ridefault.aspx</a></p>
$footer
EOF;
?>