<?php
// Administer the articles and rssfeeds tables

require_once("/var/www/includes/siteautoload.class.php");

$S = new GranbyRotary;

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

if($_POST['id']) {
  // Update database

  $id = $_POST['id'];
  $S->query("select rsstitle, rsslink, rssdesc, rssdate, date, created, expired, feedorder from rssfeeds where id='$id'");

  $row = $S->fetchrow("assoc");
  $set = "";

  $_POST = stripslashesDeep($_POST);

  foreach($row as $key=>$value) {
    if(preg_match("/expired/", $key)) {
      if(preg_match("/now|today/i", $_POST[$key])) {
        $set .= "$key='" . date("Y-m-d H:i:s") . "', ";
      } elseif($_POST[$key] == '') {
        $set .= "$key=NULL, ";
      } else {
        $set .= "$key='$_POST[$key]', ";
      }
    } else {
      $set .= "$key='" . $S->escape($_POST[$key]) . "', ";
      //$set .= "$key='" . ($_POST[$key]) . "', ";
    }
  }

  $set = rtrim($set, ', ');
  $query = "update rssfeeds set $set where id='$id'";

  //echo "$query<br>";
  
  $S->query($query);

  $h->title = "Rss Feed Admin";
  $h->banner = "<h3>Rss Feeds Table Updated</h3>";
  $top = $S->getPageTop($h);

  echo <<<EOF
$top
<div>
<a href="$S->self">Return To &quot;Feed Admin&quot;</a>
</div>

EOF;

} elseif($_GET['id']) {
  $id = $_GET['id'];

  $h->extra = <<<EOF
<script src="/js/date_input/jquery.date_input.js"></script>
<link rel="stylesheet" href="/js/date_input/date_input.css" type="text/css">
<script>
jQuery($.date_input.initialize);
jQuery.extend(DateInput.DEFAULT_OPTS, {
  stringToDate: function(string) {
    var matches;
    if (matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
      return new Date(matches[1], matches[2] - 1, matches[3]);
    } else {
      return null;
    };
  },

  dateToString: function(date) {
    var month = (date.getMonth() + 1).toString();
    var dom = date.getDate().toString();
    if (month.length == 1) month = "0" + month;
    if (dom.length == 1) dom = "0" + dom;
    return date.getFullYear() + "-" + month + "-" + dom;
  }
});
</script>

<style type='text/css'>
table {
  background-color: white;
  width: 100%;
}

input[type=text], textarea {
  width: 99%;
}
</style>

EOF;

  $h->title = "Rss Feed Admin";
  $h->banner = "<h2>Modify Feed</h2>";
  
  $top = $S->getPageTop($h);

  echo <<<EOF
$top
<form action='adminrss.php' method='post'>
  <table border='1'>

EOF;

  $S->query("select rsstitle, rsslink, rssdesc, rssdate, date, created, expired, feedorder from rssfeeds where id='$id'");

  $row = $S->fetchrow('assoc');

  $row = stripSlashesDeep($row);

  foreach($row as $key=>$value) {
    $value = htmlentities($value, ENT_QUOTES);
    if(preg_match("/rssdesc/", $key)) {
      echo <<<EOF
<tr><th>$key</th><td><textarea name='$key' cols='100' rows='10'>$value</textarea></td><tr>
EOF;
    } elseif(preg_match("/expired/", $key)) {
      echo <<<EOF
<tr><th>$key</th><td><input type='text' class='date_input' name='$key' value='$value' /></td><tr>
EOF;
    } else {
      echo <<<EOF
<tr><th>$key</th><td><input type='text' name='$key' value='$value' /></td><tr>

EOF;
    }
   }
  echo <<<EOF
</table>
<input type='hidden' name='id' value='$id' />
<input type='submit' name='submit' value='Submit Change' />
</form>

EOF;

} else {
  $S->query("select now()");
  list($now) = $S->fetchrow('num');

  $h->extra = <<<EOF
<script>
jQuery(document).ready(function($) {
  // create the control div and put button in it for all or current
  // feeds
  $("<div id='ctrl'>Show \
    <button id='all'>All</button>\
    <button id='curr'>Current</button></div>").prependTo("#articlediv");

  // start expired hidden
  $(".expired").parent().hide();
  $("#curr").hide();
  // check buttons clicked
  $("#ctrl button").click(function(e) {
    if(e.target.id == "all") {
      $(".expired").parent().show();
      $(this).hide();
      $("#curr").show();
    } else {
      $(".expired").parent().hide();
      $(this).hide();
      $("#all").show();
    }
  });
});
</script>
<style type="text/css">
#articleTbl {
   font-size: 12px;
   border: 1px solid black;
   background-color: white;
}
#articleTbl * {
   border: 1px solid black;
   padding: 0 5px;
}
.idfield, .idfield a {
   background-color: blue;
   color: white;
}
</style>

EOF;

  $h->title = "Rss Feed Admin";
  $h->banner = "<h2>Select Feed</h2>";
  $top = $S->getPageTop($h);

  echo <<<EOF
$top
  <div id="articlediv">
<table id="articleTbl">
<tr><th>Id</th><th>rsstitle</th><th>rsslink</th><th>rssdesc</th><th>rssdate</th><th>Date</th><th>Created</th><th>Expired</th><th>Order</th></tr>

EOF;

  $S->query("select id, rsstitle, rsslink, rssdesc, rssdate, date, created, expired, feedorder ".
            "from rssfeeds");

  while($row = $S->fetchrow('assoc')) {
    extract(htmlencodeDeep($row));

    $style = "";

    if($expired < $now) {
      $style = " style='color: red' class='expired'";
    } elseif($expired == "2020-01-01 00:00:00") {
      $expired = "NEVER";
      $style = " style='color: blue'";
    }

    echo <<<EOF
<tr><td class="idfield"><a style="border:0" href="articles/adminrss.php?id=$id">$id</a></td><td>$rsstitle</td><td>$rsslink</td><td>$rssdesc</td><td>$rssdate</td><td>$date</td><td>$created</td><td$style>$expired</td><td>$feedorder</td></tr>

EOF;
  }

  echo <<<EOF
</table>
</div>

EOF;
}

echo <<<EOF
<hr/>
<div>
<a href="/articles/adminrss.php">Rss Admin</a><br/>
<a href="/articles/admin.php">Article Admin</a><br/>
<a href="/articles/createarticle.php">Create Article</a><br/>
<a href="/articles/createarticle.php?page=edit">Edit Article</a><br/>
<a href="/admindb.php">Database Admin</a><br/>
</div>
$footer
EOF;

// Functions
  
function htmlencode($text) {
   $t = preg_replace("/&lt;/sm", "&amp;lt;", $text);
   $t = preg_replace("/&gt;/sm", "&amp;gt;", $t);
   $t = preg_replace("/<(.*?)>/sm", "&lt;$1&gt;", $t);
   return $t;
}

function htmlencodeDeep($value) {
  $value = is_array($value) ? array_map('htmlencodeDeep', $value) : htmlencode($value); 
  return $value;
}

function removeCrDeep($value) {
  $value = is_array($value) ? array_map('removeCrDeep', $value) : preg_replace("/\r/", "", $value); 
  return $value;
}

?>