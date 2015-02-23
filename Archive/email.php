<?php
$FILE = basename(__FILE__);
require_once("/var/www/includes/siteautoload.class.php");;

$S = new GranbyRotary;
$referer = $_SERVER['HTTP_REFERER'];

$h->title = "Send Email To Member";
$h->banner = "<h1>Send an Email to a Member</h1>";

// Don't let people come here from anywhere else than the members
// page! We can change this later to make it only our sites

// allow google reader to redirect stuff from my rss feed.

if(!preg_match("(^http://www.granbyrotary\.org|http://www\.google.com/reader/view/\?source=gmailnonewmail)i", $referer)
   && ($_REQUEST['mail'] != 1)) {

  if($referer) {
    $msg = ", You came from: $referer";
  } 

  list($top, $footer) = $S->getPageTopBottom($h);
  
  if(!$_GET['id']) {
    echo <<<EOL
$top
<h1>This page can only be accessed from our members directory$msg</h1>
<p>Please return to our <a href='index.php'>home page</a> and follow the <b>Members</b> link. </p>
$footer
EOL;
  } else {
    $id = $_GET['id'];
    
    $n = $S->query("select concat(FName, ' ', LName) as name from rotarymembers where id='$id'");
    if(!$n) {
      echo <<<EOF
$top
<h1>This page can only be accessed from our members directory$msg</h1>
<p>And you provided an invalid member id="$id".</p>
<p>Please return to our <a href='index.php'>home page</a> and follow the <b>Members</b> link. </p>
$footer
EOF;
    } else {
      list($name) = $S->fetchrow('num');
      echo <<<EOF
$top
<h1>This page can only be accessed from our members directory$msg</h1>
<p>$name please return to the members page to send an email</p>
$footer
EOF;
    }
  }
  exit();
}

// Target for form action. This does the actual mailing if 'mail' is
// set to 1, otherwise show the form and get the message etc.

if($_REQUEST['mail'] == 1) {
  extract(stripSlashesDeep($_POST));

  // Make sure that all the fields are filled in

  $m = "/^\s*$/"; // pattern to match -- line with nothing but white space
  
  if(preg_match($m, $name) || preg_match($m, $email)) {
    // This should not happen
    
    $h->banner = "Internal ERROR, no 'name' or 'email'<br/> ";
    $err = 1;
  }

  if(preg_match($m, $subject)) {
    $h->banner .= "No 'Subject' supplied (click on back)<br/> ";
    $err = 1;
  }

  if(preg_match($m, $message)) {
    $h->banner .= "No 'Message' supplied (click on back)<br/> ";
    $err = 1;
  }

  if(preg_match($m, $from)) {
    $h->banner .= "No 'From' supplied (click on back)";
    $err = 1;
  }

  if(!isset($err)) {
    $h->banner = "<h2 align='center'>Your message has been sent to<br/>$name</h2>";
  } else {
    $h->banner = "<h2>$PageTitle</h2>";
  } 

  list($top, $footer) = $S->getPageTopBottom($h);
  echo $top;

  $uploads_dir = '/tmp';

  $inx = 0;

  $attachedFiles = "";

  if($_FILES["userfile"]["error"]) {
    foreach ($_FILES["userfile"]["error"] as $key => $error) {
      if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
        $filename = $_FILES["userfile"]["name"][$key];
        move_uploaded_file($tmp_name, "$uploads_dir/$filename");
        $file[$inx++] = "$uploads_dir/$filename";
        $attachedFiles .= "$filename, ";
      }
    }
  }
  
  $crlf = "\n";
  $hdrs = array(
                'From'    => $from,
                'Subject' => $subject
               );

  require_once('Mail.php');
  require_once('Mail/mime.php');

  $mime = new Mail_mime($crlf);

  $message .= "\n\n--\nCourtesy of The Rotary Club of Granby\n";
  
  $mime->setTXTBody($message);

  if(!empty($file)) {
    foreach($file as $value) {
      $mime->addAttachment($value);
    }
  }

  //do not ever try to call these lines in reverse order
  $body = $mime->get();
  $hdrs = $mime->headers($hdrs);
    
  $arrParams['sendmail_path'] = '/usr/sbin/sendmail'; // this is actually a path to postfix. The sendmail is alias for a postfix program on many servers
  $arrParams['sendmail_args'] = "-i -f bartonphillips@gmail.com"; 

  $Mail = new Mail;
  $mail =& $Mail->factory('sendmail', $arrParams);
  $mail->send("$name <$email>", $hdrs, $body);

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
  $from =  $S->escape($from);
  $message = $S->escape($message);
  $subject = $S->escape($subject);
  $toaddress = $S->escape("$name <$email>");
  $attachedFiles = $S->escape(rtrim($attachedFiles, ', '));
  
  $query = "insert into emails (id_fk,application,subject,fromaddress,message,toaddress,attached) value ('$mid', '$FILE', '$subject', '$from', '$message', '$toaddress', '$attachedFiles')";
  $S->query($query);

  echo $footer;
  exit();
}

// Get the id of the person we are sending the message to
// This is a GET

$id = $_GET['id'];
$subject = $_GET['subject'];

// This page can not be call directly!

if(!isset($id)) {
  echo $S->getPageTop("<h2 id='head'>ERROR NO ID</h2>");  

  echo <<<EOF
<p>You can not use this page directly, it must be referenced from another page!</p>
$footer
EOF;
  exit();
}
  
$S->query("select Email, concat(FName, ' ', LName) from rotarymembers where id='$id'");

list($email, $name) = $S->fetchrow('num');
//echo "id=$id, email=$email, name=$name<br>";
$h->banner = "<h2 id='head'>Send a message to $name</h2>";

$h->extra = <<<EOF
   <meta name="robots" content="noindex, nofollow">

   <script>
jQuery(document).ready(function($) {
  $("#another").click(function() {
    $(this).before('<input name="userfile[]" type="file" /><br>');
  });
});
   </script>

   <!-- CSS for this page only -->
   <style>
#mailform table input {
        width: 100%;
        border: 0;
        background-color: #FFC0CB; /* pink; */
}
#mailform textarea {
        width: 100%;
        border: 0;
        background-color: #FFC0CB; /* pink; */
}
#mailform table {
        width: 100%;
}
#mailform .left {
        text-align: left;
        width: 20%;
}
   </style>

EOF;

list($top, $footer) = $S->getPageTopBottom($h);

$from = "";

if($S->id) {
  $S->query("select concat(FName, ' ', LName), Email from rotarymembers where id='$S->id'");
  list($fromName, $from)  = $S->fetchrow('num');
  $from = " value='$fromName <$from>'";
}

echo <<<EOF
$top
<form id='mailform' name='Email' method='post'
 enctype="multipart/form-data" action="$S->self">
   <table id='mailformtable' border="1" cellpadding="1" cellspacing="0">
      <tr>
         <td class='left'>From (email address)</td>
         <td><input autofocus required class='inputtext' id='fromname' type='text'
             name='from' tabindex='1'$from></td>
      </tr>
      <tr>
        <td class='left'>Subject</td>
        <td><input required class='inputtext' id='inputsubject' type='text' name='subject'
             value="$subject" tabindex='2'></td>
      </tr>
      <tr>
        <td class='left'>Message</td>
        <td><textarea required id='inputmessage' name="message" cols="140" rows="10"
            tabindex='3'></textarea></td>
      </tr>
   </table>
   <input type='hidden' name='email' value="$email">
   <input type='hidden' name='name' value="$name">
   <input type="hidden" name="id" value="$id">
   <input type='hidden' name='mail' value='1'>

   <!-- Attachments -->
   
   <div id='attach' style='width: 100%;'>
      Attachment:<br />
      <input name="userfile[]" type="file"  /><br />
      <input id="another" type='button' value='Another Attachment'><br/>
   </div>
   <br/>

   <input type='submit' name='submit' value='Send Mail' tabindex='4'>
</form>

<script>
  $("input[type='submit']").click(function() {
    $("body").append("<img src='/images/loading.gif' "+
      "style='width: 100px;position: fixed;top: 700px; left:600px;' />");
  });
</script>

<hr/>
$footer
EOF;
?>
