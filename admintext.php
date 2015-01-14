<?php
// The Administrators special page
// BLP 2014-08-31 -- add foodlist and eventplanner
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

$date = date("U");

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
<li><a target="_blank" href="updatesite.php?d=$date">Update the Special Sections in Pages</a></li>
<li><a target="_blank" href="updatesiteadmin.php">Administer Update Site Table</a></li>
<li><a target="_blank" href="showupdateareas.php?page=$sender&d=$date">Show Update Areas</a>.<br>
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
<li><a target="_blank" href="admindb.php?d=$date">Administer the Members Table</a></li>
</ul>
<h2>News Articles</h2>
<p><i>News Articles</i> can be added or edited. These are articles that appear on the <b>News</b>
page.</p>
<ul>
<li><a target="_blank" href="articles/createarticle.php?d=$date">Create News Articles</a></li>
<li><a target="_blank" href="articles/createarticle.php?page=edit&d=$date">Edit Existing News Articles</a></li>
<li><a target="_blank" href="articles/admin.php?d=$date">Administer the News Articles Table</a></li>
</ul>
<h2>Items Below are Expermental</h2>
<p>The <i>Fool List</i> lets you add/edit items that can be ordered for an event.
It lets you specify the
name of the item (like 'Hamberger Patties') and the way they are sold (in packages of 20 patties)
along with the price per package and the vendor or provider.</p>
<p>The <i>Event Planner</i> lets you name an event (for example, '4th of July 2014') and then select
items from the <i>Food List</i> that you want to order. Then you can indicate the number of packages
you want for the event (for example, 100 Hamburger Patties). The program calculates the
<i>total cost</i>
based on the <i>Package Quantity * Package Price * Number of Packages</i> as well as the
<i>Unit Qty</>. The final form can be printed out.</p>
<ul>
<li><a target="_blank" href="foodlist.php?d=$date">Add/Edit Event Food List</a></li>
<li><a target="_blank" href="eventplanner.php?d=$date">Plan an Event. Using the Food List</a></li>
</ul>
<p>Attendance</p>
<ul>
<li><a target="_blank" href="attendance.php?d=$date">Add/Edit Attendance Records</a></li>
</ul>
<p>You can also edit all of the entries on the <a target="_blank" href="meetings.php?d=$date">Meetings Page</a></p>
</div>
<hr>
$footer
EOF;
?>