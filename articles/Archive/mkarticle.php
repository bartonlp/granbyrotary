<?php
// Add the information from the article template to the database

// Make this zero after finished debugging
//$DEBUG = 0;

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary(); 

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

// This is called as 'mkarticle.php?article=article-file
// Now read in the article file

if(empty($_GET['article'])) {
  echo "Must have an article<br>\n";
  exit(1);
}

// Read the file into a string

$article = file_get_contents($_GET['article'], FILE_TEXT);

// The article can have three parts. 1) the template to use. If not
// pressent then we use a default template.
// 2) the header part of the page. This is not a full header, it has
// no DOCTYPE and not meta etc. It starts with the css link, the
// scripts and the page css. There are not html, head, body etc tages
// in the file.
// 3) the body part of the page.

$articleName = "";
$articleTemplate = "template.default.template";
$articleHeader = "";
$articleBody = "";
$rssfeed = "";
$rsstitle = "";
$rsslink = "";
$rssdesc =  "";
$rssdate = "";
$rssinclude = true;

// The page must be in the order above, 1, 2, 3
// Look for a 'articleTemplate' tag before the 'articleHeader' tag.

if(preg_match('/<articleTemplate>(.*?)<\/articleTemplate>/sm', $article, $match)) {
  // We found the tag and got the filename of the template
  $articleTemplate=$match[1];
}
if(preg_match('/<articleName>(.*?)<\/articleName>/sm', $article, $match)) {
  // We found the tag and got the filename of the template
  $articleName=$match[1];
}

if(preg_match('/<articleBody>(.*?)<\/articleBody>/sm', $article, $match)) {
  $articleBody = $match[1];
}

if(preg_match('/<articleHeader>(.*?)<\/articleHeader>/sm', $article, $match)) {
  $articleHeader = $match[1];
}

// Now look at the body and extract the rss stuff
// rssfeed can take these arguments:
// title
// date
// noinclude

date_default_timezone_set(GMT);

$rssdate = date(DATE_RSS); // default to todays date

// Save articleBody so we can parse out the rssfeed stuff

$body = $articleBody;

// Get the RSS Feed tag part from the articleBody and put it into rssfeed

preg_match("/(\s*<!--\s*)*<rssfeed.*?<\/rssfeed>(\s*-->\s*)*/sm", $articleBody, $match);
$rssfeed = $match[0]; // the rssfeed part of the article

// Now remove the feed part from the articleBody

if(!empty($rssfeed)) {
  $articleBody = preg_replace("/(\s*<!--\s*)*<rssfeed.*?<\/rssfeed>(\s*-->\s*)*/sm", "", $articleBody);

  // Are there any attributes to the <rssfeed> tag?

  if(preg_match('/<rssfeed(.+?)>/sm', $body, $match)) {
    // Attributes go into line
    $line =  $match[1];

    // now take line appart
    if(preg_match('/title=[\'"](.*?)["\']/sm', $line, $match)) {
      $rsstitle=$match[1];
    }

    if(preg_match('/date=[\'"](.*?)["\']/sm', $line, $match3)) {
      $rssdate=$match[1];
    }

    if(preg_match('/include=[\'"](.*?)["\']/', $line, $match3)) {
      if($match[1] == "false") {
        $rssinclude = false;
      }
    }
  }

  $rss="";

  // Get the contents between the <rssfeed> tags

  if(preg_match('/<rssfeed.*?>(\s*-->\s*)*(.*?)(\s*<!--\s*)*<\/rssfeed>/sm', $body, $match)) {
    $rss = $match[2];
  }

  if($DEBUG) {
    echo "rss=::$rss::<br>\nrssdate=$rssdate<br>\n";
  }

  // If we don't have a title then everyting is the rssdesc
  if(!empty($rsstitle)) {
    $rssdesc = $rss;
  } else {
    // Otherwise we need to grab the first <h2> tag and make that the
    // rsstitle and then remove that tag
    if(preg_match("/<h2>(.*?)<\/h2>/sm", $rss, $match)) {
      $rsstitle = $match[1];
      // only remove the first <h2>
      $rssdesc = preg_replace("/<h2>(.*?)<\/h2>/sm", "", $rss, 1);
    }
  }

  $month = array('Jan'=>'01', 'Feb'=>'02', 'Mar'=>'03', 'Apr'=>'04', 'May'=>'05', 'Jun'=>'06', 'Jul'=>'07', 'Aug'=>'08', 'Sep'=>'09', 'Oct'=>'10', 'Nov'=>'11', 'Dec'=>'12');

  preg_match("/(\d+) (.{3}) (\d{4}) (\d\d:\d\d:\d\d) (.*)/", $rssdate, $match) . "<br>\n";

  $day = $match[1];
  $mo = $month[$match[2]];
  $yr = $match[3];
  $time = $match[4];
  $zone = $match[5];

  $date = sprintf("$yr-$mo-%02d $time", $day);

  $rssfeed = mysql_real_escape_string($rssfeed);
} else {
  // No rssfeed so only article
  
  $articleInclude = "article";
}

$articleBody = mysql_real_escape_string($articleBody);
$articleHeader = mysql_real_escape_string($articleHeader);

$articleInclude = ($rssinclude == true) ? "both" : "article";

if(empty($articleName)) {
  $articleName = "News Article $articleId";
}

$query = "insert into articles (articletemplate, name, rssfeed, article, header, articleInclude, created)
 values('$articleTemplate', '$articleName', '$rssfeed', '$articleBody', '$articleHeader', '$articleInclude', now())";

if($DEBUG) {
 echo "rssdate=$date<br>\n";
 echo "$query<br>\n";
} else {
  $S->query($query);
}

// If there is a rss section then add it to the database. If not then
// don't put anything into the rssfeeds table!

$articleId = mysql_insert_id();

if(!empty($rssfeed)) {
  $rsslink = "http://www.granbyrotary.org/articles/article.$articleId";

  $rsstitle = mysql_real_escape_string($rsstitle);
  $rsslink = mysql_real_escape_string($rsslink);
  $rssdesc = mysql_real_escape_string($rssdesc);
  $rssdate = mysql_real_escape_string($rssdate);

  $query = "insert into rssfeeds (articleId_fk, rsstitle, rsslink, rssdesc, rssdate, date, created)
  values($articleId, '$rsstitle', '$rsslink', '$rssdesc', '$rssdate', '$date', now())";

  if($DEBUG) {
    echo "$query<br>\n";
  } else {       
    $S->query($query);
  }
}

$articleExpired = $_GET['articleExpired'];
$rssExpired = $_GET['rssExpired'];

$rssfeedId_fk = empty($rssfeed) ? 0 : mysql_insert_id();

if($articleExpired) {
  $query = "update articles set rssfeedId_fk='$rssfeedId_fk', expired='$articleExpired' where id=$articleId";
} else {
  $query = "update articles set rssfeedId_fk='$rssfeedId_fk' where id=$articleId";
}


if($DEBUG) {
  echo "articleExpired=$articleExpired<br>\n";
  echo "$query<br>\n";
} else {
  $S->query($query);
}

if(!empty($rssfeed)) {
  if($rssExpired) {
    $query = "update rssfeeds set expired='$rssExpired' where id=LAST_INSERT_ID()";

    if($DEBUG) {
      echo "rssExpired=$rssExpired<br>\n";
      echo "$query<br>\n";
    } else {
      $S->query($query);
    }
  }
}

if(!$DEBUG) {
  if($ret = $_GET['return']) {
    header("Location: $ret");
    exit();
  }
}
echo "Done, thank you<br>\n";
?>

