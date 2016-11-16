<?php
//   $SQLDEBUG = true;
//   $ERRORDEBUG = true;
// Create an article or edit an article
// To create an article either NO 'query' or ?page=
// To edit an article ?page=edit
require_once("../vendor/autoload.php");
$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new $_site->className($_site);

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

$footer = $S->getFooter();

// SWITCH

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "GET":
    switch($_GET['page']) {
      case "preview":
        preview($S);
        break;
      case "make":
        make($S);
        break;
      case "edit":
        edit($S);
        break;
      case "editedit":
        editedit($S);
        break;
      case "reedit":
        $S->reedit = true;
        editedit($S);
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
      default:
        throw(new Exception("default POST"));
    }
    break;    
  default:
    throw(new Exception("Not GET or POST"));
}

// ********************************************************************************

function post($S) {
  extract(stripSlashesDeep($_POST));

  $articleBody = preg_replace("/\r/s", "", $articleBody);
  $articleBody = $S->escape($articleBody);
  $articleName = $S->escape($articleName);
  $articleHeading = $S->escape($articleHeading);
  $query = <<<EOF
create table articlepreview (
  articleName varchar(255),
  articleHeading varchar(255),
  articleBody text,
  articleExpired date
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;

  // Create a table and store the values in it.
  
  $S->query("drop table if exists articlepreview");
  $S->query($query);
  
  $S->query("insert into articlepreview ".
            "values('$articleName', '$articleHeading', '$articleBody', '$articleExpired')");

  $h->extra = <<<EOF
  <base href="http://www.granbyrotary.org">

  <script>
jQuery(document).ready(function($) {
  // Discard Preview Button
  $("#reset").click(function() {
    $("#subButton").hide();
    $("#reset").hide();
    $("iframe").fadeOut(1000, function() {
      //window.history.back();
      location.href="$S->self?page=reedit";
    });
  });

  // Submit Button
  $("#subButton").click(function() {
    location.href="$S->self?page=make&return=$S->self&articleExpired=$articleExpired&rssfeed=$rssfeed&id=$id";
  });
});
  </script>  
  <style type="text/css">
body { background-color: white;}
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
EOF;

  $h->title = "Preview Article";
  $h->banner = "<h1>Preview of Article</h1>";
  $top = $S->getPageTop($h);

  echo <<<EOF
$top
<div style="margin-bottom: 20px">
 <!-- IFRAME to hold preview article -->
 <iframe src="$S->self?page=preview" width="100%" height="500px">No frames</iframe>
<hr>
<button id="subButton">Create Article</button>
&nbsp;<button id="reset">Discard and return to editor panel</button>
</div>
<hr/>
$footer
EOF;
}

// ********************************************************************************

function preview($S) {
  $n = $S->query("select * from articlepreview");
  if($n != 1) {
    throw(new Exception("select articlepreview: n=$n"));
  }
  $row = $S->fetchrow('assoc');
  extract($row);

  $title = empty($articleName) ? "News Article" : $articleName;

  $h->title = $title;
  $h->banner = "<h2>$title</h2>";
  list($top, $footer) = $S->getPageTopBottom($h);

  echo <<<EOF
$top
<h2>$articleHeading</h2>
$articleBody
<hr/>
$footer
EOF;
}

// ********************************************************************************

function make($S) {
  //$DEBUG = true;

  $rssfeed = $_GET['rssfeed'];
  $id = $_GET['id'];
  $articleExpired = $_GET['articleExpired'];

  if(!$id) {
    $S->query("select auto_increment from INFORMATION_SCHEMA.tables where table_name='articles'");
    list($nextid) = $S->fetchrow('num');

    $anchor = $S->escape("<a name='article.$nextid'></a>");
  
    $query = "insert into articles (name, article, articleInclude, created) " .
             "select articleName, concat('$anchor\n<h2>', articleHeading, '</h2>\n', articleBody), " .
             "'article', now() from articlepreview";
  } else {
    // This is an edit so update the $id entry
    $anchor =  $S->escape("<a name='article.$id'></a>");

    $query = "select articleName, concat('$anchor\n<h2>', ".
             "articleHeading, '</h2>\n', articleBody) as articleBody " .
             "from articlepreview";

    if($S->query($query)) {
      $row = $S->fetchrow('assoc');
      extract($row);
      $articleBody = $S->escape($articleBody);
      $query = "update articles set name='$articleName', article='$articleBody', ".
               "articleInclude='article' where id='$id'";
    } else {
      echo "ERROR: no info found in articlepreview<br>";
      exit();
    }
  }

  if($DEBUG) {
   echo "$query<br>\n";
  } else {
    $S->query($query);
  }

  // We have $articleName, $articleBody, $articleExpired, $id, $rssfeed

  // If there is a rss section then add it to the database. If not then
  // don't put anything into the rssfeeds table!

  $articleId = $S->getLastInsertId();

  if(!empty($rssfeed)) {
    if(!$id) {
      date_default_timezone_set(GMT);
      $t = time();
      $date = date("Y-m-d h:i:s", $t);
      $rssdate = date(DATE_RSS, $t); 
      $rsslink = "http://www.granbyrotary.org/articles/article.$articleId";

      $query = "insert into rssfeeds (articleId_fk, rsstitle, rsslink, rssdesc, rssdate, date, created) " .
               "select '$articleId', articleHeading, '$rsslink', articleBody, '$rssdate', '$date', now() from articlepreview";
    } else {
      // An Edit
      $S->query("select rssfeedId_fk from articles where id='$id'");
      list($rssfeedId_fk) = $S->fetchrow('num');

      echo "id=$id, rssfeedId_fk=$rssfeedId_fk<br>";
      
      $rsslink = "http://www.granbyrotary.org/articles/article.$id";
      
      $S->query("select articleHeading from articlepreview");
      list($articleHeading) = $S->fetchrow('num');

      $query = "update rssfeeds set rsstitle='$articleHeading', rsslink='$rsslink', rssdesc='$articleBody' where id='$rssfeedId_fk'";
    }

    if($DEBUG) {
      echo "$query<br>\n";
    } else {       
      $S->query($query);
    }
  }

  if($id) $articleId = $id;

  // If no rssfeed then zero, If a feed and we just did an insert then the insert id. If no insert then this was an edit

  $rssfeedId_fk = empty($rssfeed) ? 0 : ($t = $S->getLastInsertId()) ? $t : $rssfeedId_fk;

  //echo "rssfeedId_fk=$rssfeedId_fk<br>";
  
  if($articleExpired) {
    $query = "update articles set rssfeedId_fk='$rssfeedId_fk', expired='$articleExpired' where id='$articleId'";
  } else {
    $query = "update articles set rssfeedId_fk='$rssfeedId_fk' where id='$articleId'";
  }

  if($DEBUG) {
    echo "articleExpired=$articleExpired<br>\n";
    echo "$query<br>\n";
  } else {
    $S->query($query);
  }

  if(!empty($rssfeed)) {
    if(!$rssfeedId_fk) $id = "LAST_INSERT_ID()";
    else $id = $rssfeedId_fk;
  
    $query = "update rssfeeds set expired='$articleExpired' where id=$id";

    if($DEBUG) {
      echo "rssExpired=$articleExpired<br>\n";
      echo "$query<br>\n";
    } else {
      $S->query($query);
    }
  }

  //$DEBUG = true;  
  
  if(!$DEBUG) {
    $S->query("drop table if exists articlepreview");

    if($ret = $_GET['return']) {
      header("Location: $ret");
      exit();
    }
  }
  echo "Done, thank you<br>\n";
}
  
// ********************************************************************************

function edit($S) {
  $h->extra = <<<EOF
  <base href="http://www.granbyrotary.org">

  <script>
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

  $h->title = "Edit News Article";
  $h->banner = "<h1>Edit an Article</h1>";
  $top = $S->getPageTop($h);

  $n = $S->query("select (expired < now()) as old, id, name, expired from articles");

  if(!$n) {
    echo "No Articles Found";
    exit();
  }
    
  $tbl ="";

  while($row = $S->fetchrow('assoc')) {
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

    $tbl .= <<<EOF
<tr><td><a href="$S->self?page=editedit&id=$id">$id</a></td><td>$name</td><td $never $oldClass>$expired</td></tr>

EOF;
  }

  echo <<<EOF
$top
<div id="articlediv">
<p>Select article to edit by clicking on the ID</p>
<table id="select" style="background-color: white;" border="1">
<tr><th>Id</th><th>Article Name</th><th>Expired</th></tr>
$tbl
</table>
</div>
EOF;
}

// Edit the article

function editedit($S) {
  $id = $_GET['id'];

  $h->extra = <<<EOF
  <base href="http://www.granbyrotary.org">

  <style>
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

  $title = "Edit News Article";
  $banner = "<h1>Edit an Article</h1>";

  if($S->reedit) {
    $id = "reedit";
  }
  startpage($S, $id, $title, $banner);
}

// ********************************************************************************
// Start Page and second page of Edit

function startpage($S, $id=null, $title=null, $banner=null) {
  $h->extra = <<<EOF
  <base href="http://www.granbyrotary.org">

  <script src="/js/date_input/jquery.date_input.js"></script>
  <link rel="stylesheet" href="/js/date_input/date_input.css" type="text/css">

  <script type='text/javascript'>
jQuery(document).ready(function($) {
  $.extend(DateInput.DEFAULT_OPTS, {
    stringToDate: function(string) {
      var matches;
      if(matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
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
  $.date_input.initialize();
});  
  </script>  
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

$h->extra .= <<<EOF
  <base href="http://www.granbyrotary.org">

  <script>
jQuery(document).ready(function() {
  var auto = 1;

  $("#form input[type=submit]")
  .after("<input type='button' id='render' style='display: none' value='Quick Preview'/>" +
        "<input type='button' id='autopreview' value='Stop Auto Preview' />");

  $("#form").after("<div style='padding: 5px; border: 1px solid black' id='quickpreview'>");
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

  $h->title = empty($title) ? "Create News Article" : $title;
  $h->banner = empty($banner) ? "<h1>Create an Article</h1>" : $banner;

  $top = $S->getPageTop($h);

  if($id) {
    if($id == "reedit") {
      $n = $S->query("select * from articlepreview");
      // articleName, articleHeading, articleBody, articleExpired
      list($name, $heading, $article, $expired) = $S->fetchrow('num');
    } else {
      // Get article from database.
      $S->query("select name, rssfeedId_fk, articleInclude, rssfeed, article, expired from articles where id='$id'");
      $row = $S->fetchrow('assoc');
      extract($row);
      $rssfeed = preg_replace("~<!--\s*<rssfeed>\s*-->(.*?)<!--\s*</rssfeed>\s*-->~s", "$1", $rssfeed);
      switch($articleInclude) {
        case "article":
          $search = $article;
          break;
        case "rss":
          $search = $rssfeed;
        case "both":
          $search = "$rssfeed$article";
          break;
        default:
          throw(new Exception("articleInclude error: value=$articleInclude"));
      }
      // remove any old anchors
      $search = preg_replace("~<a\s+name=.*?>.*?</a>~s", "", $search);
      // The <h2> at the beginning of the article is the heading
      if(preg_match("~<h2>(.*?)</h2>~s", $search, $m)) {
        $heading = $m[1];
        $search = preg_replace("~<h2>$m[1]</h2>~s", "", $search);
      } else {
        $heading = $name;
      }
      $article = $search;
    }
  }

  echo <<<EOF
$top
<div id="creatediv">
<form id="form" action="$S->self" method="post">
<table id="tbl1" style="border: 1px solid black">
   <tr><th>Article Name</th><td><input type="text" name="articleName" value="$name"/></td></tr>
   <tr><th>Article Heading</th><td><input type="text" name="articleHeading" value="$heading"/></td></tr>
   <tr><th>Article Expires</th><td><input id="artexp" class="date_input" type="text" name="articleExpired" value="$expired" />
           Never <input type="checkbox" name="articlenever" /></td></tr>
   <tr><th>RSS Feed?</th><td><input type="checkbox" name="rssfeed" "checked" /></td></tr>
</table>
<h2>Article</h2>
<table id="tbl2">
   <tr><td><textarea id="bodyarea" name="articleBody" cols="100" rows="20">$article</textarea></td></tr>
</table>
   <input type="hidden" name="id" value="$id" />
   <input type="hidden" name="page" value="post" />
   <input type="submit" name="Preview" value="Submit"/>
</form>
</div>

<hr/>
<div>
<a href="/articles/adminrss.php">Rss Admin</a><br/>
<a href="/articles/createarticle.php">Create Article</a><br/>
<a href="/articles/createarticle.php?page=edit">Edit Article</a><br/>
<a href="/articles/admin.php">Article Admin</a><br/>
<a href="/admindb.php">Database Admin</a><br/>

</div>
<hr/>
$footer
EOF;
}
