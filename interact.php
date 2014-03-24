<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$h->title = "Interact Club";
$h->banner = "<h1>The Interact Club Page</h1>";
$h->extra = <<<EOF
  <script type="text/javascript">
jQuery(document).ready(function($) {
  var auto = 1;

  $("#form").after("<input type='button' id='render' style='display: none' value='Quick Preview'/>" +
                   "<input type='button' id='autopreview' value='Stop Auto Preview' />");

  $("#form").after("<p>Quick Preview</p><div style='padding: 5px; border: 1px solid black' id='quickpreview'>");
  $("#quickpreview").html($("#form textarea").val());

  $("#autopreview").click(function() {
    if(auto) {
      $(this).val("Start Auto Preview");
      $("#render").show();
      auto = 0;
    } else {
      $(this).val("Stop Auto Preview");
      $("#render").hide();
      $("#render").click();
      auto = 1;
    }
  });

  $("#render").click(function() {
    $("#quickpreview").html($("#form textarea").val());
  });

  $("#form textarea").keyup(function() {
    if(!auto) return false;

    $("#quickpreview").html($("#form textarea").val());
  });
});
  </script>
EOF;

list($S->top, $S->footer) = $S->getPageTopBottom($h, "<hr>");

// Who can change the 'info' section of this page:
// Sam, Brooke, Arielle Steinberg, Elly Zietz, Audra Lorton, Me
$S->interactAdmins = array(146, 152, 156, 162, 163, 25);

// Only I can do the Update Area!

if($S->id == ID_BARTON) {
  // !!!!! READ THIS !!!!!
  // NOTE we can't use $S->self in the 'Update Page' or 'Show Update Areas'
  // because the showupdateareas.php would see $S->self as showupdateareas and the page would fail.
  $blp = <<<EOF
<li><a href="interact.php?page=admin">Update Page</a>
EOF;
}

if(in_array($S->id, $S->interactAdmins)) {
  // !!!!! READ THIS !!!!!
  // NOTE we can't use $S->self in the 'Update Page' or 'Show Update Areas'
  // because the showupdateareas.php would see $S->self as showupdateareas and the page would fail.
  
  $S->adminmsg = <<<EOF
<div style="border: 1px solid black; margin: auto; width: 50%; background-color: pink; padding: 5px;">
<h3>$S->fname $S->lname, you are an admin</h3>
<ul>
$blp
<li><a href="showupdateareas.php?page=interact.php">Show Update Areas</a>
<li><a href="interact.php?page=updatemessage">Update Message Info</a>
</ul>
</div>
EOF;

  //require_once("includes/updatesite.class.php");

  $s->siteclass = $S;
  $s->site = "granbyrotary.org";
  $s->page = "interact.php";
  $s->itemname ="info";
  
  $u = new UpdateSite($s); // Should do this outside of the START comments

// START UpdateSite info
$item = $u->getItem();
// END UpdateSite info

  // If item is false then no item in table

  if($item !== false) {
    $S->msg = <<<EOF
<div>
<h2>{$item['title']}</h2>
<div>{$item['bodytext']}</div>
<p class="itemdate">Created: {$item['date']}</p>
</div>
<hr/>
EOF;
  }
}

// Get the POST variables and GET variables

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'postmessage':
        postmessage($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      case 'updatemessage':
        // called for admin message at top of page or
        // from select() 
        updatemessage($S);
        break;
      case 'admin':
        admin($S);
        break;
      case 'select':
        select($S);
        break;
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

// ---------------------------------------------------------------------------
/*
CREATE TABLE `interact` (
  `id` int(11) NOT NULL auto_increment,
  `message` text,
  `creatorId` int(11) default NULL,
  `created` datetime default NULL,
  `lasttime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `editorId` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
*/

function start($S) {
  $n = $S->query("select * from interact where id='1'");

  if(!$n) {
    $event = "<h2>No Records Found</h2>";
  } else {
    $row = $S->fetchrow();
    extract($row);

    $info = $message;
  }

  echo <<<EOF
$S->top
$S->adminmsg
<!-- Start UpdateSite: Info Message -->
$S->msg
<!-- UpdateSite: Info Message End -->
$info
$S->footer
EOF;
}

// ---------------------------------------------------------------------------

function admin($S) {
  $errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

  if($S->id != ID_BARTON) {
    echo <<<EOF
$errorhdr
<body>
<h1>Sorry This Is Just For Webmaster</h1>
</body>
</html>
EOF;
    exit();
  }

  $h->title = "Update Site Admin for Granby Rotary Interact Club";
  $h->banner = "<h1>Update Site Admin For Granby Rotary Interact Club</h1>";
  $h->nofooter = true;
  $footer = $S->getFooter("<hr/>");
  
  //require_once("includes/updatesite.class.php");

  $page = UpdateSite::firstHalf($S, $h);

  echo <<<EOF
$page
<br>
<a href="updatesiteadmin.php">Administer Update Site Table</a><br/>
$footer
EOF;
}

// ---------------------------------------------------------------------------
// Update the message area (Info)
// $_GET['msgid'] will be set if we are coming from select()

function updatemessage($S) {
  $msgid = ($_GET['msgid']) ? $_GET['msgid'] : '1';
  
  $n = $S->query("select message from interact where id='$msgid'");

  if($n) {
    list($message) = $S->fetchrow();
  }
  
  echo <<<EOF
$S->top
<h3>Enter message information</h3>
<p>You can edit the existing text or delete it all and start from scratch or you can
<a href="$S->self?page=select">select</a> an old version to edit.</p>
<form id="form" action="$S->self" method="post">
<textarea name="message" rows="10" style="width: 100%">$message</textarea>
<input type="submit" value="Submit Message"/>
<input type="hidden" name="page" value="postmessage"/>
</form>
<p><a href="$S->self?page=select">Select</a> a previous message to edit.</p>
$S->footer
EOF;
}

// ---------------------------------------------------------------------------

function select($S) {
  $n = $S->query("select * from interact where id!='1'");
  if(!$n) {
    echo "No items to select";
    exit();
  }
  
  while($row = $S->fetchrow()) {
    extract($row);
    $item .= "<tr><td><a href='$S->self?page=updatemessage&msgid=$id'>$id</a></td><td>$message</td></tr>\n";
  }
  echo <<<EOF
$S->top
<table border="1" style="width: 100%">
<thead>
<tr><th>Select Id</th><th style="width: 100%">Message</th></tr>
</thead>
<tbody>
$item
</tbody>
</table>
$S->footer
EOF;
}

//  ---------------------------------------------------------------------------

function postmessage($S) {
  $message = $_POST['message'];

  // copy the old message to a new id
  
  $n = $S->query("insert into interact (message, creatorId, created) ".
                                "select message, creatorId, created from interact where id='1'");
  if(!$n) {
    throw(new Exception("interact.php: postmessage insert, no records inserted"));
  }

  // Get the new ID
  
  $iid = $S->getInsertId();

  //echo "iid=$iid<br>";

  // Now update the editorId in the new record
  
  $n = $S->query("update interact set editorId='$S->id' where id='$iid'");

  if(!$n) {
    throw(new Exception("interact.php: postmessage update editorId, no records updated"));
  }
  
  $message = $S->escape($message);

  // Write the new record to position ONE
  $n = $S->query("update interact set message='$message', creatorId='$S->id', created=now() ".
                                "where id='1'");

  if(!$n) {
    throw(new Exception("interact.php: postmessage update, no records updated"));
  }

  echo <<<EOF
$S->top
<h2>Recoed Saved</h2>
<a href="$S->self">Return to Interact Page</a>
$S->footer
EOF;
  
}

?>