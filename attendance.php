<?php
// BLP 2014-09-01 -- Attendance Report

require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;
$S->title = "Granby Rotary Attendance";

// Page Switch

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "GET":
    switch($_GET['page']) {
      case "attendance":
        attendance($S);
        break;
      case "twomoago":
        $S->mo = 2;
        monthinfo($S);
        break;
      case "lastmo":
        $S->mo = 1;
        monthinfo($S);
        break;
      case "thismo":
        $S->mo = 0;
        monthinfo($S);
        break;
      default:
        startpage($S);
        break;
    }
    break;
  case "POST":
    switch($_POST['page']) {
      case "add":
        add($S);
        break;
      default:
        throw(new Exception("default POST: {$_POST['page']}"));
    }
    break;    
  default:
    throw(new Exception("Not GET or POST"));
}

exit();

// Start Page
// Show meetings for past several months and let user select one.

function startpage($S) {
  $d = date("U");
  $h->title = $S->title;
  $h->banner = "<h1>Attendance Week Selection</h1>";
  $h->extra = <<<EOF
<style>
#selectdate th, #selectdate td {
  padding: 5px;
}
</style>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($h);

  $dates = <<<EOF
<table id=selectdate border=1>
<thead>
<tr><th>Select Date</th><th>Status</th></tr>
</thead>
<tbody>
EOF;

  $sql = "select date from meetings where date > date_sub(now(), interval 5 week) and date < now()";
  $S->query($sql);
  $r = $S->getResult();
  while(list($date) = $S->fetchrow($r, 'num')) {
    $edit = "NEW";
    $sql = "select memberid from attendance where date='$date'";
    if($S->query($sql)) {
      $edit = "EDIT";
    }
    $dates .= "<tr><td><a href='$S->self?page=attendance&date=$date'>$date</a></td><td>$edit</td></tr>";
  }
  $dates .= "</tbody></table>";

  $S->body .= <<<EOF
$dates<br>
<a href='$S->self?page=twomoago&d=$d'>Show Two Months Ago Attendance</a><br>
<a href='$S->self?page=lastmo&=$d'>Show Last Months Attendance</a><br>
<a href='$S->self?page=thismo&d=$d'>Show This Months Attendance to date</a>
EOF;
  printit($S);
}

// Attendance
/*
CREATE TABLE `attendance` (
  `date` date NOT NULL,
  `memberid` int(11) NOT NULL DEFAULT '0',
  `present` enum('yes','no') DEFAULT 'no',
  `extra` int(11) DEFAULT '0' COMMENT 'number of extra credit or makeup reported',
  PRIMARY KEY (`date`, `memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/
  
function attendance($S) {
  $h->title = $S->title;
  $date = $_GET['date'];
  $sql = "select * from attendance where date = '$date'";
  if($edit = $S->query($sql)) {
    // edit
    $sql = "select memberid, concat(Fname, ' ', Lname),  present, extra ".
           "from attendance left join rotarymembers on id=memberid where date='$date'";

    $h->banner = "<h1>Edit Attendance Information for $date</h1>";
    list($S->top, $S->footer) = $S->getPageTopBottom($h);
  } else {
    // add new info
    $sql = "select id, concat(Fname, ' ', Lname) ".
           "from rotarymembers where status='active' and otherclub='granby'";
    $present = 'yes';
    $h->banner = "<h1>Add Attendance Information for $date</h1>";
    list($S->top, $S->footer) = $S->getPageTopBottom($h);
  }

  $S->body = <<<EOF
<form method=post>
<table border=1 id=newattend>
<thead>
<tr>
<th>Member</th><th>Present</th><th>Extra Credit</th>
</tr>
</thead>
<tbody>
EOF;
  $S->query($sql);

  while(list($id, $name, $present, $extra) = $S->fetchrow('num')) {
    if(!$present || $present == 'no') {
      $checked = array('', 'checked');
    } else {
      $checked = array('checked', '');
    }

    $S->body .=  <<<EOF
<tr>
<td>$name</td>
<td>
Yes <input type=radio name=id[$id] value=yes $checked[0] />
No <input type=radio name=id[$id] value=no $checked[1] />
</td>
<td><input name=extra[$id] value=0 /></td>
</tr>
EOF;
    }
    $S->body .= <<<EOF
</tbody>
</table>
<input type=hidden name=page value=add />
<input type=hidden name=date value=$date />
<input type=hidden name=edit value=$edit />
<input type=submit value=Submit>
</form>
EOF;

  printit($S);
}

// Add/edit. If $_POST['edit'] is true then edit else add

function add($S) {
  $h->title = $S->title;
  $extra = $_POST['extra'];
  $id = $_POST['id'];
  $date = $_POST['date'];
  $edit = $_POST['edit']; // 0 if add else not zero

  foreach($id as $k=>$v) {
    if($edit) {
      // update
      $sql = "update attendance set present='$v', extra='{$extra[$k]}' ".
             "where date='$date' and memberid='$k'";
    } else {
      $sql = "insert into attendance (date, memberid, present, extra) ".
             "values('$date', '$k', '$v', '{$extra[$k]}')";
    }
    $S->query($sql);
  }
  $h->banner = "<h1>Attendance Information for $date Posted</h1>";
  list($S->top, $S->footer) = $S->getPageTopBottom($h);
  if(!$edit) {
    $S->body = "<p>New Attendance record added</p>";
  } else {
    $S->body = "<p>Existing Attendance record updated</p>";
  }
  printit($S);
}

// Last Month

function monthinfo($S) {
  $dateinfo = getdate(); // today
  $mo = $dateinfo['mon'];
  $yr = $dateinfo['year'];
  if($S->mo) {
    if($mo == 1) {
      $mo = 12 - ($S->mo -1) ;
      $yr = $yr -1;
    } else {
      $mo -= $S->mo;
    }
  }
  $sql = "select id from rotarymembers where status='active' and otherclub='granby'";
  $members = $S->query($sql);

  $sql = "select date from attendance  where month(date)='$mo' and year(date)='$yr' group by date";
  if((!$meetings = $S->query($sql))) {
    echo "<h1>NO RECORD found for mo: $mo yr: $yr</h1>";
    exit();
  }

  $sql = "select sum(extra) from attendance where month(date)='$mo' and year(date)='$yr'";

  $S->query($sql);

  list($extra) = $S->fetchrow('num');

  $sql = "select sum(present) from attendance ".
         "where present='yes' and month(date)='$mo' and year(date)='$yr'";
  $S->query($sql);

  list($present) = $S->fetchrow('num');

  $total = $present + $extra;
  $percent = number_format($total / $meetings / $members *100, 2);
  $S->body = <<<EOF
<p>Number of meetings: $meetings<br>
Number of members: $members<br>
Present: $present<br>
Extra: $extra<br>
Present + Extra: $total<br>
Percent: $percent%</p>
EOF;
  $h->title = $S->title;
  $h->banner = "<h1>Last Month's Attendance ($mo/$yr)</h1>";
  list($S->top, $S->footer) = $S->getPageTopBottom($h);
  printit($S);
}

// Print it

function printit($S) {
  echo <<<EOF
$S->top
$S->body
<hr>
$S->footer
EOF;
}
?>
