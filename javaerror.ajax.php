<?php
$msg = $_GET["msg"];
$where = $_GET["where"];
$screen = $_GET["screen"];

$ret = "";

$who = "IP={$_SERVER['REMOTE_ADDR']} :: AGENT={$_SERVER['HTTP_USER_AGENT']}";

if(empty($msg)) {
  $msg = "NO DATA";
  $ret = "NO DATA";
}

if(empty($where)) {
  $where = "NO WHERE";
  $ret .= "NO WHERE";
}

if(empty($screen)) {
  $screen = "NO SCREEN";
  $ret .= "NO SCREEN";
}

mail("bartonphillips@gmail.com", "Javascript Error", "msg=$msg\nwhere=$where\nscreen=$screen\nwho=$who\n",
     "From: Webmaster <webmaster@granbyrotary.org>",
     "-f bartonphillips@gmail.com");

echo empty($ret) ? "OK" : $ret;
?>