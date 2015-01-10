<?php
// BLP 2014-03-06 -- ajax for tracker.js

//ini_set("error_log", "/tmp/debugblp.txt");

define('TOPFILE', "/home/barton11/includes/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . " not found");

Error::setNoEmailErrs(true); // For debugging
Error::setDevelopment(true); // during development
Error::setErrorType(E_ALL & ~(E_WARNING | E_NOTICE | E_STRICT));

$S = new Database($dbinfo);

/*
CREATE TABLE `tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `starttime` time DEFAULT NULL,
  `endtime` time DEFAULT NULL,
  `difftime` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 
*/

if($_POST['page'] == 'load') {
  $self = $_POST['self'];
  $referrer = $_POST['referrer'];
  
  $ip = $_SERVER['REMOTE_ADDR'];
  $agent = $_SERVER['HTTP_USER_AGENT'];
  $here = $_SERVER['PHP_SELF'];

  $S->query("insert into tracker (page, ip, agent, starttime, referrer) ".
            "values('$self', '$ip','$agent', now(), '$referrer')");
  $id = $S->getLastInsertId();
  
  echo "$id";
  exit();
}

if($_POST['page'] == 'unload') {
  $id = $_POST['id'];
  $S->query("update tracker set endtime=now(), difftime=timediff(now(),starttime) ".
            "where id=$id");
  
  echo "Good By";
  exit();
}

echo <<<EOF
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Go Away!</h1>
</body>
</html>
EOF;
?>