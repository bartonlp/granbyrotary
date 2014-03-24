<?php
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

session_cache_limiter('private');
session_start();

// ********************************************************************************
// News Feed Logic. This is an Ajax call. This lets the rest of the page load quickly and the only
// thing that waits is the div 
// with the feed. We put a message there saying that the feed is loading.

if($_GET['page'] == 'rssinit') {
  //$s['count'] = false;
  $S = new Database($dbinfo); // GranbyRotary($s); // DONT COUNT

  try {
    // New RSS feed 5/14/2013
    $feed = new RssFeed("http://www.skyhidailynews.com/csp/mediapool/sites/SwiftShared/assets/csp/rssCategoryFeed.csp?pub=SkyHiDaily&sectionId=817&sectionLabel=News");
    //$feed = new RssFeed("http://www.skyhidailynews.com/section/rss&mime=xml");
  } catch(Exception $e) {
    if($e->getCode() == 5001) {
      echo "<span style='color: red'>Can't Connect to SkyHi new feed</span>";
      exit();
    }
    throw($e);
  }
  
  $rssFeed = $feed->getDb();

  $_SESSION['rssFeed'] = $rssFeed;

  $readfeeds = $_SESSION['readfeeds'];
  $markasread = $_SESSION['markasread'];

  $skyhinews = "";

  for($i=0; $i < count($rssFeed); ++$i) {
    $f = $rssFeed[$i];
    $title = $f['title'] ? strtolower($f['title']) : "no title";

    if(isset($readfeeds[$title])) {
      $readfeeds[$title] = "skip"; 
      continue; // skip this one
    }

    // The new format is: Tue, 14 May 2013 17:29 MST. The time is no loger Tdd:dd:dd-
    $pubDate = $f['pubDate']; //preg_replace("/T(\d\d:\d\d:\d\d)-.*/", ' $1', $f['pubDate']);

    if(!$f['title']) $f['title'] = "NO TITLE";
    
    $skyhinews .= <<<EOF
<tr>
<td><a href='$S->self?item=$i#skyhianchor'>{$f['title']}</a></td>
<td>$pubDate</td>
</tr>

EOF;
  }

  if($skyhinews) {
    $skyhinews = <<<EOF
<p>Click on Headline to expand article.</p>
$markasread
<table id="skyhinewstbl" border="1" style="width: 100%">
<thead>
<tr><th>Headline</th><th>Date</th></tr>
</thead>
<tbody>
$skyhinews
</tbody>
</table>

EOF;
  } else {
    $skyhinews = <<<EOF
<h4>There are no unread news feeds at this time</h4>
<form action="$S->self" method="get">
<button name='markread' value='unread'>Mark all as Unread</button>
</form>
EOF;
  }

  echo $skyhinews;

  // Get rid of records that no longer have feeds active.
  // Do garbage collection of expired titles

  if($S->id) {
    // ONLY Members otherwise $readfeeds is empty and we will get an error.
    
    if($readfeeds) foreach($readfeeds as $k=>$v) {
      if($v == "start") {
        // Remove this from the table
        //echo "Removed: $k<br>";
        $k = $S->escape($k);
        $S->query("delete from memberfeedinfo where title = '$k'");
      }
    }
  }

  exit();
}

// Ajax to get the individual articles. This can ONLY happend after the initial rssFeed has been
// loaded via the above Ajax call. 

if($_GET['page'] == 'ajaxinx') {
  //$h->count = false;
  $S = new Database($dbinfo); //GranbyRotary($h); // DON'T COUNT
  header("Content-type: text/plain");
  
  $rssFeed = $_SESSION['rssFeed'];

  $i = $_GET['ajaxinx'];
  $f = $rssFeed[$i];

  // Members can remember what they have already read.
  // So if this is a member 

  if($S->id) {
    // We keep track of the title for articles that the member has read.
    try {
      $title = $S->escape($f['title']);
      $query = "insert ignore into memberfeedinfo (title, id, date) values('$title', '$S->id', now())";
      $S->query($query);
    } catch(Exception $e) {
      throw($e);
    }
  }
  
  $pubDate = $f['pubDate']; //preg_replace("/T\d.*/", '', $f['pubDate']);

  echo <<<EOF
<div id="skyhinewsajaxitem" style="border: 10px groove white; padding: 5px; margin-bottom: 20px;">
<p><a target="_blank" href="{$f['link']}">{$f['title']}</a></p>
{$f['description']}
</div>
EOF;

  exit();
}

// ********************************************************************************
// Page Logic. Above is Ajax logic this is the main flow of the page

$S = new GranbyRotary;

// From Ajax call at start

$rssFeed = $_SESSION['rssFeed'];

// Mark All Feeds Read

// NOTE: the two forms are method="get" not POST

if($type = $_GET['markread']) {
  switch($type) {
    case "read":
      // Mark all of the feeds read
      if($rssFeed) {
        foreach($rssFeed as $f) {
          $title = $S->escape($f['title']);
          $S->query("insert ignore into memberfeedinfo (title, id, date) values('$title', '$S->id', now())");
        }
      }
      break;
    case "unread":
      $S->query("delete from memberfeedinfo where id='$S->id'");
      break;
    default:
      throw(new Exception("markread not read or unread: $type"));
  }
}

if($S->id) {
  // for members keep track of read news and don't show it again.

  $readfeeds = array();
  
  try {
    $n = $S->query("select title from memberfeedinfo where id='$S->id'");
  } catch(Exception $e) {
    throw($e);
  }

  if($n) {
    while(list($r) = $S->fetchrow('num')) {
      $readfeeds[strtolower($r)] = "start";
    }
  }

  $markasread = <<<EOF
<form action="$S->self" method="get">
<button name='markread' value="read">Mark All As Read</a></button>
EOF;
  
  if(count($readfeeds)) {
    $markasread .= "&nbsp;<button name='markread' value='unread'>Mark All As Unread</button>\n";
  }
  $markasread .= "</form>";

  //vardump($readfeeds, "READFEEDS");

  $_SESSION['readfeeds'] = $readfeeds;
  $_SESSION['markasread'] = $markasread;
}

// If a member then keep track of feeds read

$S->lookedAtNews();

//require_once("includes/updatesite.class.php");

$s->siteclass = $S;
$s->site = "granbyrotary.org";
$s->page = "news.php";
$s->itemname ="Message";

$u = new UpdateSite($s); // Should do this outside of the START comments

// START UpdateSite Message "Important Message"
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

$page = "";
$hdr = "";

// Read news from database
  
$S->query("select article, rssfeed, articleInclude, created, expired, header, " .
                     "left(created, 10) as creat, left(expired, 10) as exp " .
                     "from articles where expired > now() order by pageorder, created desc");

while($row = $S->fetchrow("assoc")) {
  extract($row);
  switch($articleInclude ) {
    case "article":
      $story = $article;
      break;
    case "rss":
      $story = $rssfeed;
      break;
    case "both":
      $story = "$rssfeed\n$article";
      break;
  }
  if($exp == "2020-01-01") {
    $exp = "NEVER";
  }
    
  $page .= <<<EOF
<div>
$story 
</div>
<p style="color: brown; font-size: 10px; font-style: italic">Creation date: $creat, Expires: $exp</p>

<hr>

EOF;

  // $header is possible script/styles that should be added to the <head> section

  $hdr .= "$header";
}

$h->extra = <<<EOF
  <script>
jQuery(document).ready(function($) {
  // Please wait for news feed
  $("#skyhinews").html("<p style='color: red'>Please Wait While SkyHiNews Features are Loaded</p>"+
                       "<div style='text-align: center;'><img src='images/loading.gif'></div>");

  // Get the news feed

  $.get("$S->self", { page: 'rssinit' }, function(data) {
    $("#skyhinews").html(data);
    var tr;

    $("#skyhinews").on("click", "a", function(e) {
      var href = $(this).attr("href");
      // If this is an anchor from the rssfeed then it has the format ?item=<number>#skihianchor.
      // If it is the anchor to the skihinew webpage with the full article it does NOT have the
      // above format and 'inx' will be null.
      var inx = href.match(/\?item=(\d+)/);

      if(inx == null) return true; // Return true and the anchor will be followed to the site.

      // if 'inx' is not null then it has a number that is the index for the article.

      var t = $(this).parent(); // t is the <td> of the <a>

      $.get("$S->self", { page: 'ajaxinx', ajaxinx: inx[1]}, function(data) {
        //$("#skyhinewsajaxitem").remove(); // remove the div
        // tr if set is the tr of the last article so hide it
        if(tr) {
          tr.remove(); //hide();
        }
        // now get this article's tr 
        tr = t.parent(); // the tr of this item. This sets this so the above if works when it is set
        t.html(data); // replace the title with the thumbnail of the article
      });
      return false; // don't do the <a> stay on this page
    });
  });

  // for McArthur quote in old news stuff at bottom of page

  $("#quote").hover(function(e) {
    var w = e.pageX;
    if(e.pageX + $("#mcarthur").width() > $(document).width()) {
      w = $(document).width() - $("#mcarthur").width() -45;
    }
    $("#mcarthur").css({top : e.pageY + 20, left : w}).show()
  }, function() {
    $("#mcarthur").hide();
  });
});
  </script>
  <style>
h1 {
  text-align: center;
}
/*
#skyhinewstbl tbody tr td:nth-child(2) {
  font-family: Verdana;
  font-size: 14px;
}
*/
#skyhinewstbl td {
  padding: 5px;
}
#mcarthur {
        display: none;
        position: absolute;
        width: 400px;
        padding: 5px;

        background-color: white;
}
   </style>
   <!-- Possible head stuff from database -->
$hdr
   <!-- End database head stuff -->

EOF;

$date = date("l F d, Y");   

if($S->id != 0) {
  // Show Info for MEMBERS
  
  $greet = "<h2>News<br>$date<br>Welcome {$S->getUser()}</h2>";

} else {
  $greet = <<<EOF
<h2>News<br>$date</h2>
<h2>More to see on the web site if you <a href='login.php?return=/news.php'>login</a></h2>
<p>Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
<a href="login.php?return=$S->self]&page=visitor">Register</a></p>

<p>If you are a member take this time to <a href='login.php?return=/news.php'>login</a>, it is easy.
   All you need is the email address you gave the club.
   After you login click on the <b>User Profile</b> item in the navigation menu above.
   You can add a seperate password, update your email
   address, phone number, and home address.
</p>

EOF;
}

$h->title = "Club News";
$h->banner = $greet;

$top = $S->getPageTop($h);
$footer = $S->getFooter();

// Check to see if this member is a web administrator

if($S->isAdmin($S->id)) {
  // Make the Administrator's greeting
  
  $top .= $S->adminText();
}

// Echo the UpdateSite message if one
echo <<<EOF
$top
<!-- Start UpdateSite: Important Message -->
$message
<!-- UpdateSite: Importand Message End -->
<div style="text-align: center; margin-top: 10px;">
<a target="_blank" href="http://www.wunderground.com/US/CO/Granby.html?bannertypeclick=miniStates">
<img src="http://weathersticker.wunderground.com/weathersticker/miniStates_both/language/www/US/CO/Granby.gif"
alt="Click for Granby, Colorado Forecast" border="0" height="100" width="150" /></a>
</div>

EOF;

// Upcoming meetings
include("upcomingmeetings.php");

echo <<<EOF
$page
<h2>Sky Hi News Feeds</h2>
<div id="skyhinewsitem">
<a name="skyhianchor"></a>
$skyhinewsitem
</div>
<div id="skyhinews">
<p>Not available without Javascript</p>
</div>
<hr/>
<!-- OLD NEWS -->
<h2>Older News</h2>
<p>After a while news becomes somewhat <b>Old</b> and while it is no
   longer current you may still be interested in reading older news
   items. You can do that at the <a href='oldnews.php'>Old News
      Page</a> where we keep that good old stuff for quite a while. Of
   course at some point everything either becomes <i>New
      Again</i> or just <i>Fades away</i> like old soldiers who
   <i>Never Die</i>. Well unlike
   <a id="quote" href='http://en.wikipedia.org/wiki/Douglas_MacArthur'>McArthur</a>
   after a while the old news will just vanish!</p>

<!-- Quote from General Duglas McArthur, not displayed until we hover
over his name above -->
<div id='mcarthur'>&quot;Old soldiers never die; they just fade away... And like the
   old soldier of that ballad, I now close my military career and
   just fade away an old soldier who tried to do his duty as God
   gave him the light to see that duty. Good-by&quot;
</div>

<hr>

$footer
EOF;

//  -------------------------------------------------------------------------------------------
// Given a text string, calculate the width in pixles based on using Verdana 10px non-bold font
// Return: total pixel width.

// NOT USED 5/16/2013
function strlen_pixels($text) {
  /*
  Pixels utilized by each char (Verdana, 10px, non-bold)
  04: j
  05: I\il,-./:; <espace>
  06: J[]f()
  07: t
  08: _rz*
  09: ?csvxy
  10: Saeko0123456789$
  11: FKLPTXYZbdghnpqu
  12: AÇBCERV
  13: <=DGHNOQU^+
  14: w
  15: m
  16: @MW
  */

  // CREATING ARRAY $ps ('pixel size')
  // Note 1: each key of array $ps is the ascii code of the char.
  // Note 2: using $ps as GLOBAL can be a good idea, increase speed
  // keys:    ascii-code
  // values:  pixel size

  // $t: array of arrays, temporary
  $t[] = array_combine(array(106), array_fill(0, 1, 4));

  $t[] = array_combine(array(73,92,105,108,44), array_fill(0, 5, 5));
  $t[] = array_combine(array(45,46,47,58,59,32), array_fill(0, 6, 5));
  $t[] = array_combine(array(74,91,93,102,40,41), array_fill(0, 6, 6));
  $t[] = array_combine(array(116), array_fill(0, 1, 7));
  $t[] = array_combine(array(95,114,122,42), array_fill(0, 4, 8));
  $t[] = array_combine(array(63,99,115,118,120,121), array_fill(0, 6, 9));
  $t[] = array_combine(array(83,97,101,107), array_fill(0, 4, 10));
  $t[] = array_combine(array(111,48,49,50), array_fill(0, 4, 10));
  $t[] = array_combine(array(51,52,53,54,55,56,57,36), array_fill(0, 8, 10));
  $t[] = array_combine(array(70,75,76,80), array_fill(0, 4, 11));
  $t[] = array_combine(array(84,88,89,90,98), array_fill(0, 5, 11));
  $t[] = array_combine(array(100,103,104), array_fill(0, 3, 11));
  $t[] = array_combine(array(110,112,113,117), array_fill(0, 4, 11));
  $t[] = array_combine(array(65,195,135,66), array_fill(0, 4, 12));
  $t[] = array_combine(array(67,69,82,86), array_fill(0, 4, 12));
  $t[] = array_combine(array(78,79,81,85,94,43), array_fill(0, 6, 13));
  $t[] = array_combine(array(60,61,68,71,72), array_fill(0, 5, 13));
  $t[] = array_combine(array(119), array_fill(0, 1, 14));
  $t[] = array_combine(array(109), array_fill(0, 1, 15));
  $t[] = array_combine(array(64,77,87), array_fill(0, 3, 16));  

  // merge all temp arrays into $ps
  $ps = array();
  foreach($t as $sub) $ps = $ps + $sub;
  
  // USING ARRAY $ps
  $total = 1;
  for($i=0; $i<strlen($text); $i++) {
    $temp = $ps[ord($text[$i])];
    if(!$temp) $temp = 10.5; // default size for 10px
    $total += $temp;
  }
  return $total;
}
?>
