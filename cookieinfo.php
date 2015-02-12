<?php
   // Lets users check the cookies for this site.

require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;
$top = $S->getPageTop("About Our Use of Cookies", "<h2>About Our Use of Cookies</h2>");
$footer = $S->getFooter();

$cookies = "";

while(list($key, $val) = each($_COOKIE)) {
  if(is_array($val)) {
    ksort($val);
    while(list($key1, $val1) = each($val)) {
      $cookies .= "<li>$key[$key1] = $val1</li>";
    }
  } else {
    $cookies .= "<li>$key = $val</li>";
  }
}

echo <<<EOF
$top
<h3>Now about cookies</h3>
<p>
www.granbyrotary.org stores some information on your
local system to help us better service your needs. We use browser based
&quot;<u>COOKIES</u>&quot; to do this. If your browser does not support
cookies or you have disabled the use of cookies via the browser's preferences,
or options we will be unable to provide the extra services. If you have 
disabled the use of cookies you might wish to reconsider. Cookies are 
usually enabled in both Firefox (Mozzilla) and Internet Explorer by default. If you 
are especially concerned you can set the options to ask if you will allow
cookies on an individual bases rather than disabling cookies altogether.</p>

<h3>Here is a list of all the cookies that granbyrotary.org currently has on you:</h3>

<table border=1 bgcolor=yellow cellpadding=10>
<tr>
<td>
<ul>
$cookies
</ul>
</td>
</tr>
</table>

<h3>If you want to remove any of the above keys you can do so: 
<a href='resetcookie.php'>REMOVE COOKIE</a>.</h3>
<p>You may want to remove a cookie if you are using someone elses computer and you don't
want them to have access to you information. The above link will ONLY remove cookies for the
domain &quot;granbyrotary.org&quot;, it will not remove ANY other cookies from the computer you are using.</p>

<h3>Where are the Cookies?</h3>
<p>Your cookies are saved as plain text files by both Firefox
   (Mozilla) and Internet 
Explorer. Each browser handles the files differently. Firefox maintains one
file for all the cookies. The file is &quot;cookies.txt&quot;.</p>

<p>Internet Explorer on the other hand keeps its cookies under either
   the Windows 
directory. There are a lot of files all with the &quot;.txt&quot; extension.
For Windows they are in a directory under
Windows called cookies.
The files are named after the site that owns the cookie. For us that will be
something like &quot;&lt;username&gt;@granbyrotary.txt&quot;. 
It is possible that there
may be more than one granbyrotary.txt in which case there is a number appended to
the domain name like &quot;&lt;username&gt;@granbyrotary[1].txt&quot;. The username will
be your user name.</p>

<h3>You can find information about cookies at:</h3>
<ul>
  <li><a href="http://www.cookiecentral.com/">http://www.cookiecentral.com</a>.</li>
  <li><a href="http://msdn.microsoft.com/library/partbook/vb6/persistentclientsidedatausingcookies.htm">www.microsoft.com</a>.</li>
</ul>
<hr>
$footer
EOF;
?>