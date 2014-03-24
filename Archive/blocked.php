<?php
// GranbyRotary Class 
require_once('/home/bartonlp/includes/granbyrotary.conf');

$S = new GranbyRotary; // Use default DOCTYPE which is XHTML 1.0

$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$S->query("insert into blocked (ip, agent) value('$ip', '$agent') on duplicate key update count=count+1");

echo <<<EOF
<html>
<head>
<title>Blocked IP</title>
<meta name="robots" content="noindex, nofollow">
</head>
<body>
<h1>Your IP Address has been blocked</h1>
<h2>IP Address $ip has been blocked</h2>
<p>If you feel you should not be blocked please email
<a href="mailto:info@granbyrotary.org?subject=ip-$ip blocked">info@granbyrotary.org</a> and explain
why you think you should not be blocked.</p>
<p>We sent you an email before your IP was blocked and we did not receive an answer.
We are very sorry if we have inconvenienced you.</p>
</body>
</html>
EOF;
?>
