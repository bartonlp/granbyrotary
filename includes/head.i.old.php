<?php
$pageHeadText = <<<EOF
{$arg['preheadcomment']}<head>
  <title>{$arg['title']} (n)</title>
  <!-- METAs -->
  <meta charset="utf-8"/>
  <meta name="Author" content="Barton L. Phillips, mailto:barton@granbyrotary.org"/>
  <meta name="description" content="{$arg['desc']}"/>
  <meta name="keywords" content="Rotary, Granby, Grand County, Colorado, Grand County All-Club Email"/>
  <!-- Microsoft verification tag -->
  <meta name="msvalidate.01" content="769E2BFA62FFED5C91B7F4200CECA90A" />
  <!-- Google verification tag -->
  <meta name="google-site-verification" content="FtWTx_Hn0ie5lTF42jPm5zjVtsDtBFC1MC7kyDJ99AU" />
  <meta name="verify-v1" content="dgAIXuJJft+YWGYjqA2g/NbkCkbfkMdqtAjtx3E4nkc="/>
  <!-- CSS -->
  <!-- FAVICON.ICO -->
  <link rel="shortcut icon" href="http://static.granbyrotary.org/favicon.ico" type="image/x-icon" />
  <!-- RSS FEED -->
  <link href="http://feeds2.feedburner.com/http/wwwgranbyrotaryorg"
       title="Subscribe to my feed"
         rel="alternate"
        type="application/rss+xml" />
  <!-- Link our custom CSS -->
  <link rel="stylesheet" title="Rotary Style Sheet" href="http://static.granbyrotary.org/css/rotary.css" type="text/css"/>
  <!-- (\$arg['link'] Links -->
{$arg['link']}
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
  <!-- (\$arg['extra']) Script/Styles -->
{$arg['extra']}
</head>
EOF;
?>