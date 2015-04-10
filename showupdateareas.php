<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  exit();
}

$page = $_GET['page'];

// Note $page is /... so ./... is this directory

$page = basename($page);
$data = file_get_contents("$page");

$data = preg_replace("~<!-- Start UpdateSite: (.*?) -->(.*?)<!-- UpdateSite:.*?End -->~is",
                     "<div style='border: 1px solid red; background-color: #CCFFCC;'>" .
                     "<span style='color: white;background-color: black;'>$1</span><br/>$2</div>",
                     $data);

echo eval("?>".$data);