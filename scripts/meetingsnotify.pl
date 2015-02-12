#!/usr/bin/perl -w
# Designed to be run as a cron job.
# Scan the meetings table and send emails to speakers

use strict;
use POSIX qw(mktime);
use DBI();
use Time::Local;

my $ONE_WEEK=604800;
my $ONE_MONTH=2629744;

my $DEBUG = 0;     #debug some

my $DBH;

$DBH = DBI->connect('dbi:mysql:granbyrotarydotorg', 'barton', '7098653') or die "Can't connect";

my $query = "select r.FName, r.LName, r.Email, m.yes, m.name, m.date, m.subject from rotarymembers as r " .
            "left join meetings as m on r.id = m.id ".
            "where r.Email != '' and m.type='speaker'";

my $sth = $DBH->prepare($query) or die $DBH->errstr;
$sth->execute() or die $DBH->errstr;

my @sent;

while(my $row = $sth->fetch) {
  my ($fname, $lname, $email, $yes, $name, $date, $talkSubject) = @$row;

  # Some milestone dates
  # Contact member two months prior to talk if 'subject' is empty.
  # Contact member one month prior to talk if 'subject' is empty.
  # Contact member 2 weeks prior to talk as a reminder
  # Contact member 1 week prior to talk as a reminder

  my $unix_time = date2unix($date);

  if($unix_time < time) {
    # already past so skip
    next;
  }
  
  my $message;
  my $subject;
  my $footer = <<"MSG";

--
The Rotary Club of Granby
http://www.granbyrotary.org

This is an automated message.

MSG
               
  if($unix_time - $ONE_WEEK < time()) {
    #print "Within one week\n";
    $subject = "One Week to go till your talk.";

    if($yes ne 'confirmed') {
      $message = <<"MSG";
$fname $lname your are scheduled to give a Meeting Presentation next week ($date).
You have not CONFIRMED your talk on the meeting page of our web site
(http://www.granbyrotary.org/meetings.php).
If you are going to make your presentation please fill in the subject and presenters name
if other than yours.
If you can not make the presentation please contact me ASAP so I can arrange for someone else to talk.
You can just reply to this email and I will fill in the meeting subject if you would rather not
use the web page.

An automated email is sent to the SkyHiNews early Thursday morning with the presenter's name and
the topic of the talk. The information then appears in the SkyHiNews calendar on Friday
and on Wednesday of our meeting.

If you fail to CONFIRM your talk and don't provide a topic then the message in the
newspaper will have your name with a subject of "To be determined" which looks pretty dumb. 

Thank You

MSG
    } else {
      $message = <<"MSG";
$fname $lname your are scheduled to give a Meeting Presentation next week ($date). Your talk is:
"$talkSubject" by "$name". This is just a reminder. If anything has come in the way of giving this talk
please reply to this email and let me know ASAP so I can arrange for someone else to give the talk.

Thank You

MSG
    }
  } elsif($unix_time - 2*$ONE_WEEK < time() && ($yes ne "confirmed")) {
    print "Within two weeks\n";
    $subject = "Two Weeks till your talk.";

    $message = <<"MSG";
$fname $lname your are scheduled to give a Meeting Presentation on $date.
Please check the web site at http://www.granbyrotary.org/meetings.php and
fill in the 'Subject' and the name of the presenter if is no going to be you.
Or you can reply to this email with the subject of your talk and I will fill in the
information for you.
Once the subject is filled in on the website I will not bother you until the week before your talk.

An automated email is sent to the SkyHiNews early Thursday morning with the presenter's name and
the topic of the talk. The information then appears in the SkyHiNews calendar on Friday
and on Wednesday of our meeting.

If you fail to CONFIRM your talk and don't provide a topic then the message in the
newspaper will have your name with a subject of "To be determined" which looks pretty dumb. 

Thank You

MSG
  } elsif($unix_time - $ONE_MONTH < time() && ($yes ne "confirmed")) {
    #print "Within one month\n";
    $subject = "Within One Month till your talk.";

    $message = <<"MSG";
$fname $lname your are scheduled to give a Meeting Presentation on $date.
Please check the web site at http://www.granbyrotary.org/meetings.php and
fill in the 'Subject' and the name of the presenter if is no going to be you.
Or you can reply to this email with the subject of your talk and I will fill in the information for you.
Once the subject is filled in on the website I will not bother you until the week before your talk.

Thank You

MSG
  } elsif($unix_time - 2* $ONE_MONTH < time() && ($yes ne "confirmed")) {
    #print "Within two months\n";
    $subject = "Within Two Months till your talk.";

    $message = <<"MSG";
$fname $lname your are scheduled to give a Meeting Presentation on $date.
Please check the web site at http://www.granbyrotary.org/meetings.php and
fill in the 'Subject' and the name of the presenter if is no going to be you.
Or you can reply to this email with the subject of your talk and I will fill
in the information for you.
Once the subject is filled in on the website I will not bother you until the week before your talk.

Thank You

MSG
  } else {
    next;
  }
  
  
  my $msg = <<"MSG";
From: Barton Phillips <bartonphillips\@gmail.com>
To: $fname $lname <$email>
CC: bartonphillips\@gmail.com
Subject: $subject

$message
$footer
MSG

  if(!$DEBUG) {
    open(SENDMAIL, "|/usr/sbin/sendmail -t -oi") or die "Can't fork for sendmail: $!\n";
    print SENDMAIL $msg, "\n";
    close(SENDMAIL);
  } else {
    print "$msg\n";
  }
  push @sent, "Responsible member: $fname $lname, Meeting Date: $date, \t$subject";
}

# my $msg = <<"MSG";
# From: Barton Phillips <barton\@granbyrotary.org>
# To: bartonphillips\@gmail.com
# Subject: Automated Message About Meeting Presentation
# 
# Reminder message was sent to the following:
# 
# MSG
# 	  
# $msg .= join "\n", @sent;
# 
# if(!$DEBUG) {
#   open(SENDMAIL, "|/usr/sbin/sendmail -t -oi") or die "Can't fork for sendmail: $!\n";
#   print SENDMAIL $msg, "\n";
#   close(SENDMAIL);
# } else {
#   print "$msg\n";
# }

$sth->finish;

# Subs -----------

sub date2unix {
  my $date = shift;
  
  $date =~ /(\d{4,4})-(\d{2,2})-(\d{2,2})/;
  my $yr = $1 - 1900;
  my $mo = $2 -1;
  my $dy = $3;

  $date = mktime(0, 0, 0, $dy, $mo, $yr);
  return $date;
}
__END__
