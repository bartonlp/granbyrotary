<?php
$FILE = basename(__FILE__);
require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;
$referer = $_SERVER['HTTP_REFERER'];

// Don't let people come here from anywhere else than the members
// page! We can change this later to make it only our sites

if(!preg_match("(^http://www.granbyrotary\.org)i", $referer) && ($_REQUEST['mail'] != 1)) {
  if($referer) echo "referer=$referer<br/>";
  
  echo <<<EOL
<body>
<h1>This page can only be accessed from our members directory</h1>
<p>Please return to our <a href='index.php'>home page</a> and follow the <b>Members</b> link. </p>
</body></html>
EOL;

  exit;
}

$h->extra = <<<EOF
   <script type='text/javascript'>
jQuery(document).ready(function($) {
  $("#another").click(function() {
    $(this).before('<input name="userfile[]" type="file" /><br>');
  });
});
   </script>
EOF;
   
extract(stripSlashesDeep($_POST));

if(!empty($submit)) {
require_once('Mail.php');
require_once('Mail/mime.php');

  // Submited message from this page

  $h->banner = "<h2>Mail Sent</h2>";
  list($top, $footer) =$S->getPageTopBottom($h);

  $S->query("select * from rotarymembers where id in ($ids)");

  $names = array();
  $sendIds = array();
  $cc = "";
  
  while($row = $S->fetchrow()) {
    extract($row);
    $cc .= "$FName $LName\n";
    $names["$FName $LName"] = $Email;
    $sendIds["$FName $LName"] = $id;
  }
    
  $uploads_dir = '/tmp';

  $crlf = "\n";

  $arrParams['sendmail_path'] = '/usr/sbin/sendmail'; // this is actually a path to postfix. The sendmail is alias for a postfix program on many servers
  $arrParams['sendmail_args'] = "-i -f bartonphillips@gmail.com"; 

  $mail =& Mail::factory('sendmail', $arrParams);

  $attachedFiles = "";
  
  foreach($_FILES["userfile"]["error"] as $k => $error) {
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

  while(list($key, $value) = each($names)) {
    $inx = 0;
    $msg = $message;

    //vardump($hdrs, "hdrs");
    $hdrstmp = $hdrs;
    
    $hdrstmp['To'] = "$key <$value>";

    $sid = $sendIds[$key];
    $msg .= "\n\n--\ncc:\n$cc\n\nCourtesy of The Rotary Club of Granby\n";  

    $mime = new Mail_mime($crlf);
    
    if(!empty($file)) {
      foreach($file as $v) {
        $mime->addAttachment($v);
      }
    }

    $mime->setTXTBody($msg);
    //  $mime->setHTMLBody($html);
    //do not ever try to call these lines in reverse order
    $body = $mime->get();
    
    $hdrst = $mime->headers($hdrstmp);

    $mail->send("$key <$value>", $hdrst, $body);

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
  $from =  $S->escape($S->email);
  $message = $S->escape($message);
  $subject = $S->escape($subject);
  $attachedFiles = $S->escape(rtrim($attachedFiles, ', '));
  
  $query = "insert into emails (id_fk,application,subject,fromaddress,message,toaddress,attached)
value ('$mid', '$FILE', '$subject', '$from', '$message', '$ids', '$attachedFiles')";

  $S->query($query);

  echo $top;
} else {
  // Called from members_direcgtory.php

  $h->banner = "<h2>Send Multiple Emails</h2>";
  list($top, $footer) = $S->getPageTopBottom($h);

  if(!count($Name)) {
    echo <<<EOF
$top
<h3 style="color: red">No one selected. Return and try again</h3>
$footer
EOF;
    exit();
  }

  $ids = '';
  for($i=0; $i < count($Name); ++$i) {
    $ids .= "$Name[$i],";
  }
  $ids = rtrim($ids, ',');

  $S->query("select * from rotarymembers where id in ($ids)");

  echo <<<EOF
$top
<p>Sent Message to:
<ul>
EOF;

  while($row = $S->fetchrow()) {
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
      <input id="another" type='button' value='Another Attachment'/><br/>
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
echo $footer;
?>
