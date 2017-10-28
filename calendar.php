<?php
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case "print":
        printit($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      default:
        start($S);
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}

//  ---------------------------------------------------------------------------

function start($S) {
  $h->title = "Rotary Calendar";
  $h->banner = "<h2>Rotary Club Calandar</h2>";
  $h->extra = <<<EOF
  <style type="text/css">
table {
  width: 100%;
}
td {
  padding: 3px;
}
  </style>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getPageFooter();

//  require_once("includes/updatesite.class.php");

  $s->siteclass = $S;
  $s->site = "granbyrotary.org";
  $s->page = "calendar.php";
  $s->itemname ="message";

  $u = new UpdateSite($s); // Should do this outside of the START comments

// START UpdateSite Msg "Calendar Message"
  $item = $u->getItem();
// END UpdateSite Msg

  // If item is false then no item in table

  if($item !== false) {
    $msg = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
  }

  $n = $S->query("select dayname(date) as wkday, id, date, title, message from calendar where date >= curdate() ".
                              "order by date");
  if($S->isAdmin($S->id)) {
    if($n) {
      $admin = "<p>To Edit Item click on the date.</p>\n";
    }
    $admin .= <<<EOF
<a href="calendaradd.php">Add Calendar Items</a>
EOF;
  }

  if($n) {
    $event = <<<EOF
<table border="1">
<thead>
<tr><th>Date</th><th>Title</th><th>Information</th></tr>
</thead>
<tbody>
EOF;

    while($row = $S->fetchrow('assoc')) {
      extract($row);

      $date = "$wkday<br>$date";
      if($S->isAdmin($S->id)) {
        $date = "<a href='calendaradd.php?page=edit&id=$id'>$date</a>";
      }
      $event .= "\n<td>$date</td><td>$title</td><td>$message</td></tr>";
    }
    $event .= "\n</tbody>\n</table>";
  }

  ob_start();
  include("upcomingmeetings.php");
  $upcoming = ob_get_clean();

  echo <<<EOF
$top
<form action="$S->self" method="post">
<input type="hidden" name="page" value="print"/>
<input type="submit" value="Print Calendar"/>
</form>
$msg
$upcoming
$event
$admin
<hr/>
$footer
EOF;
}

//  ---------------------------------------------------------------------------

function printit($S) {
  $n = $S->query("select dayname(date) as wkday, id, date, title, message from calendar where date >= curdate() ".
                              "order by date");
  if($n) {
    $event = <<<EOF
<table border="1">
<thead>
<tr><th>Date</th><th>Title</th><th>Information</th></tr>
</thead>
<tbody>
EOF;

    while($row = $S->fetchrow('assoc')) {
      extract($row);

      $date = "$wkday<br>$date";
      $event .= "\n<td>$date</td><td>$title</td><td>$message</td></tr>";
    }
    $event .= "\n</tbody>\n</table>";
  }

  ob_start();
  include("upcomingmeetings.php");
  $upcoming = ob_get_clean();

  echo <<<EOF
<html>
<body>
$msg
$upcoming
$event
</body>
</html>
EOF;
}


