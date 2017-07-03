<?php
// For 2017-2018 Pres: Bog Saint, Tres: Wayne, Sec: Wiese, Pres Elect: Christian Hornbaker

// Get the GranbyRotary class
require_once("./vendor/autoload.php");

$_site = require_once(getenv("SITELOAD") . "/siteload.php");
$S = new $_site->className($_site);

$x = glob("../bartonphillipsnet/images/banner-photos/*");
foreach($x as $v) {
  $v = basename($v);
  $banner_photos .= "'http://bartonphillips.net/images/banner-photos/$v',";
}
$banner_photos = rtrim($banner_photos, ",");

// For a fair description of how the updatesite class works look at the class file.
// I have updated the comments quite a lot.
// Check out the admintext.php file and the updatesite.php and updatesite2.php files.

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

$s->itemname = "WhoWeAre";
// START UpdateSite WhoWeAre
$item = $u->getItem($s);
// END UpdateSite WhoWeAre

if($item !== false) {
  $whoWeAre = <<<EOF
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
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

// set up the scripts and styles

$h->extra = <<<EOF
  <!-- local extra -->
  <script>var banner_photos = new Array($banner_photos);</script>
  <script async src="js/dropdown.js"></script>
  <script src="js/ximage.js"></script>
EOF;

$h->css = <<<EOF
  <!-- local css -->
  <style>
#loginMsg {
        text-align: center;
}
#loginMsg a {
        font-variant: small-caps;
        font-size: x-large;
}
/* class button is also in rotary.css so check there too if thing change */
.button {
        border: 4px outset gray; 
        text-align: center;
        background-color: red;
        color: white;
        cursor: pointer;
        width: 280px;
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
#rotary-links-menu {
        width: 30rem;
        margin: auto;
        margin-top: 15px;
}
#bottominfo {
        width: 50%;
        margin: auto;
}
#button-group {
        margin-bottom: 10px;
}
/* Who table */
#who { /* who has birthday/been here */
        width: 100%;
        margin-bottom: 10px;
        margin-right: 5px;
}
.who {
        background-color: white;
        width: 100%;
}
.who thead tr:nth-child(2) th:first-child {
        width: 60%;
}
.who
 td {
        padding: 5px;
}
#bday {
        color: red;
        font-size: 2em;
}
@media (max-width: 700px) {
        .who {
                width: 100%;
                font-size: 80%;
        }
        #bottominfo {
                width: 85%;
        }
}
  </style>
EOF;

// Check if a member  

if($S->id != 0) {
  // MEMBER

  $S->query("select bday from rotarymembers where id=$S->id");
  list($bday) = $S->fetchrow('num');

  if(substr($bday,5) == date("m-d")) {
    $bdaymsg = "<br><span id='bday'>Happy Birthday</span>";
  }
  $memberOrLogin = <<<EOF
<h3 id='loginMsg'>Welcome {$S->getUser()}$bdaymsg.</h3>
EOF;
} else {
  // NOT A MEMBER OF NOT LOGGED IN

  $memberOrLogin = <<<EOF
<h3 id='loginMsg'>If you are a Grand County Rotarian please
<a href='login.php?return=$S->self'>Login</a> at this time.<br/>
There is a lot more to see if you <a href='login.php?return=$S->self'>Login</a>!
</h3>
<p class="center">Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
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
$memberOrLogin$AdminText
EOF;

$h->title ="Rotary Club of Granby CO.";

// Set up the footer info
$b = new stdClass;
$b->msg2 = <<<EOF
<p class="center"><a href='aboutwebsite.php'>About This Site</a></p>
EOF;

// Get the page top and footer

list($top, $footer) = $S->getPageTopBottom($h, $b);

$S->query("select concat(fname, ' ', lname), bday, day(bday) as day from rotarymembers ".
          "where month(bday) = month(now()) ".
          "and status='active' and otherclub='granby' ".
          "order by day");

while(list($bname, $bday, $bdayday) = $S->fetchrow('num')) {
  if($bdayday < 20 && $bdayday > 9) {
    $ext = 'th';
  } else {
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
  }
  $whoHasABirthdayThisMonth .= "<tr><td>$bname's birthday is on the ".
                               "$bdayday<sup>$ext</sup></td></tr>\n";
}
$whoHasABirthdayThisMonth = <<<EOF
<table class="who" border="1">
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

$S->query("select concat(fname, ' ', lname) from rotarymembers ".
               "where id!=0 and visittime >='$mostart' ".
               "group by id, last");

$moTotal = 0;

while(list($name) = $S->fetchrow()) {
  $moTotal++;
  $memlist .= <<<EOF
<tr><td>$name</td></tr>
EOF;
}


$whosBeenHereThisMonth = <<<EOF
<table class="who" border="1">
<thead>
<tr>
<th colspan="2">Total Members or Vistors using the site this month: $moTotal</th>
</tr>
</thead>
<tbody>
$memlist
</tbody>
</table>
EOF;

// ***************
// Render the page
// ***************

echo <<<EOF
$top
<!-- START UpdateSite: PresidentMsg -->
$presidentmsg
<!-- UpdateSite: PresidentMsg End -->

<!-- START UpdateSite: WhoWeAre -->
$whoWeAre
<!-- UpdateSite: WhoWeAre End -->

<div class="center">
<!-- Start UpdateSite: Other Stuff -->
$otherstuff
<!-- UpdateSite: Other Stuff End -->
<div id="button-group">
<!-- Rotary Links -->
<div id="rotary-links">
  <label for="rotary-links-checkbox">Rotary Links</label>
  <input type="checkbox" id="rotary-links-checkbox" role="button">
  <ul id="rotary-links-menu">
    <li><a target="_blank" href="http://www.clubrunner.ca/portal/Home.aspx?accountid=50085">District 5450 Web Site</a>
    <li><a target="_blank" href="http://www.rotary.org/">Rotary International Web Site</a>
    <li><a target="_blank" href='http://rmryla.org'>RYLA</a>
    <li><a target="_blank" href='http://WinterparkFraserRotary.org'>Winter Park Rotary Club</a>
    <li><a target="_blank" href='http://www.grandlakerotary.org/'>Grand Lake Rotary Club</a>
    <li><a target="_blank" href='http://escuelaminga.org/Minga/Rotary.html'>The Equator Project</a>
  </ul>
</div>
</div>
<div id="bottominfo">
<div id="who">$whoHasABirthdayThisMonth
<br>
$whosBeenHereToday
<br>
$whosBeenHereThisMonth
</div>
</div>
<hr class="clearboth"/>
$footer
EOF;
