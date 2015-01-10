<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

$S = new GranbyRotary;

echo "<style>body{ font-size: 2em; }</style>";

$S->query("select agent from logagent");
while($row = $S->fetchrow('assoc')) {
  $rows[] = $row;
}

$counts = array();

foreach($rows as $v) {
  $agent = $v['agent'];
  $pat = "~Safari|Opera|Chrome|MSIE|Firefox|Konqueror|Window|Linux|Mac OS X|".
                    "Macintosh|Android|iPhone~i";
  
  if(preg_match_all($pat, $agent, $m)) {
    //echo "<p>Agent: $agent</p><pre>". print_r($m, true) . "</pre>";
    echo "<p>Agent: $agent";
    $dup = array();
    $what = '';
    
    foreach($m[0] as $k=>$x) {
      $x = strtolower($x);
      switch($x) {
        case 'chrome':
          $dup['safari'] = true;
          $what .= "$x,";
          break;
        case 'macintosh':
          $dup['mac os x'] = true;
          $counts['mac os x']++;
          $what .= "mac os x,";
          break;
        default:
          if(!$dup[$x]) {
            $counts[$x]++;
            $what .= "$x,";
          }
          break;
      }
      $dup[$x] = true;
    }
    echo "<br>WHAT: " .rtrim($what, ',') ."<br>****</p>";
  } else {
    echo "Agent: $agent<br>Not found.<br>****<br>";
    $counts['other']++;
  }
}
foreach($counts as $k=>$v) {
  echo "$k: $v<br>";
}
echo "done<br>";
?>