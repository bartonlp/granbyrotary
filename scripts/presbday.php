#!/usr/bin/php -q
<?php
// BLP 2014-06-09 -- fix weekofyear logic again. We wan the week for this year not the year of the
// birthday.
// BLP 2014-04-14 -- fixed weekofyear logic

$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new Database($_site);

// Get todays week of year

$S->query("select concat(fname, ' ',lname), email from rotarymembers ".
          "where office='President' and otherclub='granby'");
list($presname, $presemail) = $S->fetchrow('num');

//$presemail = "bartonphillips@gmail.com";

$weekofyear = date("W"); // weeks start on Monday
$y = date('Y');
echo "weekofyear: $weekofyear\n";

// BLP 2014-06-09 -- We want this years week not the week of the birthday! So format is '$y-%c-%d'

$n = $S->query("select concat(fname, ' ', lname), email, bday, week(bday) from rotarymembers ".
          "where week(date_format(bday,'$y-%c-%d'), 3) = '$weekofyear' and status='active' and otherclub='granby' ");

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