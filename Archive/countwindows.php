<!DOCTYPE html>
<html lang="en">

<!--
This page is created by a CRON job and should not be edited manually.
It will be rewritten every night. The 'scripts/createrunlentable.php' creates this file.
-->
<head>
  <title>Web Site Statistics: count windows types (n)</title>
  <!-- METAs -->
  <meta charset="utf-8">
  <meta name="Author" content="Barton L. Phillips, mailto:barton@granbyrotary.org">
  <meta name="description" content="Web Site Statistics: count windows types">
  <meta name="keywords" content="Rotary, Granby, Grand County, Colorado, Grand County All-Club Email">
  <!-- Microsoft verification tag -->
  <meta name="msvalidate.01" content="769E2BFA62FFED5C91B7F4200CECA90A" >
  <!-- Google verification tag -->
  <meta name="google-site-verification" content="FtWTx_Hn0ie5lTF42jPm5zjVtsDtBFC1MC7kyDJ99AU" >
  <meta name="verify-v1" content="dgAIXuJJft+YWGYjqA2g/NbkCkbfkMdqtAjtx3E4nkc=">
  <!-- CSS -->
  <!-- FAVICON.ICO -->
  <link rel="shortcut icon" href="http://static.granbyrotary.org/favicon.ico" type="image/x-icon" >
  <!-- RSS FEED -->
  <link href="http://feeds2.feedburner.com/http/wwwgranbyrotaryorg"
       title="Subscribe to my feed"
         rel="alternate"
        type="application/rss+xml" >
  <!-- Link our custom CSS -->
  <link rel="stylesheet" title="Rotary Style Sheet" href="http://static.granbyrotary.org/css/rotary.css" type="text/css">
  <!-- ($arg['link'] Links -->

  <!-- jQuery -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <!-- Site Script -->
  <!-- Screen size logic -->
  <script>
jQuery(document).ready(function($) {
  var xscreen = screen.width;
  var yscreen = screen.height;
  var s = xscreen + "x" + yscreen; // + " " + x + "x" + y;
  $.get("/screensize.ajax.php", { size: s });

  // Page Resizing
  var oldfontsize = $("body").css("font-size");
  var p = .95;
  var wid = 580;
  if($("html").outerWidth() < wid) {
    var www = $("html").outerWidth();
    $("body").css("font-size", "8px");
    $("#navMap").css("width", www * p + "px");
    $("#navMap li a").css({float: "none", color: "black","background-color": "transparent"});
    $("#navMap li").css({border: "0px", display: "block"});
  }
  // Change stuff to work on windows smaller than 800px
  $(window).resize(function() {
    var w = $("html").outerWidth();
    var ww = screen.width;
    if(ww < wid) {
      $("body").css("font-size", "8px");
    } else {
      if(w < wid) {
        $("body").css("font-size", "8px");
        $("#navMap").css("width", w * p + "px");
        $("#navMap li a").css({float: "none", color: "black","background-color": "transparent"});
        $("#navMap li").css({border: "0px", display: "block"});
      } else {
        $("body").css("font-size", oldfontsize);
        $("#navMap").css("width", "");
        //$("#navMap li a").css("float", "left");
        $("#navMap li, #navMap li a").removeAttr("style");
      }
    }
  });
});
  </script>
  <!-- ($arg['extra']) Script/Styles -->
  <link rel="stylesheet"  href="/css/tablesorter.css" type="text/css" >
  <link rel="stylesheet" href="/css/hits.css" type="text/css" >
  <script type='text/javascript' src='/js/hits.js'></script>

  <script type="text/javascript"
           src="/js/tablesorter/jquery.metadata.js"></script>

  <script type="text/javascript"
           src="/js/tablesorter/jquery.tablesorter.js"></script>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  $("#wintypes").addClass("tablesorter");
  $("#wintypes").tablesorter({headers: { 1: {sorter: "currency"}, 3: {sorter: "currency"}, 4: {sorter: "currency"}}});
});
  </script>

</head>

<body>
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
</tr>
</table>
</div>
</div>
<header>
<div id='pagetitle'>

<h2>Major Windows Types for Thu October 31 2013</h2>
</div>

<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.</p>
</noscript>
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
You are running a version of Internet Explorer ().
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
You are using Internet Explorer . While this site has been tested with IE9 you are still
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
<hr>

<p>&quot;% Win&quot; shows the version as a percent of the total number of windows records.</p>
<p>&quot;% Win 30 days&quot; show the version as a percent of the total number of windows records for the last 30 days.</p>
<p>&quot;% All&quot; shows the version as a percent of the total number of records.</p>
<p>Total Number of Windows Records: 390,046,076</p>
<p>Total Number of Windows Records for the past 30 days: 7,139,748</p>
<p>Total Number of Records: 724,196,193</p>
<table id='wintypes' border="1">
<thead>
<tr>
<th>Name</th><th>Count</th><th>Windows Name</th><th>% Win</th><th>% Win<br>30 days</th><th>% All</th>
</tr>
</thead>
<tbody>
<tr>
<th>Windows NT 5.0</th>
<td style='text-align: right; padding: 5px'>1,908,779</td>
<td style='padding: 5px'>Windows 2000, 17 February 2000</td>
<td style='text-align: right'>0.49</td>
<td style='text-align: right'>0.13</td>
<td style='text-align: right'>0.26</td>
</tr>
<tr>
<th>Windows NT 5.1</th>
<td style='text-align: right; padding: 5px'>177,383,333</td>
<td style='padding: 5px'>Windows XP, 25 October 2001</td>
<td style='text-align: right'>45.48</td>
<td style='text-align: right'>42.54</td>
<td style='text-align: right'>24.49</td>
</tr>
<tr>
<th>Windows NT 5.2</th>
<td style='text-align: right; padding: 5px'>4,695,414</td>
<td style='padding: 5px'>Windows Server 2003; Windows XP x64 Edition, 28 March 2003</td>
<td style='text-align: right'>1.20</td>
<td style='text-align: right'>0.82</td>
<td style='text-align: right'>0.65</td>
</tr>
<tr>
<th>Windows NT 6.0</th>
<td style='text-align: right; padding: 5px'>63,308,164</td>
<td style='padding: 5px'>Windows Vista, 30 November 2006</td>
<td style='text-align: right'>16.23</td>
<td style='text-align: right'>4.23</td>
<td style='text-align: right'>8.74</td>
</tr>
<tr>
<th>Windows NT 6.1</th>
<td style='text-align: right; padding: 5px'>136,028,510</td>
<td style='padding: 5px'>Windows 7, 22 October 2009</td>
<td style='text-align: right'>34.87</td>
<td style='text-align: right'>45.53</td>
<td style='text-align: right'>18.78</td>
</tr>
<tr>
<th>Windows NT 6.16</th>
<td style='text-align: right; padding: 5px'>1</td>
<td style='padding: 5px'></td>
<td style='text-align: right'>0.00</td>
<td style='text-align: right'>0.00</td>
<td style='text-align: right'>0.00</td>
</tr>
<tr>
<th>Windows NT 6.2</th>
<td style='text-align: right; padding: 5px'>3,620,193</td>
<td style='padding: 5px'>Windows 8</td>
<td style='text-align: right'>0.93</td>
<td style='text-align: right'>6.62</td>
<td style='text-align: right'>0.50</td>
</tr>
<tr>
<th>Windows 95</th>
<td style='text-align: right; padding: 5px'>276,548</td>
<td style='padding: 5px'>Windows 95, 24 August 1995</td>
<td style='text-align: right'>0.07</td>
<td style='text-align: right'>0.09</td>
<td style='text-align: right'>0.04</td>
</tr>
<tr>
<th>Windows 98</th>
<td style='text-align: right; padding: 5px'>676,048</td>
<td style='padding: 5px'>Windows 98, 25 June 1998; Windows 98 Second Edition, 5 May 1999</td>
<td style='text-align: right'>0.17</td>
<td style='text-align: right'>0.05</td>
<td style='text-align: right'>0.09</td>
</tr>

</tbody>
</table>
<p>The column labeled &quot;Name&quot; is how the Windows version is refered to in the &quot;User Agent String&quot; while
the column labeled &quot;Windows Name&quot; is the common name and the date the version was released. The &quot;Count&quot;
and &quot;% Win&quot; 
columns are the total number of times this agent has been used and percentage of all Windows version. The
&quot;% Win 30 Days&quot; shows the last 30 days percentage. Finaly, the column &quot;% All&quot; show the percent
of this agent compaired to all other Windows and non-Windows agents.</p>
<p>As you can see there are still some people using Windows 95 and 98. Fortunately not many. There are many
other versions of Windows NT but they are very small.</p>
<p>Unfortunately the &quot;User Agent String&quot; that a browser supplies is not very standardized or accurate so
there are often browsers that are lying about who they really are.</p>
<hr/>
<footer>
<div id="hitCounter" style="margin-left: auto; margin-right: auto; width: 50%; text-align: center;">

<table id="hitCountertbl" style="width: 0; border: 8px ridge yellow; margin-left: auto;
margin-right: auto; background-color: #F5DEB3">
<tr id='hitCountertr'>
<th id='hitCounterth' style="color: rgb(123, 16, 66);">
656
</th>
</tr>
</table>
</div>

<div style="text-align: center;">
<p id='lastmodified'>Last Modified&nbsp;Oct 31, 2013 15:15:42</p>
</div>
</footer>
</body>
</html>