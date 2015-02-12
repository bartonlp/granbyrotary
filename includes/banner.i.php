<?php
// BLP 2014-09-04 -- Removed MSIE warnings
// BLP 2014-07-25 -- add date so page is not cached
// BLP 2014-07-17 -- Add 'Admin' to the banner

$d = date("U"); // unix epoch for no caching

if($this->isAdmin($this->id)) {
  $adminText = "\n<li><a style='color: red; background-color: white; border: 1px solid black' ".
               "href='admintext.php?key=41144blp&sender=$this->self&d=$d'>Admin</a></li>";
}

$pageBannerText = <<<EOF
<header>
<div id="header-image-div">
<div id="header-image">
<img src="banner-photos/CIMG0001n.JPG"/>
<img id='wheel' src='images/wheel.gif'/>
<img id='granbyrotarytext' src='images/text-granbyrotary.png'/>
</div>
<!-- Nav bar for big screens -->
<div id="navMap">
<ul>
<li><a href="/?d=$d">Home</a></li>
<li><a href="member_directory.php?d=$d">Members</a></li>
<li><a href="about.php?d=$d">About&nbsp;Rotary</a></li>
<li><a href="news.php?d=$d">News</a></li>
<li><a href="calendar.php?d=$d">Club&nbsp;Calendar</a></li>
<li><a href="meetings.php?d=$d">Meetings</a></li>
<li><a href="edituserinfo.php?d=$d">User&nbsp;Profile</a></li>
<li><a href="webstats.php?d=$d">Web&nbsp;Stats</a></li>$adminText
</ul>
</div>
<!-- Nav bar for small screens -->
<div id="smallnavbar">
	<label for="smallmenu" class="icon-menu">Menu</label>
	<input type="checkbox" id="smallmenu" role="button">
		<ul id="smenu">
<li><a href="/?d=$d">Home</a></li>
<li><a href="member_directory.php?d=$d">Members</a></li>
<li><a href="about.php?d=$d">About&nbsp;Rotary</a></li>
<li><a href="news.php?d=$d">News</a></li>
<li><a href="calendar.php?d=$d">Club&nbsp;Calendar</a></li>
<li><a href="meetings.php?d=$d">Meetings</a></li>
<li><a href="edituserinfo.php?d=$d">User&nbsp;Profile</a></li>
<li><a href="webstats.php?d=$d">Web&nbsp;Stats</a></li>$adminText
	</ul>
</div>
</div>

<div id='pagetitle'>
$mainTitle
</div>

<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.</p>
</noscript>
</header>
<hr/>
EOF;
