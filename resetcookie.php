<?php
$_site = require_once("/var/www/includes/siteautoload.class.php");

$S = new $_site['className']($_site);

$type = $_POST['type'];

// Reset user Cookies

$expire = time() -(60*60*24*3);  // expire at once

$ref = "granbyrotary.org";

// Type is Blank on entry

$footer = $S->getFooter();

if($type) {
  foreach($type as $v) {
    if(is_array($_COOKIE[$v])) {
      while(list($key, $val) = each($_COOKIE[$v])) {
        setcookie("$v" . "[$key]", "", $expire, "/", ""); // This will get the PHPSESSION which would be www. without the .www
        setcookie("$v" . "[$key]", "", $expire, "/", "$ref");
        $ar[$key] = "Delete $v" . "[$key]";
      }
      while(list($it, $itval) = each($ar)) {
        echo "$itval<br/>";
      }
    } else {
      setcookie("$v", "", $expire, "/", "");
      setcookie("$v", "", $expire, "/", "$ref");
    }
    $list .= "$v<br>";
  }
  echo $S->getPageTop("Reset Cookies", "<h2>Cookies deleted</h2>\n<p>$list</p>");
  
} else {
  $top = $S->getPageTop("Reset Cookies", "<h2>Remove Cookies from this Computer</h2>");
  
  $cookies = "";
  
  while(list($key, $val) = each($_COOKIE)) {
    $cookies .= <<<EOF
<tr>
   <td>$key:</td>
   <th><input type="checkbox" name="type[]" value="$key"></th>
</tr>

EOF;
  }

  echo <<<EOF
$top
<p>The list below are the 'cookie' keys on this system. If you select (using the radio buttons)
an entry and then press 'submit' all the entries for that 'cookie' key will be removed. 
You may want to do this if you are using someone elses computer so they do not have access to
your information. Please NOTE that you could also be removing something that does not belong to 
you, but that is unlikely as ONLY keys for the domain 'applitec.com' are removed.</p>
<hr>

<form action="$S->self" method="post">
<table border="1" bgcolor="yellow" cellpadding="5">
<thead>
<tr>
<th>Key</th><th>Remove It</th>
</tr>
</thead>
<tbody>
$cookies
<tr>
<td align=center colspan='2'><input type='submit'></td>
</tr>
</table>
</form>
EOF;
}
echo $footer;


