<?php
// BLP 2018-05-11 -- ADD 'NO GOOD MSIE' message

if(!$this->isBot) {
  if(preg_match("~^.*(?:(msie\s*\d*)|(trident\/*\s*\d*)).*$~i", $this->agent, $m)) {
    $which = $m[1] ? $m[1] : $m[2];
    echo <<<EOF
<!DOCTYPE html>
<html>
<head>
  <title>NO GOOD MSIE</title>
</head>
<body>
<div style="background-color: red; color: white; padding: 10px;">
Your browser's <b>User Agent String</b> says it is:<br>
$m[0]<br>
Sorry you are using Microsoft's Broken Internet Explorer ($which).</div>
<div>
<p>You should upgrade to Windows 10 and Edge if you must use MS-Windows.</p>
<p>Better yet get <a href="https://www.google.com/chrome/"><b>Google Chrome</b></a>
or <a href="https://www.mozilla.org/en-US/firefox/"><b>Mozilla Firefox</b>.</p></a>
These two browsers will work with almost all previous
versions of Windows and are very up to date.</p>
<b>Better yet remove MS-Windows from your
system and install Linux instead.
Sorry but I just can not continue to support ancient versions of browsers.</b></p>
</div>
</body>
</html>
EOF;
    exit();
  }
}
  
return <<<EOF
<head>
  <title>{$arg['title']}</title>
  <!-- METAs -->
  <meta name=viewport content="width=device-width, initial-scale=1">
  <meta charset="utf-8"/>
  <meta name="copyright" content="$this->copyright">  
  <meta name="Author" content="Barton L. Phillips, mailto:barton@granbyrotary.org">
  <meta name="description" content="{$arg['desc']}">
  <meta name="keywords" content="Rotary, Granby, Grand County, Colorado, Grand County All-Club Email">
  <!-- Microsoft verification tag -->
  <meta name="msvalidate.01" content="769E2BFA62FFED5C91B7F4200CECA90A">
  <!-- Google verification tag -->
  <meta name="google-site-verification" content="FtWTx_Hn0ie5lTF42jPm5zjVtsDtBFC1MC7kyDJ99AU">
  <meta name="verify-v1" content="dgAIXuJJft+YWGYjqA2g/NbkCkbfkMdqtAjtx3E4nkc="/>
  <!-- FAVICON.ICO -->
  <link rel="shortcut icon" href="https://www.granbyrotary.org/favicon.ico" type="image/x-icon">
  <!-- CSS -->
  <link rel="stylesheet" title="Rotary Style Sheet" href="/css/rotary.css">
  <!-- css is not css but a link to tracker via .htaccess RewriteRule. -->
  <link rel="stylesheet" href="/csstest-{$this->LAST_ID}.css" title="blp test">
{$arg['link']}
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <!-- Site Script -->
  <script>
var lastId = $this->LAST_ID, localPath = '/';
  </script>
  <script src="https://bartonphillips.net/js/tracker.js"></script>
{$arg['extra']}
{$arg['script']}
{$arg['css']}
  <style>
div {
  padding: 0;
}
  </style>
</head>
EOF;
