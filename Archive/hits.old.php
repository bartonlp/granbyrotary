<?php
// Display Web Statistics for Granby Rotary

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;
$self = $S->self;

// This section is the Ajax back end for this page

switch($_GET["table"]) {
  case "pageHits":
    // Ajax
    $query = "select filename as Filename, count as Count from counter order by count desc"; //order by last desc";
    list($table) = $S->maketable($query, array(attr=>array(id=>"pageHits")));
    echo $table;
    exit();

  case "ipHits":
    // Ajax
     $query = "select ip as IP, id as ID, count as Count from logip order by count desc"; //order by lasttime desc";
     list($table) = $S->maketable($query, array(attr=>array(id=>"ipHits")));
     echo $table;
     exit();

  case "ipAgentHits":
    require_once("/home/bartonlp/includes/db.class.php");
    $GC = new Database('localhost:3306', '3890', 'aih1el1ooC4a', 'grandchoraledotorg');

    function ipagentcallback(&$row, &$desc) {
      global $GC;
      
      $tr = "<tr";
      if($row[id]) {
        if($row[id] == '25') {
          $tr .= " class='blp'";
        } 
      } else {
        $tr .= " class='noId'";
      }

      $result2 = $GC->query("select ip from askrobotstxt where agent='$row[Agent]'");
      if(mysql_num_rows($result2)) {
        $tr .= " style='color: blue'";
      }
      $tr .= ">";

      $desc = preg_replace("/<tr>/", $tr, $desc);
      return false;
    }

    $query = "select l.ip as IP, l.agent as Agent, l.id, concat(r.FName, ' ', r.LName) as Name, l.lasttime as LastTime from logagent as l
left join rotarymembers as r on l.id=r.id order by l.lasttime desc";
    list($table) = $S->maketable($query, array(callback=>ipagentcallback, attr=>array(id=>"ipAgentHits")));
    echo $table;
    exit();

  case "Bots":
    require_once("/home/bartonlp/includes/db.class.php");
    $GC = new Database('localhost:3306', '3890', 'aih1el1ooC4a', 'grandchoraledotorg');

    $query = "select * from askrobotstxt order by lasttime desc";
    list($table) = $GC->maketable($query, array(attr=>array(id=>"Bots")));
    echo $table;
    exit();

  case "osoursite":
    $ar = array(OS=>array(types=>array(), table=>""), Browser=>array(types=>array(), table=>""));
    $types = array(Windows, Linux, Macintosh);
    $ar[OS][types] = $types;
    $types = array(Opera, Chrome, Safari, SeaMonkey, Firefox, MSIE);
    $ar[Browser][types] = $types;

    $table = <<<EOF
<table id="osoursite1" style="width: 100%">
<thead>
<tr><th>OS</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead>
<tbody>

EOF;

    $ar[OS][table] = $table;
    
    $table = <<<EOF
<table id="osoursite2" style="width: 100%">
<thead>
<tr><th>Browser</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead><tbody>

EOF;

    $ar[Browser][table] = $table;

    // logagent is only updated in login.php
    // $result = $S->query("select sum(count) as count from logagent");
    // I have changed logagent to be updated on every access to a page.

    // Don't show webmaster

    $result = $S->query("select sum(count) as count from memberpagecnt where id!='25'");
    $total = mysql_result($result, 0);
    $result = $S->query("select count(*) as count from memberpagecnt where id!='25'");
    $records = mysql_result($result, 0);
    $result = $S->query("select id from memberpagecnt where id!='25' group by id");
    $totalmembers = mysql_num_rows($result);

    foreach($ar as $k=>$t) {
      $cnt = 0;
      foreach($t[types] as $type) {
        switch($type) {
          case "Chrome":
            $stype = "Chrome%Safari";
            break;
          case "Safari":
            $stype = "Safari%') and agent not like('%Chrome";
            break;
          default:
            $stype = $type;
            break;
        }
        // dont count webmaster
        $result = $S->query("select sum(count) as count from memberpagecnt where id !='25' and agent like('%$stype%')");
        $count = 0;
        if(mysql_num_rows($result)) {
          $row = mysql_fetch_assoc($result);
          $count = $row[count];
          $cnt += $count; 
          $percent = number_format($count / $total * 100, 2, ".", ",");
          $count = number_format($count, 0, "", ",");
          $result = $S->query("select count(*) from memberpagecnt where id !='25' and agent like('%$stype%')");
          $un = mysql_result($result, 0);
          $perun = number_format($un / $records * 100, 2, ".", ",");
          $un = number_format($un, 0, "", ",");
          $result = $S->query("select id from memberpagecnt where id !='25' and agent like('%$stype%') group by id");
          $mem = mysql_num_rows($result);
          $permem = number_format($mem / $totalmembers * 100, 2, ".", ",");
          $mem = number_format($mem, 0, "", ",");
        }
        $ar[$k][table] .= <<<EOF
<tr><th style='text-align: left'>$type</th><td style='text-align: right'>$count</td>
<td style='text-align: right'>$percent</td><td style='text-align: right'>$un</td>
<td style='text-align: right'>$perun</td>
<td style='text-align: right'>$mem</td>
<td style='text-align: right'>$permem</td></tr>

EOF;
      }
      $cnt = $total - $cnt;
      $percent = number_format($cnt / $total * 100, 2, ".", ",");
      $cnt = number_format($cnt, 0, "", ",");
      $ar[$k][table] .= <<<EOF
</tbody>
</table>

EOF;
}

      $ftotal = number_format($total, 0, "", ",");
      $records = number_format($records, 0, "", ",");

      $tbl = <<<EOF
<table>
<tbody>
<tr><td>Total Records</td><td style="text-align: right; padding: 5px">$records</td></tr>
<tr><td>Total Visits</td><td style="text-align: right; padding: 5px">$ftotal</td></tr>
<tr><td>Total Visitors</td><td style="text-align: right; padding: 5px">$totalmembers</td</tr>
</tbody>
</table>

<div id='osBrowserCntDiv' class='wrapper' style='width: 80%'>
<br>
<div id='OScnt' style="margin-bottom: 20px">
{$ar[OS][table]}
</div>
<div id='browserCnt'>
{$ar[Browser][table]}
</div>
</div>
<p>Note that in some cases a &quot;Visitor&quot; (that is an IP Address) has used two or more different browsers which makes the
total visitor and percent visitors more than the number of IP Addresses. For example a couple of our members use both Firefox (good)
and Microsoft Internet Explorer (bad) on the same computer (IP Adddress).</p>
<p>Also note that some of our visitors use several different computers (work, home, laptop etc). And finally most vitors have
dynamic rather than static IP Addresses supplied by there Internet Service Provider (ISP). That means that from time to time
their ISP changes their IP Address. All very complicated.</p>
<p>It is interesting to note that visitors using Firefox visite the site a lot more than those using MSIE (55% to 38%),
but more visitors use MSIE than do Firefox (53% to 50%). Also, while 25% of our visitors use a Mac they don't visit our
site very often, only 8% of the visits are made by Mac users. Does this tell us something?</p>
<ul style="clear: both; padding-top: 20px">
<li>The <i>Visits</i> columns show the total number of times a member with the <b>OS</b> or <b>Browser</b> visited our site.</li>
<li>The <i>Records</i> columns shows the total number of times the <b>IP/AGENT</b> was used to access our site (the
&quot;User Agent String&quot; has information about the OS and Browser.)</li>
<li>The <i>Visitors</i> columns show the number of members using the <b>OS</b> or <b>Browser</b>.</li>
</ul>
<p>This table does <b>not</b> include the accesses by the Webmaster at that would skew the results toward Linux and Firefox.</p>

EOF;
      echo $tbl;
      exit();

  case "oslamphost":
    echo showlamphost(30);  // all data
    exit();
}

// End of Ajax
// Start of Main Page logic

$extra = <<<EOF
<link rel="stylesheet"  href="/css/tablesorter.css" type="text/css" />
  <link rel="stylesheet" href="/css/hits.css" type="text/css" />
  <script type="text/javascript"
           src="/js/tablesorter/jquery.metadata.js"></script>
   
  <script type="text/javascript"
           src="/js/tablesorter/jquery.tablesorter.js"></script>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  var tablename="$_GET[table]";

  // create a div for name popup
  $("body").append("<div id='popup' style='position: absolute; display: none; border: 2px solid black; background-color: #8dbdd8; padding: 5px;'></div>");

  $("h2.table").each(function() {
    $(this).append(" <span class='showhide' style='color: red'>Show Table</span>");
  });

  $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
  $("#memberHits, #otherMemberHits, #memberNot").tablesorter();
  $("#lamphost table").tablesorter({ sortList: [[2,1]], headers: { 1: {sorter: "currency"} } } );
  $("#OScnt table").tablesorter({ sortList:[[1,1]] , headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}}});
  $("#browserCnt table").tablesorter({ sortList:[[1,1]], headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}} });

  $("div.table").hide();

  if(tablename != "") {
    $("div[name='"+tablename+"']").show();
    $("div[name='"+tablename+"']").prev().children().first().text("Hide Table");
  }
  $(".showhide").css("cursor", "pointer");

  $(".showhide").toggle(function() {
    tablename = $(this).parent().next().attr("name");
    var tbl = $("#"+tablename); // The <table
    var t = $(this); // The span
    var s = t.parent().next(); // <div class="table"
    if(tbl.length) {
      s.show(); // show the <div class="table"
      t.text("Hide Table");
    } else {
      $("body").css("cursor", "wait");
      t.css("cursor", "wait");

      $.get("$self", { table: tablename }, function(data) {
        $("div[name='"+tablename+"']").append(data);
        $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
        s.show();
        t.text("Hide Table");

        // Switch for the specific table

        switch(tablename) {
          case "osoursite":
            $("#osoursite1, #osoursite2").tablesorter({ sortList: [[1,1]],
               headers: { 1: {sorter: "currency"}, 3: {sorter: "currency"}, 5: {sorter: "currency"} } });
            break;
          case "oslamphost":
            $("#oslamphost1, #oslamphost2").tablesorter({ sortList: [[1,1]],
               headers: { 1: {sorter: "currency"} } });

            break;
          case "pageHits":
            $("#pageHits").tablesorter(); //{ sortList:[[1,1]] }
            break;

          case "Bots":
            $("#bottable").append($("#Bots"));
            $("#Bots").tablesorter();

            // Put up some buttons and text if we have javascript

            $("#bottable").before('<div id="botbottons" style="margin-bottom: 10px">Show <button id="endpolio">endpolio.com</button> ' +
              '<button id="bartonphillips">bartonphillips.com</button> ' +
              '<button id="grandchorale">grandchorale.org</button> <button id="granbyrotary">granbyrotary.org</button> ' +
              '<button id="tinapurwin">tinapurwininsurance.com</button> <button id="granbyranchnews">granbyranchnews.com</button>' +
              '<button id="showAll">All</button></div>');

            $("#botbottons button").css({'color': 'green', 'cursor':'pointer'});

            // If we click on the buttons show the appropriate site

            // If we click on the buttons show the appropriate site

            $("#endpolio").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('endpolio.com'))").parent().hide();
            });
            $("#bartonphillips").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('bartonphillips.com'))").parent().hide();
            });
            $("#grandchorale").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('grandchorale.org'))").parent().hide();
            });
            $("#granbyrotary").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('granbyrotary.org'))").parent().hide();
            });
            $("#tinapurwin").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('tinapurwininsurance.com'))").parent().hide();
            });
            $("#granbyranchnews").click(function() {
              $("#Bots tr").show();
              $("#Bots td:nth-child(4):not(:contains('granbyranchnews.com'))").parent().hide();
            });
            $("#showAll").click(function() {
              $("#Bots tr").show();
            });
  
            break;

          case "ipAgentHits":
            $("#ipAgentHits").tablesorter(); // { sortList:[[4,1]] }

            // Set up the Ip Agent Hits table
            $("#ipAgentHits").before("<p>You can toggle the display of only members or all visitors \
<input type='submit' value='Show/Hide NonMembers' id='hideShowNonMembers' /><br/> \
You can toggle the display of the ID \
<input type='submit' value='Show/Hide ID' id='hideShowIdField' /><br/> \
Your can taggle the display of the Webmaster \
<input type='submit' value='Show/Hide Webmaster' id='hideShowWebmaster' /> \
<p id='botmsg' style='color: blue;visibility: hidden'>Bots are blue</p>");

            // Ip Agent Hits.
            // Hide all ids of zero
            $(".noId").hide();

            // Button to toggle between all and members only in Ip Agent Hits
            // table
            $("#hideShowNonMembers").toggle(function() {
              $(".noId").not("[name='blp']").show();
              $("#botmsg").css("visibility", "visible");
            }, function() {
              $(".noId").hide();
              $("#botmsg").css("visibility", "hidden");
            });

            // Hide the ID field in Ip Agent Hits table
            $("#ipAgentHits td:nth-child(3)").hide();
            $("#ipAgentHits thead th:nth-child(3)").hide();

            // Button to toggle between no ID and showing ID in Ip Agent Hits
            // table
            $("#hideShowIdField").toggle(function() {
              $("#ipAgentHits td:nth-child(3)").show();
              $("#ipAgentHits thead th:nth-child(3)").show();
            }, function() {
              $("#ipAgentHits td:nth-child(3)").hide();
              $("#ipAgentHits thead th:nth-child(3)").hide();
            });

            // Hide the webmaster (me) in the Ip Agent Hits table
            $(".blp").hide();

            // Button to toggle between hide and show of Webmaster in Ip Agents
            // Hits Table
            $("#hideShowWebmaster").toggle(function() {
              $(".blp").show();
            }, function() {
              $(".blp").hide();
            });
            break;

         case "ipHits":
           $("#ipHits").tablesorter(); // {sortList:[[2,1]] }
           // Set up Ip Hits
           $("#ipHits").before("<p>Move the mouse over ID to see the member&apos;s name.<br/> \
Click to toggle <i>all</i> or <i>members-only</i> \
<input id='membersonly' type='submit' value='toggle all/members-only' /></p>");

           $("#ipHits tbody tr td:nth-child(2)").hover(function(e) {
             var name = "Non Member";
             if($(this).text() != '0') {
               name = $("#Id_"+$(this).text()).text();
             }
             $("#popup").text(name).css({ top: e.pageY+20, left: e.pageX }).show();
           }, function() {
             $("#popup").hide();
           });

           // Hide and mark all IDs that are zero in the Ip Hits table
           $("#ipHits tbody tr td:nth-child(2)").each(function() {
             var \$this = $(this);
             if(\$this.text() == '0') \$this.parent().addClass('ipHitsNoId').hide();
           });

           // Members only button toggles members and all in Ip Hits table
           $("#membersonly").toggle(function() {
             $(".ipHitsNoId").show();
           }, function() {
             $(".ipHitsNoId").hide();
           });
           break;
        } // end of switch
        $("body").css("cursor", "default");
        t.css("cursor", "pointer");
      });
    }
  }, function() {
    $(this).parent().next().hide();
    $(this).text("Show Table");
  });

});

  </script>

  <style type="text/css">
#daycount tbody tr td { /*visitors*/
  text-align: right;
}
#daycount tfoot tr th {
  text-align: right;
}
#daycount tfoot tr th:first-child {
  text-align: center;
}
  </style>
EOF;

$top = $S->getPageTop(array(title=>"Web Site Statistics", extra=>$extra), "<h2>Web Site Statistics</h2>");
$footer = $S->getFooter();

// Member Hits

// NOTE the last field is San Diego time so in all cases add on hour
// via addtime().

$result = $S->query("select concat(FName, ' ', LName), addtime(last, '1:0') as last from rotarymembers
where last = (select max(last) from rotarymembers where visits != 0 and id not in ('25', '$S->id'))");

list($lastName, $lastAccess) = mysql_fetch_array($result);

$result = $S->query("select status, count(*) as count from rotarymembers where status in ('active', 'honorary') group by status");

while($row = mysql_fetch_assoc($result)) {
  $ar[$row[status]] = $row[count];
}

$totalmembers = $ar[active] + $ar[honorary];

$query = "select id, FName, LName, visits, status,  addtime(last, '1:0') as last from rotarymembers
where visits != 0 and status in('active','honorary') order by last desc";

$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%visits%</td><td>%last%</td><td>%id%</td></tr>";

$callback = create_function('&$row, &$desc',
                            '$row[name] = $row[status] == "honorary" ?
                            "<span style=\'color: gray; border: 0\'>*</span>$row[FName] $row[LName]" : "$row[FName] $row[LName]";
                             return false;');

$r = true;
$table = $S->maketbodyrows($query, $rowdesc, $callback, $r, "%");

$membercnt = mysql_num_rows($r);

echo <<<EOF
$top
<div class='wrapper'>
<h3 style="text-align: center">Total Members: $totalmembers ($ar[active] active and $ar[honorary] honorary) Members</h3>
<p style="color: gray; text-align: center; margin-top: -15px">Asterisk '*' Denotes Honorary Members</p>
<div class='left'>
<p>Members using web site=$membercnt.</p>
<h2>Members Who Visited<br/>
Our Home Page</h2>

<table id='memberHits'>
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Last Access</th><th>Id</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>

EOF;

$query = "select id, FName, LName, visits, status, addtime(last, '1:0') as last, club from rotarymembers
where visits !=0 and status not in ('active', 'honorary') order by last desc";

$rowdesc = <<<EOF
<tr>
<td id='Id_%id%'>%FName% %LName%</td><td>%visits%</td><td>%status%</td><td>%last%</td><td>%id%</td><td>%club%</td>
</tr>

EOF;

$r = true;
$table = $S->maketbodyrows($query, $rowdesc, null, $r, "%");

$membercnt = mysql_num_rows($r);

echo <<<EOF
<p>Inactive Members, Visitors and Members from Other Clubs using this web site=$membercnt.</p>
<h2>Others Who Visited<br/>
Our Home Page</h2>
<table id='otherMemberHits'>
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Status</th><th>Last Access</th><th>Id</th><th>Club</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>
  
<p>Most recient access (not counting this one):
<ul>
   <li>$lastAccess</li>
   <li>$lastName</li>
</ul>
</p>
</div>

EOF;

$whos = $S->getWhosBeenHereToday();
$query = "select id, FName, LName, status from rotarymembers where visits = 0 and status in('active','honorary')";
$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%id%</td></tr>";
$r = true;
$table = $S->maketbodyrows($query, $rowdesc, $callback, $r, "%");

$membercnt = mysql_num_rows($r);

echo <<<EOF
<div class='right'>
<p>Members <b>not</b> using web site=$membercnt.</p>
<h2>Members<br/>Who Have Not Visited</h2>
<table id='memberNot'>
<thead>
<tr>
<th>Name</th><th>Id</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>
<br/>
$whos
</div>
</div>
<br  style='clear: both'/>

EOF;

// Check to see if there are any blocked ips
// The .htaccess file can have ip addresses that are directed to blocked.php which displays a message and updates the blocked
// table. It is also possible that bots etc might access the blocked.php even though they should not because it is disallowed in
// the robots.txt file.

// Look at the .htaccess file. Blocked IP's are between #<blocked ip addresses start> and #<blocked ip addresses end>
// each line looks like RewriteCond %{REMOTE_HOST} ^90.157.48.55 with a possible [OR] at the end.

$htaccess = file_get_contents(".htaccess"); // Get the whole file

if(preg_match("/^#<blocked ip addresses start>\s*(.*?)^#<blocked ip addresses end>/sm", $htaccess, $m)) {
  $lines = explode("\n", trim($m[1]));

  $blocked = array();

  foreach($lines as $line) {
    if(preg_match("/^\s*RewriteCond\s+%{REMOTE_HOST}\s+\^(.*?)(?:\s+\[OR\]\s*)?\s*$/", $line, $m)) {
      array_push($blocked, $m[1]);
    } else {
      echo "did not find ip address: $line<br>\n";
    }
  }
  echo <<<EOF
<p>These IP Addresses are BLOCKED via the .htaccess file:<br/>

EOF;

  foreach($blocked as $ip) {
    echo "$ip<br/>\n";
  }
  echo "</p>\n";
} else {
  echo "did not find block of blocked ip addresses<pre>$htaccess</pre>\n";
}

$query = "select * from blocked";
$rowdesc = "<tr><td>ip</td><td>agent</td><td>count</td><td>lasttime</td></tr>";
$title = $S->maketbodyrows($query, $rowdesc);
if($title !== false) {
  echo <<<EOF
<h2>IP Addresses that accessed <b>blocked.php</b></h2>
<p>These IP addresses were either redirected to blocked.php because they were in the .htaccess file,
or the IP Address directly accessed the blocked.php file.
The later may be Robots that are NOT reading or obeying the robots.txt file.</p>
<table id="blocked">
<thead>
<tr><th>IP</th><th>Agent</th><th>Count</th><th>Last Time</th></tr>
</thead>
<tbody>
$table
</tbody>
</table>

EOF;
}

//**********************************************************
// Start of Show/Hide Tables
//**********************************************************

$table = "";

// NOTE for the javascript in hits.js to work the <h2 class="table" needs to have the <div class="table" right after it!!!

if($S->isAdmin($S->id)) {
  $hasjs =  '<p><a href="viewjs_screen.php">View the hasjs and screensize tables</a></p>';
}

echo <<<EOF
<hr/>
<h2 class='table'>Page Hits</h2>
<div class='table' name="pageHits">
<p>From the <i>counter</i> table.</p>
<p>Count for all pages on the site.</p>
</div>
<hr/>
<p>More information about <a href='memberpagecnt.php'>page counts</a></p>
$hasjs
<hr/>

<h2 class='table'>IP Hits</h2>
<div class='table' name="ipHits">
<p>From the <i>logip</i> table.</p>
<p>Data is for accesses to all pages</p>
<a name='ipHits'></a>
</div>
<hr/>

<h2 class='table'>IP-AGENT Hits</h2>
<div class='table' name="ipAgentHits">
<p>From the <i>logagent</i> table with join to <i>rotarymembers</i> table.</p>
<p>Data is for accesses to our Home Page only</p>
<a name='ipAgentControl'></a>
</div>
<hr/>

<!-- Side by Side -->
<h2 class="table">OS and Browsers for Our Site</h2>
<div class="table" name="osoursite">
<p>From <i>memberpagecnt</i> table. This shows <b>Only Members</b> ie. people who have <i>Logged In</i>.</p>
</div>
<hr/>

<h2 class='table'>Bots</h2>
<div class='table' name="Bots">
<p><b>Robots</b> are supposed to read the &quot;robots.txt&quot; file to see what they are allowed to &quot;crawl&quot;.
All <b>good</b> robots read that file. By looking at the Apache Web Server&apos;s &quot;access log&quot; we can
count the agents that read the &quot;robots.txt&quot; file.</p>

<div id="bottable"></div>

<p>Not all the agents listed above are <b>bots</b>, anyone who reads the &quot;robots.txt&quot; file will be listed.
The entry for <i>Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.7) Gecko/2009030422 Ubuntu/8.10 (intrepid) Firefox/3.0.7</i>
is not a robot but in fact your webmaster checking the file over the web.</p>
<p>Also note that there are two different entries for
<a href='http://ysearchblog.com/2008/04/14/yahoo-slurp-30/'>Yahoo Slurp</a> in the <b>Agent Hits</b> and yet only
one in the <b>Bots</b> list.
It seems that the newer <i>Slurp/3.0</i> has not been reading the &quot;robots.txt&quot; file but leaving that to
the older version.</p>
</div>
<hr/>

<!-- Side by Side -->
<h2 class="table">OS and Browsers for Lamphost.net</h2>
<div class="table" name="oslamphost">
<p>Note the <i>Other</i> catigory. This is made up of <b>Robots</b>, <b>Cell Phones</b> and miscellaneous other odd agents.
We are relying on the <i>User Agent String</i> provided by browsers for both OS and Browser identification. Some Robots do not have
anything we can use to identify the OS or the Browser. For example, &quot;Baiduspider+(+http://www.baidu.com/search/spider.htm)&quot;
has no OS  or Browser information. Take a look at the <b>Bots</b> table above for more examples.</p>
</div>

<hr/>

EOF;

// Add daycount
$query = "select count(*) as Visitors, sum(count) as Count, sum(robotcnt) as Robots, sum(members) as Members,
sum(visits) as Visits from daycounts";
$result = $S->query($query);

list($Visitors, $Count, $Robots, $Members, $Visits) = mysql_fetch_array($result);

$result = $S->query("select date from daycounts order by date limit 1");
list($start) = mysql_fetch_array($result);

$ftr = "<tr><th>Totals</th><th>$Visitors</th><th>$Count</th><th>$Robots</th><th>$Members</th><th>$Visits</th></tr>";

$query = "select date as Date, count(*) as Visitors, sum(count) as Count, sum(robotcnt) as Robots, sum(members) as Members,
sum(visits) as Visits from daycounts group by date order by date desc";

list($tbl) = $S->maketable($query, array(footer=>$ftr, attr=>array(border=>"1", id=>"daycount")));

echo <<<EOF

<h2>Day Counts</h2>
<p>Day Counts do NOT include webmaster visits. The counts do however include ROBOTS who seem to
be much more interested in our web page than Rotarians.<br>
Day Counts started $start.</p>
$tbl
<p>Visitors are unique IP Addresses, Count is the total number of accesses to all pages,
Robots are the portion of Count that are Robots,
Visits are accesses seperated by 10 minutes</p>
<hr/>
<p>There are several interesting thing to notice in the tables on this page:
<ul>
   <li>The <i>ID</i> field is an internal database number that has no outside meaning. It is simply and index
      into the MySql database that has member information.</li>
   <li>If the <i>ID</i> field is zero of blank then the visitor is not a member of our club or has not yet
      logged in. Be sure to login once.</li>
   <li>Some of the agents are really &quot;Bots&quot;. For example, &quot;Slurp&quot; and &quot;Googlebot&quot;.
      &quot;Bots&quot; are robots that are used to scan the web for interesting thing. In the two cases sited
      here they are indexing the web for <i>Yahoo</i> and <i>Google</i> two of the biggest search providers.</li>
   <li>Most of our members are using <i>Firefox</i> or <i>Safari</i> (Mac OS X). It is good to see that most
      members are smart enough not to use <i>Internet Explorer</i>.</li>
   <li>I, Barton Phillips (the Webmster), have used several different web browsers. I do this to try and make sure the site
      looks OK no matter what a visitor is using as a browser, even a text only browser like <i>Links</i> or
      <i>lynx</i>. I have not been able to test the site to see if special browsers for people with disabilities
      work correctly. If anyone can help me here I would appreciate it.</li>
   <li>Unfortunately, most member are using Microsoft Windows. Fortunately, no one is using old versions of Windows like
      95, 98, or ME. There are a couple of Mac OS X members, and I seem to be the only Linux user (though I have
      tested the site with Windows XP and Windows 7).</li>
</ul>
More information on <b>Bots</b> can be found <a href='http://www.jafsoft.com/searchengines/search_engines.html'>here</a>.<br/>
As I see more interesting trends I will report there here</p>

<hr/>
$footer
EOF;

// ******************************************************************************************
// For lamphost data

function showlamphost($days) {
  global $S;

  $ar = array(OS=>array(types=>array(), table=>""), Browser=>array(types=>array(), table=>""));
  
  $types = array(Windows, Linux, SunOS, FreeBSD, OpenBSD, NetBSD, Macintosh);
  $ar['OS']['types'] = $types;

  $types = array(Opera, Safari, SeaMonkey, Firefox, MSIE, Konqueror, Wget, Links,
                 Lynx, Galeon, Chrome, Slurp, GoogleBot, msnbot, Baiduspider);
  
  $ar['Browser']['types'] = $types;

  $table = <<<EOF
<h2>$tabletitle</h2>
<table id="oslamphost1">
<thead>
<tr><th>OS</th><th>Count</th><th>%</th><th>Last $days</th><th>%</th></tr>
</thead>
<tbody>

EOF;
  $ar[OS][table] = $table;

  $table = <<<EOF
<table id="oslamphost2">
<thead>
<tr><th>Browser</th><th>Count</th><th>%</th><th>Last $days</th><th>%</th></tr>
</thead><tbody>

EOF;

  $ar[Browser][table] = $table;

  $result = $S->query("select sum(count) as count from virtualagentslog");
  list($total) = mysql_fetch_row($result);

  $totalrunlen =  0;
  list($result, $n) = $S->query("select counts from virtualagentslog", true);
  if($n) {
    while($c = mysql_fetch_row($result)) {
      $totalrunlen += array_sum(explode(",", rtrim($c[0], ',')));
    }
  }
  
  foreach($ar as $k=>$t) {
    $cnt = 0;
    $cntrunlen = 0;
    
    foreach($t['types'] as $type) {
      switch($type) {
        case "Chrome":
          $stype = "Chrome%Safari";
          break;
        case "Safari":
          $stype = "Safari%') and agent not like('%Chrome";
          break;
        default:
          $stype = $type;
          break;
      }

      $result = $S->query("select agent, count, counts from virtualagentslog where agent like('%$stype%')");
      
      while($row = mysql_fetch_assoc($result)) {
        extract($row);
        $ccount += $count;
        $countrunlen += array_sum(explode(',', rtrim($counts, ',')));
      }

      $cnt += $ccount;
      $cntrunlen += $countrunlen;

      if($total) $percent = number_format($ccount / $total * 100, 2, ".", ",");
      else $percent = 0;
      
      if($totalrunlen) $percentrunlen = number_format($countrunlen / $totalrunlen * 100, 2, ".", ",");
      else $percentrunlen = 0;
      
      $ccount = number_format($ccount, 0, "", ",");
      $countrunlen = number_format($countrunlen, 0, "", ",");
      
      $ar[$k][table] .= "<tr><th style='text-align: left'>$type</th><td style='text-align: right'>$ccount</td>".
                        "<td style='text-align: right'>$percent</td>".
                        "<td style='text-align: right'>$countrunlen</td><td style='text-align: right'>$percentrunlen</td></tr>\n";
    }
    $cnt = $total - $cnt;
    $cntrunlen = $totalrunlen - $cntrunlen;

    if($total) $percent = number_format($cnt / $total * 100, 2, ".", ",");
    else $percent = 0;
    
    $cnt = number_format($cnt, 0, "", ",");

    if($totalrunlen) $percentrunlen = number_format($cntrunlen / $totalrunlen * 100, 2, ".", ",");
    else $percentrunlen = 0;
    
    $cntrunlen = number_format($cntrunlen, 0, "", ",");
      
    $ar[$k][table] .= <<<EOF
<tr><th style='text-align: left'>Other</th>
<td style='text-align: right'>$cnt</td><td style='text-align: right'>$percent</td>
<td style='text-align: right'>$cntrunlen</td><td style='text-align: right'>$percentrunlen</td>
  </tr>
</tbody>
</table>

EOF;
  }
  $ftotal = number_format($total, 0, "", ",");
  $ftotalrunlen = number_format($totalrunlen, 0, "", ",");
  $tbl = <<<EOF
<p>From <i>virtualagentslog</i> table.</p>
<p>Total Records: $ftotal<br/>
<p>Total Records for $days days: $ftotalrunlen</p>
<div id='lamphost' class='wrapper' style='width: 70%'>
<div id='OSLamphostCnt' class='left'>
{$ar[OS][table]}
</div>
<div id='BrowserLamphostCnt' class='right'>
{$ar[Browser][table]}
</div>
</div>
<br style='clear: both'/>
<p><a href="countwindows.php">More Information about Windows Versions</a>.</p>

EOF;
  return $tbl;
}

?>
