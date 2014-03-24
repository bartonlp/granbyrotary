<?php
require_once("includes/db.class.php");

$D = new Database("localhost:3306", "2844", "7098653", "granbyrotarydotorg");

$id = $_GET['id'];

try {
  $query = "update hasjs set jcount=jcount+1 where id='$id'";  
  $D->query($query);
  echo "OK";
} catch(SqlException $e) {
  echo "ERROR";
  throw($e);
}
?>