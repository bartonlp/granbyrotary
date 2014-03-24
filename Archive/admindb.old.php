<?php
// Administer the database
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

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
    list($x, $numrows) =  $S->query($query, true);
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

  $result = $S->query("select * from rotarymembers where id='$id'");
  $row = mysql_fetch_assoc($result);
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
  $h->title = "Database Admin -- Insert New Record";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<a href='$S->self'>Return to Admin Database</a><hr/>");

  // The fields are i_xxx
  $fields = "";
  $values = "";
  
  foreach($_POST as $key=>$value) {
    if(preg_match("/i_(.*)/", $key, $m)) {
      $fields .= "$m[1],";
      $values .= "'$value',";
    }
  }
  $fields .= "created,webadmin";
  $values .= "now(),'no'";

  $query = "insert into rotarymembers ($fields) values ($values)";
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

  $skip = "/^(id)|(last)|(visittime)|(created)$/"; // Skip these columns
  $specialFields = "/(status)|(otherclub)|(webadmin)/"; // these need options, below

  $options = array(status => array('active', 'inactive', 'visitor', 'honorary', 'otherclub', 'delete'),
                   otherclub => array('granby','grandlake', 'kremmling', 'winterpark', 'interact'),
                   webadmin => array('yes', 'no'));

  $id = $_GET['id'];

  $row = mysql_fetch_assoc($S->query("select * from rotarymembers where id='$id'"));

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

  $skip = "/^(id)|(last)|(visittime)|(created)|(visits)|(webadmin)$/"; // Skip these columns
  $specialFields = "/(status)|(otherclub)|(webadmin)/"; // these need options, below

  $options = array(status => array('active', 'inactive', 'visitor', 'honorary', 'otherclub', 'delete'),
                   otherclub => array('granby','grandlake', 'kremmling', 'winterpark', 'interact'),
                  );

  $id = 25; // use my id

  $row = mysql_fetch_assoc($S->query("select * from rotarymembers where id='$id'"));

  $tbl = "";

  foreach($row as $key=>$value) {
    if(preg_match($skip, $key)) { // Skip id, last, created. Don't edit them
      continue;
    }

    // check for special fields that need select options.

    if(preg_match($specialFields, $key, $m)) {
      $tbl .= "<tr><th>$key</th><td><select name='i_$key' />\n";

      $a = $options[$m[0]]; // $m[0] is the match from above. We can't use $m[1] etc as that would be the paren 1, 2, or 3

      for($i=0; $i < count($a); ++$i) {
        $tbl .= "<option value='$a[$i]'>$a[$i]</option>\n";
      }
      $tbl .= "</td><tr>\n";
    } else {
      $tbl .= "<tr><th>$key</th><td><input type='text' name='i_$key' /></td><tr>\n";
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
  $h->extra = <<<EOF
  <script type="text/javascript"
           src="/js/tablesorter/jquery.tablesorter.js"></script>
<!--  <script language="JavaScript" src="/test/jquery.horizontalTableAccordion.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="/test/jquery.horizontalTableAccordion.css" /> -->

  <script type="text/javascript">
jQuery(document).ready(function($) {
//  $("#datatable").addClass("horizontalTableAccordion");
//  $("#datatable tbody td:nth-child(2)").addClass("correct_text_spacing");
//  $("#datatable tbody td:nth-child(3)").addClass("correct_text_spacing");
//  $("#datatable").horizontalTableAccordion();

/*
  $(".FName").click(function() {
     var cl = $(this).parent().clone();
     $("#expand").html(cl).wrap("<table border='1'>");;
  });
*/
  // table sort
  $("#datatable").tablesorter();

  // Create a div for the table controls
  ctrls = $("<div id='ctrls'></div>").prependTo("#datadiv");
  // Add the controls: all, members, otherclubs, others
  ctrls.html("<input type='radio' id='all' name='ctrl' />All,\
<input type='radio' id='active' name='ctrl' checked />Members,\
<input type='radio' id='otherclub' name='ctrl' />Other Clubs,\
<input type='radio' id='others' name='ctrl' />Others");

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

  // Do whois on email address domain and also an mx lookup
  // Ajax
  $(".email").css({cursor: 'help'});
  $('<div id="whois" style="position: fixed; \
top: 10px; left: 150px; \
overflow: auto; padding: 10px; \
background-color: white; \
width: 80%; height: 400px; \
display: none;">').appendTo("body");
  
  $(".email").click(function() {
    var self = $(this);
    var email = self.text();
    $("body, .email").css({cursor: 'wait'});
    $.get("whois.ajax.php", {email: email}, function(d) {
      $("#whois").html("<pre>"+d+"</pre>").show();
      
      $("body").css({cursor: "default"});
      $(".email").css({cursor: 'help'});
    });
  });
  $("#whois").click(function() {
    $(this).hide();
  });
});
  </script>
  <style type='text/css'>
/* because this is a big wide table */
#datatable, #datatable * {
        border: 1px solid black;
}
.idfield, .idfield a {
        background-color: blue;
        color: white;
}

/* Table sorter */
table.tablesorter thead tr .header {
        background-image: url(/images/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        padding-right: 20px;
}
table.tablesorter thead tr .headerSortUp {
        background-image: url(/images/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
        background-image: url(/images/desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
}

  </style>
EOF;

  $h->title = "Database Admin";
  $h->banner = "<h2>Database Administration</h2>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr/>");

  $results = $S->query("select * from rotarymembers order by LName");

  $head = true;
  $tbl = "<table id='datatable' class='tablesorter' style='font-size: xx-small'>\n<thead>\n";

  while($row = mysql_fetch_assoc($results)) {
    $tbl .= "<tr>\n";
    if($head) {
      $keys = array_keys($row);
      foreach($keys as $key) {
        $tbl .= "<th>$key</th>";
      }
      $tbl .= <<<EOF
</tr>
</thead>
<tbody>
<tr>
EOF;

      $head = false;
    }

    foreach($row as $key=>$value) {
      switch($key) {
        case 'id':
          $tbl .= "<td class='idfield'><a href='$S->self?page=edit&id=$value'>$value</a></td>\n";
          break;
        case 'Email':
          $tbl .= "<td class='email'>$value</td>\n";
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
$tbl
</div>
<br/>
<a href="$S->self?page=add">Add New Member</a><br/>
<a href="$S->self?page=expunge">Expunge members with status DELETE</a> You will be given a chance to view items before deleting<br>
$footer
EOF;
}
?>
