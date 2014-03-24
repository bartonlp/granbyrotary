<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

if(!$S->GrIsMember) {
  $errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

  echo <<<EOF
$errorhdr
<body>
<h1>Sorry This Is Just For Members</h1>
<a href="/login.php">Login</a><br>
<a href="/index.php">Return to Welcome Page</a>
</body>
</html>
EOF;

  exit();
}

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case 'POST':
    switch($_POST['page']) {
    }        
    break;
  default:
    throw(new Exception("POST invalid page: {$_POST['page']}"));
    break;
    
  case 'GET':
    switch($_GET['page']) {
      case '1': // Top Frame
        frame1($S);
        break;
      case '2':
        // ajax
        // starttime=$starttime&makeupdate=$makeupdate");
        
        extract($_GET);

        //echo "makeupdate=$makeupdate, starttime=$starttime<br>member=$S->id, insert id=$id";
    
        if($starttime) {
          $S->query("insert into makeup (memberid, makeupdate, starttime) values('$S->id', '$makeupdate', '$starttime')");

          $id = mysql_insert_id();
          
          $S->query("update rotarymembers set makeupid = concat(makeupid, '$id,') where id='$S->id'");
          
        } elseif($endtime) {
          $ids = $S->GrMakeupid; // this is posibely a list like 10,11,12 etc.
          
          $S->query("update makeup set endtime='$endtime' where id in($ids) ".
                    "and memberid='$S->id' and  makeupdate='$makeupdate' and endtime is null");
        } else {
          // ERROR
          throw(new Exception("Not starttime or endtime"));
        }
        break;

      case '3':
        // show the selected makeup page
        showmakeup($S);
        break;

      case 'startover': // fall into default
      default:
        $makeupdate = $_GET['makeupdate'];

        // Check to see if there is already a makeup for this date.
        
        list($result, $n) = $S->query("select * from makeup where memberid='$S->id' and makeupdate='$makeupdate'", true);

        if($n) { // Yes there is a makeup already!
          if($_GET['page'] == 'startover') {
            // If startover selected from below then delete previous makeup
          
            $row = mysql_fetch_assoc($result);
            $id = $row['id'];
            $S->query("delete from makeup where id='$id'");
            $S->query("delete from makeuppages where makeupid_fk='$id'");
          } else {
            // Already a makeup for this date
            // Give member the option to delete it and "startover"
            
            $h->titel = "Meeting Makeups";
            $h->banner = "<h1>You have already done a make up for this date: $makeupdate</h1>";

            $top = $S->getPageTop($h);
            $footer = $S->getFooter("<hr>");

            echo <<<EOF
$top
<p>You can start over if you like. <a href="$S->self?page=startover&makeupdate=$makeupdate">Start Over</a></p>
$footer
EOF;
          break;
          }
        }
        
        frameset($S);
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}
exit();

// ********************************************************************************
// Top Frame with stop and back

function frame1($S) {
  $starttime = date("Y-m-d H:i:s");

  $makeupdate = $_GET['makeupdate'];

  $h->extra = <<<EOF
  <script type="text/javascript">
jQuery(document).ready(function($) {
  $.get("$S->self?page=2&starttime=$starttime&makeupdate=$makeupdate");

/*
  window.onbeforeunload = function() {
    var t = new Date();
    var endtime = t.getFullYear()+"-"+(t.getMonth()+1)+"-"+t.getDate()+" "+t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
    $.get("$S->self?page=2&endtime="+endtime+"&makeupdate=$makeupdate");
  }
*/

  $("#closewindows").click(function() {
   parent.window.close();
  });

  $("#back").click(function() {
    history.back();
  });
});
  </script>
EOF;

  mail("bartonphillips@gmail.com", "Makeup for member: $S->id", "Member=$S->id, Makeupdate=$makeupdate, starttime=$starttime", "From: Makeup");
  
  $h->titel = "Meeting Makeup";
  $top = $S->getPageHead($h);

  echo <<<EOF
$top
<body style="background-color: pink">
<p><span style="font-size: 18pt">Close this window when finished:
<input id="closewindows" type="button" value="Close Window"></span>&nbsp;&nbsp;
To navigate back in the frame below: <input id="back" type="button" value="Back"/>.</p>
</body>
</html>
EOF;
}

// ********************************************************************************
// Start page. This is the default GET

function frameset($S) {
  $makeupdate = $_GET['makeupdate'];

  $script = <<<EOF
  <script type="text/javascript">
  window.onbeforeunload = function() {
    var t = new Date();
    var endtime = t.getFullYear()+"-"+(t.getMonth()+1)+"-"+t.getDate()+" "+t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
    location.href= "$S->self?page=2&endtime="+endtime+"&makeupdate=$makeupdate";
  }
   </script>
EOF;

  // page=1 is the top frame
  // page=3 is gets the page from the database
  
  echo <<<EOF
$script
<frameset name="frameset" rows="50px,*">
  <frame src="$S->self?page=1&makeupdate=$makeupdate" name="topframe"/>
  <frame src="$S->self?page=3&makeupdate=$makeupdate" name="bottomframe"/>
</frameset>
EOF;
}

// ********************************************************************************
// Show the makeup page from the database. This is page=3

function showmakeup($S) {
  $makeupdate = $_GET['makeupdate'];

  //echo "makeupdate=$makeupdate<br>";
  
  list($result, $n) = $S->query("select id, message from makeupinfo where makeupdate='$makeupdate'", true);

  if(!$n) {
    $message = "<h2>No Records Found for $makeupdate</h2>";
  } else {
    $row = mysql_fetch_assoc($result);
    extract($row);
  }

  $top = $S->getPageTop("<h1>Makeup Page</h1>");
  $footer = $S->getFooter("<hr>");
  
  echo <<<EOF
$top
<h2>Makeup for Wednesday $makeupdate</h2>
$message
<h3>You can review all of the past meetings at the <a href="/pastmeetings.php">Past Meetings Page</a>.</h3>
$footer
EOF;

}
?>
