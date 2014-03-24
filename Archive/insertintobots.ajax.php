<?php
require_once("includes/db.class.php");

$EP = new Database('localhost:3774', '3774', 'eegieL7aiqu3', 'endpoliodotcom');
$GR = new Database("localhost:3306", "2844", "7098653", "granbyrotarydotorg");

$errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

$ip = $_GET[ip];
$agent = $_GET[agent];

if(empty($ip)) {
  echo <<<EOF
$errorhdr
<body>
BAD Missing argument: ip=$ip
</body>
</html>
EOF;

  exit();
}

$agent = $S->escape($agent);

try {
  $query = "insert ignore into bots (ip, agent, readrobots) values('$ip', '$agent', 'no')";
  $EP->query($query);
} catch(SqlException $e) {
  echo "BAD INSERT";
}

try {
  $query = "delete from hasjs where ip='$ip'";
  $GR->query($query);
} catch(SqlException $e) {
  echo "BAD DELETE";
}

echo "OK";
?>