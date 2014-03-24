<?php
// Send an email about volunteering
// This is an Ajax responder

// usage
// vMailer.ajax.php?spouse=$spouse&event=$event&subject=$subject&mailto=$mailto
// event: the event being volunteered for. For example 'Earth+Day'.
// subject: the subject of the email. If missing then use the 'event'
// mailto: the eamil address to mail to. If missing then mail to
// bartonphillips@gmail.com for now
// spouse: does the spouse volunteer also. 0=no 1=yes. 

require("includes/granbyrotary.conf");
$S = new GranbyRotary(false);

  
$referer = $_SERVER['HTTP_REFERER'];

// Don't let people come here from anywhere else than the members
// page! We can change this later to make it only our sites

if(!preg_match("(^http://www.granbyrotary\.org)i", $referer)) {
  if($referer) echo "referer=$referer<br/>";

  $errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

  echo <<<EOL
$errorhdr
<body>
<h1>This page can only be accessed indirectly from this domain.</h1>
<p>Please return to our <a href='index.php'>home page</a> link.</p>
</body>
</html>
EOL;

  exit;
}

header("Content-type: plain/text");

if(empty($S->id)) {
  // This is an error big time!
  echo "NO ID. You must login before you can volunteer\n";
  exit;
}

// This should work with GET or POST

if(count($_GET)) {
  extract($_GET);
} else if(count($_POST)) {
  extract($_POST);
} else {
  // with post this can only happen if the form has no <input text
  // etc. elements. That is only a submit.
  // With get it means someone forgot to add the params to the url.
  
  echo "Error No INFO passed in";
  exit;
}

if(empty($event)) {
  // use the referer as the event.

  $event = $referer;
}

if(empty($subject)) {
  $subject = $event;
}

if(empty($mailto)) {
  $mailto = 'Barton Phillips <bartonphillips@gmail.com>';
}

 = $S->query("select FName, LName from rotarymembers where id='$S->id'");

@extract($S->fetchrow());

$name = ${FName} . ${LName};
if(empty($name)) {
  echo "Name empty not valid";
  exit;
}

$message = "$FName $LName has volunteered for $event. ";
if($spouse) $message .= "$FName $LName's spouse has also volunteered.";

$ret = mail($mailto, $subject, $message, 'from: info@granbyrotary.org');

if($ret) {
  echo "ok:: mailto='$mailto', subject='$subject', message='$message'";
} else {
   echo "bad:: mailto='$mailto', subject='$subject', message='$message'";
}

?>  