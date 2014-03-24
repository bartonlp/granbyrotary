<?php
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$x = glob("SilverCreet-Oct-1-2013/*");
foreach($x as $k=>$v) {
  $photos .= "\"$v\",";
}
$photos = rtrim($photos, ",");

// For a fair description of how the updatesite class works look at the class file.
// I have updated the comments quite a lot.
// Check out the admintext.php file and the updatesite.php and updatesite2.php files.

//require_once("includes/updatesite.class.php");

$s->siteclass = $S;
$s->page = "index.php"; // the name of this page
$s->itemname ="PresidentMsg"; // the item we want to get first

$u = new UpdateSite($s); // Should do this outside of the '// START UpdateSite ...' comments

// Now getItem gets the info for the $s->itemname sections
// The special comments around each getItem() are MANDATORY and are used by the UpdateSite class
// to maintain the information in the 'site' table in the bartonphillipsdotorg database at
// bartonphillips.com

// START UpdateSite PresidentMsg "President's Message"
$item = $u->getItem();
// END UpdateSite PresidentMsg

// If item is false then no item in table

if($item !== false) {
  $presidentmsg = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

// To get subsequent sections just set the itemname and call getItem with the $s with the new
// itemname set.

$s->itemname ="Polio";

// START UpdateSite Polio
$item = $u->getItem($s);
// END UpdateSite Polio

if($item !== false) {
  $endpolio = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

$s->itemname ="OtherStuff";

// START UpdateSite OtherStuff
$item = $u->getItem($s);
// END UpdateSite OtherStuff

if($item !== false) {
  $otherstuff = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
}

// This is the END of the UpdateSite section
// *****

// If the 'memberid' is set in $_GET then automagically log the member in.
// This is set via member emails etc.

if(isset($_GET['memberid'])) {
  $mid = $_GET['memberid'];

  // The member is responding to an email with the query ?memberid=id
  // Set the member cookie
  $S->SetIdCookie($mid); // This sets the browser's cookie but not php's $_COOKIE
  $_COOKIE['SiteId'] = $mid;  // force $_COOKIE[GrId] to be $mid so we can set everything with CheckId!!!
  $S->CheckId();  // sets all of the GXxxx publics
}
  
$NewBBmsg = $S->checkBBoard(); // Check if new BB posts

// check if there is mail waiting in info@granbyrotary.org or webmaster
/*
$mbox = imap_open("{granbyrotary.org/imap/notls:143}INBOX", "info@granbyrotary.org", "7098653");
if(!$mbox) {
  echo "Error opening mail box<br>\n";
} else {
  $checkInfo = imap_mailboxmsginfo($mbox);

  $mbox = imap_open("{granbyrotary.org/imap/notls:143}INBOX", "webmaster@granbyrotary.org", "7098653");
  if(!$mbox) {
    echo "Error opening mail box<br>\n";
  } else {
    $checkWebmaster = imap_mailboxmsginfo($mbox);
  }
}
*/

// set up the scripts and styles

$h->link = <<<EOF
  <!-- CSS for News Rotator -->
  <link rel="stylesheet" href="css/rotator.css" type="text/css"/>
EOF;

$h->extra = <<<EOF
   <!-- News Rotator -->
   <script src="js/rotator.js"></script>
   <script src="js/dropdown.js"></script>

   <script>
// For slideshow remove when slideshow goes
var images = new Array();
var inx = 0;

function doslideshow(photos) {
  for(var i=0; i < photos.length; ++i) {
    var image = new Image();
    image.mpcInx = i;
    $(image).css("width", "600px");
    image.src = photos[i];

    $(image).load(function() {
      images[this.mpcInx] = this;
    });

    $(image).error(function(err) {
      console.log(err);
    });
  }
  setTimeout(slideshow, 3000);
}

function slideshow() {
  if(inx > images.length) {
    inx = 0;
  }

  var image = images[inx++];
  $("#pictures-silvercreek").html(image);
  setTimeout(slideshow, 5000);
}
// end of slideshow logic

jQuery(document).ready(function($) {
  $("#child").hide();

  // remove when slideshow goes
  var photos = new Array($photos);
  doslideshow(photos);
  // end of slideshow logic

  $("#parent").click(function() {
    if(!this.flag)
      $("#child").show();
    else
      $("#child").hide();
    this.flag = !this.flag;
  });
});
   </script>

   <style type="text/css">
#loginMsg {
        text-align: center;
}
#loginMsg a {
        font-variant: small-caps;
        font-size: x-large;
}
#wrapper {
        float: right;
        width: 50%;
        border: 1px solid white;
        margin-right: 10px;
}
/* link buttons */
#linkbuttons {
        border: 0;
/*        width: 280px; */
        width: 30%;
        margin-top: 30px;
        margin-bottom: 30px;
}
/* class button is also in rotary.css so check there too if thing change */
.button {
        border: 4px outset gray; 
        text-align: center;
        background-color: red;
        color: white;
        cursor: pointer;
}
.button a {
        color: white;
}
/* style for drop down */
#rotarylinks  {
        border: 0;
        width: 25%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 30px;
        margin-bottom: 30px;
}
#parent {
        cursor: pointer;
        margin: 0;
}
#child {
        display: inline;
}
#child a {
        border: 1px solid black;
        display: block;
        padding: 2px 5px;
        background-color: white; /* #FFFFEE; */
}
/* Whos Been Here */
#todayGuests { 
        background-color: white;
        width: 100%;
}
#todayGuests thead th:first-child {
        width: 60%;
}
#todayGuests td {
        padding: 5px;
}
#moTotal {
        background-color: white;
        width: 100%;
}
#moTotal thead tr:nth-child(2) th:first-child {
        width: 60%;
}
#moTotal td {
        padding: 5px;
}
   </style>

EOF;

// Check if a member  

if($S->id != 0) {
  // MEMBER

  $memberOrLogin = <<<EOF
<h3 id='loginMsg'>Welcome {$S->getUser()}.</h3>
EOF;
} else {
  // NOT A MEMBER OF NOT LOGGED IN

  $memberOrLogin = <<<EOF
<h3 id='loginMsg'>If you are a Grand County Rotarian please
<a href='login.php?return=$S->self'>Login</a> at this time.<br/>
There is a lot more to see if you <a href='login.php?return=$S->self'>Login</a>!
</h3>
<p style='text-align: center'>Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
<a href="login.php?return=$S->self&amp;page=visitor">Register</a></p>
EOF;
}

$h->banner = <<<EOF
      <p>PO Box 1430<br/>
      Granby, CO 80446<br/>
      e-mail:
      <a href='mailto:info@granbyrotary.org'>info@granbyrotary.org</a><br/>

      Meets Wednesday 12:00 PM at<br/>
      <a target="_blank" href='http://www.mavericksgrille.com/'>Maverick's Grille</a><br>
      15 E. Agate Avenue (US Highway 40)<br>
      Granby, Colorado 80446<br>
      Phone: (970) 887-9000<br>
      <a target="_blank" href="http://maps.google.com/maps?hl=en&amp;q=Maverick's%20Grille%2015%20E.%20Agate%20Ave.%20Granby%20CO%2080446">Map</a>
      </p>
$memberOrLogin
EOF;

$h->title ="Rotary Club of Granby CO.";

// Set up the footer info
$b = new stdClass;
$b->msg2 = <<<EOF
<p style='text-align: center;'><a href='aboutwebsite.php'>About
   This Site</a></p>
<p style="text-align: center">
<a target="_blank" href="http://feeds2.feedburner.com/http/wwwgranbyrotaryorg"
title="Subscribe to my feed" rel="alternate"
type="application/rss+xml"><img
src="http://www.feedburner.com/fb/images/pub/feed-icon32x32.png"
alt="" style="border:0"/></a><a target="_blank"
href="http://feeds2.feedburner.com/http/wwwgranbyrotaryorg"
title="Subscribe to my feed" rel="alternate"
type="application/rss+xml">Subscribe in a reader</a>
<br/>
or<br/>
<a target="_blank"
href="http://feedburner.google.com/fb/a/mailverify?uri=http/wwwgranbyrotaryorg&amp;loc=en_US">Subscribe
to Granby Rotary Club by Email</a>
</p>
EOF;

// Get the page top and footer

list($top, $footer) = $S->getPageTopBottom($h, $b);

// Check to see if this member is a web administrator

if($S->isAdmin($S->id)) {
  // Make the Administrator's greeting
  
  $top .= $S->adminText();
}

$S->query("select concat(fname, ' ', lname), bday, day(bday) as day from rotarymembers ".
          "where month(bday) = month(now()) ".
          "and status='active' and otherclub='granby' ".
          "order by day");

while(list($bname, $bday, $bdayday) = $S->fetchrow('num')) {
  switch(substr($bdayday, -1)) {
    case '1':
      $ext = 'st';
      break;
    case '2':
      $ext = 'nd';
      break;
    case '3':
      $ext = 'rd';
      break;
    default:
      $ext = 'th';
      break;
  }
  $whoHasABirthdayThisMonth .= "<tr><td>$bname's birthday is on the $bdayday<sup>$ext</sup></td></tr>\n";
}
$whoHasABirthdayThisMonth = <<<EOF
<table id="moTotal" border="1">
<thead>
<tr><th>Who Has A Birthday This Month</th></tr>
</thead>
<tbody>
$whoHasABirthdayThisMonth
</tbody>
</table>
EOF;

$whosBeenHereToday = $S->getWhosBeenHereToday();

$mostart = date("Y-m-01");

$n = $S->query("select concat(fname, ' ', lname) from daycounts as d ".
               "left join rotarymembers as r on d.id=r.id where d.id!=0 and date >='$mostart' ".
               "group by d.id, date");

$namecnt = array();

while(list($name) = $S->fetchrow()) {
  $namecnt[$name]++;
}

foreach($namecnt as $k=>$v) {
  $memlist .= <<<EOF
<tr><td>$k</td><td>$v</td></tr>
EOF;
}

$moTotal = count($namecnt);

$whosBeenHereThisMonth = <<<EOF
<table id="moTotal" border="1">
<thead>
<tr>
<th colspan="2">Total Members or Vistors using the site this month: $moTotal</th>
</tr>
<tr>
<th>Who Visited This Month?</th>
<th>Number of Days Visited</th>
</tr>
</thead>
<tbody>
$memlist
</tbody>
</table>
EOF;

// ************************
// Start to Render the page
// ************************

echo <<<EOF
$top
<div style="text-align: center; margin-top: 10px;">

<a target="_blank" href="http://www.wunderground.com/US/CO/Granby.html?bannertypeclick=miniStates">
<img src="http://weathersticker.wunderground.com/weathersticker/miniStates_both/language/www/US/CO/Granby.gif"
alt="Click for Granby, Colorado Forecast" border="0" height="100" width="150" /></a>

<br>
<!-- Google +1 button. See script in header -->
<g:plusone></g:plusone>
<!--  Place this tag after the last plusone tag -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</div>
<!-- START UpdateSite: PresidentMsg -->
$presidentmsg
<!-- UpdateSite: PresidentMsg End -->
<h2>Who We Are</h2>
<p>The Rotary Club of Granby was chartered in 1987, and its membership includes men and women representing a wide cross-section of
local businesses and professions. The club meets each Wednesday for fellowship, lunch, and interesting and informative programs
dealing with topics of local and global importance.</p>
<p>The 2013-2014 Club President is
<a href="email.php?id=189">Mark Shearon</a>.
See the <a href="about.php#officerstbl">About Page</a> for a full list of officers and chair persons.</p>
<p>The club is part of Rotary District 5450, comprised of 51 clubs and over 3,000 members. The 2013-2014 Rotary District Governor
is <a target="_blank" href="http://www.clubrunner.ca/portal/Home.aspx?accountid=50085">Dan Himelspach</a>.</p>
<p>The 2013-2014 President of Rotary International is Ron D. Burton.</p>

<hr/>
<!-- Start UpdateSite: Polio Info -->
$endpolio
<!-- UpdateSite: Polio Info End -->
<!-- Start UpdateSite: Other Stuff -->
$otherstuff
<!-- UpdateSite: Other Stuff End -->

<div style="float: left">
<p><a target="_blank" href="interact.php">
<img src="images/interact-club.gif" width="50" alt="InteractClub"/> Interact Club of Middle Park High School</a></p>

<img src="images/find_us_on_facebook_badge.gif" title="Find Us On Facebook" alt="find us on facebook" /><br>
<a target="_blank" href="http://www.facebook.com/group.php?gid=105501794053">Rotary Club of Granby</a><br>
<a target="_blank" href="http://www.facebook.com/home.php?ref=home#!/pages/MPHS-Interact-Club/123052577747543">
Middle Park High School Interact Club on Facebook</a>

</div>
<div style="float: right; width: 50%; margin-bottom: 10px; margin-right: 5px">
$whoHasABirthdayThisMonth
<br>
$whosBeenHereToday
<br>
$whosBeenHereThisMonth
</div>
<br style="clear: both"/>
<!-- News Feed -->
<div id="wrapper">
   <p  style="padding: 0 5px">
   <a href="about.php#rotaryint">Rotary International News Feed</a></p>
   <hr/>
   <h4 style="padding: 0 5px;">Highlights from our <a href="news.php">News Page</a></h4>
   <p style="padding: 0 5px;">Move the mouse over the story to stop it from scrolling.</p>

   <div id="news-feed">
      <a href="news.php">News Releases</a>
   </div>
</div>

<!-- News Changed? and Bulletin Board Buttons and rotary links -->
<div id='linkbuttons'>

EOF;

// Has the News page changed since member was last here?

if($S->newsChanged()) {
  echo <<<EOF
<p class='button' onclick='location.href="news.php";'>
<img src='images/new.gif' alt='New' /><a href='news.php'>Breaking News</a><br/>
<span style='font-size: 12pt; color: black;'>Since You Last Looked</span>
</p>

EOF;
}

// Has the member seen all the BB posts?

if($NewBBmsg) {
  echo <<<EOF
<p class='button' onclick='location.href="bboard.php";'>
<a href='bboard.php'>Bulletin Board $NewBBmsg</a>
</p>

EOF;
} else {
  echo <<<EOF
<p class='button' onclick='location.href="bboard.php";'>
<a href='bboard.php'>Bulletin Board</a>
</p>

EOF;
}

echo <<<EOF
<!-- Rotary Links -->
<p id='parent' class='button'>Links to Rotary Sites</p>
<div id='child'>
   <a target="_blank" href="http://www.clubrunner.ca/portal/Home.aspx?accountid=50085">District 5450 Web Site</a>
   <a target="_blank" href="http://www.rotary.org/">Rotary International Web Site</a>
   <a target="_blank" href="http://www.endpolio.com">District 5450 End Polio Campaign</a>
   <a target="_blank" href='http://rmryla.org'>RYLA (Rotary Youth Leadership Award)</a>
   <a target="_blank" href='http://WinterparkFraserRotary.org'>Winter Park Rotary Club</a>
   <a target="_blank" href='http://www.grandlakerotary.org/'>Grand Lake Rotary Club</a>
   <a target="_blank" href='http://www.kremmlingrotary.org'>Kremmling Rotary Club</a>
   <a target="_blank" href='http://escuelaminga.org/Minga/Rotary.html'>The Equator Project</a>
   <a target="_blank" href='http://www.rotaryeclubone.org/'>eClub One. On-line make ups</a>
</div>
</div>
<br style="clear: both" />
<hr/>

EOF;

if($S->isAdmin($S->id)) {
  if($checkInfo->Unread) {
    // new mail in info@granbyrotary.org
    echo <<<EOF
<p>
<a href="ckemail.php?email=info@granbyrotary.org">
<img src='images/new.gif' title="New Email at info@granbyrotary.org" alt='New Email Available'  style="border: 0"/>
</a> $checkInfo->Unread mail in info@granbyrotary.org </p>

EOF;
  }
  if($checkWebmaster->Unread) {
    echo <<<EOF
<p>
<a href="ckemail.php?email=webmaster@granbyrotary.org">
<img src='images/new.gif' title="New Email at webmaster@granbyrotary.org" alt='New Email Available'  style="border: 0"/>
</a> $checkWebmaster->Unread mail in webmaster@granbyrotary.org </p>
EOF;
  }  
}
echo $footer;
?>