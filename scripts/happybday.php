#!/usr/bin/php -q
<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

$S = new Database($dbinfo);
// Get todays DAY

$day = date("d");

$n = $S->query("select concat(fname, ' ', lname), email, bday, day(bday) as day from rotarymembers ".
          "where month(bday)=month(now()) and day(bday)='$day' ".
          "and status='active' and otherclub='granby' ");

if($n) {
  while(list($bname, $email, $bday, $bdayday) = $S->fetchrow('num')) {
    echo "$bname, $email, $bday\n";
    
    $bdate = date("F j, Y", strtotime($bday));
    $msg = <<<EOF
Happy Birthday $name from the Granby Rotary Club.
We wish you a very happy and prosperout birthday and the rest of the year.

Your birthdate of record is $bdate.

EOF;
    echo "$msg\n";

    mail($email, "Happy Birthday",
         $msg,
         "From: info@granbyrotary.org\r\n",
         "-f bartonphillips@gmail.com");
  }
} else {
  echo "No birthdays Today.\n";
}
?>