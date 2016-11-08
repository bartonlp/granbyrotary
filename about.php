<?php
// About Rotary page
// BLP 2014-07-17 -- removed admin from here and added it to includes/banner.i.php
/*
$_site = require_once("/var/www/includes/siteautoload.class.php");
$S = new $_site['className']($_site);
*/
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOAD"). "/siteload.php");
$S = new $_site->className($_site);

$s->siteclass = $S;
$s->site = "granbyrotary.org";
$s->page = "about.php";
$s->itemname ="Message";

$u = new UpdateSite($s); // Should do this outside of the START comments

// START UpdateSite Message
$item = $u->getItem();
// END UpdateSite Message

// If item is false then no item in table

if($item !== false) {
  $message = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$s->itemname = "YouthPrograms";

// START UpdateSite YouthPrograms
$item = $u->getItem($s);
// END UpdateSite YouthPrograms

// If item is false then no item in table

if($item !== false) {
  $youthPrograms = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$s->itemname = "LocalService";

// START UpdateSite LocalService
$item = $u->getItem($s);
// END UpdateSite LocalService

// If item is false then no item in table

if($item !== false) {
  $localService = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$s->itemname = "International";

// START UpdateSite International
$item = $u->getItem($s);
// END UpdateSite International


// If item is false then no item in table

if($item !== false) {
  $international = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$s->itemname = "Leadership";

// START UpdateSite Leadership
$item = $u->getItem($s);
// END UpdateSite Leadership


// If item is false then no item in table

if($item !== false) {
  $leadership = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$h->extra = <<<EOF
   <!-- rssnews.js has the generic news rotator logic -->
   <script src="js/rssnews.js"></script>

   <script>
jQuery(document).ready(function($) {
  // Only get twenty days of feed not all of it
  $("#news-feed").newsFeed("proxy.rss.php?feed=http://feeds2.feedburner.com/rotary/PBqj", 20);
});
   </script>

   <style type="text/css">
#news-feed {
        position: relative;
        /* this height must match the value in .headline below */
        height: 200px; 
        width: 100%;
        overflow: hidden;
        background-color: pink;
        border-top: 1px solid red;
}
.headline {
        position: absolute;
        /* this hight must match above. */
        height: 200px;
        /* this is height + 2*padding below + anything else you might add! */
        top: 210px;
        overflow: hidden;
        padding: 0 5px;
}
#news-feed h4 {
        margin-top: .5em;
        margin-bottom: .25em;
        font-size: 1em;
}
#news-feed h4 a {
        color: #006;
}
#news-feed .publication-date {
        margin-bottom: 1em;
        font-style: italic;
}
.news-wait {
        position: absolute;
        top: 30%;
        left: 50%;
        margin-left: -10px;
        z-index: 4;
}
.fade-slice {
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #efd;
        z-index: 3;
}
#wrapper {
        /*float: right;*/
        width: 100%;
        border: 1px solid white;
        margin-right: 20px;
}
#head {
        float: left;
}
#motto b {
        font-style: italic;
}
#motto span {
        font-variant: small-caps;
        font-weight: bold;
}
#fourtruths {
        list-style: decimal;
}
#fourtruths b {
        font-variant: small-caps;
        font-style: italic;
}
#object {
        list-style: upper-roman;
}
/* Google Map */
#googlemap {
        border: 1px solid black;
        width: 100%;
}
#dirtd {
        width: 40%;
}
#map_canvas {
        width: 100%;
        height: 600px; 
}
#map_td {
        border: 1px solid black;
}
#officerstbl td {
        padding: 5px;
}
   </style>
EOF;

$h->title = "About Rotary";
$h->banner = "<h2>About The Rotary Club</h2>";
//$h->bodytag = "<body onload='initialize()' onunload='GUnload()'>";

list($top, $footer) = $S->getPageTopBottom($h);

if($S->id != 0) {
  $greet = <<<EOF
<h3 id='loginMsg'>Welcome {$S->getUser()}.</h3>
<hr/>

EOF;
} else {
  $greet =<<<EOF
<h3 id='loginMsg'>If you are a Granby Rotary Member please <a href='login.php?return=$S->self'>Login</a> at this time.<br>
There is a lot more to see if you <a href='login.php?return=$S->self'>Login</a>!
</h3>
<p style='text-align: center'>Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
<a href="login.php?return=$S->self&page=visitor">Register</a></p>
<hr/>

EOF;
}

// ****************  
// Display the page
  
echo <<<EOF
$top
$greet
<!-- Start UpdateSite: Message -->
$message
<!-- UpdateSite: Message End -->

<h2><a href="http://en.wikipedia.org/wiki/Rotary_Club">What is
   Rotary</a>?</h2>

<h3>Rotary is:</h3>

<ul>
   <li>An organization of business and professional
      persons united worldwide who provide humanitarian service, encourage
      high ethical standards in all vocations and help build goodwill
      and peace in the world.</li>

   <li>The world&aphos;s first service club which was
      founded in Chicago in 1905.</li>


   <li><b>27,370</b> Clubs with <b>1,205,932</b> members
      located in <b>152</b> Countries.</li>

   <li><a href="http://en.wikipedia.org/wiki/Rotary_Foundation">The Rotary Foundation</a>, which each year provides
      some \$60 million for international scholarships, cultural exchanges,
      and humanitarian projects large and small that improve the quality
      of life for millions of people.</li>

   <li>PolioPlus, Rotary&apos;s commitment to eradicate
      polio in the world. More than one-half billion
      children in developing nations have been immunized against polio.
      145 countries are now declared polio free with the last case
      in the Americas in 1991 in Peru.</li>

   <li>Planning and carrying out a remarkable variety
      of humanitarian, educational, and cultural exchange programs
      that touch people&apos;s lives in their local communities and our
      world community.</li>

   <li>Membership is by invitation and is based
      upon a vocational classification. Attendance at meetings is a
      basic obligation and a member may make-up a meeting at any Rotary
      Club in the world.</li>
</ul>

<p id='motto'><b>The Rotary Motto is</b> <span>Service Above
   Self</span>.</p>

<hr/>

<h2>The Object of Rotary</h2>

<p>The object of Rotary is to encourage and foster
the ideal of service as a basis of worthy enterprise and, to encourage
and foster&nbsp;.&nbsp;.&nbsp;.</p>

<ul id='object'>
   <li>Development of acquaintance as an opportunity of service&nbsp;.&nbsp;.&nbsp;.</li>

   <li>High ethical standards in business and
      professions; the recognition of the worthiness of all useful occupations;
      and the dignifying by each Rotarian of his or her occupation as
      an opportunity to serve society&nbsp;.&nbsp;.&nbsp;.</li>

   <li>The application of the ideal service by
      every Rotarian to his or her personal, business and community
      life&nbsp;.&nbsp;.&nbsp;.</li>

   <li>Advancement of international understanding,
      goodwill, and peace through a world fellowship of business and
      professional people united in the ideal of service.</li>
</ul>

<hr/>

<h2>The 4-Way Test</h2>

<p>The 4-Way test of the things we think, say, or do:</p>

<ul id='fourtruths'>
   <li>Is it the <b>Truth</b>?</li>

   <li>Is it <b>Fair</b> to all concerned?</li>

   <li>Will it build <b>Goodwill</b> and <b>Better Friendships</b>?</li>

   <li>Will it be <b>Beneficial</b> to all concerned?</li>
</ul>

<hr/>

<h2 id="rotaryint">Rotary International</h2>
<p>
Rotary Intenational is headquartered in Evanston, Illinois. It guides and assists the activities of
Rotary Clubs worldwide, under the direction of a president who serves for one year. A District
Governor, also elected for one year, heads each of the 515 districts in Rotary.</p>

<p>The Granby club is in <a href="http://rotary5450.org/">District 5450</a>, which includes 45 clubs
and about 3,000 members. Major district-wide events include <b> The District Conference</b>, <b>The
District Assembly</b>, <b>The <a href="http://en.wikipedia.org/wiki/Paul_P._Harris">Paul Harris</a>
Dinner</b>, and <b>PETS</b> (President-Elect Training Seminar).</p> <p>The Club hosts a Group Study
Exchange (GSE) team from another District almost every year.</p> <p>Members contribute to the Rotary
Foundation, which supports many world-wide humanitarian services, including PolioPlus.</p>
<hr>
<!-- Start UpdateSite: YouthPrograms -->
$youthPrograms
<!-- UpdateSite: YouthPrograms End -->

<!-- Start UpdateSite: LocalService -->
$localService
<!-- UpdateSite: LocalService End -->

<!-- Start UpdateSite: International -->
$international
<!-- UpdateSite: International End -->

<!-- Start UpdateSite: Leadership -->
$leadership
<!-- UpdateSite: Leadership End -->

<h2>Where is our Club</h2>

<p><b>The Rotary Club of<br>
   Granby, Colorado, USA</b><br>
PO Box 1430<br>
Granby, CO 80446<br>
e-mail: <a href='mailto:info@granbyrotary.org'>info@granbyrotary.org</a><br>
web: <a href='index.php'>www.granbyrotary.org</a></p>

<!-- lat 40.085935 lon -105.941951 -->

<p>Meets Wednesday at 12:00 PM<br>
at <a href="http://www.mavericksgrille.com/">Maverick's Grille</a><br>
15 E. Agate Avenue (US Highway 40)<br>
P.O. Box 982<br>
Granby, Colorado 80446<br>
Phone: (970) 887-9000<br>
Fax: (970) 887-3221<br>
E-mail: sean@mavericksgrille.com
</p>
<a name="MapToMeetingPlace" ></a>
<hr/>
<h2><a href="cookieinfo.php">Our Use of Browser <i>COOKIES</i></a></h2>
<hr/>
$footer
EOF;
