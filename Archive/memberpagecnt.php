<?php
// Documentation of tbs_class.php is at http://www.tinybutstrong.com/manual.php
// Version 3.5.1 is at bartonphillips.com/htdocs/tbs_class/tbs_class_php5.php

require_once("bartonphillips.com/htdocs/tbs_class/tbs_class_php5.php");
$TBS = new clsTinyButStrong ;

define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");


$S = new GranbyRotary;

$h->extra = <<<EOL
   <script type="text/javascript"
            src="js/tablesorter/jquery.tablesorter.js"></script>

   <script type"text/javascript">

jQuery(document).ready( function($) {
  // table sort
  $("table").tablesorter();
  $("table").addClass('tablesorter');

  $("table:not('#hitCountertbl') tbody").hide();

  $("<div id='membercntctrl'>\
    <p>Move the mouse over the ID field to see the member's name.<br/>\
    Click on a <i>Page</i> name, and only those pages are shown, click again and all are shown again<br/>\
    Click on the <i>Id</i> to toggle between only that member and all members.</p>\
    <button id='toggle25'>Show/Hide Webmaster</button><br/>\
    </div>").prependTo("#membercnt");

  // Put the show/hide table button before each table (except the
  // footer counter
  $("<button class='showtable'>Show/Hide Table</button>").insertBefore("table:not('#hitCountertbl')");

  $("#sumcount").html("Total Count = " + sumcount(true));

  // Show hide tables
  $(".showtable").toggle(function() {
    $("tbody", $(this).next()).show();
    if($(this).next().id == "membercnttbl") {
      $("#sumcount").html("Total Count = " + sumcount(false));
    }
  }, function() {
    $("tbody", $(this).next()).hide();
  });

  // webmaster hide/show
  $("#membercnttbl tbody tr[name='25']").hide().addClass("hide25")

  $("#toggle25").toggle(function() {
    $("#membercnttbl tbody tr[name='25']").each(function() {
      $(this).removeClass("hide25");
      if($(this).attr("class").match(/^\s*$/)) {
        $(this).show();
      }
    }); 
  }, function() {
    $("#membercnttbl tbody tr[name='25']").hide().addClass("hide25");
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
  $("#membercnttbl tbody td.id").click(function() {
    var id = $(this).attr("name");
    $("#membercnttbl tbody td.id").each(function() {
      if(id != $(this).attr("name")) {
        if(webtgl == 0) {
          $(this).parent().hide();
          $(this).parent().addClass("hideId")
        } else {
          $(this).parent().removeClass("hideId")
          var cl = $(this).parent().attr("class");
          if(cl.match(/^\s*$/)) {
             $(this).parent().show();
          }
        }
      }
    });
    var sum = sumcount(false);
    $("#sumcount").html("Total Count = " + sum);

    webtgl = webtgl ? 0 : 1;
  });

  // Show only page
  var pagetgl = 0; // zero means page is showing
  $("#membercnttbl tbody td.page").click(function() {
    if(pagetgl) {
      $("#membercnttbl tbody td.page").each(function() {
        $(this).parent().removeClass("hidePage")
        if($(this).parent().attr("class").match(/^\s*$/)) {
          $(this).parent().show();
        }
      });
      pagetgl = 0;
    } else {
      var page = $(this).text();
      $("#membercnttbl tbody td.page").each(function() {
        if(page != $(this).text()) {
          $(this).parent().hide();
          $(this).parent().addClass("hidePage");
        }
      });
      pagetgl = 1; // page hidden
    }
    var sum = sumcount(false);
    $("#sumcount").html("Total Count = " + sum);
  });

  // sum of count for id=membercnttbl
  function sumcount(all) {
    var sum=0;
    if(all == false) {
      // sum all non hidden counts
      $("#membercnttbl tbody tr:visible").each(function() {
        sum += parseInt($("td.count", this).text());
      });
    } else {
      $("#membercnttbl tbody tr").each(function() {
        sum += parseInt($("td.count", this).text());
      });
    }
    return sum;
  }
});
  </script>
  <style type="text/css">
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
        background-image: url(images/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        padding-right: 20px;
}
table.tablesorter thead tr .headerSortUp {
        background-image: url(images/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
        background-image: url(images/desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
}
.hide { display: none; }

   </style>
EOL;

$h->title = "Extended Page Count Infromation by Member";
$h->banner = "<h2>Member Page Count for the Rotary Club of Granby</h2>";

list($hdr, $ftr) = $S->getPageTopBottom($h);

$caption = array('page'=>'Page', 'id'=>'Id', 'agent'=>'Agent', 'count'=>'Count', 'time'=>'Last Time');
 
$TBS->LoadTemplate('template/memberpagecnt.tbs') ;
$TBS->MergeField('pageHead', $hdr); // NOTE the template must have protect=no or the [ become &#91. Can also set $TBS->Protect=false
$TBS->MergeField('footer',$ftr);
$TBS->MergeBlock('top', $caption);
$order = $_GET['order'];
if(empty($order)) {
  $order = 'lasttime desc';
}

$TBS->MergeField('sortorder', $order);

$TBS->MergeBlock('membercnt', $S->getDb(),
                 "select a.id as id, concat(b.FName, ' ', b.LName) as name,
 a.page as page, a.count as count, a.lasttime as lasttime, a.agent as agent
 from memberpagecnt as a left join rotarymembers as b on a.id=b.id order by $order");

$TBS->MergeBlock('feedcnt', $S->getDb(),
                 "select * from feedcnt order by lasttime desc");

$TBS->MergeBlock('articlecnt', $S->getDb(),
                 "select c.*, concat(r.FName, ' ',r.LName) as memberName, a.name as articleName from articlecnt as c
                  left join articles as a on a.id=c.id left join rotarymembers as r on r.id=c.memberId order by c.lasttime desc");

$TBS->MergeBlock('articlecntnonmbrs', $S->getDb(),
                 "select c.*, a.name as articleName from articlecntnonmbrs as c
                  left join articles as a on a.id=c.id order by c.lasttime desc");

$TBS->Show();
?>

