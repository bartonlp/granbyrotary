<?php
if($_POST['page'] == 'ajax') {
  file_put_contents("temptext.txt", $_POST['data']);
  echo "OK";
  exit();
}

$tbl = file_get_contents("temptext.txt");
echo <<<EOF
$tbl
EOF;
?>