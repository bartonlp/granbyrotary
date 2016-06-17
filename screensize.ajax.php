<?php
$_site = require_once("/var/www/includes/siteautoload.class.php");
$_site['count'] = false;
$S = new $_site['className']($_site);

$size = $_GET['size'];

if(empty($size)) {
  $size = "NO SIZE";
}

try {
  $query = "insert into screensize (id, size, count) values('$S->id', '$size', 1) " .
           "on duplicate key update count=count+1";
  
  $S->query($query);
} catch(SqlException $e) {
  if($e->getCode() == 1146) {
    // If table does not exist create it
        
    $query2 = <<<EOF
CREATE TABLE `screensize` (
  `id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL,
  `count` int default 1,
  `lasttime` timestamp,
  PRIMARY KEY  (`id`, `size`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
    $S->query($query2);
    $S->query($query);
  } else {
    // echo error
    $message = "SqlError: " . $e->getCode();
    echo $message;

    throw($e);
  }
}
echo "OK";
