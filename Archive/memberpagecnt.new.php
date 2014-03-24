<?php
require_once("tbs_class.php");
$TBS = new clsTinyButStrong ;

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;
$extra =<<<EOL
   <script type="text/javascript"
            src="/js/tablesorter/jquery.tablesorter.js"></script>

   <script type="text/javascript">
jQuery(document).ready( function($) {
  // table sort
  $("table").tablesorter();
  $("table").addClass('tablesorter');

  $("table:not('#hitCountertbl')").hide();
  $("table:not('#hitCountertbl')").attr("border", "1"); // add a border

  $("<div id='membercntctrl'>\
    <p>Move the mouse over the ID field to see the member's name.<br/>\
    Click on a <i>Page</i> name, and only those pages are shown, click again and all are shown again<br/>\
    Click on the <i>Id</i> to toggle between only that member and all members.</p>\
    <button id='toggle25'>Show/Hide Webmaster</button><br/>\
    </div>").prependTo("#membercnt");

  // Put the show/hide table button before each table (except the
  // footer counter)
  $("<button class='showtable'>Show/Hide Table</button>").insertBefore("table:not('#hitCountertbl')");

  // Show hide tables
  $(".showtable").toggle(function() {
    var nxt = $(this).next();
    $("table", $(this).next()).show();
    //if($(this).next().id == "membercnt") {
      //$("#sumcount").html("Total Count = " + sumcount());
    //}
  }, function() {
    $("table", $(this).next()).hide();
  });

  // webmaster hide/show
  $("#membercnt tbody tr[name='25']").hide().addClass("hide25")

  $("#toggle25").toggle(function() {
    $("#membercnt tbody tr[name='25']").each(function() {
      $(this).removeClass("hide25");
      if($(this).attr("class").length == 0) {
        $(this).show();
      }
    }); 
  }, function() {
    $("#membercnt tbody tr[name='25']").hide().addClass("hide25");
  });

  // Show the member's name when we hover over the id
  $(".id").hover(function(e) {
    var name = $(this).attr("name");
    if(name == "") name="Non Member";
    $("#popup").css({top: e.pageY+20, left: e.pageX }).html("<span style='padding: 5px;'>" + name + "</span>").show();
  }, function() {
    $("#popup").hide();
  });

  // Show/hide rows with this id
  var webtgl = 0;
  $("#membercnt tbody td.id").click(function() {
    var id = $(this).attr("name");
    $("#membercnt tbody td.id").each(function() {
      if(id != $(this).attr("name")) {
        if(webtgl == 0) {
          $(this).parent().hide();
          $(this).parent().addClass("hideId")
        } else {
          $(this).parent().removeClass("hideId")
          if($(this).parent().attr("class").length == 0) {
             $(this).parent().show();
          }
        }
      }
    });
    webtgl = webtgl ? 0 : 1;
  });

  // Show only page
  var pagetgl = 0; // zero means page is showing
  $("#membercnt tbody td.page").click(function() {
    if(pagetgl) {
      $("#membercnt tbody td.page").each(function() {
        $(this).parent().removeClass("hidePage")
        if($(this).parent().attr("class").length == 0) {
          $(this).parent().show();
        }
      });
      pagetgl = 0;
    } else {
      var page = $(this).text();
      $("#membercnt tbody td.page").each(function() {
        if(page != $(this).text()) {
          $(this).parent().hide();
          $(this).parent().addClass("hidePage");
        }
      });
      pagetgl = 1; // page hidden
    }
    var sum = sumcount();
    $("#sumcount").html("Total Count = " + sum);
  });

  // sum of count for id=membercnt
  function sumcount() {
    var sum=0;
    // sum all non hidden counts
    $("#membercnt tbody tr:visible").each(function() {
      sum += parseInt($("td.count", this).text());
    });
    return sum;
  }
});
   </script>

   <style type="text/css">
.hide { display: none; }
#membercnttbl {
        width: 100%;
        margin: auto;
        background-color: white;
        border: 1px solid black;
}
#membercnttbl * {
        border: 1px solid black;
}
table thead {
        background-color: #CACACA;
}
#feedcnttbl, #articlecnttbl, #articlecntnonmbrstbl {
        width: 100%;
        background-color: white;         
        border: 1px solid black;
}
#feedcnttbl *, #articlecnttbl *, #articlecntnonmbrstbl * {
        border: 1px solid black;
}

/* Table sorter */
table.tablesorter thead tr .header {
        background-image: url(/images/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        padding-right: 20px;
}
table.tablesorter thead tr .headerSortUp {
        background-image: url(/images/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
        background-image: url(/images/desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
}
   </style>

EOL;

$order = $_GET['order'];

if(empty($order)) {
  $order = 'lasttime';
}

$header = array(title=>"Extended Page Count Infromation by Member", extra=>$extra);
$top =  $S->getPageTop($header, "<h2>Member Page Count for the Rotary Club of Granby</h2>");
$footer = $S->getFooter();

$query = "select a.id as ID, concat(b.FName, ' ', b.LName) as Name,
 a.page as Page, a.count as Count, a.lasttime as LastTime, a.agent as Agent
 from memberpagecnt as a left join rotarymembers as b on a.id=b.id order by $order";

$hdr = <<<EOF
<table id='membercnttbl'>
<thead>
<tr>%<th>*</th>%</tr>
</thead>

EOF;

$rowdesc = <<<EOF
<tr>
<td class="page">Page</td>
<td class='id'>ID</td>
<td>Agent</td>
<td class="count">Count</td>
<td>LastTime</td>
</tr>

EOF;

$tbl = $S->makeresultrows($query, $rowdesc, array('return'=>true, header=>$hdr));

echo <<<EOF
$top
<div id="membercnt">
<p>From <i>memberpagecnt</i> table joined with <i>rotarymembers</i> table.</p>
<table>
$tbl[hdr]
<tbody>
$tbl[rows]
</tbody>
<tfoot>
<tr>
<td colspan=5 id="sumcount">Count=0</td>
</tr>
</tfoot>
</table>
</div>
<hr/>
EOF;

$query = "select agent as Agent, count as Count, lasttime as LastTime from feedcnt order by lasttime desc";
list($rssfeed) = $S->maketable($query);

//   <tr><th>Article Id</th><th>Article Name</th><th>Member Id</th><th>Count</th><th>Last Time</th></tr>
$query = "select c.*, concat(r.FName, ' ',r.LName) as memberName, a.name as articleName from articlecnt as c 
left join articles as a on a.id=c.id left join rotarymembers as r on r.id=c.memberId order by c.lasttime desc";
list($articlecnt) = $S->maketable($query);

//    <tr><th>Article Id</th><th>Article Name</th><th>Ip Address</th><th>Agent</th><th>Count</th><th>Last Time</th></tr>
$query = "select c.*, a.name as articleName from articlecntnonmbrs as c 
left join articles as a on a.id=c.id order by c.lasttime desc";
list($articlenonmbrs) = $S->maketable($query);  

echo <<<EOF
<div id="feedcnt">
<p>RSS Feed Count. Most recent at top.</p>
<p>From the <i>feedcnt</i> table.</p>
$rssfeed
<hr/>
<p>Article Count</p>
<p>From the <i>articlecnt</i> table joined with <i>articles</i> and <i>rotarymembers</i> tables.</p>
$articlecnt
<p>Article Count Non Members</p>
<p>From the <i>articlecntnonmbrs</i> table joined with <i>articles</i> tables.</p>
<p>Shows Articles Read by NON Members.</p>
$articlenonmbrs
</div>
<hr/>

$footer
EOF;
?>

