<?php
$FILE = basename(__FILE__);
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;
$referer = $_SERVER['HTTP_REFERER'];

// Don't let people come here from anywhere else than the members
// page! We can change this later to make it only our sites

if(!preg_match("(^http://www.granbyrotary\.org)i", $referer) && ($_REQUEST['mail'] != 1)) {
  if($referer) echo "referer=$referer<br/>";
  
  echo <<<EOL
<body>
<h1>This page can only be accessed from our members directory</h1>
<p>Please return to our <a href='/index.php'>home page</a> and follow the <b>Members</b> link. </p>
</body></html>
EOL;

  exit;
}

?>
<head>
   <title>Send Multiple Emails</title>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   <meta name="Author"
      content="Barton L. Phillips, mailto:barton@granbyrotary.org"/>
   <meta name="description"
      content="Name: Rotary Club of Granby Colorado, Page: Multiple Emails"/>
   <meta name="keywords" content="rotary"/>

   <!-- Link our custom CSS -->
   <link rel="stylesheet" title="Rotary Style Sheet"
        href="/css/rotary.css" type="text/css"/>

   <script type='text/javascript' src='/prototype.js'></script>

   <script type='text/javascript'>
<!--

function addAttachment(t) {
  var d = Element.extend(t);
  t.insert({before: '<input name="userfile[]" type="file" /><br />'});
}

//-->  
   </script>
   
</head>

<body>

<?php
extract(stripSlashesDeep($_POST));

if(!empty($submit)) {
require_once('Mail.php');
require_once('Mail/mime.php');

  // Submited message from this page
  
  echo $S->getBanner("<h2>Mail Sent</h2>");

  $result = $S->query("select * from rotarymembers where id in ($ids)");

  $names = array();
  
  while($row = mysql_fetch_assoc($result)) {
    extract($row);
    $names["$FName $LName"] = $Email;
  }
    
  $uploads_dir = '/tmp';

  $crlf = "\n";
  $mime = new Mail_mime($crlf);

  $arrParams['sendmail_path'] = '/usr/sbin/sendmail'; // this is actually a path to postfix. The sendmail is alias for a postfix program on many servers
  $arrParams['sendmail_args'] = "-i -f bartonphillips@gmail.com"; 

  $mail =& Mail::factory('sendmail', $arrParams);

  $attachedFiles = "";
  
  foreach ($_FILES["userfile"]["error"] as $k => $error) {
    if ($error == UPLOAD_ERR_OK) {
      $tmp_name = $_FILES["userfile"]["tmp_name"][$k];
      $filename = $_FILES["userfile"]["name"][$k];
        //echo "file: $tmp_name, $filename<br>";

      move_uploaded_file($tmp_name, "$uploads_dir/$filename");
      $file[$inx++] = "$uploads_dir/$filename";
      $attachedFiles .= "$filename, ";
    }
  } 

  $hdrs = array(
                'From'    => $S->email,
                'Subject' => $subject
               );

  $message .= "\n\n--\nCourtesy of The Rotary Club of Granby\nhttp://www.granbyrotary.org?memberid=\n";  
             
  $mime->setTXTBody($message);
  //  $mime->setHTMLBody($html);

  if(!empty($file)) {
    foreach($file as $v) {
      $mime->addAttachment($v);
    }
  }

  //do not ever try to call these lines in reverse order
  $body = $mime->get();
  $hdrs = $mime->headers($hdrs);

  while(list($key, $value) = each($names)) {
    $inx = 0;

    //echo "Mail: $key <$value>, $hdrs, $body<br>";
    
    $mail->send("$key <$value>", $hdrs, $body);

  }
  // Now remove files

  if(!empty($file)) {
    foreach($file as $value) {      
      unlink($value);
    }
  }

    // Log this email in the emails table
  // The table looks like this
  // id_fk: the members id
  // application: this is __FILE__ which will either be email.php or multmail.php
  // subject: the subject line
  // fromaddress: the from line
  // message: the email message
  // toaddress: $name <$email>. For multmail this is a comma seperated list of id's
  // sendtime: timestamp

  $mid = $S->id;
  $from =  mysql_real_escape_string($S->email);
  $message = mysql_real_escape_string($message);
  $subject = mysql_real_escape_string($subject);
  $attachedFiles = mysql_real_escape_string(rtrim($attachedFiles, ', '));
  
  $query = "insert into emails (id_fk,application,subject,fromaddress,message,toaddress,attached) value ('$mid', '$FILE', '$subject', '$from', '$message', '$ids', '$attachedFiles')";
  $S->query($query);

} else {
  // Called from members_direcgtory.php

  echo $S->getBanner("<h2>Send Multiple Emails</h2>");

  if(!count($Name)) {
    echo <<<EOF
<h3 style="color: red">No one selected. Return and try again</h3>
</body>
</html>
EOF;
    exit();
  }

  $ids = '';
  for($i=0; $i < count($Name); ++$i) {
    $ids .= "$Name[$i],";
  }
  $ids = rtrim($ids, ',');

  $result = $S->query("select * from rotarymembers where id in ($ids)");

  echo <<<EOF
<p>Sent Message to:
<ul>
EOF;

  while($row = mysql_fetch_assoc($result)) {
    extract($row);

    echo <<<EOF
<li>$FName $LName</li>
EOF;
}

echo <<<EOF
</ul>
</p>
<hr/>
EOF;

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
   <table border="1"  cellpadding="1" cellspacing="0" style="width: 100%">
      <col style="width: 10%">
      <tr><th>Subject:</th><td><input type="text" name="subject" style="width: 100%"/></td></tr>
      <tr><th>Message:</th><td><textarea name="message" style="width: 100%"
   rows="10"></textarea></td></tr>

   <tr><th>Attachments:</th>
      <td>
   
   <div id='attach' style='width: 100%;'>
      <input name="userfile[]" type="file" /><br />
      <input type='button' value='Another Attachment'
          onclick='addAttachment(this)'></input><br/>
   </div>
   </td>
   </tr>


   <tr><th colspan='2'><input type='submit' name='submit'
   value="Send Message"/></th></tr>
   </table>
<?php   
   echo "<input type='hidden' name='ids' value='$ids'/>\n";
?>
</form>   
<hr/>

<?php
}
echo $S->getFooter();
?>

</body>
</html>
