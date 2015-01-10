<?php
// Display Web Statistics for Granby Rotary
// BLP 2014-11-02 -- tracker average now works with the showing display.

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

$myIp = gethostbyname($siteinfo['myUri']); // get my home ip address

// Ajax updatebots

if($_GET['page'] == 'updatebots') {
  $S = new Database($dbinfo);

  $S->query("insert ignore into bots2 value('{$_GET['agent']}')");
  
  $sql = "insert ignore into bots (ip) values('{$_GET['ip']}')";

  $n = $S->query($sql);
  
  echo json_encode(array('n'=>$n, 'sql'=>$sql));
  exit();
}

// This section is the Ajax back end for this page. This is called via $.get()

if($_GET["table"]) {
  $S = new Database($dbinfo);
  $t = new dbTables($S);

  switch($_GET["table"]) {
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
          $n = $S->query("select ip from bots where ip='$ip'");
          if($n) {
            $tr .= " style='color: red'";
          } else {
            // BLP 2014-11-16 -- Look for 'http://' in agent and if found add it to the
            // bots table.
            if(preg_match('~http://~', $row['Agent'])) {
              $sql = "insert ignore into bots value('$ip')";
              $S->query($sql);
              $tr .= " style='color: red'";
            }
          }

        }
        $tr .= ">";

        $desc = preg_replace("/<tr>/", $tr, $desc);
        return false;
      }

      $query = "select l.ip as IP, l.agent as Agent, l.id, ".
               "concat(r.FName, ' ', r.LName) as Name, " .
               "l.lasttime as LastTime from logagent as l ".
               "left join rotarymembers as r on l.id=r.id" .
               " where l.lasttime > date_sub(now(), interval 7 day) ".
               "order by l.lasttime desc";

      list($table) = $t->maketable($query, array('callback'=>'ipagentcallback',
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
          //$what = '';

/*          
          if(!in_array($os, array_map('strtolower', $m[0]))) {
            echo "<p>Agent: $agent, count: {$v['count']} " .
            print_r(array_map('strtolower', $m[0]), true) . "<br>";
            $counts['other'] += $v['count'];
          }
*/
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

      //vardump("counts", $counts);
      //echo "total: $total<br>totalmembers: $totalmembers<br>";
      /*
      foreach($counts as $k=>$v) {
        foreach($v as $kk=>$vv) {
          echo "$k => $kk: $vv<br>";
        }
      }
      */

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
<div id='Browsercnt' style="margin-bottom: 20px">
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

$sql = "select ip, id from memberpagecnt";

$S->query($sql);
$memberbyip = array();

while(list($ip, $id) = $S->fetchrow('num')) {
  $memberbyip[$ip] = $id;
}

$jmembers = json_encode($members);

$h->extra = <<<EOF
  <link rel="stylesheet"  href="css/tablesorter.css" type="text/css" />
  <link rel="stylesheet" href="css/hits.css" type="text/css" />
  <script src="js/tablesorter/jquery.metadata.js"></script>
  <script src="js/tablesorter/jquery.tablesorter.js"></script>
  <script>
jQuery(document).ready(function($) {
  var tablename="{$_GET['table']}";
  var idName=$jmembers;
  var flags = {webmaster: false, robots: false, ip: false , page: false};

  $("#tracker").tablesorter().addClass('tablesorter');

  // Don't show webmaster

  var myIp = "$myIp";

  // Check Flags look at other flags

  function checkFlags(flag) {
    var msg;

    if(flag) { // Flag is true.
      switch(flag) {
        case 'webmaster': // default is don't show
          $(".webmaster").parent().hide();
          msg = "Show ";
          flags.webmaster = false;
          break;
        case 'robots': // true means we are showing robots
          $('.robots').parent().hide();
          msg = "Show ";
          flags.robots = false;
          break;
        case 'ip': // true means only this ip is showing so we want to make all ips show
          $(".ip").removeClass('ip');
          $("#tracker tr").show();

          if(flags.page) {
            $("#tracker td:first-child:not('.page')").parent().hide();
          }
             
          if(!flags.webmaster) {
            $(".webmaster").parent().hide();
          }
          if(!flags.robots) {
            $(".robots").parent().hide();
          }
          msg = "Only ";
          flags.ip = false;
             
          break;
        case 'page': // true means we are only showing this page
          $(".page").removeClass('page');
          $("#tracker tr").show();
                          
          if(flags.ip) {
            $("#tracker td:nth-child(2):not('.ip')").parent().hide();
          }

          if(!flags.webmaster) {
            $(".webmaster").parent().hide();
          }
          if(!flags.robots) {
            $(".robots").parent().hide();
          }
          msg = "Only ";
          flags.page = false;
          break;
      }
      $("#"+ flag).text(msg + flag);
      calcAv();
      return;
    }   

    for(var f in flags) {
      if(flags[f]) { // if true
        switch(f) {
          case 'webmaster':
            flags.webmaster = false;
            if(true in flags) {
              $(".webmaster").parent().not(":hidden").show();
            } else {
              $(".webmaster").parent().show();
            }
            flags.webmaster = true;
            msg = "Hide ";
            break;
          case 'robots':
            flags.robots = false;
            if(true in flags) {
              $('.robots').parent().not(":hidden").show();
            } else {
              $(".robots").parent().show();
            }
            flags.robots = true;
            msg = "Hide ";
            break;
          case 'ip': 
            $("#tracker tr td:nth-child(2):not('.ip')").parent().hide();
            msg = "All ";
            break;
          case 'page':
            $("#tracker tr td:first-child:not('.page')").parent().hide();
            msg = "All ";
            break;
        }
        $("#"+ f).text(msg + f);
      }   
    }
    calcAv();
  }

  function calcAv() {
    // Calculate the average time spend using the NOT hidden elements
    var av = 0, cnt = 0;
    $("#tracker tbody :not(:hidden) td:nth-child(7)").each(function(i, v) {
      var t = $(this).text();
      if(!t) return true; // Continue

      var ar = t.match(/^(\d{2}):(\d{2}):(\d{2})$/);
      t = parseInt(ar[1], 10) * 3600 + parseInt(ar[2],10) * 60 + parseInt(ar[3],10);
      if(t > 7200) return true; // Continue if over two hours 
 
      //console.log("t: %d", t);
      av += t;
      ++cnt;      
    });

    av = av/cnt; // Average
   
    var hours = Math.floor(av / (3600)); 
   
    var divisor_for_minutes = av % (3600);
    var minutes = Math.floor(divisor_for_minutes / 60);
 
    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);

    var tm = hours.pad()+":"+minutes.pad()+":"+seconds.pad();

    console.log("av time: ", tm);
    $("#average").html(tm);
  }

  Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
  }

  // To start Webmaster is hidden

  $("#tracker td:nth-child(2) span.co-ip").each(function(i, v) {
    if($(v).text() == myIp) {
      $(v).parent().addClass("webmaster").css("color", "green").parent().hide();
    }
  });

  calcAv();

  // To start Robots are hidden

  $(".bot td:nth-child(4)").addClass('robots').css("color", "red").parent().hide();
  
  // Put a couple of buttons before the table

  $("#tracker").before("<div id='trackerselectdiv'>"+
                       "<button id='webmaster'>Show webmaster</button>"+
                       "<button id='robots'>Show robots</button>"+
                       "<button id='page'>Only page</button>"+
                       "<button id='ip'>Only ip</button>"+
                       "</div>");

  // ShwoHide Webmaster clicked

  $("#webmaster").click(function(e) {
    if(flags.webmaster) {
      checkFlags('webmaster');
    } else {
      // Show
      flags.webmaster = true;
      // Now show only my IP
      checkFlags();
    }
    //flags.webmaster = !flags.webmaster;
  });

  // Page clicked

  $("#tracker td:first-child").click(function(e) {
    if(flags.page) {
      checkFlags('page');
    } else {
      // show only this page
      flags.page = true;
      var page = $(this).text();
      $("#tracker tr td:first-child").each(function(i, v) {
        if($(v).text() == page) {
          $(v).addClass('page');
        }
      });
      checkFlags();
    }
  });

  // IP address clicked

  $("#tracker td:nth-child(2)").click(function(e) {
    if(flags.ip) {
      checkFlags('ip');
    } else {
      // show only IP
      flags.ip = true;
      var ip = $(this).text();
      $("#tracker tr td:nth-child(2)").each(function(i, v) {
        if($(v).text() == ip) {
          $(v).addClass('ip');
        }
      });
      checkFlags();
    }
  });

  // ShowHideBots clicked

  $("#robots").click(function() {
    if(flags.robots) {
      // hide
      checkFlags('robots');
    } else {
      // show
      flags.robots = true;
      checkFlags();
    }
  });

  $("#ip").click(function() {
    if(flags.ip) {
      // hide
      checkFlags('ip');
    } else {
      // show
      alert("click on the IP address you want to show");
    }
  });

  $("#page").click(function() {
    if(flags.page) {
      // hide
      checkFlags('page');
    } else {
      // show
      alert("click on the page you want to show");
    }
  });

  //************************************************
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
});
  </script>

  <style>
button {
  -webkit-border-radius: 7px;
  -moz-border-radius: 7px;
  border-radius: 7px;
  font-size: 1.2em;
  margin-bottom: 10px;
}
.country {
  border: 1px solid black;
  padding: 3px;
  background-color: #8dbdd8;
}
#tracker {
  width: 100%;
}
/* page */
#tracker td:first-child:hover {
  cursor: pointer;
}
/* ip */
#tracker td:nth-child(2):hover {
  cursor: pointer;
}
/* Member */
#tracker td:nth-child(3) {
  width: 100px;
}
/* agent, starttime, endtime */
#tracker td:nth-child(5), #tracker td:nth-child(6) {
  width: 5em;
}
/* time */
#tracker td:nth-child(7) {
  width: 3em;
}
/* referrer */
#tracker td:last-child {
  word-break: break-all;
  word-break: break-word; /* for chrome */
  width: 100px;
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
@media (max-width: 660px) {
  .right {
    float: none;
    width: 306px;
  }
  .left {
    float: none;
    width: 306px;
  }
  .left td:first-child {
    word-wrap: break-word;
    word-break: break-all;
    word-break: break-word;
  }
}
  </style>
EOF;

$h->banner = "<h2>Web Site Statistics</h2><p>All times are New York time.</p>";
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
EOF;

$sql = "select ip from tracker where starttime > date_sub(now(), interval 3 day)";

$S->query($sql);
$tkipar = array();

while(list($tkip) = $S->fetchrow('num')) {
  $tkipar[] = $tkip;
}
$list = json_encode($tkipar);
$ipcountry = file_get_contents("http://www.bartonlp.com/webstats-new.php?list=$list");
$ipcountry = (array)json_decode($ipcountry);

function tcallback(&$row, &$desc) {
  global $memberbyip, $members, $S, $ipcountry;

  $ip = $S->escape($row['ip']);

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
    
    if($S->query("select ip from bots where ip='$ip'")) {
      $desc = preg_replace("~<tr>~", "<tr class='bot'>", $desc);
    }
  }
}

$sql = "select page, ip, 'Member', agent, starttime, endtime, difftime as time, referrer ".
       "from tracker where starttime > date_sub(now(), interval 3 day) order by starttime desc";

list($trackertable) = $t->maketable($sql, array('callback'=>tcallback,
                                                'attr'=>array('id'=>"tracker", 'border'=>'1')));
if(empty($trackertable)) {
  $trackertable = "No Data Found";
}

echo <<<EOF
<hr/>
<h2 class='table'>Page Tracker</h2>
<div class='table' name="tracker">
<p>From the <i>tracker</i> table for last 3 days.</p>
<p>Average stay time: <span id='average'></span> (times over an hour are discarded.)</p>

<a name='tracker'></a>
$trackertable
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
<p>From <i>memberpagecnt</i> table.
This shows <b>Only Active Granby Members and NOT the Webmaster</b>.<br>
<b>Total Records</b> and <b>Total Vists</b> includes every page on the site.<br>
Total Visitors should equal the <b>Members Who Visited
Our Site</b> at the top of the page less the Webmaster.</p>
</div>
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
<h2 class='table'>Day Counts</h2>
<div class='table'>
<p>Day Counts do NOT include webmaster visits. The counts do however include ROBOTS who seem to
be much more interested in our web page than Rotarians.<br>
Day Counts started $start. Only showing 7 days.</p>
<div class="wrapper">
$tbl
</div>
<p>Visitors are unique IP Addresses, Count is the total number of accesses to all pages,
RobotsCnt are the portion of Count that are Robots, MembersCnt are the portion of Count that are Members,
Members are the number of unique IP Addresses that are Members, Visits are accesses seperated by 10 minutes.</p>
</div>

<hr/>
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
<hr/>
$footer
EOF;

?>
