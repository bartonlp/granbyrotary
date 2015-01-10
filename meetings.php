<?php
// BLP 2014-08-13 -- Any Admin can edit all of the entries. 
// BLP 2014-07-17 -- removed admin from here and added it to includes/banner.i.php

define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;
$S->d = date("U");

$s->siteclass = $S;
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

$h->link = <<<EOF
  <!-- force lanscape -->
  <link rel="manifest" href="manifest.json">
EOF;

if($_GET['print']) {
  $h->link .= <<<EOF
  <!-- Print css -->
  <link id="printcss" rel="stylesheet" href="css/print.css"
        title="print" media="print" />
EOF;
}

$h->script = <<<EOF
  <!-- local script -->
  <script>
jQuery(document).ready(function($) {
  $("#print").html("<input type='image' id='printbtn' src='images/print.gif' " +
                   "style='width: 100px'/>");

  $("#printbtn").click(function() {
    var w = window.open("/meetings.php?print=1", "_blank",
                        "width=2400, height=2000, menubar=yes, scrollbars=yes, "+
                        "fullscreen=yes, resizable=yes");
  });
});
  </script>
EOF;

$h->css = <<<EOF
  <!-- local css -->
  <style>
#use-landscape {
  position: fixed;
  top: 200px;
  left: 0px;
  width: 100%;
  display: none;
}
#use-landscape p {
  text-align: center;
  font-size: 1.15em;
  margin-top: 20%;
}
@media (max-width: 800px) and (orientation: portrait) {
  main {
    opacity: .1;
  }
  header {
    opacity: .1;
  }
  h3 {
    opacity: .1;
  }
  #bottom {
    opacity: .1;
  }
  #use-landscape {
    display: block;
  }
}
.special {
  color: red;
  background-color: white;
  padding: 5px;
}
#assignments {
        border: 1px solid black;
        background-color: white;
}
#assignments {
        width: 100%;
}
#assignments * {
        border: 1px solid black;
        font-size: 90%;        
}
#assignments td {
        padding: 5px;
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

$h->title = "Meetings";
$h->banner = "<h1>Program Assignments</h1>";

$S->top = $S->getPageHead($h);
$footer = $S->getFooter();

// The top and body of the page

if($date = $_GET['date']) {
  Edit($date);
} elseif($_GET['print']) {
  PrintIt($S);
} elseif($date = $_POST['post']) {
  Post($date);
} else {
  Show($message);
}

// Bottom of page

echo <<<EOF
<div id="use-landscape"><p>Rotate Your Device to Landscape</p></div>
<div id="bottom">
<hr/>

<p>If you have conflicts with the schedule, please arrange a change of date with
  another member.</p>
<hr/>
</div>
$footer
EOF;

// END OF MAIN
// ---------------------------------------
// FUNCTIONS

// Show

function Show($message) {
  global $S;

  $banner = $S->getBanner("<h2>Program Assignments</h2>");


  if($S->id != 0) {
    echo <<<EOF
$S->top
$banner
<h3 class="center">Welcome {$S->getUser()}.</h3>
<hr/>
<!-- Start UpdateSite: Message -->
$message
<!-- UpdateSite: Message End -->
EOF;
  } else {
    echo <<<EOF
$S->top
$banner
<h3 class="center">If you are a Grand County Rotarian please <a href='login.php?return=$S->self'>Login</a> at this time.<br/>
There is a lot more to see if you <a href='login.php?return=$S->self'>Login</a>!
</h3>
<p style='text-align: center'>Not a Grand County Rotarian? You can <b>Register</b> as a visitor.
<a href="login.php?return=$S->self&page=visitor">Register</a></p>

<hr/>

EOF;
  }

  // Callback function for maketbodyrows()
    
  function callback(&$row, &$desc) {
    global $S;
    
    $row[Status] = preg_replace("/-/", " ", $row['Status']);
    switch($row['Edit']) {
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
          $row['Subject'] = "&nbsp;";
        }
        break;
    }

    if($row['Owner'] == "") {
      $row['Owner'] = "&nbsp;";
    }

    if(($row['id'] == $S->id && $S->id != 0) || ($S->isAdmin($S->id))) {
      $row['Edit'] = "<a href='$S->self?date=$row[Date]&d=$S->d'>EDIT</a>";
    } else {
      $row['Edit'] = "";
    }
    
    return false;
  }

  // Could make the FName, LName into an as membername
  // then I would not need to do the % delimiter stuff and $r=false for the reference. But this is a good
  // test of the logic.

  $query = "select r.id, date as Date, concat(FName, ' ', LName) as Owner, yes as Status, name as Presenter, subject as Subject, " .
           "type as Edit from meetings as m ".
           "left join rotarymembers as r on r.id=m.id where date_add(date, interval 1 day) > now() order by date";

  $rowdesc = "<tr><td class='date'>%Date%</td><td>%Owner%</td><td>%Status%</td><td>%Presenter%</td><td>%Subject%</td>".
             "<td>%Edit%</td></tr>";

  $header = "<thead><tr>%<th>*</th>%</tr></thead>";
  $t = new dbTables($S);
  list($table, $r, $n, $hdr) = $t->makeresultrows($query, $rowdesc,
    array('return'=>true, 'callback'=>'callback', 'delim'=>"%", 'header'=>$header));

  // This is a bit of a klug as I need the 'id' but I don't want it in the <thead>
  
  $hdr = preg_replace("/<th>id<\/th>/", '', $hdr);
  
  echo <<<EOF
<main>
<div>
<p>You can edit the assignments that belong to you. If there is an <b>EDIT</b> link
you can click on it and change the fields. Here is what you can do:
<ul>
<li>Change you <b>Status</b> to &quot;confirmed&quot;. Once you do this, even if you do not have a subject yet,
the email &quot;Heckling&quot; will stop!</li>
<li>If you have someone who is doing the presentation for you
you can change the name in the <b>Presenter</b> field to that persion's name. <b>The assignment still belongs to you however</b></li>
<li>You can update the <b>Subject</b> field</li>
</ul>
<p class="special">An automated email is sent to the SkyHiNews early on the Thursday
before your talk. The SkyHiNews posts the information in the paper for a week starting
the Friday before our meeting. If you don't have your information updated by the
Thursday before your talk the email goes out with your name and &quot;TBD&quot; which
looks dumb and usually does not get into the paper. Please try to get your talk
information updated before the Wednesday meeting prior to your talk.</p>

<div id="print">
<a href="$S->self?print=1"><img src="images/print.gif" width="100" alt="print logo"/></a>
</div>

<table id='assignments'>
$hdr
<tbody>
$table
</tbody>
</table>
</main>
EOF;
}

//--------------------
// Edit

function Edit($date) {
  global $S;

  $S->query("select m.*, r.FName, r.LName from meetings as m ".
            "left join rotarymembers as r on m.id=r.id where m.date='$date'");

  // $id is the member's id that is responsible for the presentation

  $row = $S->fetchrow();
  extract($row);

  $banner = $S->getBanner("<h1>Edit Your Program Assignment</h1>");

  $options = array('not confirmed', 'confirmed', 'can-not');
  
  echo <<<EOF
$S->top
$banner
<h3>Responsible Member: $FName $LName</h3>
<p>$FName if you know your subject and who will be doing the presentation please fill in those items.
If you do not yet have a subject etc. but you <b>will</b> be doing the presentation, please select <b>confirmed</b>
from the <b>Status</b> drop down. If you feel you can't give the presentation or find a presenter please
select <b>can-not</b> from the <b>Status</b> drop down.</p>

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
<span style="font-size: 10pt">this can be another person who is presenting for the  member</span></td></tr>
<tr><th>Subject</th><td><input type="text" style="width: 100%" name="subject" value="$subject" /></td></tr>

EOF;

  // Let Admins edit the additional fields

  if($S->isAdmin($S->id)) {
    $S->query("select id as memberId, concat(FName, ' ', LName) as name from rotarymembers ".
              "where status='active'");

    // BLP 2014-08-13 -- blp_id is missleading as any Admin can edit the additional fields
    echo <<<EOF
<tr><th>ID</th>
<td><select name="blp_id">
EOF;

  echo "<option value='-1'" . ($id == -1 ? " selected" : "") . ">-1: Open</option>\n" .
       "<option value='0'" . ($id == 0 ? " selected" : "") . ">0 : Business Meeting</option>\n";

  while($row = $S->fetchrow()) {
    extract($row);
    // $id is the member who is responsible

    echo "<option value='$memberId'" . ($memberId == $id ? " selected" : "") .
         ">$memberId : $name</option>\n";
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

  $banner = $S->getBanner("<h1>Post Updated to Program Assignment</h1>");

  echo <<<EOF
$S->top
$banner

EOF;

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
      $S->query("select concat(FName, ' ', LName) as membername from rotarymembers where id='$id'");
      $row = $S->fetchrow();
      extract($row);
      break;
  }

  $emailsubject = stripslashes($subject);

  if(!$S->isAdmin($S->id)) {
    if($status == "can-not") {
      $message = "$membername: talk on $date.";
      mail("bartonphillips@gmail.com", "Meetings: Can Not", $message, 'from: info@granbyrotary.org');
      echo <<<EOF
<h3>An Email has been sent informing the club that you CAN NOT do the talk.</h3>
<p>Please try to think of someone who can do the talk.</p>

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

  $subject = $S->escape($subject);

  // If Admins update the type and id

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

function PrintIt($S) {
  $T = new dbTables($S);
  
  function callback2(&$row, &$desc) {
     if($row['type'] == "business") {
      $row['name'] = "<span class='bis' style='border: 0'>Business Meeting</span>";
      if($row['subject'] == "") {
        $row['subject'] = "various";
      }
     }
     return false;
  }
    
  $query = "select * from meetings where date_add(date, interval 1 day) > now() order by date";
  $rowdesc = "<tr><td>date</td><td>name</td><td>subject</td></tr>";
  $table = $T->makeresultrows($query, $rowdesc, array('callback'=>'callback2'));
  
  echo <<<EOF
$S->top
<table id='assignments'>
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
