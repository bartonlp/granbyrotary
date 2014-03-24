<?php
require("includes/granbyrotary.conf");
$S = new GranbyRotary(false);
$S->feedCount();
header('Content-Type: application/xml');
echo file_get_contents("rssfeed.xml");
?>