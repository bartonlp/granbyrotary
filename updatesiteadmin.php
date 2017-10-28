<?php
require_once("./vendor/autoload.php"); // Get GranbyRotary class
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

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
