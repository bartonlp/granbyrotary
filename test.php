<?php
$info = `grep "^// START UpdateSite " *.php`;
$info = preg_replace("/\n/", "<br>", $info);
echo "info<br>$info";

