<?php
$self = $_SERVER[PHP_SELF];

require_once("/home/bartonlp/includes/db.class.php");

$GR = new Database("localhost:3306", "2844", "7098653", "granbyrotarydotorg");

if($del = $_POST[del]) {
  // Delete checked
  foreach($del as $ip) {
    $ip = preg_replace("/>|</", '', $ip);
    try {
      $GR->query("delete from hasjs where ip='$ip'");
    } catch(SqlException $e) {
      echo "ERROR $ip NOT DELETED<br>";
      thow($e);
    }
    $deleted .= "IP $ip Deleted<br>";
  }
  $deleted = "<h2>Deleted Items</h2>$deleted<hr/>";
}

list($hasjstbl) = $GR->maketable("select * from hasjs order by lasttime desc", array(attr=>array(id=>"hasjs")));

/*"<tr><td><input type='checkbox' name='del[]' value='>ip<'/><td>id</td>".
"<td class='ip'>ip</td><td class='id'>memberid</th>".
"<td class='agent'>agent</td><td>filename</td><td>hasjs</td>".
"<td>lasttime</td></tr>");
*/

list($sizetbl) = $GR->makettable("select * from screensize order by lasttime desc", array(attr=>array(id=>"screensize")));
/*"<tr><td class='id'>id</td><td>size</td><td>count</td><td>lasttime</td></tr>");*/

$deleted = "";
 
echo <<<EOF
<h3>HasJs</h3>
$hasjstbl
<h3>Screensize</h3>
$sizetbl
EOF;

?>
