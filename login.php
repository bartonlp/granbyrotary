<?php
require_once("./vendor/autoload.php");
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

$h->title = "Login fo Granby Rotary";
$h->css = <<<EOF
  <style>
.required {
  color: red;
}
#errors {
  display: none;
  color: red;
  text-align: center;
}
#formdiv {
  width: 100%;
}
#visitortbl {
  width: 100%;
}
#visitortbl th {
  width: 30%;
}
#visitortbl td input {
  width: 99%;
}
  </style>
EOF;

$S->pageHead = $S->getPageHead($h);
$S->footer = $S->getPageFooter();

// Get the POST variables and GET variables

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case 'checkpassword':
        checkpassword($S);
        break;
      case 'memberstep2':
        memberstep2($S);
        break;
      case 'visitor2': // send email to visitor
        visitor2($S);
        break;
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
    }
    break;
  case "GET":
    switch($_GET['page']) {
      case 'visitor':  // start page for visitors
        visitor($S);
        break;
      case 'visitoremail': // verify code sent in email to visitor
        visitoremail($S);
        break;
      case 'testcookie':
        testcookie($S);
        break;
      default:
        memberstart($S);
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}

// ********************************************************************************
// GET this is the first step of member login

function memberstart($S) {
  extract($_GET);

  // This is the first step in logging in.

  // $email is not set so check if there is a cookie for this user
  
  if($S->id == 0) {
    // No Id Yet. Show the 'get email address dialog

    $banner = $S->getPageBanner("<h1>Login to Set Up Cookie</h1>");

    echo <<<EOF
$S->pageHead
$banner
<p>To <b>login</b> please enter your email address.
We will search the Grand County Rotary members database. If you are found we will
set a <i>COOKIE</i> in your web browser so you will not have to do this
step again. If you do not have <i>COOKIE</i>s enabled in your browser you
will not be able to log into the site.</p>
<p>If you have questions please email <a href='mailto:webmaster@granbyrotary.org?subject=Questions'>webmaster@granbyrotary.org</a></p>

<form action='$S->self' method='post'>
   Enter Email Address: <input type='text' autofocus name='newemail'>
   <input type='hidden' name='return' value='$return'>
   <input type='hidden' name='page' value='memberstep2'>
   <input type='submit' name='submit'>
</form>
<hr/>
$S->footer
EOF;
  } else {
    // User already has a cookie set so he is already logged in.

   $banner = $S->getPageBanner("<h1>You are already logged in. Thanks</h1>");
    
   echo <<<EOF
$S->pageHead
$banner
<p>If you want to update your <b>User Profile</b> go to the <a href="edituserinfo.php">User Profile</a> Page.</p>
<p>You can remove your ID COOKIE by going to our <a href="resetcookie.php">Reset Cookie</a> Page.</p>
<hr/>
$S->footer
EOF;
  }
}

// ********************************************************************************
// POST 
// This is step 2 of member login

function memberstep2($S) {
  extract($_POST);

  $n = $S->query("select id, password from rotarymembers where Email='$newemail'");

  if(!$n) {
    // Email Address not found in database.

    $banner = $S->getPageBanner("<h1>Email address not found in database</h1>");

    echo <<<EOF
$S->pageHead
$banner
<p>Check the spelling of your email address and <a href='$S->self?return=$return'>try again</a></p>
<p>Or return to our <a href='index.php'>home page</a></p>
<p>If you are a Grand County Rotary Club member and you have changed your email address or you think
the email in the database may be wrong, please email to <a href='mailto:webmaster@granbyrotary.org?subject=email changed'>webmaster@granbyrotary.org</a>
and provide your correct email or other information that will help us resolve this issue. Thank You</p>
<p>If you are not a Grand County Rotary Club member you can be added to our database as a
<a href="$S->self?page=visitor">Visitor</a>. Just click the link.</p>
<hr/>
$S->footer
EOF;
    exit();
  }
  
  list($id, $password) = $S->fetchrow();

  if(!empty($password) && ($password != $newemail)) {
    // This is the possible third step.
    $banner = $S->getBanner("<h1>Set Up Cookie</h1>");

    echo <<<EOF
$S->pageHead
$banner
<p>Enter the password you selected:</p>
<form action='$S->self' method='post'>
Enter Password: <input type='text' name='password'><br/>
<input type='hidden' name='emailcheck' value='$newemail'>
<input type='hidden' name='return' value='$return'>
<input type='hidden' name='page' value='checkpassword'>
<input type='submit' name='submit'>
</form>
<hr/>
$S->footer
EOF;
    exit();
  }

  // Set the cookie so user does not have to do this every time.

  $S->setIdCookie($id);

  // Almost done, now check to see if the cookie took.
  
  header("Location: $S->self?page=testcookie&id=$id&return=$return\r\n");
  exit();
}

// ********************************************************************************
// Step 3 of member login

function checkpassword($S) {
  extract($_POST);
  
  // This is possibly step 3

  // If the user had to enter a password because his 'password' field
  // is not blank or his email address

  $S->query("select id, password from rotarymembers where Email='$emailcheck'");
  
  list($id, $rowpassword) = $S->fetchrow();

  //echo "password row=$row[password], password=$password, checkemail=$emailcheck<br/>";
  
  if($password == $rowpassword) {
    $S->setIdCookie($id);

    header("Location: $S->self?page=testcookie&id=$id&return=$return\r\n");
  } else {
    $banner = $S->getPageBanner("<h1>Password does not match</h1>");

    // password does not match
    echo <<<EOF
$S->pageHead
$banner
<p>Check the spelling and case of your password and <a href='$S->self'>try again</a></p>
<p>Or return to our <a href='index.php'>home page</a></p>
<p>If you are a Grand County Rotary Club member and have problems please email to
<a href='mailto:webmaster@granbyrotary.org?subject=Problem'>webmaster@granbyrotary.org</a>
and provide your correct email address and password,
or other information that will help us resolve this issue. Thank You</p>
<hr/>
$S->footer
EOF;
  }  
}

// ********************************************************************************
// GET: This is the LAST STEP of member login and visitor registration

function testcookie($S) {
  extract($_GET);
  
  // test if cookie took. This is the last step in login.
  // Log the user, even if he does not have cookies set. Also he may
  // not go back to the home page until later so get him counted now.

  if(isset($id) && $id != 0) {
    $S->setId($id);
  } else {
    mail("bartonphillips@gmail.com", "login.php testcookie() id is zero", "id is zero. Line #=" . __LINE__,
         "From: info@granbyrotary.org");
  }
  
  $testId = $_COOKIE['SiteId'];

  if(!isset($testId)) {
    $banner = $S->getPageBanner("<h1>Unable To Set COOKIE</h1>");

    echo <<<EOF
$S->pageHead
$banner
<p style='border: 1px solid black; padding: 10px; font-size: large; background-color: pink; color: black;'>
Unable to set a <i>COOKIE</i> for <b>granbyrotary.org</b>
in your web browser. You may have <i>COOKIES</i>
disabled (check your preference) or have specified this site as a blocked site via the preferenced exception dialog.
In either case your experience on this site will be reduced untill you resolve this issue.<br/><br/>
To resolve this issue please send an email to
<a href='mailto:webmaster@granbyrotary.org?subject=Login Problem'>webmaster@granbyrotary.org</a></p>
<hr/>
<p><a href='index.php'>Return to Home Page</a></p>
$S->footer
EOF;
    // Send me an email about this
    $S->query("select * from rotarymembers where id='$id'");
    $row = $S->fetchrow();
    extract($row);
    
    $emailmsg = <<<EOF
Person logging in does not have COOKIEs enabled:
ID: $id
Name: $FName $LName
LastTime: $last
EOF;
    mail("bartonphillips@gmail.com", "GranbyRoray Login No Cookie", $emailmsg, "From: info@granbyrotary.org");
    exit();
  }

  header("Location: $return\r\n");
}

// ********************************************************************************
// GET
// This is the start page for visitor registration

function visitor($S) {
  extract($_GET);
  
  // Not a Grand County Rotary member but would like to become a
  // visitor
  $banner = $S->getPageBanner("<h1>Visitor Registration</h1>"); 

  echo <<<EOF
$S->pageHead
$banner
<hr/>
<p>Please Enter the following information. Item names in <span class="required">Red</span>
are required. Once you complete the form you will be sent an email. To complete your registration
follow the link in the email.</p>
<div id="errors"></div>
<div id="formdiv">
<form id="visitorform" action="$S->self" method="post">
<table id="visitortbl"border="1">
<tr><th class="requiredfld">First Name</th><td><input required class="required" type="text" name="fname"/></td></tr>
<tr><th class="requiredfld">Last Name</th><td><input required class="required" type="text" name="lname"/></td></tr>
<tr><th class="requiredfld">Email Address</th><td><input required class="required email" type="text" name="email"/></td></tr>
<tr><th>Rotary Club (if none leave blank)</th><td><input type="text" name="club"/></td></tr>
<tr><th>Address</th><td><input type="text" name="address"/></td></tr>
<tr><th>Phone</th><td><input type="text" name="phone"/></td></tr>
</table>
<input type="submit" value="Submit"/>
<input type="hidden" name="page" value="visitor2"/>
</div>
</form>
<p><span style="color: red">As a visitor you have somewhat restricted access.</span>
If you would like full access to our site please send the
Webmaster an email at <a href="mailto:webmaster@granbyrotary.org?subject=Request Full Membership">webmaster@granbyrotary.org</a>,
the Webmaster will contact your when your request is being considered. Please include a phone number in your email to the
Webmaster.</p>
<hr/>
$S->footer
EOF;

}

// ********************************************************************************
// POST. This is the second step of Visitor Registration
// Send the visitor an email with verification code

function visitor2($S) {
  // Second half of Visitor registration
  extract($_POST);

  // Check to see if the name is already in the database. If so tell use to login.
  
  if($S->query("select id from rotarymembers where FName='$fname' and LName='$lname'")) {
    list($id) = $S->fetchrow('num');

    echo <<<EOF
$S->pageHead
<h1>The First and Last name already exist in the database</h1>
<p>The ID is $id. You should be able to just LOGIN instead of Registering. Go to the LOGIN page
and enter your email address.</p>
$S->footer
EOF;
    exit();
  }

  if($email == "") {
    echo "<h1>No Email Address</h1>\n";
    exit();
  } elseif($fname == "" || $lname == "") {
    echo "<h1>Must have both First and Last Name</h1>\n";
    exit();
  }
  $banner = $S->getPageBanner("<h1>Check Your Email</h1>");

  echo <<<EOF
$S->pageHead
$banner
<p>We have sent an email to the address you provided ($email). Check your email. All you have to do to complete your registration
is click on the link provided in the email (or cut and past the link into your browser). 
We will set a <b>COOKIE</b> on you browser and you should not have to log in again. Your name will appear on the <b>Members</b>
page under <i>Visitors</i>. Once you have logged in you can edit your profile further if you like.</p>
<p>If for some reason you do not get an email please send the
<a href="mailto:bartonphillips@gmail.com?subject=Visitor did not get email">webmaster</a> an email with your information.
<p>Thank You</p>
<hr/>
<a href="index.php">Return to our Home Page</a>
$S->footer
EOF;

  // Now do a little more testing to see if we really think this is a visitor we want!
  // If the first and last name are the same he is a jerk.
  // If the hphone is 123456 or the like he is a jerk.
  // We will silently just NOT send this jerk an email.
  $err = false;
  
  if($fname == $lname) $err = "first and last name the same";
  if(preg_match("/1234.*/", $phone)) $err .= "\nphone number starts with 1234 ($phone)";

  if(!$err) {
    $message = <<<MSG
Thank you for registering at https://www.granbyrotary.org. If you did not register please disregard this email.
To complete your registration follow this link:
https://www.granbyrotary.org/login.php?page=visitoremail&fname=$fname&lname=$lname&email=$email&club=$club&phone=$phone&code=Ab77J95Bc

or cut and past the above link into your browser.
Thank You again for registering

--
Rotary Club of Granby Colorado
https://www.granbyrotary.org
MSG;
    
    mail($email, "Granbyrotary.org Registration", $message, "From: Granby Rotary Registration <info@granbyrotary.org>");
  } else {
    $message = <<<MSG
Error.
$fname, $lname, $phone
$err

Sorry.
MSG;

    mail($email, "Sorry", $message, "Bcc: bartonphillips@gmail.com\r\nFrom: info@granbyrotary.org");
  }
}

// ********************************************************************************
// GET: This is the last step of Vistor Registration

function visitoremail($S) {
  extract($_GET);
  
  if($code != "Ab77J95Bc") {
    $banner = $S->getPageBanner("<h1>Registration Error</h1>");

    echo <<<EOF
$S->pageHead
$banner
<p>The validation code we sent you is not correct. If you copied the link please make sure you copied the whole link
including the full code at the end. Try again.
</p>
<hr/>
$S->footer
EOF;
     exit();
  }

  if($club == "") {
    $club = "None";
  }

  // NOTE: we set 'visitor' and otherclub='none' for visitors initially.
  // A visitor can request some other status for otherclub
  // if he wants to send me an email.

  $query = "insert into rotarymembers (FName, LName, Email, address, hphone, status, club, otherclub, created)".
           "value('$fname', '$lname', '$email', '$address', '$phone', 'visitor', '$club', 'none', now()) ".
           "on duplicate key update visittime=now()"; // dup key should never happend due to check in visitor2.

  $S->query($query);
    
  $id = $S->getLastInsertId(); // Get the new id number
  
  $blpnotify = <<<EOF
Registration Activated.
Name: $fname $lname
Email: $email
Club: $club
Phone: $phone
Id: $id

EOF;

  mail("bartonphillips@gmail.com", "Notification: Granbyrotary.org Registration Activated", $blpnotify, "From: info@granbyrotary.org");
  
  $S->setIdCookie($id);
  
  header("Location: $S->self?page=testcookie&id=$id&return=/index.php\r\n");
}

