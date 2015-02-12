<?php
// Administer the articles and rssfeeds tables
require_once("/var/www/includes/siteautoload.class.php");

$S = new GranbyRotary;

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

$footer = $S->getFooter();

$self = $_SERVER['PHP_SELF'];

if($_POST['id']) {
  // Update database

  $id = $_POST['id'];
  $S->query("select name, articleInclude, expired, pageorder from articles where id='$id'");
  
  $row = $S->fetchrow('assoc');
  $set = "";
  
  foreach($row as $key=>$value) {
    if(preg_match("/^expired$/", $key)) {
      if($_POST[never]) {
        $_POST[$key] = "2020-01-01 00:00:00";
      }
      if($_POST[$key] == '') {
         $set .= "$key=NULL, ";
      } else {
         $set .= "$key='$_POST[$key]', ";
      }
    } else {
       $set .= "$key='" . $S->escape($_POST[$key]) . "', ";
    }
  }

  $set = rtrim($set, ', ');

  $h->title = "Admin Articles";
  $h->banner = "<h3>Articles Table Updated</h3>";

  $top = $S->getPageTop($h);

  $query = "update articles set $set where id='$id'";

  $S->query($query);

  echo <<<EOF
$top
<script type='text/javascript'>
  setTimeout(function() { location.href='$self'; }, 2000);
</script>  

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

EOF;

  $h->title = "Admin Articles";
  $h->banner =  "<h1>Article Admin</h1>";
  $top = $S->getPageTop($h);

  echo <<<EOF
$top
<form action='$self' method='post'>
  <table border='1'>

EOF;

  $options = array('rss', 'article', 'both', );
  
  $S->query("select name, articleInclude, expired, pageorder from articles where id='$id'");

  $row = $S->fetchrow('assoc');

  $row = stripSlashesDeep($row);

  foreach($row as $key=>$value) {
    if("articleInclude" == $key) {
      print("<tr><th>$key</th><td><select name='$key' />\n");
      $a = $options;
      for($i=0; $i < count($a); ++$i) {
        print("<option value='$a[$i]'" .
        $sel = (($a[$i] == $value) ? " selected" : "") . ">$a[$i]</option>\n");
      }
      print("</td><tr>\n");
    } elseif("expired" == $key) {
      echo <<<EOF
<tr><th>$key</th><td><input type='text' class='date_input' name='$key' value='$value' />
Never<input type="checkbox" name="never"/></td><tr>

EOF;

    } else {
      $value = htmlentities($value, ENT_QUOTES);
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
  $h->extra = <<<EOF
<script type="text/javascript">
jQuery(document).ready(function($) {
  // create the control div and put button in it for all or current
  // feeds
  $("<div id='ctrl'>Show \
    <button id='all'>All</button>\
    <button id='curr'>Current</button></div>").prependTo("#articlediv");
  // start expired hidden
  $(".old").parent().hide();
  $("#curr").hide();
  // check buttons clicked
  $("#ctrl button").click(function(e) {
    if(e.target.id == "all") {
      $(".old").parent().show();
      $(this).hide();
      $("#curr").show();
    } else {
      $(".old").parent().hide();
      $(this).hide();
      $("#all").show();
    }
  });
});
</script>
<style type="text/css">
#articleTbl {
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
.old {
   color: red;
}
</style>

EOF;

  $h->title = "Admin Articles";
  $h->banner = "<h1>Article Admin</h1>";
  
  $top = $S->getPageTop(array($h));

  echo <<<EOF
$top
<div id="articlediv">
<table border='1' id="articleTbl">
<thead>
<tr><th>Id</th><th>Name</th><th>Include</th><th>Expired</th><th>Page Order</th></tr>
</thead>
<tbody>

EOF;

  $S->query("select id, (expired < now()) as old, name, articleInclude, expired, pageorder ".
            "from articles");
  
  while($row = $S->fetchrow('assoc')) {
    extract(stripSlashesDeep($row));
    $oldClass = "";
    if($old) {
      $oldClass = "class='old'";
    }

    $never = "";
    if($expired == "2020-01-01 00:00:00") {
      $expired = "NEVER";
      $never = " style='color: blue'";
    }
  
    echo <<<EOF
<tr><td class="idfield"><a style="border:0" href="$self?id=$id">$id</a></td><td>$name</td>
<td>$articleInclude</td><td $never$oldClass>$expired</td><td>$pageorder</td></tr>

EOF;
  }

  echo <<<EOF
</tbody>
</table>
</div>

EOF;
}

echo <<<EOF
<hr/>
<div>
<a href="/articles/adminrss.php">Rss Admin</a><br/>
<a href="/articles/createarticle.php">Create Article</a><br/>
<a href="/articles/createarticle.php?page=edit">Edit Article</a><br/>
<a href="/admindb.php">Database Admin</a><br/>
</div>
$footer
EOF;
  
?>

