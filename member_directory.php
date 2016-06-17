<?php
//BLP 2015-02-22 -- changed to use symlink email.php (/var/www/html/email.php)

$_site = require_once("/var/www/includes/siteautoload.class.php");

$S = new $_site['className']($_site);

$h->script = <<<EOF
  <script>
$(document).ready(function() {
  // put select boxes in

  if($("#welcomemember").length) {
    $("#topbox").append("<div>Select: <span id='selectAll' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNone' class='select' "+
      "style='color: blue;'>None</span></div>");

    $("#activediv").append("Select: <span id='selectAllMem' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNoneMem' class='select' "+
      "style='color: blue;'>None</span>");

    $("#inactivediv").append("Select: <span id='selectAllInact' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNoneInact' class='select' "+
      "style='color: blue;'>None</span>");

    $("#visitordiv").append("Select: <span id='selectAllVis' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNoneVis' class='select' "+
      "style='color: blue;'>None</span>");

    $("#honorarydiv").append("Select: <span id='selectAllHon' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNoneHon' class='select' "+
      "style='color: blue;'>None</span>");

    $("#otherclubdiv").append("Select: <span id='selectAllOther' class='select' "+
      "style='color: blue;'>All</span>, <span id='selectNoneOther' class='select' "+
      "style='color: blue;'>None</span>");

    $(".select").css("cursor", "pointer");

    $("#selectAll").click( function() {
      $("input[type='checkbox']").attr("checked","true");
    });

    $("#selectNone").click( function() {
      $("input[type='checkbox']").removeAttr("checked");
    });

    // Add or remove members from individual catagouries
  
    $("#selectAllMem").click( function() {
      // 'this' is the span we clicked
      // The parent is the 'p' the span is in.
      // next gets us to the 'p' with the input
      // Then we do what we did above with all.
      $(this).parent().next().find("input[type='checkbox']").attr("checked","true");
    });

    $("#selectNoneMem").click( function() {
      $(this).parent().next().find("input[type='checkbox']").removeAttr("checked");
    });

    $("#selectAllInact").click( function() {
      $(this).parent().next().find("input[type='checkbox']").attr("checked","true");
    });
    $("#selectNoneInact").click( function() {
      $(this).parent().next().find("input[type='checkbox']").removeAttr("checked");
    });
  
    $("#selectAllHon").click( function() {
      $(this).parent().next().find("input[type='checkbox']").attr("checked","true");
    });
    $("#selectNoneHon").click( function() {
      $(this).parent().next().find("input[type='checkbox']").removeAttr("checked");
    });
  
    $("#selectAllVis").click( function() {
      $(this).parent().next().find("input[type='checkbox']").attr("checked","true");
    });
    $("#selectNoneVis").click( function() {
      $(this).parent().next().find("input[type='checkbox']").removeAttr("checked");
    });
  
    $("#selectAllOther").click( function() {
      $(this).parent().next().find("input[type='checkbox']").attr("checked","true");
    });
    $("#selectNoneOther").click( function() {
      $(this).parent().next().find("input[type='checkbox']").removeAttr("checked");
    });
  }

  $("#child").hide();
  
  $("#parent").toggle(function() {
    $("#child").show();
  }, function() {
    $("#child").hide();
  });

  $("form").append("<input style='position: fixed; top: 10px; right: 20px;' id='#sendemails' type='submit' value='Compose Email'>");
});
  </script>
EOF;

$h->css =<<<EOF
  <style>
#loginMsg {
        text-align: center;
}
#loginMsg a {
        font-variant: small-caps;
        font-size: x-large;
}
table {
        width: 100%;
}
input[type='submit'] {
  font-size: 1em;
  padding: .5em;
  border-radius: 1em;
}
@media (max-width: 820px) {
        form > table td, form > table th { display: none; }
        table td:first-child, td table td:nth-child(2),
        table th:first-child {
          display: table-cell;
        }
}
  </style>
EOF;

$h->title = "Rotary Club of Granby CO. Members Page";
$h->banner = "<h2><i>All-Club</i> Grand County Rotary Members</h2>";

list($top, $footer) = $S->getPageTopBottom($h);

// Check if we have a user ID. If yes then let this user edit his
// contact information.

if($S->id != 0) {
  $greet = <<<EOF
<div id='welcomemember' style='width: 400px; margin-left: auto; margin-right: auto; text-align: center;'>
<p>Welcome {$S->getUser()}</p>
</div>
<hr/>
EOF;
} else {
  // NO ID not can play!

  $greet = <<<EOF
<h3 id='loginMsg'>To use this feature you must login.<br>
If you are a Grand County Rotarian please
<a href='login.php?return=$S->self'>Login</a> at this time.<br/>
There is a lot more to see if you <a href='login.php?return=$S->self'>Login</a>!
</h3>
<p style='text-align: center'>Not a Grand County Rotarian?
You can <b>Register</b> as a visitor.
<a href="login.php?return=$S->self&page=visitor">Register</a></p>
<hr/>
EOF;

  echo <<<EOF
$top
$greet
$footer
EOF;
   exit();
}

if(!empty($S->id) && $S->isMember()) {
  $member = <<<EOF
<br>Or to send multiple emails check the names and use
the button at the bottom to submit the list.<br>
EOF;
}
  
// Start of Looping logic

// If a member allow both email and multimail. A member of Any of the local clubs

if(!empty($S->id) && $S->isMember()) {
   echo "<form action='/email.php' method='post'>\n";
   $extrathead = "<th>Address</th><th>Home Phone</th><th>Business Phone</th>".
                 "<th>Cell Phone</th>";
}

$activetitle = "Granby Rotary Active Members (%1):";
$activethead = "<tr><th>Name</th><th>Officer</th>$extrathead</tr>";

$inactivetitle = "Granby Rotary Inactive Members (%1) (information may be out of date):";
$inactivethead = "<tr><th>Name</th>$extrathead</tr>";

$honorarytitle = "Granby Honorary Members (%1) (information may be out of date):";
$honorarythead = "<tr><th>Name</th>$extrathead</tr>";

$visitortitle = "Granby Rotary Visitors (%1) (information may be out of date):";
$visitorthead = "<tr><th>Name</th>$extrathead<th>Club</th></tr>";

$othertitle = "Members From Grand Lake, Kremmling, and Winter Park/Fraser Clubs (%1) (information may be out of date):";
$otherthead = "<tr><th>Name</th>$extrathead<th>Club</th></tr>";

$group = array(active=>array(order=>"LName", title=>$activetitle, thead=>$activethead),
               inactive=>array(order=>"LName", title=>$inactivetitle, thead=>$inactivethead),
               honorary=>array(order=>"LName", title=>$honorarytitle, thead=>$honorarythead),
               visitor=>array(order=>"LName", title=>$visitortitle, thead=>$visitorthead),
               otherclub=>array(order=>"otherclub,LName", title=>$othertitle,
                                thead=>$otherthead)
              );

$rows = '';

foreach($group as $k=>$v) {
  $cnt = $S->query("select * from rotarymembers where status='$k' order by $v[order]");
  $title = preg_replace("/%1/", $cnt, $v[title]);
  $rows .= <<<EOF
<div id="${k}div">
<h2>$title</h2>
</div>
<table id="${k}table" border="1">
<thead>
$v[thead]
</thead>
<tbody>
EOF;

  $clubs = array(none=>"NONE", granby=>"Granby", winterpark=>"Winter Park",
                 kremmling=>"Kremmling", grandlake=>"Grand Lake");

  while($row = $S->fetchrow()) {
    foreach($row as $key=>$val) {
      $row[$key] = $val ? $val : "&nbsp;";
    }
    extract($row);

    $officerfield = '';
    $clubfield = '';

    switch($k) {
      case 'active':
        $officerfield = "<td>$office</td>";
        break;
      case 'visitor':
        $clubfield = "<td>$club</td>";
        break;
      case 'otherclub':
        if(empty($club)) {
          $club = $clubs[$otherclub];
        } 
        $clubfield = "<td>$club</td>";
        break;
    }

    $checkbox = <<<EOF
<td style='width: 50%'>
  <a href='email.php?id=$id&from=$S->id'>$FName $LName</a>
</td>
EOF;

    $extrainfo = '';

    if($S->isMember()) {
      if($Email != "&nbsp;") {
        $checkbox = <<<EOF
<td style='width: 20%'>
<table>
  <tr>
    <td style="width: 1px">
      <input type='checkbox' name='id[]' value='$id' />
    </td>
    <td>
      <a href='email.php?id=$id&from=$S->id'>$FName $LName</a>
    </td>
  </tr>
</table>
</td>

EOF;
      } else {
        // $Email is a &nbsp;
        $checkbox = <<<EOF
<td style='width: 20%'>
<table>
  <tr>
    <td style="width: 1px"><input type='checkbox' 'disabled'/></td>
    <td>$FName $LName<br>NO EMAIL ADDRESS</td>
  </tr>
</table>
</td>

EOF;
      }
      $extrainfo = "<td>$address</td><td>$hphone</td><td>$bphone</td><td>$cphone</td>";
    }  

    $rows .= <<<EOF
<tr>
$checkbox
${officerfield}
${extrainfo}
${clubfield}</tr>
EOF;
  }

  $rows .= <<<EOF
</tbody>
</table>
EOF;
}

$rows .= <<<EOF
<br/>
<input type='submit' value='Compose Email'>
<input type='hidden' name='from' value='$S->id'>
</form>
</p>

<h3><a href="showemailsent.php">Review Emails You Sent</a></h3>
EOF;

// Start of page

echo <<<EOF
$top
$greet
<p id="topbox" style='border: 1px solid black; padding: 15px; width: 50%; margin: auto auto;'>
<span style='color: red'>To send an email to the member click on his <b style='color: blue;'>Name</b></span>.
$member
</p><br style='clear: both'/>
<hr/>
$rows
<hr/>
$footer
EOF;

