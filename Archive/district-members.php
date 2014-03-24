<?php
// Read the RI Member Information
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;

// Use Curl to get the information from the RI site.
// Log into the site
// Account is bartonphillips@gmail.com, password is blp7098653

$data = array('UserLogin1$txtUserName' => "bphillips6878",
              'UserLogin1$txtPassword' => '7098653',
              "__EVENTVALIDATION" => "",
              '__EVENTTARGET' => 'ctl00$MainBody$lvColumn2$ctrl2$rptBoxItem$ctl00$lbItem',
              'UserLogin1$btnLogin' => "Sign-in"
             );

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://www.crsadmin.com/gen/Login.aspx?rid=478&aid=50085");

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

//preg_match('/'__doPostBack('__EVENTTARGET' value="(.*?)"/', $result, $m);
//$data['__REQUESTDIGEST'] = $m[1];
preg_match('/__VIEWSTATE.*? value="(.*?)"/', $result, $m);

$data['__VIEWSTATE'] = $m[1];
preg_match('/__EVENTVALIDATION.*? value="(.*?)"/', $result, $m);
$data['__EVENTVALIDATION'] = $m[1];

//echo "<br>data=<br>";
//var_dump($data);

curl_setopt($ch, CURLOPT_HTTPGET, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id()); 

$result = curl_exec($ch);
preg_match_all('/__VIEWSTATE(\d*).*? value="(.*?)"/', $result, $m);

//echo "m[2][1]={$m[2][1]}<br>\n";

//echo "m=<br>\n"; var_dump($m);
//echo "<br>\n";

for($i=0; $i < ($m[2][0])+1; ++$i) {
  $x = $m[2][$i+1];
  
  $data["__VIEWSTATE{$m[1][$i+1]}"] = $x;
}
$data["__VIEWSTATE{$m[1][1]}"] = $m[2][1];

//echo "<br>data=<br>\n";
//var_dump($data);
//exit();

//preg_match('/href="(.*?)" onMouseOut.*?90_Update/', $result, $m);
//$url = $m[1];

//curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_setopt($ch, CURLOPT_URL, 'http://www.crsadmin.com/gen/Admin.aspx?aid=50085');  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$result = curl_exec($ch);
echo "<br>Page=<br>";
var_dump($result);
exit();


preg_match_all('~Terminate</a></td><td class="ms-vb2">(.*?)</td><td class="ms-vb2">(.*?)</td><td class="ms-vb2">(.*?)</td><td class="ms-vb2">(.*?)</td>~',
                 $result, $m);

//var_dump($m);

$j = 0;

do {
  for($i=1; $i<count($m); ++$i) {
    echo "{$m[$i][$j]}, ";
  }
  echo "<br>";
  
} while(++$j<count($m[0]));

curl_close($ch);

//echo "<br><br>Page=<br>";
//var_dump($result);
//echo $result;
?>
