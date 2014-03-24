<?php
// Administer the members database
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$errorhdr = <<<EOF
<!DOCTYPE HTML>
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

// SWITCH

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "GET":
    switch($_GET['page']) {
      case "edit":
        edit($S);
        break;
      case "add":
        add($S);
        break;
        case "expunge";
        expunge($S);
        break;
      default:
        startpage($S);
        break;
    }
    break;
  case "POST":
    switch($_POST['page']) {
      case "post":
        post($S);
        break;
      case "expunge2":
        expunge2($S);
        break;
      case "insert":
        insert($S);
        break;
      default:
        throw(new Exception("default POST"));
    }
    break;    
  default:
    throw(new Exception("Not GET or POST"));
}

// ********************************************************************************
// GET: Expunge deleted items. View items first

function expunge($S) {
  $h->title = "View Items to Expunge";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  $query = "select id, concat(LName, ' ', FName) as Name, Email, otherclub from rotarymembers where status='delete'";

  // NOTE by using >< I will get '>id<' in the box array. I could change the delimiter but then I would need
  // to have each of the other row items like '%Name%' for example and that seems like more work.
  // I will just cull the >< in expunge2() instead.
  
  $rowdesc = <<<EOF
<tr>
<td><input type="checkbox" "checked" name="box[]" value=">id<"/></td>
<td>Name</td><td>Email</td><td>otherclub</td>
</tr>
EOF;
  $tblrows = $S->makeresultrows($query, $rowdesc);

  if($tblrows === false) {
    echo <<<EOF
$top
<h2>No Items Found</h2>
$footer
EOF;
    exit();
  }

  echo <<<EOF
$top
<form action="$S->self" method="post">
<table border="1">
<thead>
<tr><th>Select</th><th>Name</th><th>Email</th><th>Club</th></tr>
</thead>
<tbody>
$tblrows
</tbody>
</table>
<input type="submit" value="Expunge Checked Items"/>
<input type="hidden" name="page" value="expunge2"/>
</form>
<br>
$footer
EOF;

}

// ********************************************************************************
// POST: Expunge page 2

function expunge2($S) {
  $h->title = "Expunge";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  extract($_POST);

  // NOTE: $box has each id as '>id<' so I have to strip off the >< stuff.

  if($box) {
    // First turn the array $box into a comma seperated strin line ">1<,>2<..."
    $ids = implode(",", $box);
    // then remove the delimiting ">..<" from the list so we would have "1,2..."
    $ids = preg_replace("/>(\d+)</", "$1", $ids);
    //echo "ids=$ids<br>\n";
    
    $query = "delete from rotarymembers where id in($ids)";
    //echo "query=$query<br>\n";

    // Get the number of rows involved. $x is result which is just a dummy here.
    $numrows =  $S->query($query);
    $msg = "<h2>$numrows Items Deleted</h2>";
  } else {
    $msg = "<h2>No items checked</h2>\n<input type='submit' value='Try again' onclick='history.go(-1)'/>";
  }
  echo <<<EOF
$top
$msg
$footer
EOF;
}

// ********************************************************************************
// Post edited data

function post($S) {  
  $h->title = "Database Admin -- POST";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  // Update database
  $id = $_POST['id'];

  $S->query("select * from rotarymembers where id='$id'");
  $row = $S->fetchrow('assoc');
  $skip = "/^(id)|(last)|(visittime)|(created)$/"; // Don't update these columns

  $set = "";

  foreach($row as $key=>$value) {
    if(preg_match($skip, $key)) { // Skip id, last, created
      continue;
    }
    if(empty($_POST[$key])) { // Skip any columns that are empty
      continue;
    }
    // If the column has new data 
    $set .= "$key='$_POST[$key]', ";
  }

  $set = rtrim($set, ', ');
  $query = "update rotarymembers set $set where id='$id'";
  //echo "QUERY: $query<br>\n";
  $S->query($query);

  header("refresh:3;url=$S->self"); // Go back to admin page ASAP

  echo <<<EOF
$top
<h2>Database Updated</h2>
<p>You will automatically return to the Database Admin Page in 3 seconds.</p>
$footer
EOF;
}

// ********************************************************************************

function insert($S) {
  // remove the extra post items 'page' and 'submit' the rest are all keys.
  unset($_POST['page'], $_POST['submit']);

  $h->title = "Database Admin -- Insert New Record";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  // The fields are i_xxx
  $fields = "";
  $values = "";
  
  foreach($_POST as $key=>$value) {
    $fields .= "$key,";
    $values .= "'$value',";
  }
  $fields .= "created,webadmin";
  $values .= "now(),'no'";

  $query = "insert into rotarymembers ($fields) values ($values)";
  //echo "$query<br>";
  $S->query($query);
  
  echo <<<EOF
$top
<h2>New Member Added</h2>
$footer
EOF;
}

// ********************************************************************************

function edit($S) {
  $h->title = "Database Admin -- EDIT";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  $dateitems = "/^(bday)|(rotanniv)|(anniv)/";
  $skip = "/^(id)|(last)|(visittime)|(created)|(visits)$/"; // Skip these columns
  $specialFields = "/(status)|(otherclub)|(webadmin)/"; // these need options, below

  $options = array('status'=>array('active', 'inactive', 'visitor', 'honorary', 'otherclub', 'delete'),
                   'otherclub'=>array('granby','grandlake', 'kremmling', 'winterpark', 'interact'),
                   'webadmin'=>array('yes', 'no'));

  $id = $_GET['id'];
  
  $S->query("select * from rotarymembers where id='$id'");
  $row = $S->fetchrow('assoc');

  $tbl = "";

  foreach($row as $key=>$value) {
    if(preg_match($skip, $key)) { // Skip id, last, created. Don't edit them
      continue;
    }

    // check for special fields that need select options.

    if(preg_match($specialFields, $key, $m)) {
      $tbl .= "<tr><th>$key</th><td><select name='$key' />\n";

      $a = $options[$m[0]]; // $m[0] is the match from above. We can't use $m[1] etc as that would be the paren 1, 2, or 3

      for($i=0; $i < count($a); ++$i) {
        $tbl .= "<option value='$a[$i]'" . (($a[$i] == $value) ? " selected" : "") . ">$a[$i]</option>\n";
      }
      $tbl .= "</td><tr>\n";
    } elseif(preg_match($dateitems, $key, $m)) {
      $tbl .= "<tr><th>$key (YYYY-MM-DD)</th><td><input type='text' name-'$key' value='$value' /></td></tr>\n";
    } else {
      $tbl .= "<tr><th>$key</th><td><input type='text' name='$key' value='$value' /></td><tr>\n";
    }
  }

  echo <<<EOF
$top
<form action='$S->self' method='post'>
<table border='1'>
$tbl
</table>
<input type='hidden' name='id' value='$id' />
<input type='hidden' name='page' value='post' />
<input type='submit' name='submit' value='Submit Change' />
</form>
<br/>
$footer
EOF;
}

// ********************************************************************************

function add($S) {
  $h->title = "Database Admin -- Add New Member";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  $dateitems = "/^(bday)|(rotanniv)|(anniv)/";
  $skip = "/^(id)|(last)|(visittime)|(created)|(visits)$/"; // Skip these columns
  $specialFields = "/(status)|(otherclub)|(webadmin)/"; // these need options, below

  $options = array('status'=>array('active', 'inactive', 'visitor', 'honorary', 'otherclub', 'delete'),
                   'otherclub'=>array('granby','grandlake', 'kremmling', 'winterpark', 'interact'),
                   'webadmin'=>array('no', 'yes'));

  $id = 25; // use my id
  $S->query("select * from rotarymembers where id='$id'");
  $row = $S->fetchrow('assoc');

  $tbl = "";

  foreach($row as $key=>$value) { // we don't use value just key
    if(preg_match($skip, $key)) { // Skip id, last, created. Don't edit them
      continue;
    }

    // check for special fields that need select options.

    if(preg_match($specialFields, $key, $m)) {
      $tbl .= "<tr><th>$key</th><td><select name='$key' />\n";

      $a = $options[$m[0]]; // $m[0] is the match from above.
                            // We can't use $m[1] etc as that would be the paren 1, 2, or 3

      for($i=0; $i < count($a); ++$i) {
        $tbl .= "<option value='$a[$i]'>$a[$i]</option>\n";
      }
      $tbl .= "</td><tr>\n";
    } elseif(preg_match($dateitems, $key, $m)) {
      $tbl .= "<tr><th>$key (YYYY-MM-DD)</th><td><input type='text' name='$key' /></td></tr>\n";
    } else {
      $tbl .= "<tr><th>$key</th><td><input type='text' name='$key' /></td><tr>\n";
    }
  }

  echo <<<EOF
$top
<form action='$S->self' method='post'>
<table border='1'>
$tbl
</table>
<input type='hidden' name='page' value='insert' />
<input type='submit' name='submit' value='Submit' />
</form>
<br/>
$footer
EOF;
}

// ********************************************************************************

function startpage($S) {
  // This is START page so show all database info
  $h->link =<<<EOF
<style>
#datatable {
  font-size: 18px;
  border-collapse: collapse;
  background: white;
  width: 4000px;
}
#datatable th:nth-child(1), #datatable td:nth-child(1) {
  position: absolute;
  width: 50px;
  left: 11px;
  top: auto;
}

#datatable th:nth-child(2), #datatable td:nth-child(2)
{
  position: absolute;
  width: 95px;
  left: 66px;
  top: auto;
}
#datatable th:nth-child(3), #datatable td:nth-child(3) {
  position: absolute;
  width: 95px;
  left: 166px;
  top: auto;
}

#datatable th, #datatable td {
  padding: 0 2px 0 2px;
}
#datatable th {
  background: yellow;
}
#tbldiv {
  overflow-x:scroll;  
  margin-left: 255px;
  overflow-y:visible;
}
#bigdiv {
  border: 1px solid black;
  border-top: 5px solid #333;
}
tbody .id, tbody .id a {
  background-color: blue;
  color: white;
}
#datatable.tablesorter thead tr .headerSortDown, #datatable.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
}
</style>
EOF;

  $h->extra = <<<EOF
  <script src="js/tablesorter/jquery.tablesorter.js"></script>
  <script>
jQuery(document).ready(function($) {
  var maxWidth = 30; // this is percent
  var clx = '';
  var hdr = '';

/*
  $("#datatable td:nth-child(2)").each(function(i, v) {
    var h = $(v).prop("offsetHeight");
    $(v).siblings().eq(0).attr("height", (h-2)+"px");
  });
*/

  // table sort

  $("#datatable").tablesorter({handlertype: "click"}); // Make double click the binding

  // Create a div for the table controls

  ctrls = $("<div id='ctrls'></div>").prependTo("#datadiv");

  // Add the controls: all, members, otherclubs, others

  ctrls.html("Click header cell to sort column</p>"+
             "<p><input type='radio' id='all' name='ctrl' />All,"+
             "<input type='radio' id='active' name='ctrl' checked />Members,"+
             "<input type='radio' id='otherclub' name='ctrl' />Other Clubs,"+
             "<input type='radio' id='others' name='ctrl' />Others");

  // Start with only members showing

  $("#datadiv td.status").filter(function() {
    return ($(this).text() != 'active');
  }).parent().hide();

  // Now for each type show and hide.
  // For all we just show everything

  $("#all").change(function() {
    $("#datadiv tr").show();
  });

  // For the rest we show the type and hide others

  $("#active").change(function() {
    $("#datadiv td.status").each(function(index, item) {
      var txt = $(this).text();
      if(txt == 'active') 
        $(this).parent().show();
      else
        $(this).parent().hide();
    });
  });

  $("#otherclub").change(function() {
    $("#datadiv td.status").each(function(index, item) {
      var txt = $(this).text();
      if(txt == 'otherclub') 
        $(this).parent().show();
      else
        $(this).parent().hide();
    });
  });

  $("#others").change(function() {
    $("#datadiv td.status").each(function(index, item) {
      var txt = $(this).text();
      if(txt == 'active' || txt == 'otherclub') 
        $(this).parent().hide();
      else
        $(this).parent().show();
    });
  });
});
  </script>
EOF;

  $h->title = "Database Admin";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr/>");

  $S->query("select * from rotarymembers order by LName");

  $head = true;
  $tbl = "<table id='datatable' border='1' class='tbl tablesorter'>\n<thead>\n";

  while($row = $S->fetchrow('assoc')) {
    $tbl .= "<tr>\n";
    if($head) {
      $keys = array_keys($row);
      foreach($keys as $key) {
        $tbl .= "<th class='$key'>$key</th>";
      }
      $tbl .= <<<EOF
</tr>
<tr>
</thead>
<tbody>
EOF;

      $head = false;
    }

    foreach($row as $key=>$value) {
      switch($key) {
        case 'id':
          $tbl .= "<td class='id'><a href='$S->self?page=edit&id=$value'>$value</a></td>\n";
          break;
        default:
          $tbl .= "<td class='$key'>$value</td>\n";
          break;
      }
    }
    $tbl .= "</tr>\n";
  }
  $tbl .= "</tbody>\n</table>";

  echo <<<EOF
$top
<!--<div id="expand"></div>-->


<div id='datadiv'>
<div id="bigdiv">
<div id="tbldiv">
$tbl
</div>
</div>
</div>

<br/>
<a href="$S->self?page=add">Add New Member</a><br/>
<a href="$S->self?page=expunge">Expunge members with status DELETE</a> You will be given a chance to view items before deleting<br>
$footer
EOF;
}
?>
