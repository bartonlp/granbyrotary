<?php
// BLP 2014-09-04 -- Removed MSIE warnings
// BLP 2014-07-17 -- Add 'Admin' to the banner

if($this->isAdmin($this->id)) {
  $adminText = "\n<li><a style='color: red; background-color: white; border: 1px solid black' ".
               "href='admintext.php?key=41144blp&sender=$this->self&d=$d'>Admin</a></li>";
}

return <<<EOF
<header>
<div id="header-image-div">
<div id="header-image">
<img id='slideshow' src="/blp/images/banner-photos/CIMG0001n.JPG"/>
<img id='wheel' src='/blp/images/wheel.png'/>
<img id='granbyrotarytext' src='/blp/images/text-granbyrotary.png'/>
<img id='logo' src='/blp/images/blank.png'>
<img id='dummyimg' src='/tracker.php?page=normal&id=$this->LAST_ID'/>
</div>
<!-- Nav bar for big screens -->
<div id="navMap">
<ul>
<li><a href="/">Home</a></li>
<li><a href="member_directory.php">Members</a></li>
<li><a href="about.php">About&nbsp;Rotary</a></li>
<li><a href="news.php">News</a></li>
<li><a href="calendar.php">Club&nbsp;Calendar</a></li>
<li><a href="meetings.php">Meetings</a></li>
<li><a href="edituserinfo.php">User&nbsp;Profile</a></li>
<li><a href="webstats.php">Web&nbsp;Stats</a></li>$adminText
</ul>
</div>
<!-- Nav bar for small screens -->
<div id="smallnavbar">
	<label for="smallmenu" class="icon-menu">Menu</label>
	<input type="checkbox" id="smallmenu" role="button">
		<ul id="smenu">
<li><a href="/">Home</a></li>
<li><a href="member_directory.php">Members</a></li>
<li><a href="about.php">About&nbsp;Rotary</a></li>
<li><a href="news.php">News</a></li>
<li><a href="calendar.php">Club&nbsp;Calendar</a></li>
<li><a href="meetings.php">Meetings</a></li>
<li><a href="edituserinfo.php">User&nbsp;Profile</a></li>
<li><a href="webstats.php">Web&nbsp;Stats</a></li>$adminText
	</ul>
</div>
</div>

<div id='pagetitle'>
$mainTitle
</div>
<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>
<img src="/tracker.php?page=noscript&id=$this->LAST_ID">
Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.</p>
</noscript>
</header>
<hr/>
EOF;
