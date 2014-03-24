<?php
// This is pretty complicated and should be rewriten.
// There are many steps here:
// Both member login and Visitor Registration are done in this file.
// In all cases the final step is 'test' where we see if the COOKIE could be set.
//
// This should be rewriten to use a switch on $page like all new file do.
//
// For member login we have three steps: The initial screen, "$newemail" set, and possibly "$password" set.
// For Visitors: $_GET['visitor'] is set initially. The POST has 'visitor2' set so POST does something different from
// member login. We send the visitor an email. The email has a link to this file with '&code=Ab77J95Bc' set. We check
// $_GET['code'] for this signature. If code is correct we insert into the database and then do 'test'.
//
// All calls have 'return' set the the page we should finaly return to (usually index.php).

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

$self = $_SERVER['PHP_SELF'];

$h->title = "Login fo Granby Rotary";
$h->extra = <<<EOF
  <style type="text/css">
.required {
  color: red;
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

// note: $S->getBanner() used in the 'if's: rotary.i.php overrides site.class.php

$pageHead = $S->getPageHead($h);
$footer = $S->getFooter();

// Get the POST variables and GET variables
switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    extract($_POST, EXTR_SKIP);
    break;
  case "GET":
    extract($_GET, EXTR_SKIP);
    break;
}

// This is the LAST STEP

if(isset($test)) {
  // test if cookie took. This is the last step in login.

  // Log the user, even if he does not have cookies set. Also he may
  // not go back to the home page until later so get him counted now.

  if(isset($id) && $id != 0) {
    //echo "setting logs $id<br>";
    $S->setId($id);
    $S->loginInfo(); // Only after member loged in, and even if cookie does not work.
  }
  
  $testId = $_COOKIE['SiteId'];

  if(!isset($testId)) {
    $banner = $S->getBanner("<h1>Unable To Set COOKIE</h1>");
    echo <<<EOF
$pageHead
$banner
<p style='border: 1px solid black; padding: 10px; font-size: large; background-color: pink; color: black;'>
Unable to set a <i>COOKIE</i> for <b>granbyrotary.org</b>
in your web browser. You may have <i>COOKIES</i>
disabled (check your preference) or have specified this site as a blocked site via the preferenced exception dialog.
In either case your experience on this site will be reduced untill you resolve this issue.<br/><br/>
To resolve this issue please send an email to
<a href='mailto:webmaster@granbyrotary.org?subject=Login Problem'>webmaster@granbyrotary.org</a></p>
<hr/>
<p><a href='/index.php'>Return to Home Page</a></p>
$footer
EOF;
    exit();
    }
  header("Location: http://www.granbyrotary.org$return\n\n");
  exit();
}

// If 'newemail' is set then we have had to ask user for his email
// address because no cookie was set

// This is step 2

if(isset($newemail)) {
  // This is the second step.
  
  $result = $S->query("select id, password from rotarymembers where Email='$newemail'");
  
  $row = mysql_fetch_array($result);

  if(mysql_num_rows($result) == 0) {
    // Email Address not found in database.

    $banner = $S->getBanner("<h1>Email address not found in database</h1>");
    echo <<<EOF
$pageHead
$banner
<p>Check the spelling of your email address and <a href='$self?return=$return'>try again</a></p>
<p>Or return to our <a href='/index.php'>home page</a></p>
<p>If you are a Grand County Rotary Club member and you have changed your email address or you think
the email in the database may be wrong, please email to <a href='mailto:webmaster@granbyrotary.org?subject=email changed'>webmaster@granbyrotary.org</a>
and provide your correct email or other information that will help us resolve this issue. Thank You</p>
<p>If you are not a Grand County Rotary Club member you can be added to our database as a
<a href="$self?visitor=1">Visitor</a>. Just click the link.</p>
<hr/>
$footer
EOF;
    exit();
  }

  $id = $row['id']; 
  $password = $row['password'];

  //echo "$password<br>";
  
  if(!empty($password) && ($password != $newemail)) {
    // This is the possible third step.
    $banner = $S->getBanner("<h1>Set Up Cookie</h1>");

    echo <<<EOF
$pageHead
$banner
   <p>Enter the password you selected:</p>
      <form action='$self' method='post'>
         Enter Password: <input type='text' name='password'><br/>
         <input type='hidden' name='emailcheck' value='$newemail'>
         <input type='hidden' name='return' value='$return'>
         <input type='submit' name='submit'>
      </form>
<hr/>
$footer
EOF;
    exit;
  }

  // Set the cookie so user does not have to do this every time.
  
  $S->setIdCookie($id);

  // Almost done, now check to see if the cookie took.
  
  header("Location: http://www.granbyrotary.org$self?test=1&id=$id&return=$return\n\n");
  exit();
  
} elseif(isset($password)) {
  // This is possibly step 3

  // If the user had to enter a password because his 'password' field
  // is not blank or his email address

  $result = $S->query("select id, password from rotarymembers where Email='$emailcheck'");
  
  $row = mysql_fetch_array($result);

  //echo "password row=$row[password], password=$password, checkemail=$emailcheck<br/>";
  
  if($password == $row['password']) {
    $id = $row['id'];
    $S->setIdCookie($id);

    header("Location: http://www.granbyrotary.org$self?test=1&id=$id&return=$return\n\n");
    //header("Location: http://www.granbyrotary.org$return\n\n");
    exit();
  } else {

    $banner = $S->getBanner("<h1>Password does not match</h1>");

    // password does not match
    echo <<<EOF
$pageHead
$banner
<p>Check the spelling and case of your password and <a href='$self'>try again</a></p>
<p>Or return to our <a href='/index.php'>home page</a></p>
<p>If you are a Grand County Rotary Club member and have problems please email to
<a href='mailto:webmaster@granbyrotary.org?subject=Problem'>webmaster@granbyrotary.org</a>
and provide your correct email address and password,
or other information that will help us resolve this issue. Thank You</p>
<hr/>
$footer
EOF;
    exit();
  }
} elseif($_GET['visitor']) {
  // Not a Grand County Rotary member but would like to become a
  // visitor
  $banner = $S->getBanner("<h1>Visitor Registration</h1>"); // rotary.i.php overrides site.class.php

  echo <<<EOF
$pageHead
$banner
<hr/>
<p>Please Enter the following information. Item names in <span class="required">Red</span>
are required. Once you complete the form you will be sent an email. To complete your registration
follow the link in the email.</p>
<div id="formdiv">
<form action="$self" method="post">
<table id="visitortbl"border="1">
<tr><th class="required">First Name</th><td><input type="text" name="fname"/></td></tr>
<tr><th class="required">Last Name</th><td><input type="text" name="lname"/></td></tr>
<tr><th class="required">Email Address</th><td><input type="text" name="email"/></td></tr>
<tr><th>Rotary Club (if none leave blank)</th><td><input type="text" name="club"/></td></tr>
<tr><th>Address</th><td><input type="text" name="address"/></td></tr>
<tr><th>Phone</th><td><input type="text" name="phone"/></td></tr>
</table>
<input type="submit" value="Submit"/>
<input type="hidden" name="visitor2" value="1" />
</div>
</form>
<p><span style="color: red">As a visitor you have somewhat restricted access.</span>
If you would like full access to our site please send the
Webmaster an email at <a href="mailto:webmaster@granbyrotary.org?subject=Request Full Membership">webmaster@granbyrotary.org</a>,
the Webmaster will contact your when your request is being considered. Please include a phone number in your email to the
Webmaster.</p>
<hr/>
$footer
EOF;
  exit();

} elseif($_POST['visitor2']) {
  // Second half of Visitor registration
  extract($_POST);

  if($email == "") {
    echo "<h1>No Email Address</h1>\n";
    exit();
  } elseif($fname == "" || $lname == "") {
    echo "<h1>Must have both First and Last Name</h1>\n";
    exit();
  }
  $banner = $S->getBanner("<h1>Check Your Email</h1>");

  echo <<<EOF
$pageHead
$banner
<p>We have sent an email to the address you provided ($email). Check your email. All you have to do to complete your registration
is click on the link provided in the email (or cut and past the link into your browser). 
We will set a <b>COOKIE</b> on you browser and you should not have to log in again. Your name will appear on the <b>Members</b>
page under <i>Visitors</i>. Once you have logged in you can edit your profile further if you like.</p>
<p>Thank You</p>
<hr/>
<a href="/index.php">Return to our Home Page</a>
$footer
EOF;

  $message = <<<MSG
Thank you for registering at http://www.granbyrotary.org. If you did not register please disregard this email.
To complete your registration follow this link:

http://www.granbyrotary.org/login.php?fname=$fname&lname=$lname&email=$email&club=$club&phone=$phone&code=Ab77J95Bc

or cut and past the above link into your browser.
Thank You again for registering

--
Rotary Club of Granby Colorado
http://www.granbyrotary.org

MSG;

  $ip = $_SERVER['REMOTE_ADDR'];
  $agent = $_SERVER['HTTP_USER_AGENT'];

  $blpnotify = <<<EOF
New Registration:
Name: $fname $lname
Email: $email
Club: $club
Phone: $phone
IP: $ip
AGENT: $agent

Email sent to provided email address.

EOF;

  mail($email, "Granbyrotary.org Registration", $message, "From: Granby Rotary Registration <info@granbyrotary.org>");
  mail("bartonphillips@gmail.com", "Notification: Granbyrotary.org Registration", $blpnotify, "From: info@granbyrotary.org");

  exit();
} elseif($code = $_GET['code']) {
  if($code != "Ab77J95Bc") {
    $banner = $S->getBanner("<h1>Registration Error</h1>");

    echo <<<EOF
$pageHead
$banner
<p>The validation code we sent you is not correct. If you copied the link please make sure you copied the whole link
including the full code at the end. Try again.
</p>
<hr/>
$footer
EOF;
     exit();
  }

  extract($_GET);
  if($club == "") {
    $club = "None";
  }
  // NOTE: we set 'visitor' and otherclub='none' for visitors initially. A visitor can request some other status for otherclub
  // if he wants to send me an email.
  
  $results = $S->query("insert into rotarymembers (FName, LName, Email, address, hphone, status, club, otherclub, created)".
                       "value('$fname', '$lname', '$email', '$address', '$phone', 'visitor', '$club', 'none', now())");

  $blpnotify = <<<EOF
Registration Activated.
Name: $fname $lname
Email: $email
Club: $club
Phone: $phone

EOF;

  mail("bartonphillips@gmail", "Notification: Granbyrotary.org Registration Activated", $blpnotify, "From: info@granbyrotary.org");
  
  $id = mysql_insert_id();
  $S->setIdCookie($id);
  header("Location: http://www.granbyrotary.org$self?test=1&id=$id&return=/index.php\n\n");
  exit();

} else {
  // This is the first setp in logging in.

  // $email is not set so check if there is a cookie for this user
  
  if($S->id == 0) {
    // No Id Yet. Show the 'get email address dialog

    $banner = $S->getBanner("<h1>Login to Set Up Cookie</h1>");

    echo <<<EOF
$pageHead
$banner
<p>To <b>login</b> please enter your email address.
We will search the Grand County Rotary members database. If you are found we will
set a <i>COOKIE</i> in your web browser so you will not have to do this
step again. If you do not have <i>COOKIE</i>s enabled in your browser you
will not be able to log into the site.</p>
<p>If you have questions please email <a href='mailto:webmaster@granbyrotary.org?subject=Questions'>webmaster@granbyrotary.org</a></p>

<form action='$self' method='post'>
   Enter Email Address: <input type='text' name='newemail'>
   <input type='hidden' name='return' value='$return'>
   <input type='submit' name='submit'>
</form>
<hr/>
$footer
EOF;

    exit;
  } else {
    // User already has a cookie set so he is already logged in.

   $banner = $S->getBanner("<h1>You are already logged in. Thanks</h1>");
    
   echo <<<EOF
$pageHead
$banner
<p>If you want to update your <b>User Profile</b> go to the <a href="edituserinfo.php">User Profile</a> Page.</p>
<p>You can remove your ID COOKIE by going to our <a href="resetcookie.php">Reset Cookie</a> Page.</p>
<hr/>
$footer
EOF;
  }
}
?>

