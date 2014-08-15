<?php
// The Administrators special page
// BLP 2014-07-17 -- Use $_REQUEST instead of $_POST so this will work with GET or POST calls

define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

if($_REQUEST['key'] != "41144blp") {
  echo "<h1>Sorry. Where did you come from?</h1>";
  exit();
}

$sender = $_REQUEST['sender'];

$S = new GranbyRotary;

if(!$S->isAdmin($S->id)) {
  echo "<h1>You are not an admin!</h1>";
  exit();
}

$h->title = "Administrator Features";
$h->banner = "<h1>Administrator Features</h1>";

list($top, $footer) = $S->getPageTopBottom($h);
 
echo <<<EOF
$top
<div style="color: blue; background-color: pink; width: 70%; margin: auto; padding: 10px; border: 5px solid red;">
<h2>You Are A Web Page Administrator</h2>
<p>Because you are an administrator you have access to the following special pages:</p>
<ul>
<li><a target="_blank" href="updatesite.php">Update the special sections in pages</a></li>
<li><a target="_blank" href="updatesiteadmin.php">Administer Update Site Table</a></li>
<li><a target="_blank" href="showupdateareas.php?page=$sender">Show Update Areas</a>.
Update areas are shown in <span style="background-color: #CCFFCC;">Light Green</span> with 
a white on black <span style="color: white; background-color: black;">title</span>.</li>

<li><a target="_blank" href="admindb.php">Administer the Granby Rotary Members Table</a></li>
<li><a target="_blank" href="articles/createarticle.php">Create News Articles</a></li>
<li><a target="_blank" href="articles/createarticle.php?page=edit">Edit Existing Articles</a></li>
<li><a target="_blank" href="articles/admin.php">Administer the Articles Table</a></li>
<li><a target="_blank" href="articles/adminrss.php">Administer the Rss Table</a></li>
</ul>
<p>You can also edit all of the entries on the <a target="_blank" href="meetings.php">Meetings Page</a></p>
</div>
$footer
EOF;
?>