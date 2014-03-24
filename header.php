<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

unset($siteinfo['bannerFile']);

$S = new GranbyRotary;

$h->link = <<<EOF
<style>
#header-image-div {
  border-top: none;
  border-left: 10px solid #3739b0;
  border-right: 10px solid #3739b0;
  border-bottom: 10px solid #3739b0;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
  width: 990px;
  height: 280px;
  margin: auto;
  background-color: #7AC5CD;
}
#header-image {
  width: 990px;
  height: 235px;
  border-bottom: 15px solid #3739b0;
  background-image: url("images/header-image.jpg");
}
#nav-bar {
  margin-top: -5px;
  padding-top: 3px;
  height: 20px;
  background-color: #7AC5CD;
  border-radius: 10px;
}
#nav-bar table {
  width: 100%;
  color: #474747;
  
}
#nav-bar a {
  text-decoration: none;
}
table th {
/*  border: 1px solid black;*/
}
</style>
EOF;

$h->extra = <<<EOF
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function($) {
/*
  var image = new Image;
  image.src = "images/header-image.jpg";
  image.width = 990;
  image.height = 200;
  $(image).load(function() {
    $("#header-image").html(this);
  });
*/
});
</script>
EOF;

$top = $S->getPageHead($h);

echo <<<EOF
$top
<div id="header-image-div">
<div id="header-image"></div>
<div id="nav-bar">
<table>
<tr>
<th><a href="/">Home</a></th>
<th><a href="member_directory.php">Members</a></th>
<th><a href="about.php">About Rotary</a></th>
<th><a href="news.php">News</a></th>
<th><a href="calendar.php">Club Calendar</a></th>
<th><a href="meetings.php">Meetings</a></th>
<th><a href="edituserinfo.php">User Profile</a></th>
<th><a href="hits.php">Web Stats</a></th>
<th><a href="contactus.php">Contact Us</a></th>
</tr>
</table>
</div>
</div>

EOF;
?>