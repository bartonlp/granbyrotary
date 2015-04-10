<?php
// Display Web Statistics for Granby Rotary
// BLP 2015-03-15 -- check if id is me. Clean up logic so everything is done and put in
// variables and then at end we do 'render page'.
// BLP 2015-02-12 -- use barton.bots and bots2
// BLP 2014-11-02 -- tracker average now works with the showing display.

require_once("/var/www/includes/siteautoload.class.php");

$myIp = gethostbyname($siteinfo['myUri']); // get my home ip address

// ********************************************************************************
// AJAX Start

// Ajax updatebots

if($_GET['page'] == 'updatebots') {
  $S = new Database($dbinfo);
  // BLP 2015-02-12 -- 
  $S->query("insert ignore into barton.bots2 (agent) value('{$_GET['agent']}')");
  
  $sql = "insert ignore into barton.bots (ip) values('{$_GET['ip']}')";

  $n = $S->query($sql);
  
  echo json_encode(array('n'=>$n, 'sql'=>$sql));
  exit();
}

// Ajax table
// This section is the Ajax back end for this page. This is called via $.get()

if($_GET["table"]) {
  $S = new Database($dbinfo);
  $T = new dbTables($S);

  switch($_GET["table"]) {
    case "tracker":
      $sql = "select ip from tracker where starttime > date_sub(now(), interval 3 day)";

      $S->query($sql);
      $tkipar = array();

      while(list($tkip) = $S->fetchrow('num')) {
        $tkipar[] = $tkip;
      }
      $list = json_encode($tkipar);
      $ipcountry = file_get_contents("http://www.bartonlp.com/webstats-new.php?list=$list");
      $ipcountry = (array)json_decode($ipcountry);

      $sql = "select id, concat(FName, ' ', LName) from rotarymembers";
      $S->query($sql);
      $members = array();

      while(list($id, $name) = $S->fetchrow('num')) {
        $members[$id] = $name;
      }

      $sql = "select ip, id from memberpagecnt";

      $S->query($sql);
      $memberbyip = array();

      while(list($ip, $id) = $S->fetchrow('num')) {
        $memberbyip[$ip] = $id;
      }

      function tcallback(&$row, &$desc) {
        global $memberbyip, $members, $S, $ipcountry;

        $ip = $S->escape($row['ip']);
        $agent = $S->escape($row['agent']);
        $co = $ipcountry[$ip];
        $row['ip'] = "<span class='co-ip'>$ip</span><br><div class='country'>$co</div>";
        $ref = urldecode($row['referrer']);
        // if google then remove the rest because google doesn't have an info in q= any more.
        if(strpos($ref, 'google') !== false) {
          $ref = preg_replace("~\?.*$~", '', $ref);
        }
        $row['referrer'] = $ref;
        if($memberbyip["$ip"]) {
          $row['Member'] = $members[$memberbyip["$ip"]];
        } else {
          $row['Member'] = '';
          if($S->query("select ip from barton.bots where ip='$ip'") ||
              $S->query("select agent from barton.bots2 where agent='$agent'")) {
            $desc = preg_replace("~<tr>~", "<tr class='bot'>", $desc);
          }
        }
      }

      $sql = "select page, ip, 'Member', agent, starttime, endtime, difftime as time, ".
             "referrer ".
             "from tracker where starttime > date_sub(now(), interval 3 day) ".
             "order by starttime desc";

      list($trackertable) = $T->maketable($sql, array('callback'=>tcallback,
        'attr'=>array('id'=>"tracker", 'border'=>'1')));

      if(empty($trackertable)) {
        $trackertable = "No Data Found";
      }
      echo $trackertable;
      break;
    case "daycount":
      // Callback to get the number of IPs that were members.

      $memtotal = 0;

      function callback(&$row, &$desc) {
        global $S, $memtotal;
        // For this Date see if there are any members
        $date = $row['Date'];
        $n = $S->query("select count(*) ".
                       "from daycounts where date='$date' and members!=0 group by date;");

        if($n) {
          list($c) = $S->fetchrow('num');
          $row['Members'] = $c;
          $memtotal += $c;
        }
      }

      // Get info for <fbody>
      
      $query = "select count(*) as Visitors, sum(count) as Count, sum(robotcnt) as Robots, ".
               "sum(members) as Members, sum(visits) as Visits from daycounts ".
               "where lasttime > date_sub(now(), interval 7 day) order by date";

      $S->query($query);

      list($Visitors, $Count, $Robots, $Members, $Visits) = $S->fetchrow('num');

      $ftr = "<tr><th>Totals</th><th>$Visitors</th><th>$Count</th>".
             "<th>$Robots</th><th>$Members</th><th class='memtotal'>&nbsp;</th>".
             "<th>$Visits</th></tr>";

      // Get info for <body>
      
      $query = "select date as Date, count(*) as Visitors, sum(count) as Count, ".
               "sum(robotcnt) as RobotsCnt, sum(members) as MembersCnt, ".
               "'0' as Members, sum(visits) as Visits ".
               "from daycounts where date > date_sub(now(), interval 7 day) ".
               "group by date order by date desc";

      // Make the table
      list($tbl) = $T->maketable($query,
                                 array('callback'=>'callback',
                                       'footer'=>$ftr,
                                       'attr'=>array('border'=>"1", 'id'=>"daycount")));

      $dayCount = preg_replace("~(<tfoot>.*?<th class='memtotal'>)&nbsp;~sm", "\${1}$memtotal",
                               $tbl);
      echo $dayCount;
      break;      
    case "counter":
      $sql = "select * from counter where lasttime > date_sub(now(), interval 7 day)";
      list($table) = $T->maketable($sql, array('attr'=>array('id'=>"counter", 'border'=>"1")));
      echo $table;
      break;
    case "counter2":
      $sql = "select * from counter2 where lasttime > date_sub(now(), interval 7 day) ".
             "order by lasttime desc";
      list($table) = $T->maketable($sql, array('attr'=>array('id'=>"counter2", 'border'=>"1")));
      echo $table;
      break;
    case "memberpagecnt":
      $sql = "select * from memberpagecnt where lasttime > date_sub(now(), interval 7 day) " .
             "order by lasttime desc";
      list($table) = $T->maketable($sql, array('attr'=>array('id'=>"memberpagecnt", 'border'=>"1")));
      echo $table;
      break;
    case "ipAgentHits":
      function ipagentcallback(&$row, &$desc) {
        global $S;

        $ip = $S->escape($row['IP']);

        $tr = "<tr";

        // escape markup in agent
        $row['Agent'] = escapeltgt($row['Agent']);

        if($row['id']) {
          if($row['id'] == '25') {
            $tr .= " class='blp'";
          } 
        } else {
          $tr .= " class='noId'";
          $n = $S->query("select ip from barton.bots where ip='$ip'");
          if($n) {
            $tr .= " style='color: red'";
          } else {
            // BLP 2014-11-16 -- Look for 'http://' in agent and if found add it to the
            // bots table.
            if(preg_match('~http://~', $row['Agent'])) {
              $sql = "insert ignore into barton.bots value('$ip')";
              $S->query($sql);
              $tr .= " style='color: red'";
            }
          }

        }
        $tr .= ">";

        //echo "id: " . $row['id'] . "<br>";
        
        $desc = preg_replace("/<tr>/", $tr, $desc);
        return false;
      }

      $query = "select l.ip as IP, l.agent as Agent, l.id, ".
               "concat(r.FName, ' ', r.LName) as Name, " .
               "l.lasttime as LastTime from logagent as l ".
               "left join rotarymembers as r on l.id=r.id" .
               " where l.lasttime > date_sub(now(), interval 7 day) ".
               "order by l.lasttime desc";

      list($table) = $T->maketable($query, array('callback'=>'ipagentcallback',
        'attr'=>array('id'=>"ipAgentHits", 'border'=>'1')));

      echo $table;
      break;

    case "osoursite1":
      // BLP 2014-12-09 -- redone
      // Don't show webmaster

      $records = $S->query("select m.id, ip, page, agent, count, m.lasttime ".
                           "from memberpagecnt as m left join rotarymembers as r ".
                           "on m.id=r.id where m.id !='25' and r.otherclub='granby' and ".
                           "r.status='active'");
      $rows = array();
      
      while($row = $S->fetchrow('assoc')) {
        $rows[] = $row;
      }

      $total = 0;
      $members = array();
      $counts = array();
      $pat = "~Safari|Opera|Chrome|MSIE|Firefox|Konqueror|Window|Linux|Mac OS X|".
             "Macintosh|Android|iPhone~i";

      $browsers = array("safari", "opera", "chrome", "msie", "firefox", "fonqueror");
      $os = array("mac os x", "window", "linux", "android", "iphone");
      
      foreach($rows as $v) {
        $agent = $v['agent'];
  
        if(preg_match_all($pat, $agent, $m)) {
          //echo "<p>Agent: $agent, count: {$v['count']}";
          $dup = array();
          // Make the array lower case.
          
          $mm = array_map(strtolower, $m[0]);

          foreach($mm as $x) {
            switch($x) {
              case 'chrome':
                $dup['safari'] = true;
                $counts['browser'][$x] += $v['count'];
                //$what .= "$x,";
                break;
              case 'macintosh':
                $dup['mac os x'] = true;
                $counts['os']['mac os x'] += $v['count'];
                //$what .= "mac os x,";
                break;
              default:
                if(!$dup[$x]) {
                  $type = in_array($x, $os) ? "os" : "browser";
                  $counts[$type][$x] += $v['count'];
                  //$what .= "$x,";
                }
                break;
            }
            $dup[$x] = true;
          }
          if(count(array_intersect($mm, $browsers)) == 0) {
            $counts['browser']['other'] += $v['count'];
          }
          //echo "<br>WHAT: " .rtrim($what, ',') ."<br>****</p>";
        } else {
          //echo "Agent: $agent<br>Not found.<br>****<br>";
          $counts['os']['other'] += $v['count'];
        }

        $total += $v['count'];
        $members[$v['id']]++;
      }
      $totalmembers = count($members);
      foreach(array('os'=>"osoursite1", 'browser'=>'osoursite2') as $k=>$v) {
        $ar[$k] = <<<EOF
<table id="$v" border="1">
<thead>
<tr><th style="text-transform: uppercase;">$k</th><th>Visits</th><th>%</th></tr>
</thead>
<tbody>
EOF;
      }
      
      foreach($counts as $k=>$v) {
        foreach($v as $kk=>$vv) {
          $per = number_format($vv / $total * 100, 2);
          $vv = number_format($vv);
          $ar[$k] .= <<<EOF
<tr>
<th>$kk</th>
<td style='text-align: right'>$vv</td>
<td style='text-align: right'>$per</td>
</tr>
EOF;
        }
      }
$srecords = number_format($records);
$stotal = number_format($total);
$stotalmembers = number_format($totalmembers);
      $tbl = <<<EOF
<table border="1">
<tbody>
<tr><td>Total Records</td><td style="text-align: right; padding: 5px">$srecords</td></tr>
<tr><td>Total Visits</td><td style="text-align: right; padding: 5px">$stotal</td></tr>
<tr><td>Total Visitors</td><td style="text-align: right; padding: 5px">$stotalmembers</td</tr>
</tbody>
</table>
<div id='osBrowserCntDiv' class='wrapper' style='width: 100%'>
<br>
<div id='OScnt' style="margin-bottom: 20px">
{$ar['os']}
</tbody>
</table>
</div>
<div id='BrowserCnt' style="margin-bottom: 20px">
{$ar['browser']}
</tbody>
</table>
</div>
</div>
EOF;
      echo $tbl;
      break;
  }
  exit();
}

// End of Ajax
// ********************************************************************************

// ********************************************************************************
// Start of Main Page logic

$S = new GranbyRotary;
$T = new dbTables($S);

$sql = "select id, concat(FName, ' ', LName) from rotarymembers";
$S->query($sql);
$members = array();

while(list($id, $name) = $S->fetchrow('num')) {
  $members[$id] = $name;
}

$sql = "select ip, id from memberpagecnt";

$S->query($sql);
$memberbyip = array();

while(list($ip, $id) = $S->fetchrow('num')) {
  $memberbyip[$ip] = $id;
}

$jmembers = json_encode($members);

// Member Hits

// NOTE the last field is San Diego time so in all cases add on hour
// via addtime().

// Get the last person to access the webpage

$S->query("select concat(FName, ' ', LName), addtime(visittime, '1:0') as lastvisit from rotarymembers
where visittime = (select max(visittime) from rotarymembers where visits != 0 and id not in ('25', '$S->id'))");

list($fullName, $lastAccess) = $S->fetchrow();

// Get the count of active and honorary members.

$S->query("select status, count(*) as count from rotarymembers where status in ('active', 'honorary') group by status");

while($row = $S->fetchrow()) {
  $ar[$row['status']] = $row['count'];
}

$activeMembers = $ar['active'];
$honoraryMembers = $ar['honorary'];

// Count of active and honorary
$totalMembers = $activeMembers + $honoraryMembers;

// Members Who Visited
$query = "select id, FName, LName, visits, status,  addtime(visittime, '1:0') as lastvisit ".
         "from rotarymembers ".
         "where visits != 0 and status in('active','honorary') order by lastvisit desc";

$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%visits%</td><td>%lastvisit%</td><td>%id%</td></tr>";

// function to add '*' to honorary members
function callback2(&$row, &$desc) {
  $row['name'] = ($row['status'] == "honorary") ?
                 "<span style='color: gray; border: 0'>*</span>{$row['FName']} {$row['LName']}" : "{$row['FName']} {$row['LName']}";
  return false;
}

$ar = array('callback'=>'callback2', 'delim'=>"%"); // Use this for all makeresultrows()

$visitedSiteMembers = $T->makeresultrows($query, $rowdesc, $ar);
$memberCnt = $S->getNumRows();

// Others who visited

$query = "select id, FName, LName, visits, status, addtime(visittime, '1:0') as lastvisit, club ".
         "from rotarymembers ".
         "where visits !=0 and status not in ('active', 'honorary') ".
         "and visittime > date_sub(now(), interval 1 month) order by lastvisit desc";

$rowdesc = <<<EOF
<tr>
<td id='Id_%id%'>%FName% %LName%</td><td>%visits%</td><td>%status%</td><td>%lastvisit%</td><td>%id%</td><td>%club%</td>
</tr>
EOF;

$otherMemberHits = $T->makeresultrows($query, $rowdesc, $ar);

if(!empty($otherMemberHits)) {
  $otherMemberHits = <<<EOF
<h2>Others Who Visited<br>
Our Site in the Last Month</h2>

<table id='otherMemberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Status</th><th>Last Visit</th><th>Id</th><th>Club</th>
</tr>
</thead>
<tbody>
$otherMemberHits
</tbody>
</table>
EOF;
}

$whos = $S->getWhosBeenHereToday();

// Members who have NOT visited.

$query = "select id, FName, LName, status from rotarymembers where visits = 0 and status in('active','honorary')";
$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%id%</td></tr>";

$notVisitedSiteMembers = $T->makeresultrows($query, $rowdesc, $ar);
$memberNotVisitCnt = $S->getNumRows();

// Setup extra, banner, titel for page.

// BLP 2015-03-15 -- The javascript should probably be put in an external file and loaded
// rather than having it in line like this.
// However if one does that the $xxx stuff must be fixed!

$h->extra = <<<EOF
  <!-- Custome links and scripts for this page -->
  <link rel="stylesheet"  href="css/tablesorter.css">
  <link rel="stylesheet" href="css/webstats.css">
  <script src="js/tablesorter/jquery.metadata.js"></script>
  <script src="js/tablesorter/jquery.tablesorter.js"></script>
  <script src="js/webstats.js"></script>
  <!-- Inline to init variables form webstats.js via PHP. -->
  <script>
  var idName=$jmembers,
  ajaxfile = "$S->self",
  myIp = "$myIp";

  console.log("ajaxfile: %s", ajaxfile);
  </script>
EOF;

$h->banner = "<h2>Web Site Statistics</h2><p>All times are New York time.</p>";
$h->title = "Web Site Statistics";

list($top, $footer) = $S->getPageTopBottom($h);

// Render Page

echo <<<EOF
$top
<div class='wrapper'>
<h3 style="text-align: center">Total Members: $totalMembers ($activeMembers active
and $honoraryMembers honorary) Members</h3>
<p style="color: gray; text-align: center; margin-top: -15px">Asterisk '*' Denotes Honorary Members</p>
<div class='left'>
<p>Members using web site=$memberCnt.</p>
<h2>Members Who Visited<br>
Our Site</h2>
<table id='memberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Last Visit</th><th>Id</th>
</tr>
</thead>
<tbody>
$visitedSiteMembers
</tbody>
</table>
$otherMemberHits
<p>Most recient access (not counting this one):
<ul>
   <li>$lastAccess</li>
   <li>$fullName</li>
</ul>
</p>
</div>
<div class='right'>
<p>Members <b>not</b> using web site=$memberNotVisitCnt.</p>
<h2>Members<br>Who Have Not Visited</h2>
<table id='memberNot' border="1">
<thead>
<tr>
<th>Name</th><th>Id</th>
</tr>
</thead>
<tbody>
$notVisitedSiteMembers
</tbody>
</table>
<br>
$whos
</div>
</div>
<br  style='clear: both'>

<!-- Start Show/Hide wrapper -->
<div class="wrapper">

<hr>
<h2 class='table'>Page Count</h2>
<div class='table' name="counter">
<p>From the <i>counter</i> table for last 7 days.</p>
</div>

<hr>
<h2 class='table'>Page Count2</h2>
<div class='table' name="counter2">
<p>From the <i>counter2</i> table for last 7 days.</p>
</div>

<hr>
<h2 class='table'>Page Tracker</h2>
<div class='table' name="tracker">
<p>From the <i>tracker</i> table for last 3 days.</p>
<p>Average stay time: <span id='average'></span> (times over an hour are discarded.)</p>
</div>

<hr>
<h2 class='table'>Member Page Count</h2>
<div class='table' name="memberpagecnt">
<p>From the <i>memberpagecnt</i> table for last 7 days.</p>
</div>

<hr>
<h2 class='table'>IP-AGENT Hits</h2>
<div class='table' name="ipAgentHits">
<p>From the <i>logagent</i> table with join to <i>rotarymembers</i> table for last 7 days.</p>
</div>

<hr>
<!-- Side by Side -->
<h2 class="table">OS and Browsers for Our Site</h2>
<div class="table" name="osoursite1">
<p>From <i>memberpagecnt</i> table.
This shows <b>Only Active Granby Members and NOT the Webmaster</b>.<br>
<b>Total Records</b> and <b>Total Vists</b> includes every page on the site.<br>
Total Visitors should equal the <b>Members Who Visited
Our Site</b> at the top of the page less the Webmaster.</p>
</div>

<hr>
<h2 class='table'>Day Counts</h2>
<div class='table' name="daycount">
<p>Visitors are unique IP Addresses, Count is the total number of accesses to all pages,
RobotsCnt are the portion of Count that are Robots, MembersCnt are the portion of Count that are Members,
Members are the number of unique IP Addresses that are Members, Visits are accesses seperated by 10 minutes.</p>

<p>Day Counts do NOT include webmaster visits. The counts do however include ROBOTS who seem to
be much more interested in our web page than Rotarians.<br>
Only showing 7 days.</p>
</div>

<!-- end of show/hide wrapper -->
</div>

<!-- Bottom information -->

<hr>
<p>There are several interesting thing to notice in the tables on this page:
<ul>
<li>The <i>ID</i> field is an internal database number that has no outside meaning. It is simply an
index into the MySql database that has member information.</li>
<li>If the <i>ID</i> field is zero of blank then the visitor is not a member of our club or has not
yet logged in. Be sure to login once.</li>
<li>Some of the agents are really &quot;Bots&quot;. For example, &quot;Slurp&quot; and
&quot;Googlebot&quot;.  &quot;Bots&quot; are robots that are used to scan the web for interesting
thing. In the two cases sited here they are indexing the web for <i>Yahoo</i> and <i>Google</i> two
of the biggest search providers.</li>
<li>Most of our members are using <i>Firefox</i>, <i>Chrome</i> or <i>Safari</i> (Mac OS X). It is
good to see that most members are smart enough not to use <i>Internet Explorer</i> though recent
versions of IE (9, 10, 11) do seem to be pretty standard complient now.</li>
<li>I, Barton Phillips (the Webmster), have used several different web browsers. I do this to try
and make sure the site looks OK no matter what a visitor is using as a browser, even a text only
browser like <i>Links</i> or <i>lynx</i>. I have not been able to test the site to see if special
browsers for people with disabilities work correctly. If anyone can help me here I would appreciate
it.</li>
<li>Unfortunately, most member are using Microsoft Windows. Fortunately, no one is using old
versions of Windows like 95, 98, or ME. There are a couple of Mac OS X members, and I seem to be the
only Linux user (though I have tested the site with Windows XP, Windows 7 and Windows 8/8.1).</li>
</ul>
<hr>
$footer
EOF;
