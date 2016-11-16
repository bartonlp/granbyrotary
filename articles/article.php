<?php
require_once("../vendor/autoload.php");
$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new $_site->className($_site);

// Does the file exist already?

if(empty($_GET['article'])) {
  echo "Must have an article<br>";
  exit(1);
}

$id = $_GET['article'];

$S->query("select * from articles where id='$id'");

$row = $S->fetchrow('assoc');

if(empty($row)) {
  echo "Article Not Found on Server<br>\n";
  exit(1);
}

extract($row);

$title = empty($name) ? "News Article $id" : "$name";
$mainTitle = "$title<br>$created";
$body = "";

switch($articleInclude) {
  case "rss":
    $body = $rssfeed;
    break;
  case "article";
    $body = $article;
    break;
  case "both";
    $body = "$rssfeed\n$article";
    break;
}

$h->title = $title;
$h->banner = "<h2>$mainTitle</h2>";
list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
$body
<hr/>
$footer
EOF;
