<?php
// BLP 2017-06-25 -- remove article logic

use bartonlp\RssFeed; // Using NEW RssFeed in /var/www/vendor/

require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOAD"). "/siteload.php");
$S = new $_site->className($_site);

session_cache_limiter('private');
session_start();

$d = date("U"); // date to force uncached GETs

// ********************************************************************************
// News Feed Logic. This is an ajax $.get() call. This lets the rest of the page load quickly
// and the only thing that waits is the div with the feed.
// We put a message there saying that the feed is loading.

if($_GET['page'] == 'rssinit') {
  if($S->id) {
    // For members keep track of read news and don't show it again.
    // We create the $readfeeds array which has the title of the article as the key and the value
    // is set to 'start'. Later (below) any feed item that is in this array is marked 'skip'. Still
    // later (at the end) we look to see if any items in the $readfeeds array are still marked
    // 'start'. If there are entries that are still 'start' that means we did not find a feed with
    // that title and therefore we should delete the entry in the memberfeedinfo table because that
    // feed has expired.
    
    $readfeeds = array();
  
    try {
      $n = $S->query("select title from memberfeedinfo where id='$S->id'");
    } catch(Exception $e) {
      throw($e);
    }

    if($n) {
      while(list($r) = $S->fetchrow('num')) {
        if(!$r) $r = "NO TITLE";
        $readfeeds[strtolower($r)] = "start";
      }
    }

    $markasread = <<<EOF
<form action="$S->self" method="get">
<button name='markread' value="read">Mark All As Read</a></button>
<input type="hidden" name="date" value="$d"/>
EOF;
  
    if(count($readfeeds)) {
      $markasread .= <<<EOF
&nbsp;<button name='markread' value='unread'>Mark All As Unread</button>
EOF;
    }
    $markasread .= "</form>";
  }

  try {
    // The class is in the granbyrotary/includes directory

    $feed = new RssFeed("http://www.skyhidailynews.com/feed/");
  } catch(Exception $e) {
    if($e->getCode() == 5001) {
      echo "<span style='color: red'>Can't Connect to SkyHi new feed</span>";
      exit();
    }
    throw($e);
  }

  // Get the parsed feed data structure
  
  $rssFeed = $feed->getDb();

  // Use a session variable to pass the $rssFeed to the other ajax function
  
  $_SESSION['rssFeed'] = $rssFeed;
  
  $skyhinews = "";

  foreach($rssFeed as $i=>$f) {
    if(!$f['title']) {
      $f['title'] = "NO TITLE";
    }
    $title = strtolower($f['title']);

    if(isset($readfeeds[$title])) {
      $readfeeds[$title] = "skip"; 
      continue; // skip this one
    }

    // The new format is: Tue, 14 May 2013 17:29 MST. The time is no loger Tdd:dd:dd-
    $pubDate = $f['pubDate']; //preg_replace("/T(\d\d:\d\d:\d\d)-.*/", ' $1', $f['pubDate']);

    $pubDate = date("Y-m-d H:i", strtotime($pubDate));
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
<table id="skyhinewstbl" border="1" style="width: 100%; line-height: 3em">
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
<input type="hidden" name="date" value="$d"/>
</form>
EOF;
  }

  echo $skyhinews;

  // Get rid of records that no longer have feeds active.
  // Do garbage collection of expired titles

  if($S->id) {
    // ONLY Members otherwise $readfeeds is empty and we will get an error.
    
    foreach($readfeeds as $k=>$v) {
      // If the record still has 'start' then we didn't find a feed
      
      if($v == "start") {
        // Remove this from the table
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
<div id="skyhinewsajaxitem" style="border: 10px groove white; padding: 5px;
 margin-bottom: 20px; line-height: 1.2em;">
<p><a target="_blank" href="{$f['link']}">{$f['title']}</a></p>
{$f['description']}
</div>
EOF;

  exit();
}

// ********************************************************************************
// Page Logic. Above is Ajax logic this is the main flow of the page
// Mark All Feeds Read or Unread
// NOTE: the two forms are method="get" not POST

if($type = $_GET['markread']) {
  // From Ajax call at start

  $rssFeed = $_SESSION['rssFeed'];

  switch($type) {
    case "read":
      // Mark all of the feeds read
      if($rssFeed) {
        foreach($rssFeed as $f) {
          $title = $S->escape($f['title']);
          $sql = "insert ignore into memberfeedinfo (title, id, date) values('$title', '$S->id', now())";
          $n = $S->query($sql);
        }
      }
      break;
    case "unread":
      $n = $S->query("delete from memberfeedinfo where id='$S->id'");
      break;
    default:
      throw(new Exception("markread not read or unread: $type"));
  }
}

// If a member then keep track of feeds read

$S->lookedAtNews();

$s->siteclass = $S;
$s->site = "granbyrotary.org";
$s->page = "news.php";
$s->itemname ="Message";

$u = new UpdateSite($s); // Should do this outside of the START comments

// Now getItem gets the info for the $s->itemname sections
// The special comments around each getItem() are MANDATORY and are used by the UpdateSite class
// to maintain the information in the 'site' table in the bartonphillipsdotorg database at
// bartonphillips.com

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

$s->itemname = 'webdesign';

// START UpdateSite WebDesign
$item = $u->getItem($s);
// END UpdateSite WebDesign
if($item !== false) {
  $webdesign = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr>
EOF;
}

$h->script = <<<EOF
  <!-- local script -->
  <script>
jQuery(document).ready(function($) {
  // Please wait for news feed
  $("#skyhinews").html("<p style='color: red'>Please Wait While SkyHiNews Features are Loaded</p>"+
                       "<div style='text-align: center;'>"+
                       "<img src='http://bartonphillips.net/images/loading.gif'></div>");

  // Get the news feed

  var date = new Date;

  // add a date to the ajax calls to prevent caching!

  $.get("$S->self", { page: 'rssinit', date: date.getTime() }, function(data) {
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

      $.get("$S->self", { page: 'ajaxinx', ajaxinx: inx[1], date: date.getTime() }, function(data) {
        //$("#skyhinewsajaxitem").remove(); // remove the div
        // tr if set is the tr of the last article so hide it
        if(tr) {
          tr.hide(); //hide();
        }
        t.html(data); // replace the title with the thumbnail of the article
        // now get this article's tr 
        tr = t.parent(); // the tr of this item. This sets this so the above if works when it is set
        $("body").scrollTop(tr.offset().top);
      });
      return false; // don't do the <a> stay on this page
    });
  });

});
  </script>
EOF;

$h->css = <<<EOF
  <!-- local style -->
  <style>
h1 {
  text-align: center;
}
button[name='markread'] {
  font-size: 1rem;
  border-radius: .3rem;
  margin-bottom: .5rem;
}
#skyhinewstbl tbody tr td:nth-child(2) {
  font-family: Verdana;
  font-size: .8rem;
  width: 8rem;
}

#skyhinewstbl td {
  padding: 5px;
}
@media (max-width: 800px) {
  #skyhinewstbl tbody tr td:nth-child(2), #skyhinewstbl thead tr th:nth-child(2) {
    display: none;
  }
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

list($top, $footer) = $S->getPageTopBottom($h);

// Echo the UpdateSite message if one
echo <<<EOF
$top
<!-- Start UpdateSite: Important Message -->
$message
<!-- UpdateSite: Importand Message End -->
EOF;

// Upcoming meetings
include("upcomingmeetings.php");

echo <<<EOF
<!-- Start UpdateSite: Webdesign -->
$webdesign
<!-- UpdateSite: Webdesign End -->
<h2>Sky Hi News Feeds</h2>
<div id="skyhinewsitem">
<a name="skyhianchor"></a>
$skyhinewsitem
</div>
<div id="skyhinews">
<p>Not available without Javascript</p>
</div>
<hr/>
$footer
EOF;
