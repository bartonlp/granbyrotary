<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary(false); // dont count this
header("Content-type: text/plain");

list($result, $n) = $S->query("select count(*) as count from survey", true);
if(!$n) echo "0";
else {
  list($count) = mysql_fetch_row($result);
  echo $count;
}
?>