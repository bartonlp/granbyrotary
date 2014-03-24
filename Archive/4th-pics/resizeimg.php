<?php
// File and new size
$filename = $_GET['image'];
$imgwidth = $_GET['width'];
$imgheight = $_GET['height'];
$imgpercent = $_GET['percent'];

header('Content-type: image/jpeg');

$newwidth = $imgwidth;
$newheight = $imgheight;

list($width, $height) = getimagesize($filename);

if(!empty($imgpercent)) {
  $imgpercent /= 100;
  // Get new sizes
  $newwidth = $width * $imgpercent;
  $newheight = $height * $imgpercent;
} else {
  if(!empty($imgwidth) && empty($imgheight)) {
    $newwidth = $imgwidth;
    $newheight = $height * $imgwidth/$width;
  } elseif(!empty($imgheight) && empty($imgwidth)) {
    $newheight = $imgheight;
    $newwidth = $width * $imgheight/$height;
  }
}
// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb);
?>