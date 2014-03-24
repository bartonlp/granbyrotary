<?php
$pageBannerText = <<<EOF
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
<th><a href="webstats.php">Web Stats</a></th>
</tr>
</table>
</div>
</div>
<header>
<div id='pagetitle'>
$mainTitle
</div>

<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.</p>
</noscript>

EOF;

  // WARNING About MSIE!

  preg_match('/(MSIE.*?);/', $this->agent, $m);
  $msie = $m[1];

  $pageBannerText .= <<<EOF
<!--[if eq IE 8]>
<hr>
style='color: red; background-color: white; border: 1px solid black; padding: 5px;'>
You are running Internet Explorer 8. If you are NOT running Windows XP you should upgrade to IE9
or Firefox or Chrome (the latter two being a much better choice). There is NO reason not to upgrade
(unless of course this is your companies computer and then you shouldn't be waisting your time
browseing the Internet). All three of the mentioned browsers are free and take just a few minutes
to download and install. And if you upgrade to Firefox or Chrome you will not see this big RED
message everytime you visit this site.</p>
<![endif]-->
<!--[if lt IE 8]>
<hr>
<p style='color: red; background-color: white; border: 1px solid black; padding: 5px;'>
You are running a version of Internet Explorer ($msie).
Unfortunatly IE is not standards compliant.
There are several features that will not work correctly on this page depending on the
version of Internet Explorer you are using (actually almost everything is not going to work).
This page has been tested with
<a href='http://www.getfirefox.com'>Firefox</a>,
<a href='http://www.google.com/chrome'>Chrome</a>,
<a href='http://www.opera.com'>Opera</a>,
<a href='http://www.apple.com/safari/download/'>Safari</a>
and works well. You don't need to live with a sub-standard and outdated web browser.
For best results download either Firefox or Chrome. I highly recomend changing your
browser to one of the standard complient browsers. If you must use Internet Explorer then
upgrade to IE9 which is pretty
standard complient (but of course Microsoft is forcing you to also upgrade to Windows 7 or 8 also)!
You can upgrade to IE8 even if you are still using Windows XP.</p>
<![endif]-->
<!--[if gt IE 8]>
<div  style='text-align: center; width: 50%; margin: auto;
color: white; background-color: #BABAD4; border: 1px solid red; padding: 5px;'>
<p>
You are using Internet Explorer $msie. While this site has been tested with IE9 you are still
using Internet Explorer which has been non-standard for years. Sent Microsoft a message,
upgrade to Firefox, Chrome or Opera now, these browsers
really work.
With security, stability, speed and much more. All of these browsers are standard complient and
really work. They are FREE and easy to install! Don't live in the 1900's with Microsoft.</p>
<p>You can get a real browser from one of these links:</p>
<ul style='text-align: left'>
<li><a href='http://www.getfirefox.com'>Get Firefox</a></li>
<li><a href='http://www.google.com/chrome'>Get Chrome</a></li>
<li><a href='http://www.opera.com'>Get Opera</a></li>
</ul>
</div>
<![endif]-->
<!--[if lte IE 7]>
<div style='color: white; background-color: red; border: 1px solid black; padding: 5px;'>
<p>
Really any Internet Explorer less then version 9 just does not work! It doesn't take much to upgrade to
a real browsers that supports the Web Standards and they are all FREE. Upgrade to Firefox, Chrome, or
Opera. You don't need to live in the 1900's just because Microsoft wants your money.</p>
<ul style='text-align: left'>
<p>You can get a real browser from one of these links:</p>
<li><a href='http://www.getfirefox.com'>Get Firefox</a></li>
<li><a href='http://www.google.com/chrome'>Get Chrome</a></li>
<li><a href='http://www.opera.com'>Get Opera</a></li>
</ul>
</div>
<![endif]-->
</header>
<hr/>
EOF;
?>