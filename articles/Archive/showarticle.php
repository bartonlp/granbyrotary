<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;  // not header etc.

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

// Use the tinyButStrong template class. It can do a lot more than
// what we need, but it can do what we need so why not.

// New version
require_once("/home/bartonlp/bartonphillips.com/htdocs/tbs_class/tbs_class_php5.php");
$TBS = new clsTinyButStrong ;

// This is called as 'showarticle.php?article=article-file
// Now read in the article file

if(empty($_GET['article'])) {
  echo "Must have an article<br>";
  exit(1);
}

// Read the file into an array

$file = $_GET['article'];
if(!file_exists($file)) {
  echo "file does not exist: $file<br>\n";
  exit(1);
}

$article = file($file, FILE_TEXT | FILE_SKIP_EMPTY_LINES);

// The article can have three parts. 1) the template to use. If not
// pressent then we use a default template.
// 2) the header part of the page. This is not a full header, it has
// no DOCTYPE and not meta etc. It starts with the css link, the
// scripts and the page css. There are not html, head, body etc tages
// in the file.
// 3) the body part of the page.

$articleName = "";
$articleTemplate="";
$articleHeader = "";
$articleBody = "";

// The page must be in the order above, 1, 2, 3
// Look for a 'articleTemplate' tag before the 'articleHeader' tag.

for($i=0; !preg_match('/<articleHeader>|<articleBody>/', $article[$i]) && $i < count($article); ++$i) {
  if(preg_match('/<articleTemplate>(.*?)<\/articleTemplate>/', $article[$i], $match)) {
    // We found the tag and got the filename of the template
    $articleTemplate=$match[1];
  }
  if(preg_match('/<articleName>(.*?)<\/articleName>/', $article[$i], $match)) {
    // We found the tag and got the filename of the template
    $articleName=$match[1];
  }
}

// Now look for the end of the 'articleHeader'
// Everything between the tags is the header stuff

if(!preg_match("/<articleBody>/", $article[$i])) {
  for(++$i; !preg_match('/<\/articleHeader>/', $article[$i]) && $i < count($article); ++$i) {
    $articleHeader .= $article[$i];
  }
}
// Now look for the start of the 'articleBody'

for(; !preg_match('/<articleBody>/', $article[$i]) && $i < count($article); ++$i);

// And get all the code between the tags.

for(++$i; !preg_match('/<\/articleBody>/', $article[$i]) && $i < count($article); ++$i) {
  $articleBody .= $article[$i];
}

// Default template file

$template= "template.default.template";

// Did we find a 'articleTemplate'?

if(!empty($articleTemplate)) {
  $template=$articleTemplate;
}

// Load the template

$TBS->LoadTemplate($template);

// Fill in the fields

$title = empty($articleName) ? "News Article" : $articleName;

$top = $S->getPageTop(array(title=>$title, extra=>$articleHeader), "<h2>$title</h2>");
$footer = $S->getFooter();

$TBS->MergeField('top', $top); // just a place holder
$TBS->MergeField('body', $articleBody);
$TBS->MergeField('footer',$footer);

$TBS->Show();
?>

