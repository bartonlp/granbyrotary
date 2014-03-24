<?php
// if the .htaccess file redirects acceses to robots.txt to this file:
// RewriteCond %{REQUEST_URI} =/robots.txt
// RewriteRule ^ robots.php [L]

// First get the database stuff
require_once("grandchorale.org/htdocs/grandchorale.i.php");

$S = new GrandChorale;

$host = $_SERVER['HTTP_HOST']; // Get host. This will be something like 'www.xxxx.yyy'

// Log this info in askrobotstxt table
// Table has ip (primary key), agent, count, lasttime (timestamp).

$S->query("insert into askrobotstxt (ip, agent, site) value('$S->ip', '$S->agent', '$host') ".
          "on duplicate key update count=count+1");

$robotstxt = file_get_contents("robots.txt");
header("Content-Type: text/plain");
echo "$robotstxt";
?>