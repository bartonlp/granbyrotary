<?php
$file = $_GET['file'];
echo "$file<br>\n";
echo chown($file, 'bartonlp');

