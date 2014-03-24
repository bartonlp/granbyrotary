<?php
// GranbyRotary Class
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary; // Use default DOCTYPE which is XHTML 1.0

?>
<head>
   <title>Earth Day 2009 Photos</title>

   <!-- METAs -->
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   <meta name="Author"
      content="Barton L. Phillips, mailto:barton@granbyrotary.org"/>
   <meta name="description"
      content="Name: Rotary Club of Granby Colorado, Page: Earthday 2009"/>
   <meta name="keywords" content="rotary"/>

   <!-- Google verifcation tag -->
   <meta name="verify-v1"
      content="dgAIXuJJft+YWGYjqA2g/NbkCkbfkMdqtAjtx3E4nkc="/>

   <!-- FAVICON.ICO -->
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
   
   <!-- RSS FEED -->
   <link rel="alternate" type="application/rss+xml" title="RSS"
        href="/rssfeed.xml" />

   <!-- Link our custom CSS -->
   <link rel="stylesheet" title="Rotary Style Sheet"
        href="/rotary.css" type="text/css"/>

   <!-- CSS for News Rotator -->
   <link rel="stylesheet" href="/css/rotator.css" type="text/css"/>

   <!-- jQuery -->
   <script type="text/javascript"
            src="/js/jquery-1.3.2.min.js"></script>
   <script type="text/javascript"
            src="/js/jquery.dimensions.min.js"></script> 
   <script type="text/javascript"
            src="/js/jquery.dragndrop.js"></script> 

   <!-- styles for this page only -->
   <style type="text/css">
#big {
        position: absolute;
        display: none;
        border: 10px ridge gray;
        background-color: white;
        z-index: 10;
}
#big img {
        z-index: 1;
}
.handle {
        position: absolute;
        top: 10px;
        z-index: 8;
        opacity: 0.6;
        background-color: white;
}
#photeframe {
        width: 100%;
        height: 100%;
}

#photos {
        position: relative;
        overflow: auto;
        width: 150px;
        height: 500px;
        margin: 0;
        padding: 0;
        border: 10px groove black;

}
#photos .item {
        position: relative;
        width: 150px; /* 15 * 150 = 2250 */
        height: auto;
        margin: 0;
        padding: 0;
}

#photos img {
        width: 150px;
        padding: 0;
        margin: 0;
        border: 0;
}
   </style>

   <script type="text/javascript">

$(document).ready(function() {
  var bigpicloc = { top : $("#photos").offset().top, left : 200 };

  $("img").click(function(event) {
    var src = $(this).attr("src");

    $("#big").hide('slow', function() {
      //var $this = $(this);
      $("#photoframe").empty();
      $("<img src='" + src + "' alt='"+src+"'/>")
          .appendTo("#photoframe");
      $("#big")
          .css({'top' : bigpicloc.top , 'left' : bigpicloc.left })
          .show('slow');
    });
  });
  
  $("#big").dblclick(function() {
    $(this).hide('slow' );
  });

  $("#big").Drags({
    handler: ".handle",
    onDrop: function(e) {
               bigpicloc.top = e.offsetY;
               bigpicloc.left = e.offsetX;
             }
  });
});

function createControl(src) {
  return $("<img/>")
      .attr({"src" : src})
      .addClass("control")
      .css({"opacity": 0.6})
      .hide();
}
  
   </script>
</  head>

<body>

<?php
echo $S->getBanner("<h2>Earth Day May 12, 2009 Photos</h2>");

$photos = array(
"P1010006.JPG",
"P1010008.JPG",
"P1010012.JPG",
"P1010020.JPG",
"P1010022.JPG",
"P1010032.JPG",
"P1010033.JPG",
"P1010035.JPG",
"P1010037.JPG",
"P1010041.JPG",
"P1010042.JPG",
"P1010044.JPG",
"P1010047.JPG",
"P1010048.JPG",
"P1010050.JPG"
);

echo <<<EOF
<p>Click on the thumbnail picture to get an enlarged version. Double Click on the enlarged photo to discard it.
You can drag the enlarged photo to a new position where new enlarged photos will appear</p>

<div id='photos'>
<div class='item'>
EOF;

foreach($photos as $inx=>$photo) {
  echo "<div><img src='$photo' alt='$photo' /></div>";
}
echo <<<EOF
</div>
</div>

EOF;
?>

<div id='big'>
   <span class='handle'>Drag Here</span>
<div id="photoframe"></div>
</div>

<!-- Footer. Setup Footer text -->
<?php

echo $S->getFooter();
?>
