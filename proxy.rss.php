<?php
$feed = $_GET['feed'];
foreach($_GET as $k=>$v) {
  if($k == "feed") continue;
  $feed .= "&$k=$v";
}
header('Content-Type: application/xml');
echo file_get_contents($feed);
?>