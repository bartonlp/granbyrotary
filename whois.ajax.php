<?php
// special whois for granbyrotary.org admin
// We are passed the email address
// We do a whois on the domain part of the address
// and a dig mx on the it too

$ret = array();

$ip = $_GET['ip'];

$email = $_GET['email'];
Header("Content-type: text/plain");
$errorhdr = <<<EOF
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
<body>
EOF;
$footer = "</body></html>";

if(!$ip) {
  if(!$email) {
    echo <<<EOF
$errorhdr
Error no email address supplied
$footer
EOF;
    exit();
  }
  // now look at the passed in email address and see if it looks OK
  if(preg_match("/@(?:.*?\.)*(.*?\..*?)$/", $email, $m)) {
    $domainname = $m[1];
  } else {
    echo "email ($email) does not look like an email address";
    exit();
  }
  $ip = gethostbyname($domainname);

  echo <<<EOF
<h1>Info for: $email</h1><h2>Domain: $domainname, IP: $ip</h2>
EOF;

  $command = "dig $domainname mx";

  echo "<h2>MX Record</h2>*******************************\n$command\n";

  exec($command, $ret);

  $command = "whois $ip";
  array_push($ret, "<h2>Whois Records</h2>*******************************\n$command\n");


  exec($command, $ret);

  $command = "whois $domainname";

  array_push($ret, "\n*******************************\n$command\n");
  exec($command, $ret);

  echo implode("\n", $ret);
} else {
  // If given an IP address (or a url) instead of an email address.
  
  $command = "whois $ip";
  array_push($ret, "<h2>Whois Records</h2>*******************************<pre>\n$command\n");

  exec($command, $ret);
  $ret[] = "</pre>";
  echo implode("\n", $ret);
}
  
?>
