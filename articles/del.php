<?php
// Used by the article suite

$file = $_GET['file'];
if(preg_match("/^\s*$/", $file)) {        
$file = $_POST['file'];
}
if(preg_match("/^\s*$/", $file)) {
  echo "NO arguments<br>\n";
  exit();
}

echo unlink($file);
?>
