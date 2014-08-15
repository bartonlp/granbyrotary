#!/usr/bin/php -q
<?php
// Email the SkyHiNews with info for their calendar section.
// BLP 2014-07-17 -- remove business meeting stuff

define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

$S = new Database($dbinfo);

list($week, $year) = explode(",", date("W,Y"));

$date = find_first_day_ofweek($week, $year);
$start = date("Y-m-d", $date);
$end = date("Y-m-d", strtotime("+1 week", $date));
$sql = "select name, date, id, subject from meetings where date between '$start' and '$end'";

$n = $S->query($sql);

list($name, $date, $id, $subject) = $S->fetchrow('num');
$subject = preg_replace("/^\s*(.*?)[.\s]*$/", "$1", $subject);

if(strlen($subject) > 80) {
  $subject = wrap($subject);
}

$meetdate = date("l M d, Y", strtotime($date));

if(empty($subject)) $subject = "To be determined";
  
$msg = <<<EOF
Dear Amber Phillipps,

The Rotary Club of Granby is having its weekly meeting on $meetdate.
Our guest speaker will be $name and the subject of the talk is:
$subject.

All Rotarians, as well as the general public, are invited. Maverick's provides a wonderful
lunch for \$12 but purchasing lunch is not required.

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

//echo $msg;

mail("aphillipps@skyhidailynews.com", "Rotary Item for the Calendar Section", $msg,
     "From: info@granbyrotary.org\r\nCC: bartonphillips@gmail.com\r\n");

/**
 * Function to find the day of a week in a year
 * @param integer $week The week number of the year
 * @param integer $year The year of the week we need to calculate on
 * @param string  $start_of_week The start day of the week you want returned
 *                Monday is the default Start Day of the Week in PHP. For
 *                example you might want to get the date for the Sunday of wk 22
 * @return integer The unix timestamp of the date is returned
 */

function find_first_day_ofweek($week, $year, $start_of_week='sunday') {
   // Get the target week of the year with reference to the starting day of
   // the year as UNIX time.

   $target_week = strtotime("+$week week +1 day", strtotime("1 January $year"));
   // Get the date information for the day in question which
   // is "n" number of weeks from the start of the year
   $date_info = getdate($target_week);

   // Get the day of the week (integer value)
   $day_of_week = $date_info['wday'];

   // Make an adjustment for the start day of the week because in PHP the
   // start day of the week is Monday
   
   switch (strtolower($start_of_week)) {
       case 'sunday':
           $adjusted_date = $day_of_week;
           break;
       case 'monday':
           $adjusted_date = $day_of_week-1;
           break;
       case 'tuesday':
           $adjusted_date = $day_of_week-2;
           break;
       case 'wednesday':
           $adjusted_date = $day_of_week-3;
           break;
       case 'thursday':
           $adjusted_date = $day_of_week-4;
           break;
       case 'friday':
           $adjusted_date = $day_of_week-5;
           break;
       case 'saturday':
           $adjusted_date = $day_of_week-6;
           break;

       default:
           $adjusted_date = $day_of_week-1; // monday
           break;
   }

   // Get the first day of the weekday requested
   $first_day = strtotime("-$adjusted_date day", $target_week);

   //return date('l dS of F Y h:i:s A', $first_day);

   return $first_day;
}

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
?>