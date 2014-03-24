<?php
// Get the Name from the rotarymembers database given the ID
// This is an Ajax responder

define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary(false);
$referer = $_SERVER['HTTP_REFERER'];

// Don't let people come here from anywhere else than the members
// page! We can change this later to make it only our sites

$errorhdr = <<<EOF
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

if(!preg_match("(^http://www.granbyrotary\.org)i", $referer) && ($_REQUEST['mail'] != 1)) {
  if($referer) echo "referer=$referer<br/>";
  
  echo <<<EOL
$errorhdr
<body>
<h1>This page can only be accessed indirectly.</h1>
<p>Please return to our <a href='index.php'>home page</a></p>
</body>
</html>
EOL;

  exit();
}

$id = $_GET['id'];

$S->query("select FName, LName from rotarymembers where id='$id'");

$row = $S->fetchrow();
if(empty($row)) {
  $name = ":::ERROR=id not valid";
} else {
  $name = "$row[FName] $row[LName]";
}

Header("Content-type: text/plain");
echo $name;
?>
  