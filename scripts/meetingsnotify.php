#!/usr/bin/php
<?php
# Designed to be run as a cron job.
# Scan the meetings table and send emails to speakers

define('ONE_WEEK',604800);
define('ONE_MONTH', 2629744);

//$DEBUG = true; #debug some

$_site = require_once(getenv("SITELOAD")."/siteload.php");
$S = new Database($_site);

$S->query("select r.FName, r.LName, r.Email, m.yes, m.name, m.date, m.subject ".
          "from rotarymembers as r " .
          "left join meetings as m on r.id = m.id ".
          "where r.Email != '' and m.type='speaker' and m.date > curdate() ".
          "and m.date < date_add(curdate(), interval 2 month)"
         );

$sent = [];

while(list($fname, $lname, $email, $yes, $name, $date, $talkSubject) = $S->fetchrow('num')) {
  # Some milestone dates
  # Contact member two months prior to talk if 'subject' is empty.
  # Contact member one month prior to talk if 'subject' is empty.
  # Contact member 2 weeks prior to talk as a reminder
  # Contact member 1 week prior to talk as a reminder
  
  $message =<<<MSG
$fname $lname your are scheduled to give a Meeting Presentation on $date.
You have not CONFIRMED your talk on the meeting page of our web site
(http://www.granbyrotary.org/meetings.php). If you are going to make your presentation 
please fill in the subject and presenters name if other than yours. If you can not make
the presentation please contact me ASAP so I can arrange for someone else to talk.
You can just email me (bartonphillips@gmail.com) and I will fill in the meeting subject
if you would rather not use the web page.

An automated email is sent to the SkyHiNews early Thursday morning with the presenter's name
and the topic of the talk. The information then appears in the SkyHiNews calendar on Friday
and on Wednesday of our meeting.

If you fail to CONFIRM your talk and don't provide a topic then the message in the
newspaper will have your name with a subject of "To be determined" which looks pretty dumb. 

Thank You
MSG;

  $subject = '';
  $footer = <<<MSG
--
The Rotary Club of Granby
http://www.granbyrotary.org

This is an automated message.
MSG;

  $unix_time = date("U", strtotime($date));
  $now = date("U");

  if($DEBUG) echo "$fname $lname $date $yes\n\n";

  if($unix_time - ONE_WEEK < $now) {
    $subject = "One Week to go till your talk.";

    if($yes == 'confirmed') {
      $message =<<<MSG
$fname $lname your are scheduled to give a Meeting Presentation next week ($date).
Your talk is: "$talkSubject" by "$name". This is just a reminder. If anything has come 
in the way of giving this talk please email me (bartonphillips@gmail.com) ASAP so I can 
arrange for someone else to give the talk.

Thank You
MSG;
    }
  } elseif($unix_time - 2 * ONE_WEEK < $now && ($yes != "confirmed")) {
    $subject = "Two Weeks till your talk.";
  } elseif($unix_time - ONE_MONTH < $now && ($yes != "confirmed")) {
    $subject = "Within One Month till your talk.";
  } elseif($unix_time - 2 * ONE_MONTH < $now && ($yes != "confirmed")) {
    $subject = "Within Two Months till your talk.";
  } else {
    continue;
  }
  
  $msg = <<<MSG
$message

$footer
MSG;

  if(!$DEBUG) {
    mail("$fname $lname <$email>", $subject, $msg, "From: info@granbyrotary.org\r\n".
         "CC: bartonphillips@gmail.com\r\n", "-fbartonphillips@gmail.com");

    echo "$subject\n$msg\n";
  } else {
    echo "DEBUG: $subject\n$msg\n";
  }
  $sent[] = "Responsible member: $fname $lname, Meeting Date: $date, \t$subject";
}

echo implode("\n", $sent) . "\n";
