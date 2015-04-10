#!/usr/bin/php -q
<?php
require_once("/var/www/includes/siteautoload.class.php");

$S = new Database($dbinfo);


// like botscan.pl
$opentxt = array("/var/log/apache2/access.log");

$cnt = 0;
$new = 0;
$dup = 0;

foreach($opentxt as $t) {
  $contents = file($t);
  foreach($contents as $v) {
    if(!strpos($v, 'robots.txt'))
      continue;

    ++$cnt;
    
    preg_match("/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*?\"-\" \"(.*?)\"/", $v, $m);

    // bots2 has only agents
    $agent = $S->escape($m[2]);
    $S->query("insert ignore into barton.bots2 values('$agent')");
    
    try {
      // bots has only ip addresses
      $sql="insert ignore into barton.bots (ip) values('$m[1]')";
      $new += $S->query($sql);
    } catch(Exception $e) {
      ++$dup;
    }
  }
}
echo "Processed $cnt records\nnew: $new\ndup: $dup\n";
