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
$update = 0;
$toupdate = 0;

foreach($opentxt as $t) {
  $contents = file($t);
  foreach($contents as $v) {
    if(!strpos($v, 'robots.txt'))
      continue;

    ++$cnt;
    
    preg_match("/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*?\"-\" \"(.*?)\"/", $v, $m);

    $S->query("insert ignore into bots2 values('$m[2]')");
    
    try {
      $sql="insert into bots (ip, agent) values('$m[1]', '$m[2]')";
      $new += $S->query($sql);
    } catch(Exception $e) {
      $sql="update bots set agent='$m[2]' where ip='$m[1]'";
      //echo "$sql\n";
      ++$toupdate;
      $update += $S->query($sql);
    }
  }
}
echo "Processed $cnt records\nnew: $new\ntoupdate: $toupdate\nupdated: $update\n";
?>