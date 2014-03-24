<?php
//   $SQLDEBUG = true;
//   $ERRORDEBUG = true;

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

require_once("/home/bartonlp/includes/email.class.php");

$header = <<<EOF
<p style="border: 1px solid black; display: table-cell; padding: 15px">
<span style="color: red">To send an email to the member click on his
<b style="color: blue;">Name</b></span>.
EOF;

$formemail = <<<EOF
<br/>Or to send multiple emails check the names and use<br/>
the button at the bottom to submit the list.<br/>
Select: <span id="selectAll" class="select" style="color: blue; display: none">All</span>,
<span id="selectNone" class="select" style="color: blue; display: none">None</span>
</p>
EOF;

$sender = array('id'=>$S->id, 'name'=>"$S->fname $S->lname", 'email'=>$S->email);

$e = new Email($S, "tmptable", $sender); // siteclass and member table name

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case "multiemailform":
        multiEmail($S, $e);
        break;
      case "multiemailsend":
        multiEmailSend($S, $e);
        break;
      case "singleemailsend":
        singleEmailSend($S, $e);
        break;
      default:
        throw(new Exception("default POST"));
    }
    break;
   
  case "GET":
    switch($_GET['page']) {
      case "singleemailform":
        singleEmail($S, $e);
        break;
      default:
        startpage($S, $e);
        break;
    }
    break;

  default:
    throw(new Exception("Not GET or POST"));
}

// ********************************************************************************

function startpage($S, $e) {
  maketemptable($S);
  
  $h->title = "Club Presidents Email";
//  $h->banner = "<h1>Test</h1>";
  $h->extra = <<<EOF
  <script type='text/javascript'>
jQuery(document).ready(function($) {
  $(".select").show();
  
  $("#selectAll").click( function() {
    $("input[type='checkbox']").attr("checked","true");
  });
  $("#selectNone").click( function() {
    $("input[type='checkbox']").removeAttr("checked");
  });
});
  </script>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr/>");

  if($S->id) {
    $query = "select id, concat(fname, ' ', lname) as Name, clubname as Club, hphone as 'Home Phone', ".
             "bphone as 'Busyness Phone', cphone as 'Cell Phone' from tmptable order by lname";
    
    $page =  $e->displayMembers($query, "$header$formeamil<h2>District Club Presidents</h2>", true); // display form
  } else {
    $query = "select id, concat(fname, ' ', lname) as Name, clubname as Club from tmptable order by lname";
    $page = $e->displayMembers($query, "$header<h2>District Club Presidents</h2>");
  }
  echo <<<EOF
$top
$page
$footer
EOF;
}

// ********************************************************************************

function singleEmail($S, $e) {
  $h->title = "Club Presidents Single Email";
  $h->banner = "<h1>Email Club President</h1>";
  $h->extra = <<<EOF
  <style type="text/css">
.mailforminputtext {
  width: 100%;
}
  </style>
EOF;

  $S->top = $S->getPageTop($h);
  $S->footer = $S->getFooter("<hr/>");

  // This takes an array of fields. The required fields are 'name, email' use as to come up with the right names if not in
  // the table.
  
  $e->singleEmailForm();
}

// ********************************************************************************

function singleEmailSend($S, $e) {
  $h->title = "Send Single Email";
  $h->banner = "<h1>Mail Sent</h1>";

  $S->top = $S->getPageTop($h);
  $S->footer = $S->getFooter("<hr/>");

  $mailFooter = "\n\n--\nGranby Rotary\nhttp://www.granbyrotary.org\n";

  $e->singleEmailSend($mailFooter, "clubofficersemail.php single");

  $S->query("drop table tmptable");
}

// ********************************************************************************

function multiEmail($S, $e) {
  $h->title = "Multi Email";
  $h->banner = "<h1>Multi Email</h1>";
  $h->extra = <<<EOF
  <style type="text/css">
.mailforminputtext {
  width: 100%;
}
  </style>
EOF;

  $S->top = $S->getPageTop($h);
  $S->footer = $S->getFooter("<hr/>");

  // This takes an array of fields. The required fields are 'name, email' use as to come up with the right names if not in
  // the table.
  
  $e->multiEmailForm();
}

// ********************************************************************************

function multiEmailSend($S, $e) {
  $h->title = "Email Sent";
  $h->banner = "<h1>Mail Sent</h1>";

  $S->top = $S->getPageTop($h);
  $S->footer = $S->getFooter();

  $mailFooter = "\n\n--\nGranby Rotary\nhttp://www.granbyrotary.org\n";

  $e->multiEmailSend($mailFooter, "clubofficersemail.php multi");

  $S->query("drop table tmptable");
}

// ********************************************************************************
// Make the temp table we will use

function maketemptable($S) {
  // select must have id, Name and Email the rest is optional!

  $S->query("drop table if exists tmptable");

  $query = <<<EOF
create table tmptable ( 
  id int(11) not null auto_increment,
  fname varchar(50),
  lname varchar(50),
  email varchar(255),
  hphone varchar(20),
  bphone varchar(20),
  cphone varchar(20),
  clubname varchar(255),
  primary key(id)
);
EOF;

  $S->query($query);

  $query = "select clubname, pname, pemail, phphone, pbphone, ".
           "pcphone from clubofficers";


  list($result, $n) = $S->query($query, true);

  if(!$n) throw(new Exception("No records"));

  while($row = mysql_fetch_assoc($result)) {
    $row = mysqlEscapeDeep($S->db, $row);
    //vardump($row, "deep");
    extract($row);

    // From name make fname and lname
    if(preg_match("/^(.*?)\s+(.*?)(?:\s+(.*?))?$/", $pname, $m)) {
      if($m[3]) {
        // is it like fff m. lll or is it fff fff lll
        if(preg_match("/^.\.$/", $m[2])) {
          // This is a middle inital so trash it
          $fname = $m[1];
          $lname = $m[3];
        } else {
          // This is a double first name like "Marry Ann"
          $fname = "$m[1] $m[2]";
          $lname = $m[3];
        }
      } else {
        $fname = $m[1];
        $lname = $m[2];
      }
    } else {
      throw(new Exception("did not find a match in pname=$pname"));
    }

    $S->query("insert into tmptable (fname, lname, email, hphone, bphone, cphone, clubname) ".
              "values('$fname', '$lname', '$pemail', '$phphone', '$pbphone', '$pcphone', '$clubname')");
  }
}
?>

