<?php
/*
$_site = require_once("/var/www/includes/siteautoload.class.php");
$S = new $_site['className']($_site);
*/
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOAD"). "/siteload.php");
$S = new $_site->className($_site);

$h->extra = <<<EOF
  <meta name="robots" content="noindex, nofollow">

  <script type='text/javascript'>
jQuery(document).ready(function($) {
  $("tr.show").click(function() {
    var item = $("td.hidden", this).text();
    location.href="showemailsent.php?item="+item;
  });
});
  </script>

  <!-- CSS for this page only -->
  <style type='text/css'>
.hidden {
  display: none;
}
  </style>
EOF;

$h->title = "Show Emails Sent";
$h->banner = "<h1>Emails Sent</h1>";
$b = new StdClass;
$b->msg = <<<EOF
<hr>
<a href="">Return to Welcome Page</a><br/>
<a href="member_directory.php">Return to Members Page</a><br/>
<hr>
EOF;

list($top, $footer) = $S->getPageTopBottom($h, $b);  

if($item = $_GET['item']) {
  echo <<<EOF
$top
<table border="1">
<tbody>

EOF;
  $S->query("select * from emails where item='$item'");
  $row = $S->fetchrow('assoc');

  $app = $row['application'];

  foreach($row as $key=>$value) {
    if(in_array($key, array("id_fk", "item"))) {
      continue;
    }
    if($key == "attached") {
      $value = !$value ? "&nbsp;" : $value;
    }

    if(in_array($key, array("message", "toaddress", "fromaddress"))) {
      $value = escapeltgt($value);
    }

    if($key == "toaddress" && $app == "multmail.php") {
      $S->query("select concat(fname, ' ', lname) as name from rotarymembers where id in($value)");
      $value = "";
      while($row = $S->fetchrow('assoc')) {
        $value .= "{$row['name']}<br>\n";
      }
    }

    echo <<<EOF
<tr>
<td>$key</td><td>$value</td>
</tr>

EOF;
  }
  echo <<<EOF
</tbody>
</table>

EOF;

} else {

  if($S->isAdmin($S->id)) {
    $query = "select * from emails";
  } else {
    $query = "select * from emails where id_fk='$S->id'";
  }

  $n = $S->query($query);

  if(!$n) {
    echo <<<EOF
$top
<h1>Sender: {$S->getUser()}</h1>
<h2>You have not sent and emails since Nov. 21</h2>
$footer
EOF;
    exit();
  }
  
  echo <<<EOF
$top
<body>
<h2>Sender: {$S->getUser()}</h2>
<p>To see the full description click on the entry.</p>
<table border='1'>
<thead>
<tr>
<th>From</th><th>Subject</th><th>To</th><th>Attached</th><th>Send Time</th>
</tr>
</thead>
</tbody>

EOF;

  while($row = $S->fetchrow('assoc')) {
    extract($row);

    if($application == "multmail.php") {
       $toaddress = "multiple";
    }
    $attached = !$attached ? "&nbsp;" : $attached;

    $fromaddress = escapeltgt($fromaddress);
    $toaddress = escapeltgt($toaddress);
    echo <<<EOF
<tr class="show">
<td class="hidden">$item</td><td>$fromaddress</td><td>$subject</td><td>$toaddress</td><td>$attached</td><td>$sendtime</td>
</tr>

EOF;
  }

  echo <<<EOF
</tbody>
</table>

EOF;
}

echo <<<EOF
$footer
EOF;


