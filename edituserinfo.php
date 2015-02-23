<?php
require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;

if($mid = $_GET['memberid']) {
  // The member is responding to an email with the query ?memberid=id
  // Set the member cookie
  $S->SetIdCookie($mid);
  $S->CheckId($mid);  // sets all of the GXxxx publics
}

$h->extra = <<<EOF
   <style>
#sendmail {
        color: red;
}
#bdayTable {
        width: 100%;
}
#bdayTable select {
        width: 100%;
        font-weight: bold;
        font-size: 12pt;
}
#bdayTable th {
        width: 20%;
        text-align: left;
}
#formDiv {
        width: 40%;
        margin-left: auto;
        margin-right: auto;
}
#formDiv h3 {
        text-align: center;
}
#submit {
        border: 1px solid black;
        width: 30%;
        margin-left: auto;
        margin-right: auto;
}
#submit input {
        background-color: yellow;
        width: 100%;
        font-size: large;
}
#userInfo {
        width: 100%;
}
#userInfo,
#userInfo th,
#userInfo td {
        background-color: white;
        border: 1px solid black;
}
#userInfo input {
        width: 96%;
}
#userInfo th {
        padding-left: 1em;
        padding-right: 1em;
#note {
        color: red;
        font-size: 80%;
        font-style: italic;
}
   </style>

EOF;

$h->title = "Edit Member Profile";
$h->banner = "<h2>Edit Contact Information</h2>";

echo $S->getPageTop($h);
$footer = $S->getFooter();

// Form submitted for update of database

if($_POST['submit'] == 'Submit') {
  extract($_POST);

  //echo "b_day=$b_day, $b_mo, $b_yr<br>";
  $bday = "$b_yr-$b_mo-$b_day";
  //echo "$bday<br>";
  $S->query("update rotarymembers set Email='$email', address='$address', hphone='$hphone', bphone='$bhome', cphone='$cphone', bday='$bday', password='$password' where id=$S->id");

  echo <<<EOF
<h3>Database Updated</h3>
$footer
EOF;
  exit;
}

// Not update so get info and display it for the user

$cnt = $S->query("select * from rotarymembers where id=$S->id");

if($cnt != 1) {
  if($S->id == 0) {
    echo "<p>You have not logged in yet. Please <a href='login.php'>Login</a></p>";
  } else {
    echo "<p>Internal Error: id=$S->id, but count is $cnt not 1</p>";
  }
  exit;
}

$row = $S->fetchrow('assoc');
extract($row);

echo <<<EOF
<div id='formDiv'>
<h3>Contact Information for {$S->getUser()}</h3>
<form action='edituserinfo.php' method='post'>
<table id='userInfo'>
<tr>
<th>Account Name</th><th>{$S->getUser()}</th>
</tr><tr>
<th>Email:</th><td><input type='text' name='email' value='$Email'></td>
</tr><tr>
<th>Address:</th><td><input type='text' name='address' value='$address'></td>
</tr><tr>
<th>Home Phone:</th><td><input type='text' name='hphone' value='$hphone'></td>
</tr><tr>
<th>Work Phone:</th><td><input type='text' name='bphone' value='$bphone'></td>
</tr><tr>
<th>Cell Phone:</th><td><input type='text' name='cphone' value='$cphone'></td>
</tr><tr>
<th>Birthday:</th><td>
<table id='bdayTable'>
   <tr><th>Month:</th><td><select name='b_mo'>

EOF;

$l = implode(",", "January,February,March,April,May,June,July,August,September,October,November,December");
list($y, $m, $d) = implode("-", $row['bday']);

foreach ($l as $k=>$mo) {
  $i=$k+1;
  print("<option value='$i'" . (($m == $i) ? " selected" : "") . ">$mo</option>\n");
}

print("</select></td>
</tr><tr><th>Day:</th><td><select name='b_day'>
");

for($i=1; $i < 32; ++$i) {
  print("<option value='$i'" . (($d == $i) ? " selected" : "") . ">$i</option>\n");
}

print("</select></td>
</tr><tr><th>Year:</th><td><select name='b_yr'>
");

for($i=1910; $i < 2001; ++$i) {
  print("<option value='$i'" . (($y == $i) ? " selected" : "") . ">$i</option>\n");
};

echo <<<EOF
</select></td>
</tr>
</table>
</td>
</tr><tr>
<th>Password:</th><td><input type='text' name='password' value='$password'></td>
</tr>
</table>
<div id='submit'><input type='submit' name='submit' value='Submit'></div>
</form>
</div>
<p>If the password is blank then only your email address will be used for log-in. If you add a password then both
the email address and the password will be requested during log-in.<br/>
<span id='note'>NOTE: If you have browser COOKIES enabled (which is the usual default) you will only need to log-in once on any computer.</span></p>
<hr/>
$footer
EOF;

?>