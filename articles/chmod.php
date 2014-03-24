
<?php
$file = $_GET['file'];

echo chmod($file, 0666);
?>
