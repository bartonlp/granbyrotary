<?php
// Create an article

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;  // not header etc.

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

$self = $_SERVER['PHP_SELF'];
$footer = $S->getFooter();

if(count($_GET) == 0 && count($_POST) == 0) {
  Start($S, $self);
} elseif($rssid = $_GET['rssid']) {
  // NOTE check for rssid before id as the Preview passes both id and
  // rssid
  Post($S, $self, $_GET['id'], $rssid);
} elseif($_POST['Submit']) {
  Preview($S, $self, $id, $rssid);
} elseif($id = $_GET['id']) {
  // If we didn't see rssid then look for id  
  Edit($S, $self, $id);
}

echo <<<EOF
<hr/>
<div>
<a href="/articles/admin.php">Article Admin</a><br/>
<a href="/articles/adminrss.php">Rss Admin</a><br/>
<a href="/articles/createarticle.php">Create Article</a><br/>
<a href="/articles/editarticle.php">Edit Article</a><br/>
<a href="/admin/admin.php">Database Admin</a><br/>
</div>
<hr/>
$footer
EOF;

exit();

// stat by displaying the available articles and letting user select
// the on to edit.

function Start($S, $self) {
  $extra = <<<EOF
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
.old {
   color: red;
}
</style>

EOF;

  $top = $S->getPageTop(array(title=>"Edit News Article", extra=>$extra), "<h1>Edit an Article</h1>");

  echo <<<EOF
$top
<div id="articlediv">
<p>Select article to edit by clicking on the ID</p>
<table id="select" style="background-color: white;" border="1">
<tr><th>Id</th><th>Article Name</th><th>Expired</th></tr>

EOF;

  $results = $S->query("select (expired < now()) as old, id, name, expired from articles");

  while($row = mysql_fetch_assoc($results)) {
    extract($row);
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
<tr><td><a href="$self?id=$id">$id</a></td><td>$name</td><td $never$oldClass>$expired</td></tr>

EOF;
  }

  echo <<<EOF
</table>
</div>
EOF;
}

// Edit the article

function Edit($S, $self, $id) {
  $extra = <<<EOF
<style type='text/css'>
div {
   width: 100%;
}
#formTbl {
   width: 100%;
}
#formTbl th {
   vertical-align: top;
}
#form textarea {
   width: 100%;
}

</style>

EOF;

  $top = $S->getPageTop(array(title=>"Edit News Article", extra=>$extra), "<h1>Edit an Article</h1>");

  // Get article from database.
  // Show the article in the database and select the one to edit.

  $results = $S->query("select name, articletemplate, rssfeedId_fk, rssfeed, article, header from articles where id='$id'");

  $row = mysql_fetch_assoc($results);

  extract($row);

  // Present form to fill out on initial contact  


  echo <<<EOF
$top
<p>Edit the article and click Submit to preview your edits.</p>
<div>

<form id='form' action="$self" method='post'>
<table id='formTbl'>
   <tr><th>Article Name</th><td><input type='text' name='articleName' value="$name"/></td></tr>
   <tr><th>Article Template</th><td><input type='text' name='articleTemplate value="$articletemplate"'/></td></tr>
   <tr><th>Article Body</th><td><textarea id='body' name='articleBody' cols='100' rows='20'>$rssfeed\n$article</textarea></td></tr>
   <tr><th>Article Header</th><td><textarea id='headerarea' name='articleHeader' cols='100' rows='5'>$header</textarea></td></tr>
</table>
   <input type='submit' name='Submit' value='Submit'/>
   <input type='hidden' name='id' value="$id" />
   <input type='hidden' name='rssid' value="$rssfeedId_fk"/>
</form>
</div>
</body>
</html>

EOF;
}  

// Preview the article

function Preview($S, $self, $id, $rssid) {
  extract(stripSlashesDeep($_POST));
  
  if(!empty($articleName)) {
    $articleName = "<articleName>$articleName</articleName>";
  }
  if(!empty($articleTemplate)) {
    $articleTemplate = "<articleTemplate>$articleTemplate</articleTemplate>";
  }
  if(!empty($articleHeader)) {
    $articleHeader = preg_replace("/\r/sm", "", $articleHeader);
    $articleHeader = "<articleHeader>\n$articleHeader\n</articleHeader>";
  }
  if(empty($articleBody)) {
    echo "Must have a Body!<br>\n";
    exit();
  }
  $articleBody = preg_replace("/\r/sm", "", $articleBody);
  $articleBody = "<articleBody>\n$articleBody\n</articleBody>";

  $article = <<<EOF
$articleName
$articleTemplate
$articleHeader
$articleBody

EOF;

  $f = fopen("/tmp/article-preview.article", "w");
  fwrite($f, $article);
  fclose($f);

  $extra = <<<EOF
<style type="text/css">
body {background-color: white; }
#subButton {
  font-size: 1.5em;
  background-color: green;
  color: white;
  padding: 20px;
}
#reset {
  font-size: 1.5em;
  background-color: red;
  color: white;
  padding: 20px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function() {
  // discard and reedit
  jQuery("#reset").click(function() {
    jQuery.ajax({
type: "POST",
data: "file=/tmp/article-preview.article",
url: "del.php",
error: function(req, status) {
      alert("error:" + status);
   }
    });
    jQuery("#subButton").hide();
    jQuery("#reset").hide();
    jQuery("iframe").fadeOut(1000, function() {
      // The ajax needs time to do its thing so do a 1 second hide
      // before doing the back
      window.history.back();
    });
  });

  // Submit for post
  jQuery("#subButton").click(function() {
    location.href="$self?id=$id&rssid=$rssid";
  });
});

</script>

EOF;

  $top = $S->getPageTop(array(title=>"Edit News Article", extra=>$extra), "<h1>Preview of Article</h1>");

  echo <<<EOF
$top
<div style="margin-bottom: 20px">
<hr>
<iframe src="showarticle.php?article=/tmp/article-preview.article" width="100%" height="500px">No frames</iframe>
<hr>
<button id="subButton">Create Article</button>
&nbsp;<button id="reset">Discard and return to editor panel</button>
</div>

EOF;

}

// Update the article and the rssfeed

function Post($S, $self, $id, $rssid) {
  $article = preg_replace("/^\s*$/sm", '', file_get_contents("/tmp/article-preview.article"));

  $articleName = "";
  $articleTemplate = "template.default.template";
  $articleHeader = "";
  $articleBody = "";
  $rssfeed = "";
  $rsstitle = "";
  $rssdesc =  "";
  $rssdate = "";
  $rssinclude = "both";
  
  if(preg_match('/<articleTemplate>(.*?)<\/articleTemplate>/sm', $article, $match)) {
    // We found the tag and got the filename of the template
    $articleTemplate=$match[1];
  }
  if(preg_match('/<articleName>(.*?)<\/articleName>/sm', $article, $match)) {
    // We found the tag and got the filename of the template
    $articleName=$match[1];
  }

  if(preg_match('/<articleHeader>(.*?)<\/articleHeader>/sm', $article, $match)) {
    $articleHeader = $match[1];
  }

  if(preg_match('/<articleBody>(.*?)<\/articleBody>/sm', $article, $match)) {
    $articleBody = $match[1];
  }

  // Now look at the body and extract the rss stuff
  // rssfeed can take these arguments:
  // title
  // date
  // include

  date_default_timezone_set(GMT);

  $rssdate = date(DATE_RSS); // default to todays date

  if(preg_match("/(<!--\s*)*<rssfeed(.*?)<\/rssfeed>(\s*-->)*/sm", $articleBody, $match)) {
    $rssfeed = $match[0];
  }

  $articleBody = preg_replace("/(<!--\s*)*<rssfeed.*?<\/rssfeed>(\s*-->)*/sm", "", $articleBody);

  if(preg_match("/<rssfeed(.+?)>/sm", $rssfeed, $match)) {
    $rssline = $match[1];
    if(preg_match("/title=['\"](.*?)[\"']/sm", $rssline, $match)) {
      $rsstitle=$match[1];
    }
    if(preg_match("/date=['\"](.*?)[\"']/sm", $rssline, $match)) {
      $rssdate=$match[1];
    }
    if(preg_match("/include=['\"](.*?)[\"']/sm", $rssline, $match)) {
      switch($match[1]) {
        case "article":
          $rssinclude = "article";
          break;
        case "both":
          $rssinclude = "both";
          break;
        case "rss":
          $rssinclude = "rss";
          break;
        default:
          $rssinclude = "both";
          break;
      }
    }
  }


  if(!empty($rsstitle)) {
    if(preg_match("/(<!--\s*)*<rssfeed.*?>\s*(-->)*(.*?)(<!--)*\s*<\/rssfeed>(\s*-->)*/sm", $rssfeed, $match)) {
      $rssdesc = $match[3];
    }
  } else {
    if(preg_match("/<h2>(.*?)<\/h2>/sm", $rssfeed, $match)) {
      $rsstitle = $match[1];
      $rssdesc = preg_replace("/<h2>(.*?)<\/h2>/sm", "", $rssfeed, 1);  // only replace first h2
      if(preg_match("/(<!--\s*)*<rssfeed.*?>\s*(-->)*(.*?)(<!--)*\s*<\/rssfeed>(\s*-->)*/sm", $rssdesc, $match)) {
        $rssdesc = $match[3];
      }
    }
  }

  $month = array('Jan'=>'01', 'Feb'=>'02', 'Mar'=>'03', 'Apr'=>'04', 'May'=>'05', 'Jun'=>'06', 'Jul'=>'07', 'Aug'=>'08', 'Sep'=>'09', 'Oct'=>'10', 'Nov'=>'11', 'Dec'=>'12');

  preg_match("/(\d+) (.{3}) (\d{4}) (\d\d:\d\d:\d\d) (.*)/", $rssdate, $match) . "<br>\n";

  $day = $match[1];
  $mo = $month[$match[2]];
  $yr = $match[3];
  $time = $match[4];
  $zone = $match[5];

  $date = sprintf("$yr-$mo-%02d $time<br>$rssdate<br>\n", $day);

  $rssfeed = mysql_real_escape_string($rssfeed);
  $articleBody = mysql_real_escape_string($articleBody);
  $articleHeader = mysql_real_escape_string($articleHeader);

  $articleInclude = $rssinclude;

  $query = "update articles set articletemplate='$articleTemplate', rssfeed='$rssfeed', article='$articleBody', header='$articleHeader', articleInclude='$articleInclude' where id='$id'";
  $S->query($query);

  $rsstitle = mysql_real_escape_string($rsstitle);
  $rssdesc = mysql_real_escape_string($rssdesc);
  $rssdate = mysql_real_escape_string($rssdate);

  //$rssdesc = preg_replace("/\n/sm", " ", $rssdesc);
  
  $query = "update rssfeeds set rsstitle='$rsstitle', rssdesc='$rssdesc', rssdate='$rssdate', date='$date' where id='$rssid'";
  $S->query($query);

  if(empty($articleName)) {
    $articleName = "News Article $id";
  }

  $query = "update articles set name='$articleName' where id='$id'";
  $S->query($query);

  $top = $S->getPageTop("Edit News Article", "<h1>Edit Posted</h1>");
  
  echo <<<EOF
$top
<p align='center'>You will return to <b>Edit Article</b> in five seconds if you do not navigate elsewere.</p>
<hr/>
<script type="text/javascript">
setTimeout(function() { location.href='$self'; }, 5000);
</script>

EOF;
}
  
?>