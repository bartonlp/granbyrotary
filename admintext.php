<?php
// The Administrators special page

require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

if($_REQUEST['key'] != "41144blp") {
  echo "<h1>Sorry. Where did you come from?</h1>";
  exit();
}

$sender = $_REQUEST['sender'];

if(!$S->isAdmin($S->id)) {
  echo "<h1>You are not an admin!</h1>";
  exit();
}

$h->title = "Administrator Features";
$h->banner = "<h1>Administrator Features</h1>";
$h->extra = <<<EOF
<script>
jQuery(document).ready(function($) {
  $("ul").after("<hr>");
});
</script>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);
 
echo <<<EOF
$top
<div style="color: blue; background-color: pink; width: 70%; margin: auto; padding: 10px; border: 5px solid red;">
<h2>You Are A Web Page Administrator</h2>
<p>Because you are an administrator you have access to the following special pages:</p>
<h2>Secial Sections</h2>
<p>Most pages have <i>Special Section</i> that can be updated ON-Line.</p>
<ul>
<li><a target="_blank" href="updatesite.php">Update the Special Sections in Pages</a></li>
<li><a target="_blank" href="updatesiteadmin.php">Administer Update Site Table</a></li>
<li><a target="_blank" href="showupdateareas.php?page=$sender">Show Update Areas</a>.<br>
Update areas are shown with a <span style="background-color: #CCFFCC;">Light Green</span>
background and 
a white on black <span style="color: white; background-color: black;">title</span>.<br>
This frature shows you where the <i>Special Sections</i> are in the page you were looking at
($sender).
</li>
</ul>
<h2>Members Table</h2>
<p>The <i>Members Table</i> is the main table in the Granby Rotary database, it contains all the
members we know about in the four Grand County Rotary Clubs. New members can be added, and existing
member's information can be edited and updated.</p>
<ul>
<li><a target="_blank" href="admindb.php">Administer the Members Table</a></li>
</ul>
</div>
<hr>
$footer
EOF;
