<?php
// Atendance Page. NOT USED currently (10/12/2012)
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'selectmembers':
        selectmembers($S);
        break;
      case 'postedit':
      case 'post':
        post($S);
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
  $h->title = "Granby Attendance Select Date";
  $h->banner = "<h1>Weekly Attendance Select Date</h1>";
  $h->extra = <<<EOF
  <script type="text/javascript" src="/js/date_input/jquery.date_input.js"></script>
  <link rel="stylesheet" href="/js/date_input/date_input.css" type="text/css">

  <script type='text/javascript'>
jQuery(document).ready(function($) {
  $.extend(DateInput.DEFAULT_OPTS, {
    stringToDate: function(string) {
      var matches;
      if(matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
        return new Date(matches[1], matches[2] - 1, matches[3]);
      } else {
        return null;
      };
    },

    dateToString: function(date) {
      var month = (date.getMonth() + 1).toString();
      var dom = date.getDate().toString();
      if (month.length == 1) month = "0" + month;
      if (dom.length == 1) dom = "0" + dom;
      return date.getFullYear() + "-" + month + "-" + dom;
    }
  });
  $.date_input.initialize();
});  
  </script>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  
  list($displaydate, $meetingdate) = getmeetingdate("wednesday");

  echo <<<EOF
$S->top
<form action="$S->sefl" method="post">
<h3>Select Meeting Date:
<input class="date_input" type="text" name="meetingdate" value="$meetingdate" title="Wednesday $displaydate"/><h3>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="selectmembers"/>
</form>
<hr>
$S->footer
EOF;
}

//  ---------------------------------------------------------------------------

function selectmembers($S) {
  $h->title = "Granby Attendance Select";
  $h->banner = "<h1>Weekly Attendance Select Members</h1>";
  $h->extra = <<<EOF
  <style type="text/css">
td {
  padding: 5px;
}
  </style>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  $meetingdate = $_POST['meetingdate'];

  $result = $S->query("select date_format('$meetingdate', '%W %M %D, %Y')");
  list($displaydate) = mysql_fetch_row($result);

  list($result, $n) = $S->query("select id from attend where meetingdate='$meetingdate'", true);
  if($n) {
    $S->edit = "<h3>Attendance for $meetingdate already exists. Edit Entry</h3>";
    edit($S);
    exit();
  }

  $query = "select id, concat(lname, ', ', fname) ".
           "from rotarymembers where status='active' and otherclub='granby' order by lname";

  $result = $S->query($query);

  while(list($id, $name) = mysql_fetch_row($result)) {
    $memberlist .= "<tr><td><input type='checkbox' name='ids[]' value='$id'></td><td>$name</td></tr>\n";
  }

  echo <<<EOF
$S->top
<h3>Check members who where pressent for meeting on $displaydate</h3>
<form action="$S->self" method="post">
<table border="1">
<thead>
<tr><th>Present</th><th>Member Name</th></tr>
</thead>
<tbody>
$memberlist
</tbody>
<tfoot>
<tr><td colspan="2"><input type="submit" value="Submit"/></td></tr>
</tfoot>
</table>
<input type="hidden" name="page" value="post"/>
<input type="hidden" name="meetingdate" value="$meetingdate"/>
</form>
<hr>
$S->footer
EOF;
}

//  ---------------------------------------------------------------------------

function edit($S) {
  $h->title = "Granby Attendance Edit";
  $h->banner = "<h1>Weekly Attendance Edit Members</h1>$S->edit";
  $h->extra = <<<EOF
  <style type="text/css">
td {
  padding: 5px;
}
  </style>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  $meetingdate = $_POST['meetingdate'];

  $result = $S->query("select date_format('$meetingdate', '%W %M %D, %Y')");
  list($displaydate) = mysql_fetch_row($result);

  $result = $S->query("select id from attend where meetingdate='$meetingdate'");
  while(list($id) = mysql_fetch_row($result)) {
    $ids[] = $id;
  }

  $result = $S->query("select id, concat(lname, ', ', fname) from rotarymembers where status='active' and otherclub='granby' ".
                      "order by lname");

  while(list($id, $name) = mysql_fetch_row($result)) {
    if(in_array($id, $ids)) {
      $checked = "checked='checked'";
    } else {
      $checked = "";
    }

    $memberlist .= "<tr><td><input type='checkbox' $checked name='ids[]' value='$id'></td><td>$name</td></tr>\n";
  }

  echo <<<EOF
$S->top
<h3>Check members who where pressent for meeting on $displaydate</h3>
<form action="$S->self" method="post">
<table border="1">
<thead>
<tr><th>Present</th><th>Member Name</th></tr>
</thead>
<tbody>
$memberlist
</tbody>
<tfoot>
<tr><td colspan="2"><input type="submit" value="Submit"/></td></tr>
</tfoot>
</table>
<input type="hidden" name="page" value="postedit"/>
<input type="hidden" name="meetingdate" value="$meetingdate"/>
</form>
<hr>
$S->footer
EOF;
}  
//  ---------------------------------------------------------------------------
/*
CREATE TABLE `attend` (
  `id` int(11) NOT NULL,
  `meetingdate` date NOT NULL,
  PRIMARY KEY  (`id`,`meetingdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
*/

function post($S) {
  $h->title = "Granby Attendance Posted";
  $h->banner = "<h1>Weekly Attendance Posted</h1>";
  
  list($S->top, $S->footer) = $S->getPageTopBottom($h);
  echo <<<EOF
$S->top
$S->footer
EOF;

  extract($_POST);
  //vardump($ids, "ids");

  switch($page) {
    case 'postedit':
      $query = "delete from attend where id not in (" . implode(",", $ids) . ")";
      list($result, $n) = $S->query($query, true);
      //echo "$n records deleted<br>";
    case 'post':
      foreach($ids as $id) {
        list($result, $n) = $S->query("insert ignore into attend (id, meetingdate) values('$id', '$meetingdate')", true);
        //echo "$n inserted<br>";
      }
      break;
  }
}

//  ---------------------------------------------------------------------------

function getmeetingdate($weekday) {
  /**
   * Function to find the day of a week in a year
   * @param integer $week The week number of the year
   * @param integer $year The year of the week we need to calculate on
   * @param string  $start_of_week The start day of the week you want returned
   *                Monday is the default Start Day of the Week in PHP. For
   *                example you might want to get the date for the Sunday of wk 22
   * @return integer The unix timestamp of the date is returned
   */

  function find_first_day_ofweek($week, $year, $start_of_week='sunday') {
    // Get the target week of the year with reference to the starting day of
    // the year
    $target_week = strtotime("$week week", strtotime("1 January $year"));

    // Get the date information for the day in question which
    // is "n" number of weeks from the start of the year
    $date_info = getdate($target_week);

    // Get the day of the week (integer value)
    $day_of_week = $date_info['wday'];

    // Make an adjustment for the start day of the week because in PHP the
    // start day of the week is Monday
    switch (strtolower($start_of_week)) {
      case 'sunday':
        $adjusted_date = $day_of_week;
        break;
      case 'monday':
        $adjusted_date = $day_of_week-1;
        break;
      case 'tuesday':
        $adjusted_date = $day_of_week-2;
        break;
      case 'wednesday':
        $adjusted_date = $day_of_week-3;
        break;
      case 'thursday':
        $adjusted_date = $day_of_week-4;
        break;
      case 'friday':
        $adjusted_date = $day_of_week-5;
        break;
      case 'saturday':
        $adjusted_date = $day_of_week-6;
        break;

      default:
        $adjusted_date = $day_of_week-1;
        break;
    }

    // Get the first day of the weekday requested
    $first_day = strtotime("-$adjusted_date day", $target_week);

    //return date('l dS \of F Y h:i:s A', $first_day);
    //echo  date('l jS \of F Y h:i:s A', $first_day) . "<br>";

    return $first_day;
  }

  // date("W") is based on Monday as the first day of the week. There can also be either 52 or 53 weeks in a year.

  $week = date("W");
  $year = date("Y");

  $date = find_first_day_ofweek($week, $year, $weekday);

/*
  if($date < time()) {
    $date = find_first_day_ofweek($week+1, $year, $weekday);
  }
*/
  return array(date("F j, Y", $date), date("Y-m-d", $date));
}
?>