<?php
// Administer the members database
// BLP 2014-08-03 -- empty columns should be posted as they my be removing something. Also added
// new logic for ctrls input selection. Add PRINT function.

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
    // BLP 2014-08-03 -- Empty columns should be posted 
    //if(empty($_POST[$key])) { // Skip any columns that are empty
    //  continue;
    //}
    
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
  $fields .= "created";
  $values .= "now()";

  //echo "fields: $fields<br>values: $values<br>";
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
  $admindbspecialcss = preg_replace("~\n~", '\\n', file_get_contents("css/admindb.css"));
  
  $h->link =<<<EOF
<style>
#datatable {
  font-size: 12px;
  border-collapse: collapse;
  background: white;
  width: 4000px;
}
#datatable th, #datatable td {
  padding: 0 2px 0 2px;
}
#datatable th {
  background: yellow;
}
tbody .id, tbody .id a {
  background-color: blue;
  color: white;
}
#datatable.tablesorter thead tr .headerSortDown, #datatable.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
}
#print button {
  background-color: pink;
  border-radius: 10px;
  width: 100px;
  height: 30px;
  margin-bottom: 10px;
  font-size: 20px;
}
</style>

<style id="stylespecial">
</style>
EOF;

  $h->extra = <<<EOF
  <script src="js/tablesorter/jquery.tablesorter.js"></script>
  <script>
jQuery(document).ready(function($) {
  var admindbspecialcss = '$admindbspecialcss';
  var maxWidth = 30; // this is percent
  var clx = '';
  var hdr = '';

  // table sort

  $("#datatable").tablesorter({handlertype: "click"}); 

  // BLP 2014-08-03 -- PRINT

  $("<div id='print'><button>PRINT</button></div>").prependTo("#datadiv");

  $("#stylespecial").html(admindbspecialcss);

  $("#print").click(function() {
    var name = $("#ctrls input:checked").val();
    var printtbl = '<!DOCTYPE HTML><html>'+
                   '<head>'+
                   '<title>Members Database</title>'+
                   '<style>td, th {padding: 2px 5px; font-size: 10px;}'+
                   '.page-break {page-break-before: always;}</style>'+
// This next line has to have the / seperated from the script. If not then the browser thinks it is
// the end of a script tag and gets all screwed up!
                   '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">'+
                   '</'+'script>'+
                   '<script src="splitForPrint.js"><'+'/'+'script>'+
                   '</head>'+
                   '<body><h1>Granby Rotary Database</h1>'+
                   '<h2>Showing '+name+'</h2>'+
                   '<div class="report_data">'+
                   '<table class="splitForPrint" border="1"><thead><tr>';

    // table items to print
    var fields = new Array("FName", "LName", "office", "Email", "address",
                           "hphone", "bphone", "cphone", "bday", "club", "classif", "spouse");
    // header
    for(var x in fields) {
      printtbl += "<th>"+fields[x]+"</th>";
    }
    printtbl += "</tr></thead></tbody>";

    // body
    $("#tbldiv tbody tr:visible").each(function() {
      printtbl += "<tr>";
      for(var x in fields) {
        var y = $("td."+fields[x], this);
        printtbl += "<td>"+y.text()+"</td>";
      }
      printtbl += "</tr>";
    });
    printtbl += "</tbody></table></div>"+
                "</body></html>";

    // This is a real clug. I wanted to open a pop up and write the printtbl into it but the pop up
    // doesn't seem to run the javascript.
    // SO I use a ajax to write printtbl to a file then open adminprint.php which loads the file I
    // saved with printtbl in it and renders the page. It works but it isn't what I wanted.

    $.ajax({
      url: 'adminprint.php',
      data: { page: 'ajax', data: printtbl },
      type: 'post',
      success: function(d) {
        console.log("success: ", d);
        // Pop up print window
        var dd = new Date().getTime();
//        location = "adminprint.php?t="+dd;
        var w = window.open("adminprint.php?t="+dd);
        w.print();
//        w.close();
        return false;
      },
      error: function(e) {
        console.log("error: ", e);
      }
    });
  });

  // Create a div for the table controls at the beginning of the datadiv (the outer most div)

  var ctrls = $("<div id='ctrls'></div>").prependTo("#datadiv");

  // Add the controls: all, members, otherclubs, others

  ctrls.html("Click header cell to sort column"+
             "<p>"+
             "<input type='radio' id='all' name='ctrl' value='All' />All,"+
             "<input type='radio' id='active' name='ctrl' value='Members' checked />Members,"+
             "<input type='radio' id='otherclub' name='ctrl' value='Other Clubs' />Other Clubs,"+
             "<input type='radio' id='others' name='ctrl' value='Others'/>Others<br>"+
             "<input type='checkbox' id='expand'>Expand</input>"+
             "</p>").prependTo("#datadiv");

  $("#expand").change(function() {
    if(!this.flag) {
      $("#stylespecial").html('');
    } else {
      $("#stylespecial").html(admindbspecialcss);
    }
    this.flag = !this.flag;
  });

  // Start with only members showing
  // If the filter function return true then hide the parent. In this case if the status text in
  // the table (<div id='tbldiv'>$tbl) does not equal 'active' hide the parent.

  $("#datadiv td.status").filter(function() { // the table inside the datadiv, bigdiv, tbldiv
    return ($(this).text() != 'active');
  }).parent().hide();

  // BLP 2014-08-03 -- new logic
  // Now for each type show and hide.
  // For all we just show everything.
  // For the rest we show the type and hide others
  // The radio buttons are: All (id=all),Members (id=active),Other Clubs (id=otherclub),Others
  // (id=others)
  // td.status is 'active', 'inactive', 'otherclub' or (others which can be 1. 'inactive', 2.
  // 'visitor', 3. 'interact'). So 'active' is for our club while 'otherclub' is
  // one of the other Rotary clubs in Grand County. Others are 'inactive', 'visitor' or 'interact'.
  // NOTE: 'inactive' can be an inactive Granby or otherclub member.

  $("#ctrls input").change(function() { // When one of the ctrls inputs changes
    var id = $(this).attr('id'); // id is 'all', 'active', 'otherclub' or 'others'
    //console.log(id);
    switch(id) {
      case 'all':
        $("#datadiv tr").show();
        break;
      case 'active':
      case 'otherclub':
        // For 'active' or 'otherclub' grab the status from the table.
        $("#datadiv td.status").each(function() {
          var txt = $(this).text(); 
          if(txt == id) // active or otherclub
            $(this).parent().show();
          else
            $(this).parent().hide(); // every one else.
        });
        break;
      case 'others':
        $("#datadiv td.status").each(function(index, item) {
          var txt = $(this).text();
          if(txt == 'active' || txt == 'otherclub') 
            $(this).parent().hide();
          else
            $(this).parent().show(); // what is left is Others.
        });
        break;
    }
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
