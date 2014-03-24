<?php
$page = $_GET['page'];

// Note $page is /... so ./... is this directory

$page = basename($page);

$data = file_get_contents("$page");

$data = preg_replace("~<!-- Start UpdateSite: (.*?) -->(.*?)<!-- UpdateSite:.*?End -->~is",
                     "<div style='border: 1px solid red; background-color: #CCFFCC;'>" .
                     "<span style='color: white;background-color: black;'>$1</span><br/>$2</div>",
                     $data);

// This will evaluate all of the php in $data

echo eval("?>" . $data . "<?");
?>