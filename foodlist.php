<?php
// BLP 2014-08-25 -- remove qty etc.
// BLP 2014-08-21 -- Food list for fund raisers
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;
$S->t = new dbTables($S);

$S->h->title = "Food List";

/* table
 CREATE TABLE `foodlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food` varchar(255) NOT NULL,
  `packageqty` int(11) DEFAULT '1',
  `packageprice` decimal(10,2) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `food` (`food`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 
*/

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "GET":
    switch($_GET['page']) {
      case "edit":
        add($S);
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
      case "Post":
        post($S);
        break;
      default:
        throw(new Exception("default POST: {$_POST['page']}"));
    }
    break;    
  default:
    throw(new Exception("Not GET or POST"));
}

exit();

// Start page. Show existing table entries and menu

function startpage($S) {
  $S->h->banner = "<h1>Fund Raiser Food List</h1>";
  $S->h->extra = <<<EOF
<style>
#idnumber {
  background-color: blue;
  color: white;
}
#foodlist td, #foodlist th {
  padding: 5px;
}
#foodlist {
  background-color: white;
}
#foodlist td:first-child {
  background-color: blue;
  color: white;
  cursor: pointer;
}
#foodlist td:nth-child(4), #foodlist td:nth-child(3) {
  text-align: right;
}
</style>
<script>
jQuery(document).ready(function($) {
  // If we click on the id number then go to edit

  $("#foodlist td:first-child").click(function() {
    var id = $(this).text();
    location = "foodlist.php?page=edit&id="+id;
  });
});
</script>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($S->h);

  $sql = "select id as ID, food as Item, packageqty as 'Package Qty', ".
         "packageprice as 'Package Price', provider as Provider from foodlist";

  list($S->body) = $S->t->maketable($sql, array('attr'=>array('id'=>"foodlist", 'border'=>"1")));

  $S->body = <<<EOF
<p><b>Item</b> must be a unique name within the list<br>
<b>Package Qty</b> is the number of items in a <b>Package</b><br>
<b>Package Price</b> is the price of a <b>Package</b><br>
<b>Provider</b> is where the item was purchased last time<br>
</p>
<p>
<p>Click on 'ID' <span id='idnumber'>number</span> to edit item.</p>
$S->body
<ul>
<li><a href="$S->self?page=add">Add New Item</a></li>
<li><a href="eventplanner.php">Go To Event Planner</a></li>
</ul>
EOF;
  printit($S);
}

// Add/Edit
// Add new item or if id pressent then Edit

function add($S) {
  // If an id is pressent we do an edit.

  if($id = $_GET['id']) {
    // Edit
    $sql = "select food, packageqty, packageprice, provider from foodlist where id=$id";
    $S->query($sql);
    list($food, $packageqty, $packageprice, $provider) = $S->fetchrow('num');
    $S->h->banner = "<h1>Edit Item</h1>";
  } else {
    $S->h->banner = "<h1>Add New Item to List</h1>";
  }

  list($S->top, $S->footer) = $S->getPageTopBottom($S->h);
  $S->body = <<<EOF
<form method="post">
<table>
<tr><th>Item</th><td><input type="text" name="food" value="$food"/> * must be unique in database</td></tr>
<tr><th>Package Qty</th><td><input type="text" name="packageqty" value="$packageqty"/> Items in package</td></tr>
<tr><th>Package Price</th><td><input type="text" name="packageprice" value="$packageprice"/> Price per package</td></tr>
<tr><th>Vendor</th><td><input type="text" name="provider" value="$provider"/></td></tr>
</table>
<input type="hidden" name="id" value="$id"/>
<input type="submit" name="page" value="Post"/>
</form>
EOF;

  if($id) {
    $S->body .= <<<EOF
<br>
<p><a href="$S->self?page=expunge&id=$id">Delete This Item.</a>
NOTE: When you click this the item is GONE!</p>
EOF;
  }
  printit($S);
}

// Expunge. After expunge go back to the start page

function expunge($S) {
  $sql = "delete from foodlist where id={$_GET['id']}";
  $S->query($sql);
  startpage($S);
}

// Post. After the post go back to the start page

function post($S) {
  $S->h->banner = "<h1>POST</h1>";
  extract($_POST); 

  if($id) {
    $sql = "update foodlist set food='$food', packageqty='$packageqty', ".
           "packageprice='$packageprice', provider='$provider' ".
           "where id=$id";
  } else {
    $sql = "insert into foodlist (food, packageqty, packageprice, provider) ".
           "values('$food', '$packageqty', '$packageprice', '$provider')";
  }

  try {
    $S->query($sql);
  } catch(SqlException $e) {
    $S->h->banner = "<h1>Error: " . $e->getMessage() . "</h1>";
    if($e->getCode() == 1062) {
      $S->h->banner = "<h1>What</h1>";
      if(preg_match('~error=&quot;<i>(.*?)</i>&quot;,~s', $e->getMessage(), $m)) {
        $S->h->banner = "<h1>ERROR: $m[1]</h1>";
      }
    } else {
      $S->h->banner = "<h1>ERROR: $e->getMessage</h1>";
    }
    list($S->top, $S->footer) = $S->getPageTopBottom($S->h);
    printit($S);
    exit();
  }

  list($S->top, $S->footer) = $S->getPageTopBottom($S->h);
  startpage($S);
}

// Final print function

function printit($S) {
  echo <<<EOF
$S->top
$S->body
<hr>
$S->footer
EOF;
}

?>