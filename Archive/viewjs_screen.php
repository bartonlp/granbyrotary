<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new Granbyrotary(false); // Don't do any counters

$errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

if(!$S->isAdmin($S->id)) {
  echo <<<EOF
$errorhdr
<body>
<h1>Sorry This Is Just For Designated Admin Members</h1>
</body>
</html>
EOF;
  exit();
}

$h->extra = <<<EOF
  <link rel="stylesheet"  href="/css/tablesorter.css" type="text/css" />
  <script type="text/javascript"
         src="/js/tablesorter/jquery.tablesorter.js"></script>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  // Add a screen size parser. The looks at hor*vert
  // add the new parser to tablesorter
  $.tablesorter.addParser({
		id: "xsize",
		is: function(s,table) {
      return /^\d+x\d+$/.test(s); // a screen size has 1234x123 etc. The 'x' seperates the two sizes.
		},
		format: function(s) {
      var x = s.split("x");
			return $.tablesorter.formatInt(x[0]*x[1]);
		},
		type: "numeric"
	});

  $("table").addClass('tablesorter');
  $("table").tablesorter(); // the "is" in the new parser causes this the select xsize for the appropriate rows.
  
  $("body").append("<div id='popup' style='position: absolute; background-color: white; \
border: 4px solid black; padding: 5px; display: none'></div>");

  var membercache = new Array;
  var x = $("#screensize").offset().left;
  x += $("#screensize").width() + 50;

  $("#screensize tbody .id, #hasjs tbody .id").hover(function(e) {
    var y = $(this).offset().top;
    var tr = $(this).parent();
    var id = $(".id", tr).text();
    if(id != 0) {
      id = id.replace(/\**(\d)/, '$1');
      if(!membercache[id]) {
        $("body").css("cursor", "wait");
        $.get("getNameById.ajax.php", { id: id }, function(data) {
          membercache[id] = data;
          $("body").css("cursor", "default");
          $("#popup").text(data).css({ top: y, left: x }).show();
        });
      } else {
        $("#popup").text(membercache[id]).css({ top: y, left: x }).show();
      }
    } else {
      $("#popup").text("NON MEMBER").css({ top: y, left: x }).show();
    }
  }, function() {
    $("#popup").hide();
  });

  $(".ip").hover(function() {
    $(this).css("cursor", "pointer");
  }, function() {
    $(this).css("cursor", "default");
  });

  $(".ip").click(function() {
    var self = $(this).parent(); // this is the TR
    var ip = $(".ip", self).text();
    var agent = $(".agent", self).text();
    //alert("IP="+ip+" Agent="+agent);
    $("body").css("cursor", "wait");
    $.get("insertintobots.ajax.php", {ip: ip, agent: agent}, function(data) {
      if(data != "OK") {
        alert("ERROR adding ip via insertintobots.ajax.php: NOT OK: "+data);
        $("body").css("cursor", "default");
      } else {
        //alert("OK: "+ip);
        $(".ip:contains(" + ip + ")").parent().hide();
        $("body").css("cursor", "default");
      }
    });
  });

  // Add select ALL/NONE
  $("#selectbuttons").html("<div><button id='selectall'>Select All</button> " +
                           "<button id='selectnone' style='display: none'>Select None</button> " +
                           "<button id='selectnonmembers'>Select Non-members</button> " +
                           "<button id='selectnojava'>Select Non-Javascript</button></div>");

  function selectnone() {
    $("#selectall").hide();
    $("#selectnone").show();
  }

  $("#selectall").click(function() {
    $("#jsform input[type='checkbox']").attr("checked", "checked");
    selectnone();
    return false;
  });
  $("#selectnone").click(function() {
    $("#jsform input[type='checkbox']").removeAttr("checked");
    $(this).hide();
    $("#selectall").show();
    return false;
  });
  $("#selectnonmembers").click(function() {
    $("#jsform .id").each(function() {
      if($(this).text() == "0") {
        $(this).parent().find("input[type='checkbox']").attr("checked", "checked");
      }
    });
    selectnone();
    return false;
  });
  $("#selectnojava").click(function() {
    $("#jsform .jcount").each(function() {
      if($(this).text() == "0") {
        $(this).parent().find("input[type='checkbox']").attr("checked", "checked");
      }
    });
    selectnone();
    return false;
  });

  $("body").append("<div id='whois' style='position: absolute; background-color: white; \
border: 4px solid black; padding: 5px; display: none; overflow: auto; width: 50%'></div>");

  $(".agent").click(function() {
    var h = $(window).height() - 30;
    $("#whois").css("height", h +"px");
    var y = $("html").scrollTop() + 10;
    // Work around for Chrome which does not seem to understand the above line but rather needs "body". But Firefox doesn't
    // understand "body" but rather "html" -- darned inconsistencies!
    if(y==10) {
      y = $("body").scrollTop()+10;
    }
    var x = $(this).offset().left;
    $("body").css("cursor", "wait");
    // get ip address and do whois
    var ip = $(this).parent().find(".ip").text();
    $.get("whois.ajax.php", {ip: ip}, function(data) {
      $("#whois").html(data).css({ top: y, left: x}).show();
      $("body").css("cursor", "default");
    });
  });
  $("#whois").click(function() {
    $(this).hide();
  });
});
  </script>
  <style type="text/css">
table {
  background-color: white;
}
#hitCountertbl {
  display: none;
}
  </style>
EOF;

$h->title = "Show hasjs and screensize";
$h->banner =  "<h1>Show Hasjs and Screensize</h1>";

$h->nohasjs = true;
$b->ctrmsg =  "<h4>This Page Not Counted</h4>";

list($top, $footer) = $S->getPageTopBottom($h, $b);

// page = del-nonjavascript

if($_POST['page'] == "del-nonjavascript") {
  list($x, $n) = $S->query("delete from hasjs where jcount='0'", true);
  $deleted = "<h2>$n Non-Javascript entries deleted from database</h2>\n";
}

// 'del' is the checkbox array set by the <form>

if($del = $_POST['del']) {
  // Delete checked
  foreach($del as $id) {
    $id = preg_replace("/>|</", '', $id);
    try {
      $S->query("delete from hasjs where id='$id'");
    } catch(SqlException $e) {
      echo "ERROR $id NOT DELETED<br>";
      thow($e);
    }
  }
  $deleted = "<h2>Items Deleted</h2>";
}

function callbackhasjs(&$row, &$desc) {
  global $S;

  // Check the ip against the logagent table to see if ip belongs to a member.
  // First check if we already have a memberid
  if($row['memberid']) return false;
  list($result, $num) = $S->query("select id from logagent where ip='{$row['ip']}' and id!='0'", true);

  if(!$num) return false;
  list($id) = mysql_fetch_row($result);
  $row['memberid'] = "*$id";
  return false;
}

if($show = $_POST['show']) {
  $showinfo = "";
  if($show['js']) {
    $showinfo .= "where jcount != 0";
  }
  if(!$show['all']) {
    if(!$showinfo) {
      $showinfo = "where lasttime > curdate()";
    } else {
      $showinfo .= " and lasttime > curdate()";
    }  
  }
} else {
//  $showinfo = "where lasttime > curdate()";
}

$showonly = <<<EOF
<div>
<form action="$S->self" method="post">
<table border="1">
<tr><th>SHOW</th><th>Select</th></tr>
<tr><th>Only JS</th><td>Yes <input type="checkbox" name="show[js]]" value="yesjs"></td></tr>
<tr><th>All Records</th><td>Yes <input type="checkbox" name="show[all]" value="yseall"><br>
<tr><th colspan="2">$showinfo</th></tr>
<tr><th colspan="2"><input type="submit" value="submit"></th></tr>
</table>
</form>
</div>
<br>
EOF;
    
$hasjstbl = $S->makeresultrows("select * from hasjs $showinfo order by lasttime desc",
                               "<tr><td><input type='checkbox' name='del[]' value='>id<'/><td>id</td>".
                               "<td class='ip'>ip</td><td class='id'>memberid</th>".
                               "<td class='agent'>agent</td><td>filename</td><td>count</td>".
                               "<td class='jcount'>jcount</td>".
                               "<td>lasttime</td></tr>", array(callback=>callbackhasjs));

$sizetbl = $S->makeresultrows("select * from screensize where id!='25' and lasttime > date_sub(curdate(), interval 4 day) ".
                              "order by lasttime desc",
                              "<tr><td class='id'>id</td><td>size</td><td>count</td><td>lasttime</td></tr>");

//list($summary) = $S->maketable("select size as Size, sum(count) as Count from screensize where id!='25'
//group by size order by Count desc", array(attr=>array(border=>"1")));

// Another summary sorted by the x part of size XxY
list($result, $n) = $S->query("select size, sum(count) as count from screensize where id !='25' group by size", true);

if($n) {
  $totalcount = 0;
  while($row = mysql_fetch_assoc($result)) {
    extract($row);
    $totalcount += $count;
    $ar[$size] = $count;
  }
  // now sort by key using the x part of size.
  function sortcallback($a, $b) {
    $aa = explode("x", $a);
    $bb = explode("x", $b);
    return ($bb[0]*$bb[1]) - ($aa[0]*$aa[1]);
  }

  uksort($ar, sortcallback);

  $gt1024x768 = 0;
  
  foreach($ar as $k=>$v) {
    $x = explode("x", $k);
    $s = $x[0] * $x[1];
    //echo "s=$s : $v<br>";
    
    if($s >=  786432 /* 1024 * 768 */) {
      $gt1024x768 += $v;
    }
    $summary .= "<tr><td>$k</td><td>$v</td></tr>\n";
  }
}

$percent = number_format($gt1024x768 / $totalcount * 100);
$gt1024x768 = number_format($gt1024x768);
$totalcount = number_format($totalcount);

// Sort order for sortcallback for these three examples
// 1366x768 =1049088
// 1024x1024=1048576
// 1360x768 =1044480
// 1024x768 = 786432

echo <<<EOF
$top
<h2>Table hasjs</h2>
$deleted
$showonly
<form action="$S->self" method="post">
Delete all Non-Javascript items in database: <input type="submit" value="Delete Now"/>
<input type="hidden" name="page" value="del-nonjavascript"/>
</form>

<form id="jsform" action="$S->self" method="post">
<p>Check Items To Delete</p>
<p>Click the IP Address to ADD the Item to the Bots Table</p>
<p>Click on the Agent to see the <i>whois</i> for the IP</p>
<p>Count is the number of times the item has been seen. JsCount is the number of times counted by the Javascript Ajax program.
The JsCount indicates that the client browser had Javascripts enabled. Note that Robots do not use Javascript!</p>
<p>LastTime is San Diego time which is an hour earlier than Mountain time.</p>
<p>An astrix (*) before the GrId means the id was gleaned from the ip address which corresponded to an id in the logagent table.</p>
<div id="selectbuttons"></div>
<table id="hasjs" border="1">
<thead>
<tr><th>DEL</th><th>ID</th><th>IP</th><th>GrId</th><th>Agent</th><th>Filename</th><th>Count</th><th>JsCount</th><th>LastTime</th></tr>
</thead>
<tbody>
$hasjstbl
</tbody>
</table>
<input type="submit" name="delete" value="Delete" />
</form>
<h2>Table screensize</h2>
<p>Screen size is captured by a Javascript Ajax program. Only the last five days are shown.</p>
<table id="screensize" border="1">
<thead>
<tr><th>ID</th><th>Size</th><th>Count</th><th>LastTime</th></tr>
</thead>
<tbody>
$sizetbl
</tbody>
</table>
<h2>Summary of Screen Size</h2>
<p>There are $totalcount accesses and $gt1024x768 are greater than or equal to 78,6432 sq. pixels (1024x768).
That is $percent% of the accesses are by systems with screen size greater than or equal to 78,6432 sq. pixels.
Nowadays most of the screen sizes less than 800x600 are from cell phones.</p>
<p>The 'Size' column is sorted on the product of the horizontal size times the vertical size.</p>
<table id="screensizetbl" border="1">
<thead>
<tr><th>Size</th><th>Count</th></tr>
</thead>
<tbody>
$summary
</tbody>
</table>
<hr/>
$footer
EOF;

?>
