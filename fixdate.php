<?php
define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$sql = "select id, anniv from rotarymembers where anniv is not null and anniv !=''";
$n = $S->query($sql);
echo "n=$n<br>";
$result = $S->getResult();
while(list($id, $anniv) = $S->fetchrow($result, 'num')) {
  //echo "id: $id, ANNIV: $anniv<br>";
 
  if(preg_match("~^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$~", $anniv, $m)) {
    $newanniv = sprintf("%4d-%02d-%02d", $m[3], $m[1], $m[2]);
//    echo "$id, anniv: $anniv, newanniv: $newanniv<br>";
  } elseif(preg_match("~(\d{4})[/-](\d{1,2})[/-](\d{1,2})$~", $anniv, $m)) {
    $newanniv = sprintf("%4d-%02d-%02d", $m[1], $m[2], $m[3]);
//    echo "$id, anniv: $anniv, newanniv: $newanniv<br>";
  } else { 
    echo "XXXX:$id, anniv: $anniv<br>";
    continue;
  }
  $sql = "update rotarymembers set anniv='$newanniv' where id='$id'";
  echo "$sql<br>";
  $S->query($sql);
}
    
?>