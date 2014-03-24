<?php
// Meetings Page. Shows who is going to speak during the year.
// NOT USED YET 10/18/2012

//  CREATE TABLE `rotarymembers` (
//   `id` int(11) NOT NULL auto_increment,
//   `FName` varchar(255) NOT NULL,
//   `LName` varchar(255) NOT NULL,
//   `office` varchar(255) default NULL,
//   `Email` varchar(255) default NULL,
//   `address` varchar(255) default NULL,
//   `hphone` varchar(255) default NULL,
//   `bphone` varchar(255) default NULL,
//   `cphone` varchar(255) default NULL,
//   `bday` date default NULL,
//   `rotanniv` date default NULL,
//   `status` enum('active','inactive','visitor','honorary','otherclub','delete') default NULL,
//   `last` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
//   `visittime` datetime default NULL,
//   `lastnews` datetime default '0000-00-00 00:00:00',
//   `visits` int(11) default '0',
//   `club` varchar(255) default NULL,
//   `otherclub` enum('granby','grandlake','kremmling','winterpark','interact','none') default NULL,
//   `password` varchar(255) default NULL,
//   `created` datetime default NULL,
//   `districtid` int(11) default '0',
//   `webadmin` enum('no','yes') default 'no',
//   `makeupid` varchar(255) default '',
//   `badge` varchar(10) default NULL,
//   `fax` varchar(20) default NULL,
//   `anniv` varchar(20) default NULL,
//   `classif` varchar(50) default NULL,
//   `spouse` varchar(50) default NULL,
//   PRIMARY KEY  (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;
//    
// require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;

require_once("/home/bartonlp/includes/updatesite.class.php");

$s->site = "granbyrotary.org";
$s->page = "meetings.php";
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

if(!$_GET['print']) {
  $extra = <<<EOF
   <link id="defaultcss" rel="stylesheet" title="Rotary Style Sheet"
        href="/css/rotary.css" type="text/css" media="screen" />
EOF;
} else {
  $extra = <<<EOF
   <link id="printcss" rel="stylesheet" href="/css/print.css"
       type="text/css" title="print" media="print" />
EOF;
}

$extra .= <<<EOF
   <script type="text/javascript">
jQuery(document).ready(function($) {
  $("#print").html("<input type='image' id='printbtn' src='/images/print.gif' style='width: 100px'/>");
  $("#printbtn").click(function() {
    var w = window.open("/meetings.php?print=1", "Print", "width=800, height=600, menubar=yes,\
      scrollbars=yes, resizable=yes");
  });
});
   </script>
   <style type='text/css'>
#assignments {
        border: 1px solid black;
        background-color: white;
}
#assignments table {
        width: 100%;
}
#assignments * {
        border: 1px solid black;
        font-size: 90%;        
}
#assignments td {
        padding: 5px 10px;
}
.bis {
        font-style: italic;
        font-weight: bold;
}
.date {
        color: red;
}
   </style>

EOF;

echo $S->getPageHead("Meeting Programs", null,  $extra);

$footer = $S->getFooter();

if($date = $_GET['date']) {
  Edit( $date);
} elseif($_GET['print']) {
  PrintIt();
} elseif($date = $_POST['post']) {
  Post($date);
} else {
  Show($message);
}

// The bottom of the page

echo <<<EOF
<hr/>

<p>If you have conflicts with the schedule, please arrange a change of date with
  another member and notify Barton Phillips at 970-877-3696 or
  <a href='mailto:bartonphillips@gmail.com'>bartonphillips@gmail.com</a>
  or <a href='mailto:barton@bartonphillips.org'>barton@bartonphillips.org</a>
  or <a href='mailto:info@granbyrotary.org'>info@granbyrotray.org</a>
</p>
<hr/>
$footer
EOF;


// END OF MAIN
// ---------------------------------------
// FUNCTIONS

// Show

function Show($message) {
  global $S;
  
  $top = $S->getBanner("<h2>Program Assignments</h2>");

  if($S->id != 0) {
    if($S->isAdmin($S->id)) {
      // Make the Administrator's greeting
  
      $top .= $S->adminText();
    }
    echo <<<EOF
$top
<h3 id='loginMsg'>Welcome $S->GrUser.</h3>
<hr/>
<!-- Start UpdateSite: Message -->
$message
<!-- UpdateSite: Message End -->
EOF;
  } else {
    echo <<<EOF
$top
<h3 id='loginMsg'>If you are a Grand County Rotarian please <a href='/login.php?return=$S->self'>Login</a> at this time.<br/>
There is a lot more to see if you <a href='/login.php?return=$S->self'>Login</a>!
</h3>
<p style='text-align: center'>Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
<a href="/login.php?return=$S->self&page=visitor">Register</a></p>

<hr/>

EOF;
  }

  // Callback function for maketbodyrows()
    
  function callback(&$row, &$desc) {
    global $S;
    
    $row['Status'] = preg_replace("/-/", " ", $row['Status']);
    switch($row['Editable']) {
      case 'business':
        $row['Presenter'] = "<span class='bis' style='border: 0'>Business Meeting</span>";
        if($row['Subject'] == "") {
          $row['Subject'] = "various";
        }
        break;
      case 'open':
        $row['Presenter'] = "<span style='color: red; border: 0'>OPEN DATE</span>";
        break;
      case 'none':
        $row['Presenter'] = "<span style='color: green; border: 0'>NO MEETING</span>";
        $row['Subject'] = "No Meeting on this date";
        break;
      case 'speaker':
        if($row['Subject'] == "") {
          $row['subject'] = "&nbsp;";
        }
        break;
    }

    if($row['Owner'] == "") {
      $row['Owner'] = "&nbsp;";
    }

    if(($row['id'] == $S->id && $S->id != 0) || ($S->isAdmin($S->id))) {
      $row['Editable'] = "<a href='$S->self?date={$row['Date']}'>EDIT</a>";
    } else {
      $row['Editable'] = "";
    }
    
    return false;
  }

  // Could make the FName, LName into an as membername
  // then I would not need to do the % delimiter stuff and $r=false for the reference.
  // But this is a good test of the logic.

  $query = "select r.id, date as Date, concat(FName, ' ', LName) as Owner, yes as Status, ".
           "name as Presenter, subject as Subject, " .
           "type as Editable from meetings as m ".
           "left join rotarymembers as r on r.id=m.id where date_add(date, interval 1 day) > now() order by date";

  $rowdesc = "<tr><td class='date'>%Date%</td><td>%Owner%</td><td>%Status%</td><td>%Presenter%</td><td>%Subject%</td>".
             "<td>%Editable%</td></tr>";

  $header = "<thead><tr>%<th>*</th>%</tr></thead>";
  
  list($table, $r, $n, $hdr) = $S->makeresultrows($query, $rowdesc,
    array('return'=>true, callback=>callback, delim=>"%", header=>$header));

  // This is a bit of a klug as I need the 'id' but I don't want it in the <thead>
  
  $hdr = preg_replace("/<th>id<\/th>/", '', $hdr);
  
  echo <<<EOF
<div>
<p>You can edit the assignments that belong to you. If there is an <b>EDIT</b> link in the <i>Editable</i> field
you can click on it and change the fields. Here is what you can do:
<ul>
<li>Change you <b>Status</b> to &quot;confirmed&quot;. Once you do this, even if you do not have a subject yet,
the email &quot;Heckling&quot; will stop!</li>
<li>If you have someone who is doing the presentation for you
you can change the name in the <b>Presenter</b> field to that persion's name. <b>The assignment still belongs to you however</b></li>
<li>You can update the <b>Subject</b> field</li>
</ul>
If you need to get someone else to do the assignment please contact
<a href='/email.php?id=25&subject=Can+Not+Do+Talk+HELP'>Barton</a>! Also, if you can't come up with a presentation please
contact <a href='/email.php?id=25&subject=HELP+finding+a+presenter'>Barton</a> as we have several orginizations that
would like to give our group a presentation.</p>
<p>If you would like to sign up for future dates please contact <a href='/email.php?id=25&subject=Talk+Signup'>Barton</a>.</p>

<div id="print">
<a href="$S->self?print=1"><img src="/images/print.gif" width="100" alt="print logo"/></a>
</div>

<table id='assignments'>
$hdr
<tbody>
$table
</tbody>
</table>


EOF;

}

//--------------------
// Edit

function Edit($date) {
  global $S;

  $results = $S->query("select m.*, r.FName, r.LName from meetings as m
left join rotarymembers as r on m.id=r.id where m.date='$date'");

  // $id is the member's id that is responsible for the presentation

  $row = mysql_fetch_assoc($results);
  extract($row);

  echo $S->getBanner("<h2>Edit Your Program Assignment</h2>");

  $options = array('not confirmed', 'confirmed', 'can-not');
  
  echo <<<EOF
<h3>Responsible Member: $FName $LName</h3>
<p>$FName if you know your subject and who will be doing the presentation please fill in those items.
If you do not yet have a subject etc. but you <b>will</b> be doing the presentation, please select <b>confirmed</b>
from the <b>Status</b> drop down. If you feel you can't give the presentation or find a presenter please
select <b>can-not</b> from the <b>Status</b> drop down. If you do select <b>can-not</b> an email will
be sent to Barton Phillips so he can try to find a replacement speaker. Thanks</p>

<form action="$S->self" method="POST">

<table style="width: 95%">
<tr><th>Date</th><td>$date</td></tr>
<tr><th>Status</th><td><select name='status'>

EOF;

for($i=0; $i < count($options); ++$i) {
  $opt = $options[$i];
  echo "<option value='$opt'" . ($yes == $opt ? " selected" : "") . ">$opt</option>\n";
}
  echo <<<EOF
</select>
</td></tr>
<tr><th>Name</th><td><input type="text" name="name" value="$name" />
<span style="color: gray; font-size: 8pt">this can be another person who is presenting for the  member</span></td></tr>
<tr><th>Subject</th><td><input type="text" style="width: 100%" name="subject" value="$subject" /></td></tr>

EOF;

  // Let me edit the additional fields

  if($S->isAdmin($S->id)) {

    $result1 = $S->query("select id as memberId, concat(FName, ' ', LName) as name from rotarymembers where status='active'");

    echo <<<EOF
<tr><th>ID</th>
<td><select name="blp_id">
EOF;

  echo "<option value='-1'" . ($id == -1 ? " selected" : "") . ">-1: Open</option>\n" .
       "<option value='0'" . ($id == 0 ? " selected" : "") . ">0 : Business Meeting</option>\n";

  while($row = mysql_fetch_assoc($result1)) {
    extract($row);
    // $id is the member who is responsible

    echo "<option value='$memberId'" . ($memberId == $id ? " selected" : "") . ">$memberId : $name</option>\n";
  }
  echo <<<EOF
</select>
</td>
</tr>
<tr><th>Type</th><td><select name="type">

EOF;
  $types = array('speaker','business','none', 'open');
  for($i=0; $i < count($types); ++$i) {
    $opt = $types[$i];
    echo "<option value='$opt'" . ($type == $opt ? " selected" : "") . ">$opt</option>\n";
  }
  echo <<<EOF
</select>
</td></tr>

EOF;
  } // end Admins

  echo <<<EOF
</table>
<input type="submit" value="Submit Changes" />
<input type="hidden" name="post" value="$date" />
<input type="hidden" name="id" value="$id" />
</form>

EOF;
}

// FUNCTION
// Post

function Post($date) {
  global $S;

  extract($_POST);

  // If I am editing then blp_id is set and use it rather than id
  // NOTE that Business == 0 so we can't check !$blp_id but rather isset()

  //echo "blp_id=$blp_id<br>\n";
  
  if(isset($blp_id)) {
    //echo "blp_id is set<br>\n";
    $id = $blp_id;
  }
  
  // $id here is the member who is responsible

  echo $S->getBanner("<h2>Post Updated to Program Assignment</h2>");

  switch($id) {
    case 0:
      $membername = "(The Board)";
      $name = 'Business Meeting';
      break;
    case -1:
      $membername = "(OPEN SLOT)";
      $name = 'OPEN';
      break;
    default:
      $results = $S->query("select concat(FName, ' ', LName) as membername from rotarymembers where id='$id'");
      $row = mysql_fetch_assoc($results);
      extract($row);
      break;
  }

  $emailsubject = stripslashes($subject);

  if(!$S->isAdmin($S->id)) {
    if($status == "can-not") {
      $message = "$membername: talk on $date.";
      mail("bartonphillips@gmail.com", "Meetings: Can Not", $message, 'from: info@granbyrotary.org');
      echo <<<EOF
<h3>An Email has been sent to Barton Phillips informing him that you CAN NOT do the talk.</h3>
<p>Please try to think of someone who can do the talk. Barton will get back to you ASAP.</p>

EOF;
    } elseif($status == "confirmed") {
      $message = "$membername: talk on $date. Subject: $emailsubject, Presenter: $name";
      mail("bartonphillips@gmail.com", "Meetings: confirmed", $message, 'from: info@granbyrotary.org');
    } else {
      // status say "not-confirmed"
      // check if the subject has been added or a new name or a link. If so set status to
      // confirmed.
      // Else scold the member and tell him to do something!
      if(!empty($subject)) {
        $status = "confirmed";
        echo <<<EOF
<p>You have changed the <b>Subject</b> field but not set the <b>Status</b>.
That is OK. I have set the status to <b>confirmed</b> you will have someone give a talk. If this is not correct
please return to the edit page and fix it.</p>

EOF;
      $message = "$membername: talk on $date. Subject: $emailsubject, Presenter: $name";
      mail("bartonphillips@gmail.com", "Meetings: confirmed", $message, 'from: info@granbyrotary.org');

      } elseif($name != $membername) {
        $status = "confirmed";
        echo <<<EOF
<p>You have changed the <b>Name</b> field to the name of the presenter but have not added a subject yet.
That is OK. I have set the status to <b>confirmed</b> you will have someone give a talk. If this is not correct
please return to the edit page and fix it.</p>

EOF;
        $message = "$membername: talk on $date. Subject: $emailsubject, Presenter: $name";
        mail("bartonphillips@gmail.com", "Meetings: confirmed", $message, 'from: info@granbyrotary.org');
      } else {
        // member has not confirmed or set the name or subject. He
        // needs to do something.
        echo <<<EOF
<p>It looks like you have not done anything? You should either change the <b>Status</b>, <b>Name</b>, or <b>Subject</b>.
Please re-edit the page. <a href="$S->self?date=$date">Return to Edit Page</a></p>

EOF;
        exit();
      }
    }
  }

  $subject = mysql_real_escape_string($subject);

  // If me update the type and id

  if($S->isAdmin($S->id)) {
    //echo "<br>ID=$id<br>\n";
    //exit();
    $S->query("update meetings set yes='$status', name='$name', subject='$subject', type='$type', id='$id' where date='$date'");
  } else {
    $S->query("update meetings set yes='$status', name='$name', subject='$subject' where date='$date'");
  }
  echo "<p>Return to <a href='$S->self'>Meetings</a></p>\n";
}

// PRINT

function PrintIt() {
  global $S;

  function callback2(&$row, &$desc) {
     if($row[type] == "business") {
      $row[name] = "<span class='bis' style='border: 0'>Business Meeting</span>";
      if($row[subject] == "") {
        $row[subject] = "various";
      }
     }
     return false;
  }
    
  $query = "select * from meetings where date_add(date, interval 1 day) > now() order by date";
  $rowdesc = "<tr><td>date</td><td>name</td><td>subject</td></tr>";
  $table = $S->maketbodyrows($query, $rowdesc, callback2);
  
  echo <<<EOF
<body>
<table id='assignments' style="width: 100%">
<thead>
<tr><th>Date</th><th>Name</th><th>Subject</th></tr>
</thead>
<tbody>
$table
</tbody>
</table>
</body>
</html>
EOF;
exit();
}

?>
