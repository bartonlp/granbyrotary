#!/usr/bin/php -q
<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

$S = new Database($dbinfo);


// like botscan.pl
$opentxt = array("/usr/local/apache/domlogs/bartonphillips.bartonphillips.org",
                 "/usr/local/apache/domlogs/bartonphillipsdotcom.bartonphillips.org",
                 "/usr/local/apache/domlogs/bartonphillipsdotnet.bartonphillips.org",
                 "/usr/local/apache/domlogs/bartonphillips.org",
                 "/usr/local/apache/domlogs/endpolio.bartonphillips.org",
                 "/usr/local/apache/domlogs/granbyrotary.bartonphillips.org",
                 "/usr/local/apache/domlogs/grandchorale.bartonphillips.org",
                 "/usr/local/apache/domlogs/grandlakerotary.bartonphillips.org",
                 "/usr/local/apache/domlogs/kremmlingrotary.bartonphillips.org",
                 "/usr/local/apache/domlogs/mountainmessiah.bartonphillips.org",
                 "/usr/local/apache/domlogs/pokerclub.bartonphillips.org",
                 "/usr/local/apache/domlogs/purwininsurance.bartonphillips.org",
                 "/usr/local/apache/domlogs/tinapurwininsurance.bartonphillips.org"
                );

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
    $S->query("insert ignore into bots2 values('$agent')");
    
    try {
      // bots has only ip addresses
      $sql="insert into bots (ip) values('$m[1]')";
      $new += $S->query($sql);
    } catch(Exception $e) {
      ++$dup;
    }
  }
}
echo "Processed $cnt records\nnew: $new\ndup: $dup\n";
?>