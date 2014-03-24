<?php
$fd = fopen("memberlist11.csv", "r") or die("can't open");

while(($data = fgetcsv($fd, 1000, ",")) !== FALSE) {
  $num = count($data);
  //echo "<p> $num fields in line $row: <br /></p>\n";
  $row++;
  if($row == 1) {
    for($c=0; $c < $num; $c++) {
      $ar[$c] = $data[$c];
    }
    continue;
  }
  for ($c=0; $c < $num; $c++) {
    echo "$ar[$c]: $data[$c]<br>\n";
  }
  echo "<br>\n";
}
fclose($fd);
?>
