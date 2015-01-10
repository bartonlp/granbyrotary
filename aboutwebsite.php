<?php
// About this site page   
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$h->title = "About This Web Site and Server";
$h->banner = "<h2>About This Web Site and Server</h2>";
$h->css = <<<EOF
  <style>
img { border: 0; }
/* About this web site (aboutwebsite.php)  */
#aboutWebSite {
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2em;
        display: block;
        width: 100%;
        text-align: center;
}
#aboutWebSite h1 {
        margin-top: 0;
        margin-bottom: .2em;  
}
#runWith {
        background-color: white;
        border: groove blue 10px;
        margin: 2em;
}
@media (max-width: 800px) {
        #runWith {
          width: 94%;
          margin: 0px;
        }
}
  </style>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
<div id="aboutWebSite">

<div id="runWith">
  <p>This site's designer is Barton L. Phillips<br/>
     <a href="http://www.bartonphillips.com">www.bartonphillips.com</a><br>
     Copyright &copy; 2010 Granby Rotary Club
  </p>
  
	<p>This site is hosted by <a href="http://www.lamphost.net">
		 <img width="200" height="40" border="0" align="middle"
						alt="LAMP Host (www.lamphost.net)"
						src="http://www.lamphost.net/sites/all/themes/lamphostnet/images/logo.jpg"/>
		 </a>
		 </p>

  <p>This site is run with Linux, Apache, MySql, and PHP.</p>
	<p><img src="images/linux-powered.gif" alt="Linux Powered"></p>
	<p><a href="http://www.apache.org/"><img border="0" src="images/apache_logo.gif" alt="Apache" width="400" height="148"></a></p>
	<p><a href="http://www.mysql.com"><img border=0 src="images/powered_by_mysql.gif" alt="Powered by MySql"></a></p>
	<p><a href="http://www.php.net"><img
  src="images/php-small-white.png" alt="PHP Powered"></a></p>
  <p><a href="http://jquery.com/"><img
  src="images/logo_jquery_215x53.gif" alt="jQuery logo"
  style="background-color: black"/></a></p>

	<p><a href="http://www.mozilla.org"><img src="images/bestviewedwithmozillabig.gif" alt="Best viewed with Mozilla or any other browser"></a></p>
	<p><a href="http://www.mozilla.org"><img
	src="/images/shirt3-small.gif" alt="Mozilla"></a></p>
	<p><img src="images/msfree.png" alt="100% Microsoft Free"
  style="width: 100px"></p>

	<p><a href="http://www.netcraft.com/whats?host=www.granbyrotary.org">
	<img src="images/powered.gif" width=90 height=53 border=0 alt="Powered By ...?"></a>
	</p>
</div>
</div>
$footer
EOF;

?>
