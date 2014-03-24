<?php
// Display Web Statistics for Granby Rotary

$referer = $_SERVER['HTTP_REFERER'];

if(!preg_match("/granbyrotary\.org/", $referer)) {
  echo <<<EOL
<h1>Access Forbiden</h1>
<p>You must get here via the <a href="/">Home Page</a></p>

EOL;
  exit();
}

define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

// Ajax updatebots

if($_GET['page'] == 'updatebots') {
  $S = new Database($dbinfo);
  
  $sql = "insert ignore into bots (ip, agent) values('{$_GET['ip']}', '{$_GET['agent']}')";

  $n = $S->query($sql);

  echo json_encode(array('n'=>$n, 'sql'=>$sql));
  exit();
}

// This section is the Ajax back end for this page. This is called via $.get()

if($_GET["table"]) {
  $S = new Database($dbinfo);
  $t = new dbTables($S);

  switch($_GET["table"]) {
    case "tracker":
      $myIp = gethostbyname($siteinfo['myUri']); // get my home ip address

      $sql = "select * from tracker where ip!='$myIp' && " .
             "starttime > date_sub(now(), interval 7 day) ".
             "order by starttime desc";
      
      list($table) = $t->maketable($sql, array('attr'=>array('id'=>"tracker", 'border'=>'1')));
      if(empty($table)) {
        $table = "No Data Found";
      }
      echo $table;
      break;
    case "counter":
      $sql = "select * from counter where lasttime > date_sub(now(), interval 7 day)";
      list($table) = $t->maketable($sql, array('attr'=>array('id'=>"counter", 'border'=>"1")));
      echo $table;
      break;
    case "counter2":
      $sql = "select * from counter2 where lasttime > date_sub(now(), interval 7 day) ".
             "order by lasttime desc";
      list($table) = $t->maketable($sql, array('attr'=>array('id'=>"counter2", 'border'=>"1")));
      echo $table;
      break;
    case "memberpagecnt":
      $sql = "select * from memberpagecnt where lasttime > date_sub(now(), interval 7 day) " .
             "order by lasttime desc";
      list($table) = $t->maketable($sql, array('attr'=>array('id'=>"memberpagecnt", 'border'=>"1")));
      echo $table;
      break;
    case "ipAgentHits":
      function ipagentcallback(&$row, &$desc) {
        global $S;

        // escape markup in agent
        $row['Agent'] = escapeltgt($row['Agent']);

        $tr = "<tr";
        if($row[id]) {
          if($row[id] == '25') {
            $tr .= " class='blp'";
          } 
        } else {
          $tr .= " class='noId'";
        }

        $ip = $row['IP'];

        $n = $S->query("select ip from bots where ip='$ip'");
        if($n) {
          $tr .= " style='color: red'";
        }
        $tr .= ">";

        $desc = preg_replace("/<tr>/", $tr, $desc);
        return false;
      }

      $query = "select l.ip as IP, l.agent as Agent, l.id, concat(r.FName, ' ', r.LName) as Name, " .
               "l.lasttime as LastTime from logagent as l left join rotarymembers as r on l.id=r.id" .
               " where l.lasttime > date_sub(now(), interval 7 day) order by l.lasttime desc";

      list($table) = $t->maketable($query, array('callback'=>'ipagentcallback',
        'attr'=>array('id'=>"ipAgentHits", 'border'=>'1')));

      echo $table;
      break;

    case "osoursite1":
      $ar = array('OS'=>array('types'=>array(), 'table'=>""),
                  'Browser'=>array('types'=>array(), 'table'=>""));
      $types = array('Windows', 'Linux', 'Macintosh', 'Android', 'iPhone');
      $ar[OS][types] = $types;
      $types = array('Opera', 'Chrome', 'Safari', 'Firefox', 'MSIE');
      $ar['Browser']['types'] = $types;

      $table = <<<EOF
<table id="osoursite1" style="width: 100%" border="1">
<thead>
<tr><th>OS</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead>
<tbody>

EOF;

      $ar['OS']['table'] = $table;

      $table = <<<EOF
<table id="osoursite2" style="width: 100%" border="1">
<thead>
<tr><th>Browser</th><th>Visits</th><th>%</th><th>Records</th><th>%</th><th>Visitors</th><th>%</th></tr>
</thead><tbody>

EOF;

      $ar['Browser']['table'] = $table;

      // Don't show webmaster

      $S->query("select sum(count) as count from memberpagecnt where id!='25'");
      list($total) = $S->fetchrow();
      $S->query("select count(*) as count from memberpagecnt where id!='25'");
      list($records) = $S->fetchrow();
      $totalmembers = $S->query("select id from memberpagecnt where id!='25' group by id");

      foreach($ar as $k=>$t) {
        $cnt = 0;
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
          // dont count webmaster
          $n = $S->query("select sum(count) as count from memberpagecnt where id !='25' and agent like('%$stype%')");
          $count = 0;
          if($n) {
            $row = $S->fetchrow();
            $count = $row['count'];
            $cnt += $count; 
            $percent = number_format($count / $total * 100, 2, ".", ",");
            $count = number_format($count, 0, "", ",");
            $S->query("select count(*) from memberpagecnt where id !='25' and agent like('%$stype%')");
            list($un) = $S->fetchrow();
            $perun = number_format($un / $records * 100, 2, ".", ",");
            $un = number_format($un, 0, "", ",");
            $mem = $S->query("select id from memberpagecnt where id !='25' and agent like('%$stype%') group by id");
            $permem = number_format($mem / $totalmembers * 100, 2, ".", ",");
            $mem = number_format($mem, 0, "", ",");
          }
          $ar[$k]['table'] .= <<<EOF
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
        $ar[$k]['table'] .= <<<EOF
</tbody>
</table>

EOF;
      }

      $ftotal = number_format($total, 0, "", ",");
      $records = number_format($records, 0, "", ",");

      $tbl = <<<EOF
<table border="1">
<tbody>
<tr><td>Total Records</td><td style="text-align: right; padding: 5px">$records</td></tr>
<tr><td>Total Visits</td><td style="text-align: right; padding: 5px">$ftotal</td></tr>
<tr><td>Total Visitors</td><td style="text-align: right; padding: 5px">$totalmembers</td</tr>
</tbody>
</table>

<div id='osBrowserCntDiv' class='wrapper' style='width: 100%'>
<br>
<div id='OScnt' style="margin-bottom: 20px">
{$ar[OS]['table']}
</div>
<div id='browserCnt'>
{$ar['Browser']['table']}
</div>
</div>
<p style="clear: both">Note that in some cases a &quot;Visitor&quot; (that is an IP Address) has used two or more different browsers which makes the
total visitor and percent visitors more than the number of IP Addresses. For example a couple of our members use both Firefox (good)
and Microsoft Internet Explorer (bad) on the same computer (IP Adddress).</p>
<p>Also note that some of our visitors use several different computers (work, home, laptop etc). And finally most visitors have
dynamic rather than static IP Addresses supplied by there Internet Service Provider (ISP). That means that from time to time
their ISP changes their IP Address. All very complicated.</p>
<ul style="clear: both; padding-top: 20px">
<li>The <i>Visits</i> columns show the total number of times a member with the <b>OS</b> or <b>Browser</b> visited our site.</li>
<li>The <i>Records</i> columns shows the total number of times the <b>IP/AGENT</b> was used to access our site (the
&quot;User Agent String&quot; has information about the OS and Browser.)</li>
<li>The <i>Visitors</i> columns show the number of members using the <b>OS</b> or <b>Browser</b>.</li>
</ul>
<p>This table does <b>not</b> include the accesses by the Webmaster as that would skew the results toward Linux and Firefox.</p>

EOF;
      echo $tbl;
      break;
  }
  exit();
}

// ********************************************************************************
// End of Ajax
// Start of Main Page logic

$S = new GranbyRotary;
$t = new dbTables($S);

$sql = "select id, concat(FName, ' ', LName) from rotarymembers";
$S->query($sql);
$members = array();
while(list($id, $name) = $S->fetchrow('num')) {
  $members[$id] = $name;
}
$members = json_encode($members);

$h->extra = <<<EOF
  <link rel="stylesheet"  href="css/tablesorter.css" type="text/css" />
  <link rel="stylesheet" href="css/hits.css" type="text/css" />
  <script src="js/tablesorter/jquery.metadata.js"></script>
  <script src="js/tablesorter/jquery.tablesorter.js"></script>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  var tablename="{$_GET['table']}";
  var idName=$members;

  // create a div for name popup
  $("body").append("<div id='popup' style='position: absolute; display: none; border: 2px solid black; background-color: #8dbdd8; padding: 5px;'></div>");

  $(".wrapper").on("click", "#memberpagecnt td:nth-child(2)", function(e) {
    var id = $(this).text();
    var pos = $(this).offset();
    var name = idName[id];
    $("#popup").text(name).css({display: 'block', top: pos.top, left: pos.left+50});
  });

  $("h2.table").each(function() {
    $(this).append(" <span class='showhide' style='color: red'>Show Table</span>");
  });

  // attach class tablesorter to all except our counter and nav-bar
  $("table").not($("#hitCountertbl, #nav-bar table")).addClass('tablesorter');

  $("#counter, #counter2, #memberHits, #otherMemberHits, #memberNot, #memberpagecnt").tablesorter();
  $("#lamphost table").tablesorter({ sortList: [[2,1]], headers: { 1: {sorter: "currency"} } } );
  $("#OScnt table").tablesorter({ sortList:[[1,1]] , headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}}});
  $("#browserCnt table").tablesorter({ sortList:[[1,1]], headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}} });

  $("div.table").hide();

  if(tablename != "") {
    $("div[name='"+tablename+"']").show();
    $("div[name='"+tablename+"']").prev().children().first().text("Hide Table");
  }
  $(".showhide").css("cursor", "pointer");

  // when the Show/Hide button for the table is pressed we do an Ajax call to get the data and
  // append it to the div the first time. After that we only show/hide the table.

  $(".showhide").click(function() {
    // tgl is not set initially so false.

    $("#popup").hide();

    if(!this.tgl) {
      // Show

      tablename = $(this).parent().next().attr("name"); // global
      var tbl = $("#"+tablename); // The <table>
      var t = $(this); // The span
      var s = t.parent().next(); // <div class="table"

      s.show();
      t.text("Hide Table");

      // if the table has already been instantiated just show it above
      // if not then create the table

      if(!tbl.length) {
        // Make the table from the database via Ajax

        $("body").css("cursor", "wait");
        t.css("cursor", "wait");

        $.get("$S->self", { table: tablename }, function(data) {
          $("div[name='"+tablename+"']").append(data);
          $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter

          // Switch for the specific table

          switch(tablename) {
            case "counter":
            case "counter2":
            case "tracker":
            case "memberpagecnt":
              $("#"+tablename).tablesorter(); 
              break;

            case "osoursite1":
              // [[1,1]] means, column 1 (columns start at zero) should sort decending
              // (0=assending). The default for columns is NO sort order (arrow up/down).
              // Note the little arrow is pointing down because we selected [1,1] all of the other
              // arrows are up/down indicating no selected order.

              $("#osoursite1, #osoursite2").tablesorter({ sortList: [[1,1]],
                 headers: { 1: {sorter: "currency"},
                            3: {sorter: "currency"},
                            5: {sorter: "currency"}}});
              break;
            case "pageHits":
              $("#pageHits").tablesorter(); //{ sortList:[[1,1]] }
              break;
            case "ipAgentHits":
              $("#ipAgentHits").tablesorter(); // { sortList:[[4,1]] }

              // Set up the Ip Agent Hits table

              $("#ipAgentHits").before("<p>You can toggle the display of only members or all visitors"+
                "<input type='submit' value='Show/Hide NonMembers' id='hideShowNonMembers' /><br/>"+
                "You can toggle the display of the ID "+
                "<input type='submit' value='Show/Hide ID' id='hideShowIdField' /><br/>"+
                "Your can toggle the display of the Webmaster "+
                "<input type='submit' value='Show/Hide Webmaster' id='hideShowWebmaster' /> "+
                "<p class='botmsg'><span style='color: red;'>"+
                "Bots from bots table are red</span><br>"+
                "You can toggle the dispaly of Bots "+
                "<input type='submit' value='Show/Hide Bots' id='hidebots' /></p>");

              // Ip Agent Hits.
              // Hide all ids of zero
              $(".noId, .botmsg").hide();
              
              // Button to toggle bots show/hide

              $("#hidebots").click(function() {
                if(!this.flag) {
                  // show
                  $("#ipAgentHits tr[style^='color: red']").show();
                } else {
                  // hide
                  $("#ipAgentHits tr[style^='color: red']").hide();
                }
                this.flag = !this.flag;
              });

              // Button to toggle between all and members only in Ip Agent Hits
              // table

              $("#hideShowNonMembers").click(function() {
                if(!this.flag) {
                  $(".noId").not("[name='blp']").show();
                  $(".botmsg").show();
                  $("#hidebots").prop("flag", true);
                } else {
                  $(".noId").hide();
                  $(".botmsg").hide();
                }
                this.flag = !this.flag;
              });

              // Hide the ID field in Ip Agent Hits table
              $("#ipAgentHits td:nth-child(3)").hide();
              $("#ipAgentHits thead th:nth-child(3)").hide();

              // Button to toggle between no ID and showing ID in Ip Agent Hits
              // table

              $("#hideShowIdField").click(function() {
                if(!this.flag) {
                  $("#ipAgentHits td:nth-child(3)").show();
                  $("#ipAgentHits thead th:nth-child(3)").show();
                } else {
                  $("#ipAgentHits td:nth-child(3)").hide();
                  $("#ipAgentHits thead th:nth-child(3)").hide();
                }
                this.flag = !this.flag;
              });

              // Hide the webmaster (me) in the Ip Agent Hits table
              $(".blp").hide();

              // Button to toggle between hide and show of Webmaster in Ip Agents
              // Hits Table

              $("#hideShowWebmaster").click(function() {
                if(!this.flag) {
                  $(".blp").show();
                } else {
                  $(".blp").hide();
                }
                this.flag = !this.flag
              });
              break;
          } // end of switch

          $("body").css("cursor", "default");
          t.css("cursor", "pointer");
        });
      }
    } else {
      $(this).parent().next().hide();
      $(this).text("Show Table");
    }
    this.tgl = !this.tgl;
  });

  // ipAgents click on agent to add to bots

  $(".wrapper").on("click", "#ipAgentHits td:nth-child(2)" , function(e) {
    var self = $(this);
    var id = $("td:nth-child(3)", self.parent()).text();
    if(id != '0')
      return;

    var agent = self.text();
    var ip = $("td:nth-child(1)" , self.parent()).text();
    console.log("ip: %s, agent: %s", ip, agent);
    $.ajax({url: "webstats.php",
            data: {page: 'updatebots', ip: ip, agent: agent},
            type: "get",
            dataType: "json",
            success: function(data) {
              console.log("OK", data);
              if(data.n) {
                self.html("<span style='color: white; background: red'>"+agent+"</span>");
              }
            },
            error: function(err) {
              console.log("ERR", err);
            }
    });
  });

  // tracker click on ip to only show that ip

  $(".wrapper").on("click", "#tracker td:nth-child(3)" , function(e) {
    if(this.flag) {
      // show all
      $("#tracker tr").show();
      $("#showall").remove();
    } else {
      // show only IP
      var ip = $(this).text();
      $("#tracker tr").hide();
      $("#tracker tr td:contains("+ip+")").parent().show();
      $(".table[name='tracker']").before("<button id='showall'>Show All</button>");
      $("#showall").click(function(e) {
        $("#tracker tr").show();
        $(this).remove();
      });
    }
    this.flag = !this.flag;
    return false;
  });
});
  </script>

  <style>
button {
  -webkit-border-radius: 7px;
  -moz-border-radius: 7px;
  border-radius: 7px;
  font-size: 1.2em;
}
#tracker {
  width: 100%;
}
#tracker td:nth-child(3):hover {
  cursor: pointer;
}
#tracker td:last-child {
  word-break: break-all;
  word-break: break-word; /* for chrome */
}
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

$h->banner = "<h2>Web Site Statistics</h2>";
$h->title = "Web Site Statistics";

list($top, $footer) = $S->getPageTopBottom($h);

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

// Count of active and honorary
$totalmembers = $ar['active'] + $ar['honorary'];

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

$table = $t->makeresultrows($query, $rowdesc, $ar);
$membercnt = $S->getNumRows();

echo <<<EOF
$top
<div class='wrapper'>
<h3 style="text-align: center">Total Members: $totalmembers ($ar[active] active
and $ar[honorary] honorary) Members</h3>
<p style="color: gray; text-align: center; margin-top: -15px">Asterisk '*' Denotes Honorary Members</p>
<div class='left'>
<p>Members using web site=$membercnt.</p>
<h2>Members Who Visited<br/>
Our Site</h2>
<table id='memberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Last Visit</th><th>Id</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>

EOF;

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

$table = $t->makeresultrows($query, $rowdesc, $ar);

if(!empty($table)) {
  $table = <<<EOF
<h2>Others Who Visited<br/>
Our Site in the Last Month</h2>

<table id='otherMemberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Status</th><th>Last Visit</th><th>Id</th><th>Club</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>

EOF;
}

$membercnt = $S->getNumRows();

echo <<<EOF
$table
<p>Most recient access (not counting this one):
<ul>
   <li>$lastAccess</li>
   <li>$fullName</li>
</ul>
</p>
</div>

EOF;

$whos = $S->getWhosBeenHereToday();

// Members who have NOT visited.

$query = "select id, FName, LName, status from rotarymembers where visits = 0 and status in('active','honorary')";
$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%id%</td></tr>";

$table = $t->makeresultrows($query, $rowdesc, $ar);
$membercnt = $S->getNumRows();

echo <<<EOF
<div class='right'>
<p>Members <b>not</b> using web site=$membercnt.</p>
<h2>Members<br/>Who Have Not Visited</h2>
<table id='memberNot' border="1">
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

//**********************************************************
// Start of Show/Hide Tables
//**********************************************************

$table = "";

echo <<<EOF
<div class="wrapper">

<hr/>
<h2 class='table'>Page Count</h2>
<div class='table' name="counter">
<p>From the <i>counter</i> table for last 7 days.</p>
<a name='counter'></a>
</div>

<hr/>
<h2 class='table'>Page Count2</h2>
<div class='table' name="counter2">
<p>From the <i>counter2</i> table for last 7 days.</p>
<a name='counter2'></a>
</div>

<hr/>
<h2 class='table'>Page Tracker</h2>
<div class='table' name="tracker">
<p>From the <i>tracker</i> table for last 7 days.</p>
<a name='tracker'></a>
</div>

<hr/>
<h2 class='table'>Member Page Count</h2>
<div class='table' name="memberpagecnt">
<p>From the <i>memberpagecnt</i> table for last 7 days.</p>
<a name='memberpagecnt'></a>
</div>
<hr/>

<h2 class='table'>IP-AGENT Hits</h2>
<div class='table' name="ipAgentHits">
<p>From the <i>logagent</i> table with join to <i>rotarymembers</i> table for last 7 days.</p>
<a name='ipAgentControl'></a>
</div>
<hr/>
<!-- Side by Side -->
<h2 class="table">OS and Browsers for Our Site</h2>
<div class="table" name="osoursite1">
<p>From <i>memberpagecnt</i> table. This shows <b>Only Members</b> ie. people who have <i>Logged In</i>.</p>
</div>
<!-- Side by Side -->
<!--
<hr/>
<h2 class="table">OS and Browsers for Lamphost.net</h2>
<div class="table" name="oslamphost">
<p>Note the <i>Other</i> catigory. This is made up of <b>Robots</b>, <b>Cell Phones</b> and miscellaneous other odd agents.
We are relying on the <i>User Agent String</i> provided by browsers for both OS and Browser identification. Some Robots do not have
anything we can use to identify the OS or the Browser. For example, &quot;Baiduspider+(+http://www.baidu.com/search/spider.htm)&quot;
has no OS  or Browser information.</p>
</div>
-->
</div>
<hr/>

EOF;

// Add daycount

// Callback to get the number of IPs that were members.
$memtotal = 0;

function callback(&$row, &$desc) {
  global $S, $memtotal;
  
  // For this Date see if there are any members
  
  $date = $row['Date'];
  $n = $S->query("select count(*) from daycounts where date = '$date' and members != 0 group by date;");

  if($n) {
    list($c) = $S->fetchrow();
    $row['Members'] = $c;
    $memtotal += $c;
  }
}

$query = "select count(*) as Visitors, sum(count) as Count, sum(robotcnt) as Robots, ".
         "sum(members) as Members, sum(visits) as Visits from daycounts ".
         "where lasttime > date_sub(now(), interval 7 day) order by date";
$S->query($query);

list($Visitors, $Count, $Robots, $Members, $Visits) = $S->fetchrow();

$S->query("select date from daycounts order by date limit 1");
list($start) = $S->fetchrow();

$ftr = "<tr><th>Totals</th><th>$Visitors</th><th>$Count</th><th>$Robots</th><th>$Members</th><th class='memtotal'>&nbsp;</th><th>$Visits</th></tr>";

$query = "select date as Date, count(*) as Visitors, sum(count) as Count, ".
         "sum(robotcnt) as RobotsCnt, sum(members) as MembersCnt, ".
         "'0' as Members, sum(visits) as Visits ".
         "from daycounts where date > date_sub(now(), interval 7 day) ".
         "group by date order by date desc";

list($tbl) = $t->maketable($query, array('callback'=>'callback',
                                         'footer'=>$ftr,
                                         'attr'=>array('border'=>"1", 'id'=>"daycount")));

//echo "<pre>".escapeltgt($tbl)."</pre>";

$tbl = preg_replace("~(<tfoot>.*?<th class='memtotal'>)&nbsp;~sm", "\${1}$memtotal", $tbl);

echo <<<EOF
$moTotal
<h2>Day Counts</h2>
<p>Day Counts do NOT include webmaster visits. The counts do however include ROBOTS who seem to
be much more interested in our web page than Rotarians.<br>
Day Counts started $start. Only showing 7 days.</p>
<div class="wrapper">
$tbl
</div>
<p>Visitors are unique IP Addresses, Count is the total number of accesses to all pages,
RobotsCnt are the portion of Count that are Robots, MembersCnt are the portion of Count that are Members,
Members are the number of unique IP Addresses that are Members, Visits are accesses seperated by 10 minutes.</p>
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
As I see more interesting trends I will report them here.</p>

<hr/>
$footer
EOF;

?>
