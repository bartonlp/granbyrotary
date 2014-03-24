#!/usr/bin/php -q
<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

$S = new Database($dbinfo);
// Get todays week of year

$S->query("select concat(fname, ' ',lname), email from rotarymembers ".
          "where office='President' and otherclub='granby'");
list($presname, $presemail) = $S->fetchrow('num');

//$presemail ="bartonphillips@gmail.com";

$weekofyear = date("W"); // weeks start on Monday
echo "Week: $weekofyear\n";

$n = $S->query("select concat(fname, ' ', lname), email, bday, week(bday) from rotarymembers ".
          "where week(bday)+1 = '$weekofyear' and status='active' and otherclub='granby' ");

$havehas = "has";
if($n > 1) {
  $plural = 's';
  $havehas = "have";
}

$msg = <<<EOF
Dear President $presname,

The following Rotarian{$plural} $havehas a birthday this week (week #$weekofyear):

EOF;

if($n) {
  while(list($bname, $email, $bday, $week) = $S->fetchrow('num')) {
    //echo "$bname, $email, $bday, $week\n";
    
    $bdate = date("F j, Y", strtotime($bday));
    $msg .= "\t$bname: $bdate\n";
  }
  echo "$msg\n";

  mail($presemail, "Birthday{$plural} This Week",
       $msg,
       "From: info@granbyrotary.org\r\nCC: bartonphillips@gmail.com\r\n",
       "-f bartonphillips@gmail.com");
    
} else {
  echo "No birthdays this week.\n";
}

?>