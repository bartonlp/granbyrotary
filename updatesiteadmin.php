<?php
require_once("/home/barton11/includes/siteautoload.php");

$S = new GranbyRotary;

$errorhdr = <<<EOF
<!DOCTYPE HTML>
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