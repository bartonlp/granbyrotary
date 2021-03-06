#!/usr/bin/php -q
<?php
// Email the SkyHiNews with info for their calendar section.
// BLP 2017-06-23 -- add multiple email recipients
$_site = require_once("/var/www/vendor/bartonlp/site-class/includes/siteload.php");
$S = new Database($_site);

// Get the unixdate. The cron jobs runs on THURSDAY after the Wednesday meeting

$date = date("U");
//echo "date: $date\n";

$start = date("Y-m-d", $date);
$end = date("Y-m-d", strtotime("+1 week", $date));
//echo "start: $start, end: $end\n";

$sql = "select name, date, id, subject, type ".
       "from meetings where date between '$start' and '$end'";

//echo "$sql\n";

$n = $S->query($sql);

list($name, $date, $id, $subject, $type) = $S->fetchrow('num');

if($type == 'none' || $type == 'open') {
  echo "NO Meeting or OPEN\n";
  exit();
}

$subject = preg_replace("/^\s*(.*?)[.\s]*$/", "$1", $subject);

if(strlen($subject) > 80) {
  $subject = wrap($subject);
}

// NOTE $date is now from the database

$meetdate = date("l M d, Y", strtotime($date));

//echo "meetdate: $meetdate\n";

if(empty($subject)) $subject = "To be determined";

// BLP 2017-06-23 -- Add multiple recipients

$emailaddress = array(array('name'=>"Calendar", 'email'=>'calendar@skyhinews.com'),
                      array('name'=>"Sawyer D'argone", 'email'=>'sdargonne@skyhinews.com'),
                      array('name'=>'Bryce Martin', 'email'=>'bmartin@skyhinews.com'));

foreach($emailaddress as $inx) {
  $newsname = $inx['name'];
  $email = "{$inx['name']} <{$inx['email']}>";

  $msg = <<<EOF
Dear $newsname,

The Rotary Club of Granby is having its weekly meeting on $meetdate.
Our guest speaker will be $name and the subject of the talk is:
$subject.

All Rotarians, as well as the general public, are invited. Maverick's provides a wonderful
lunch for \$13.50 but purchasing lunch is not required.

Our meetings start at 12 noon every Wednesday and are held at
Maverick's Grille
15 E. Agate Avenue (US Highway 40)
Granby, Colorado 80446
Phone: (970) 887-9000

Please put this information in your weekly calendar section.
If you have any questions please email me at bartonphillips@gmail.com
or phone me at 970-887-3646.

Thank you

Barton Phillips
EOF;

  echo $msg;
   
  mail($email, "Rotary Item for the Calendar Section", $msg,
       "From: info@granbyrotary.org\r\nBcc: bartonphillips@gmail.com\r\n");
}
// BLP 2015-03-14 -- 
// Now send a message to the presenter and to the president

$sql = "select concat(FName, ' ', LName), Email ".
       "from rotarymembers where id=$id";

$S->query($sql);

// Get new $email from rotarymembers

list($member, $email) = $S->fetchrow('num');

$sql = "select concat(FName, ' ', LName), Email ".
       "from rotarymembers where office='President'";

$S->query($sql);
list($pres, $pemail) = $S->fetchrow('num');

$msg2 =<<<EOF
FYI: $member (CC: $pres).
This was sent to the SkyHiNews publisizing your upcoming talk.
=============================================================>

$msg
EOF;

echo "$msg2\n";
mail($email, "FYI SkyHiNews Notification of Talk", $msg2,
     "From: info@granbyrotary.org\r\nCC: $pemail\r\n");
  
exit();

function wrap($line) {
  $newline = "";
  $tmp = "";
  $ar = explode(" ", $line);

  for($i=0; $i < count($ar); ++$i) {
    if(strlen("$tmp $ar[$i]") > 80) {
      $newline .= "$tmp\n";
      $tmp = "$ar[$i] ";
    } else {
      $tmp .= "$ar[$i] ";
    }
  }
  return rtrim($newline . $tmp, " ");
}
