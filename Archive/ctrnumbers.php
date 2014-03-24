<?php
   // This is the counter wigget. It is at the bottom of most every page and shows the number
   // of hits for the page. It is used by site.class.php. This should probably be in the
   // 'includes' directory along with site.class.php
   // Possible arguments are:
   // s is the font size, defaults to 11pt if not pressent
   // text is the message, required
   // font is the font face, defaults to TIMESBD.TTF is not pressent
   // rgb is the box color, defaults to '245,222,179' or Wheat.
   
$s = $_GET['s'];
$text = $_GET['text'];
$font = $_GET['font'];
$rgb = $_GET['rgb'];

$fontfile = "/var/www/granbyrotary.org/htdocs/fonts/";

if(!isset($rgb)) {
  $rgb = '245,222,179'; // Wheat: rgb=245,222,179 hex=#F5DEB3 
}

if(!isset($s)) $s=11; // If no size then use 11 point

if(!isset($font)) {
  $font = "TIMESBD.TTF";
} 

// Look for the font either as upper or lower case

if(file_exists($fontfile . $font)) {
  $fontfile .= $font;
} else if(file_exists($fontfile . strtolower($font))) {
  $fontfile .= strtolower($font);
} else {
  Header("Content-type: text/html");
  echo "<h1 style='color: red'>ERROR: ctrnumbers.php, can't find $font</h1>";
  exit(2);
}
                      
Header("Content-type: image/png");

$size = imagettfbbox($s,0, $fontfile, $text);
$dx = abs($size[2]-$size[0]);
$dy = abs($size[5]-$size[3]);

$xpad=10;
$ypad=7;
$im = imagecreate($dx+$xpad, $dy+$ypad);

list($red, $green, $blue) = split( ',', $rgb);

$background = ImageColorAllocate($im, $red, $green, $blue);
$num = ImageColorAllocate($im, 123, 16, 66);
$black = ImageColorAllocate($im, 132, 119, 123);

// Fill the rectange to make the background color.

ImageFilledRectangle($im, 0, 0, $dx, $dy, $background);

ImageTTFText($im, $s, 0, (int)($xpad/2)+1, $dy+(int)($ypad/2), $black, $fontfile, $text);
ImageTTFText($im, $s, 0, (int)($xpad/2), $dy+(int)($ypad/2)-1, $num, $fontfile, $text);

ImagePng($im);
ImageDestroy($im);
?>