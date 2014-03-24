<?php
// Test of timing members and frame set
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

//echo "id=$S->id<br>";

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
</body>
</html>
EOF;

  exit();
}

if($_GET['ajax']) {
  header("Content-type: text/html");
  
  list($result, $n) = $S->query("select * from makeup where memberid='$S->id'", true);
  if($n) {
    $ret = "<ul>";
    while($row = mysql_fetch_assoc($result)) {
      extract($row);
      $ret .= "<li>Makeup date=$makeupdate, starttime=$starttime, endtime=$endtime</li>";
    }
    $ret .= "</ul>";
  } else {
    $ret = "No Records found";
  }
  echo "$ret";
  exit();
}

frame1($S);

function frame1($S) {
  $h->title = "Meeting Makeup Page";
  $h->banner = "<h1>Meeting Makeup</h1>";
  $h->extra = <<<EOF
   <script type="text/javascript">
jQuery(document).ready(function($) {
  var newwin;
  
  $("#show").click(function() {
    if(newwin != null && !newwin.closed) {
      alert("window still open");
    } else {
      $.get("$S->self?ajax=1", function(data) {
        $("#box").html(data);
      });
    }
  });
  $("form").submit(function() {
    var params  = 'width='+screen.width;
    params += ', height='+screen.height;
    params += ', top=0, left=0';
    params += ', toolbar=0';
    params += ', status=1';
//    params += ', location=1';
    params += ', fullscreen=1';
    var makeupdate = $("#makeupdate").val();

    newwin = window.open("/makeup.php?makeupdate="+ makeupdate,'makeup', params);
    if(window.focus) { newwin.focus() }
    newwin.creator = self;
    return false;
  })
});
   </script>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr>");
  list($result, $n) = $S->query("select * from makeupinfo", true);
  if(!$n) {
    echo "NO RECORDS";
    exit;
  }
  $options = "";
  while($row = mysql_fetch_assoc($result)) {
    extract($row);
    $options .= "<option value='$makeupdate'>$makeupdate</option>\n";
  }
  
  echo <<<EOF
$top
<p>If you have missed a meeting you can now makeup that meeting by using this web page. Once you have completed the makeup
an E-mail will be sent to the secretary. If you did not miss a meeting you can still use this page to review what went on
at a given meeting. You will not receive extra credit but you might find the experience interesting.</p>

<form method="post">
<table border="1">
<tr><td style="padding: 4px">Select Makeup date:</td><td style="padding: 4px"><select id="makeupdate" name="makeupdate">
$options
</select></td></tr>
<tr><th colspan="2">
<input id="open" type="submit" value="Start Makeup Session"/></th></tr>
</table>
<br><br>
<input id="show" type="button" value="Show Meetings I have already made up"/>
<input type="hidden" name="page" value="start"/>
<div id="box"></div>
</form>
$footer
EOF;
}

?>