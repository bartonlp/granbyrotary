<?php
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
