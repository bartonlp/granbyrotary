<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

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

$h->title = "Update Site Admin for Granby Rotary";
$h->banner = "<h1>Update Site Admin For Granby Rotary</h1>";
$s->site = "granbyrotary.org";

if(!$_GET && !$_POST) {
  $_GET['page'] = "admin"; // Force us to the admin page if not get or post
}
                       
$updatepage = UpdateSite::secondHalf($S, $h, $s);

echo <<<EOF
$updatepage
EOF;
?>