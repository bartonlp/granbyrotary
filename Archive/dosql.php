<?php
// Ajax responder
// given a select string return result set

require_once("/home/bartonlp/includes/granbyrotary.conf");
$gr = new GranbyRotary;

$query = $_GET['query'];

// should check that query is only a select

$result = $gr->query($query);

$data = '';
while($row = $gr->fetchrow($result)) {
  foreach($row as $key=>$value) {
    $data .= "$key=>$value, ";
  }
}
Header("Content-type: text/plain");

echo $data;

?>
