<?php
// BLP 2014-08-24 -- Event Planner
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;
$S->t = new dbTables($S);

$S->h->title = "Event Planner";

/* table
CREATE TABLE `foodlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food` varchar(255) NOT NULL,
  `packageqty` int(11) DEFAULT '1',
  `packageprice` decimal(10,2),
  `provider` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `food` (`food`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 
*/

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "GET":
    switch($_GET['page']) {
      default:
        startpage($S);
        break;
    }
    break;
  case "POST":
    switch($_POST['page']) {
      case "Submit":
        planner($S);
        break;
      default:
        throw(new Exception("default POST: {$_POST['page']}"));
    }
    break;    
  default:
    throw(new Exception("Not GET or POST"));
}

exit();

// Start Page.
// Ask for event name

function startpage($S) {
  $S->h->banner = "<h1>Event Planner</h1>";
  $S->h->extra = <<<EOF
<style>
#foodlist td, #foodlist th {
  padding: 5px;
}
#foodlist td:nth-child(4), #foodlist td:nth-child(5) {
  text-align: right;
}
#foodlist th:nth-child(2), #foodlist td:nth-child(2) {
  display: none;
}
#popup {
  position: fixed;
  left: 200px;
  top: 400px;
  padding: 10px;
  background-color: red;
  color: white;
  border: 1px solid black;
  border-radius: 10px;
}
</style>
<script>
jQuery(document).ready(function($) {
  // Add checkbox to foodlist table

  $("#foodlist tr").each(function() {
    var tr = $(this);
    var id = $("td:first-child", tr).text();
    $("td:first-child", tr).before("<td><input type='checkbox' name='selected[]' value='"+id+"'/></td>");
    $("td:nth-child(5)", tr).text(Number($("td:nth-child(5)", tr).text()).toLocaleString("en",
      {style: 'currency', currency: 'USD', minimumFractionDigits: 2}));

  });

  $("#foodlist tr th:first-child").before("<th>Select</th>");

  $("form").submit(function() {
    if($("input[type='checkbox']:checked").length == 0) {
      $("body").append("<div id='popup'>Nothing Selected</div>");
      setTimeout(function() { $("#popup").remove() }, 2000);
      return false;
    }
  });
});
</script>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($S->h);

  $sql = "select id, food as Item, packageqty as 'Package Qty', ".
         "packageprice as 'Package Price', provider as Provider from foodlist";

  list($S->body) = $S->t->maketable($sql, array('attr'=>array('id'=>"foodlist", 'border'=>"1")));

  $S->body = <<<EOF
<p>Select the items you want for this event. After you have slected all of your items click on
Submit. On the next page you can enter the quantities and see the extensions for Price etc.</p>
<form method="post">
Enter Event Name: <input type='text' required name='event' title='Please Enter an Event' /><br>
$S->body
<input type='submit' name='page' value='Submit'/>
</form>
<ul>
<li><a href="foodlist.php">Go To Food List</a></li>
</ul>
EOF;

  printit($S);
}

// Planner

function planner($S) {
  $selected = implode(',', $_POST['selected']);
  $sql = "select food as Item, packageqty as 'Package Qty', ".
         "packageprice as 'Package Price', provider as Provider ".
         "from foodlist where id in($selected)";
  
  list($S->body) = $S->t->maketable($sql, array('attr'=>array('id'=>"planner", 'border'=>"1")));

  $S->body = <<<EOF
<p>Enter the <i>Unit Qty</i>, which is the total number of items you want. The program will take
that number and divide it by the <i>Package Qty</i> and then round that number up to the next
larger integer. So for example if you want 100 total items and the package quantity is eight
then the number of packages you must buy is 13. <i>Total Units</> is then 104.
<i>Total Cost</i> is <i>Number of Packages * Package Price</i>.</p>
<button id="print">PRINT</button>
<div id="printpage">
<h2>Event: {$_POST['event']}</h2>
$S->body
</div>
<ul>
<li><a href="foodlist.php">Go To Food List</a></li>
</ul>
EOF;

  $S->h->banner = "<h1>Plan Event</h1>";
  $S->h->extra = <<<EOF
<style>
#planner td, #planner th { padding: 5px; }
#planner td:nth-child(3), #planner td:nth-child(4),
#planner td:nth-child(5),#planner td:nth-child(7), #planner td:nth-child(8) {
  text-align: right;
}
#print {
  background-color: green;
  color: white;
  padding: 10px;
  font-size: 20px;
  font-weight: bold;
  border-radius: 10px;
}
</style>
<script>
jQuery(document).ready(function($) {
  // Add checkbox to foodlist table

  $("#planner tr th:nth-child(1)").after("<th>Unit Qty</th>");
  $("#planner thead tr").append("<th>Total Cost</th><th>Total Units</th>");
  $("#planner tbody tr").append("<td></td><td></td>");
  
  $("#planner tr td:nth-child(1)").after("<td><input type='text' name='qty' autocomplete='off'/></td>");
  $("#planner tbody tr td:nth-child(4)").after("<td></td>");
  $("#planner thead tr th:nth-child(4)").after("<th>Number of Packages</th>");
  $("#planner").after("<div>Overall Total: <span id='totaltotal'>$0.0</span></div>");

  $("#planner tbody tr:first-child td:nth-child(2) input").attr("autofocus", true);

  $("#planner tbody td:nth-child(4)").each(function() {
    var td = $(this);
    td.text(Number(td.text()).toLocaleString("en",
      {style: 'currency', currency: 'USD', minimumFractionDigits: 2}));
  });

  $("#planner tr td:nth-child(2)").keyup(function() {
    var tr = $(this).parent();
    var qty = $("input", this).val();
    var npackages = Math.ceil(qty / $("td:nth-child(3)", tr).text());
    var packageprice = $("td:nth-child(4)", tr).text().replace("\$", '');
    var total = npackages * packageprice;
    $("td:nth-child(5)", tr).text(npackages);
    $("td:nth-child(7)", tr).text(total.toLocaleString("en",
      {style: 'currency', currency: 'USD', minimumFractionDigits: 2}));
    $("td:nth-child(8)", tr).text(npackages * $("td:nth-child(3)", tr).text());

    var totaltotal = 0.0;

    $("#planner tbody tr td:nth-child(7)").each(function() {
      totaltotal += Number($(this).text().replace("\$", ''));
    });

    $("#totaltotal").text(totaltotal.toLocaleString('en',
      {style: 'currency', currency: 'USD', minimumFractionDigits: 2}));
  });

  $("#print").click(function() {
    var w = window.open();
    var c = $("#printpage").clone();
    c.prepend("<style>"+
             "#planner td, #planner th { padding: 5px; }"+
             "#planner td:nth-child(2), #planner td:nth-child(4),"+
             "#planner td:nth-child(3), #planner td:nth-child(4),"+
             "#planner td:nth-child(5),#planner td:nth-child(7), #planner td:nth-child(8) {"+
             "  text-align: right;"+
             "}</style>");

    $("thead tr", c).append("<th>Comment</th>");
    $("tbody tr", c).append("<td style='width: 200px'></td>");
    $("tbody tr td:nth-child(2)", c).each(function() {
      var v = $("input", this).val();
      $(this).text(v);
    });
    w.document.write(c.html());
    return false;
  });
});
</script>
EOF;

  list($S->top, $S->footer) = $S->getPageTopBottom($S->h);
  printit($S);
}

// Final print function

function printit($S) {
  echo <<<EOF
$S->top
$S->body
<hr>
$S->footer
EOF;
}

?>
  