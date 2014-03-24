#!/usr/bin/php -q
<?php
// This is a CLI PHP script
// does what cntagents.php and does a 100 day moving count.
// This replaces cntagents.php in the crontab!

/* Table
CREATE TABLE `runlength` (
  `agent` varbinary(255) NOT NULL, # case sensitive
  `count` int(11) default 0,  # total forever
  `counts` varchar(255),      # comma seperated list of last 30 day counts. 30 days of counts in the '999999,' (6 digits plus a coma) fits in 255.
  `flag` int(1) default 0,    # if updated today. Set during part one reset at end
  PRIMARY KEY  (`agent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

The 'counts' is updated each day.
The 'counts' string is a comma seperated list of each count for 30 days.
Each day the first number in the string is removed and the days count is added to the end of the string.
The n day count is the sum of the days in 'counts'.
To start off there are 29 zeros and today' count which is the same as 'count'.
This program only accumulates the counts it does not to the adding etc.
Every agent is updated every day even if it is not found in the access log for that day. If the agent is not in the access log then a
zero is added at the end of the string and the first count in the string is removed.
For example: say we have five days instead of 30. The five days are 3,8,9,10,2. Today this agent is not in the log so the first count '3,' is
removed and today's count is added at the end (say today was 30): 8,9,10,2,30. If the agent was not in the log today the '3,' would be
removed and a zero would be added at the end: 8,9,10,2,0.
*/

// Read the /var/log/apache2/virtualhost_access_log log file
// This file runs around 40 to 50M

require_once("/home/bartonlp/includes/db.class.php");
// Database Information

$Host = "localhost:3306";
$User = "2844";
$Password = "7098653";
$Database = "granbyrotarydotorg";

$db = new Database($Host, $User, $Password, $Database);

$f = fopen("/var/log/apache2/virtualhost_access_log", "r");
if(!$f) {
  exit("Can't open file\n");
}

$agents = array();

while(!feof($f)) {
  $line = rtrim(fgets($f));

  if(preg_match('/^.*?".*?".*?".*?".*?"(.*?)"/', $line, $m)) {
    $agents[$m[1]]++;
  }
}
fclose($f);

$table = "runlength"; 

// now update the database

foreach($agents as $k=>$v) {
  if(empty($k)) {
    continue;
  }

  $k = $db->escape($k); // Agent

  try {
    // Try an insert. If it fails do the exception
    // New agents get 29 zeros and todays value
    $zeros = "0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";
    
    $query = "insert into $table (agent, count, counts, flag) values('$k', '$v', '$zeros,$v', 1)";
    $db->query($query);
  } catch(Exception $e) {
    if($e->getCode() == 1062) {
      // Already exists so do a select
      list($result, $n) = $db->query("select counts ".
                                     "from $table where agent='$k'", true);
      
      if(!n) {
        throw(new Exception("select agent=$k not found after dup key error"));
      }

      list($counts) = $db->fetchrow($result);

      // Remove the first day value and add today's value at the end. We still have 30 values.
      
      $counts = preg_replace("/^\d+,(.*)/", "$1,$v", $counts);

      if(preg_match('~Yandex/1\.01\.001 (compatible; Win16; I)~', $agent)) {
        echo "$agent, $count, $counts, v=$v\n";
      }
      
      $db->query("update $table set count=count+'$v', counts='$counts', flag=1 where agent='$k'");
    } else {
      throw($e);
    }
  }
}
// Now update every one else who does NOT have the 'flag' field set. We need to take the 'counts' and remove the first value
// and add a zero at the end. To do that we look for the first comma in the string and add one to get the comma too. Then
// we get the mid string or substring from the character after the first comma to the end of the line. We concatanate a comma and
// a zero at the end. Now we have 30 days and reset the 'flag' to zero.

$query = "update $table set counts=concat(mid(counts, locate(',', counts)+1), ',0') where flag=0";
$db->query($query);
$db->query("update $table set flag=0");

?>