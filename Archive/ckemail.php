<?php
   // ckemail. Called from index.php
   // Checks the two emails info@granbyrotary.org and webmaster@granbyrotary.org
   // index.php checks the two mailboxes to see if there is any pending mail and if there
   // is a message is put at the bottom of the page for the administrator and a link to this page.
   
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");


$S = new GranbyRotary(array(count=>false,countBlp=>false)); // dont count

$errorhdr = <<<EOF
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

if(!$S->isAdmin($S->id)) {
  echo <<<EOF
$errorhdr
<body>
<h1>Sorry This Is Just For Designated Admin Members</h1>
</body>
</html>
EOF;
  exit();
}

$self = $S->self;

$email = $_GET[email];

echo $pageHead = $S->getPageHead("Check Email info or webmaster @granbyrotary.org");
$footer = $S->getFooter("<a href='$self?email=$email'>Return To Email Page</a>");
$mbox = imap_open("{granbyrotary.org/imap/notls:143}INBOX", $email, "7098653");

if(!$mbox) {
  echo "Error opening mail box<br>\n";
  exit();
}

if($_POST[submit] == "Delete") {
  $inx = $_POST[index];
  imap_delete($mbox, $inx);
  echo $S->getBanner("<h1>Message $inx Deleted</h1>");
  echo $footer;
  imap_close($mbox);
  exit();
}

if($_POST[submit] == "Expunge") {
  $del = $_POST[del];
  if($del) {
    foreach($del as $inx) {
      imap_delete($mbox, $inx);
    }
  }
  imap_expunge($mbox);
//  echo $S->getBanner("<h1>All Deleted Messages Removed</h1>");
//  echo $footer;
//  imap_close($mbox);
//  exit();
}

// Display a Message by Number

if($inx = $_GET['msgnum']) {
  $header = imap_headerinfo($mbox, $inx);

  $structure = imap_fetchstructure($mbox, $inx);
  //echo "mime type= " . get_mime_type($structure) . "<br>\n";
  //print_r(create_part_array($structure));
  
    // GET TEXT BODY
  $msgBody = get_part($mbox, $inx, "TEXT/PLAIN");
  $htmlBody = get_part($mbox, $inx, "TEXT/HTML");
  $msgBody = preg_replace("/\n+/s", "\n", $msgBody);
  $msgBody = preg_replace("/^>.*?\n/sm", "", $msgBody);
  $msgBody = preg_replace("/^\s*-+\s*Original Message.*/sm", "", $msgBody);
  $msgBody = escapeltgt($msgBody);
  $to = decodeMimeString(escapeltgt($header->toaddress));
  $from = decodeMimeString(escapeltgt($header->fromaddress));
  $subject = decodeMimeString(escapeltgt($header->subject));

  $banner = $S->getBanner("<h1>Read Message</h1>");

  echo <<<EOF
$banner
<p>
To: $to<br/>
From: $from<br/>
Date: $header->date<br/>
Subject: $subject<br/><br/>
<pre style="border: 1px solid black; width: 500px; overflow: auto;">
$msgBody
</pre>
<hr/>
<div style="border: 1px solid black; width: 500px; overflow: auto;">
$htmlBody
</div>
<form action="$self?email=$email" method="post">
Delete This Message: <input type="submit" name="submit" value="Delete" />
<input type="hidden" name="index" value="$inx" />
</form>
$footer
EOF;
  imap_close($mbox);

  exit();
  // DONE dispay message
} 

// Initial Page
// Display List of Unread Messages

$check = imap_mailboxmsginfo($mbox);

if($check->Nmsgs) {
  $banner = $S->getBanner("<h1>Unread Messages at $email</h1>");

  echo <<<EOF
$banner
<div style="width: 100%">
<form action="$self?email=$email" method="post">
<table style="text-align: left">
<tr><th>number of messages</th><td>$check->Nmsgs</td></tr>
<tr><th>number recent</th><td>$check->Recent</td></tr>
<tr><th>number unread</th><td>$check->Unread</td></tr>
<tr><th>number deleted</th><td>$check->Deleted</td></tr>
</table>
<p>Unread messages are <span style="background-color: #99ff99">highlighted</span></p>
<p>Deleted messages are <span style="background-color: red; color: white">highlighted</span></p>
<hr/>
<table border="1">
<thead>
<tr><th>Delete</th><th>Msg Num</th><th>To</th><th>From</th><th>Subject</th><tr>
</thead>
<tbody>

EOF;

  for($i=1; $i <$check->Nmsgs+1; ++$i) {
    $header = imap_headerinfo($mbox, $i);
    //echo "$i, Unseen=$header->Unseen, Recent=$header->Recent<br>\n";

    $new = ' style="background-color: #99FF99"';
    $del = "<th><input type='checkbox' name='del[]' value='$i' /></th>";

    if(($header->Recent != "N") && ($header->Unseen != "U") && ($header->Deleted != "D")) {
      $new = "";
      //$del = "<th>&nbsp;</th>";
    } elseif($header->Deleted == "D") {
      $new = ' style="background-color: red; color: white;"';
      $del = "<th>&nbsp;</th>";
    }

    $to = escapeltgt($header->toaddress);
    $from = escapeltgt($header->fromaddress);

    $subject = escapeltgt($header->subject);

//    $subject .= " =?ISO-8859-1?Q?Keld_J=F8rn_Simonsen?= <keld@example.com>";
//    echo decodeMimeString(escapeltgt($subject));

    $to = decodeMimeString($to);
    $from = decodeMimeString($from);
    $subject = decodeMimeString($subject);

    echo <<<EOF
<tr$new>$del<th><a "style="background-color: #66FFFF" href="$self?email=$email&msgnum=$i">&nbsp;&nbsp;$i&nbsp;&nbsp;</a></th>
<td>$to</td><td>$from</td><td>$subject</td></tr>

EOF;
  }

  echo <<<EOF
</tbody>
</table>
<p>Expunge all Deleted  and marked Deleted Items <input type="submit" name="submit" value="Expunge" />
</form>
</div>
$footer
EOF;
} else {
  echo $S->getBanner("<h1>No Unread Messages at $email</h1>");
  echo $S->getFooter("<a href='/'>Return to Home Page</a>");
}

imap_close($mbox);

// HELPER FUNCTION

function get_mime_type(&$structure) {
  $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
  if($structure->subtype) {
    return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
  }
  return "TEXT/PLAIN";
}

// The function get_part() needs 3 parameters.
// 1. Mailbox connection (e.g. $mbox from my connection example)
// 2. Message number to look up (e.g. $msg from my message list example)
// 3. A content type to check for

function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) {
  if(!$structure) {
    $structure = imap_fetchstructure($stream, $msg_number);
  }
  if($structure) {
    if($mime_type == get_mime_type($structure)) {
   	  if(!$part_number) {
   		  $part_number = "1";
   		}
   		$text = imap_fetchbody($stream, $msg_number, $part_number);

      $x = $structure->parts[$part_number -1]; // imap_bodystruct($stream, $msg_number, $part_number);

   		if($x->encoding == 3) {
   		  return imap_base64($text);
   		} else if($structure->encoding == 4) {
        return imap_qprint($text);
   		} else {
   		  return $text;
   		}
   	}
   //echo "type=$structure->type<br>";
		if($structure->type == 1) /* multipart */ {
   	  while(list($index, $sub_structure) = each($structure->parts)) {
   		  if($part_number) {
   			  $prefix = $part_number . '.';
   			}
   			$data = get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));

        if($data) {
   				return $data;
   			}
   		} // END OF WHILE
    } // END OF MULTIPART
  } // END OF STRUTURE
  return false;
} // END OF FUNCTION

// other functions

function create_part_array($struct) {
    if (sizeof($struct->parts) > 0) {    // There some sub parts
        foreach ($struct->parts as $count => $part) {
            add_part_to_array($part, ($count+1), $part_array);
        }
    }else{    // Email does not have a seperate mime attachment for text
        $part_array[] = array('part_number' => '1', 'part_object' => $struct);
    }
   return $part_array;
}

/*
function create_part_array($structure, $prefix="") {
    //print_r($structure);
    if (sizeof($structure->parts) > 0) {    // There some sub parts
        foreach ($structure->parts as $count => $part) {
            add_part_to_array($part, $prefix.($count+1), $part_array);
        }
    }else{    // Email does not have a seperate mime attachment for text
        $part_array[] = array('part_number' => $prefix.'1', 'part_object' => $obj);
    }
   return $part_array;
}
*/
// Sub function for create_part_array(). Only called by create_part_array() and itself.
function add_part_to_array($obj, $partno, & $part_array) {
    $part_array[] = array('part_number' => $partno, 'part_object' => $obj);
    if ($obj->type == 2) { // Check to see if the part is an attached email message, as in the RFC-822 type
        //print_r($obj);
        if (sizeof($obj->parts) > 0) {    // Check to see if the email has parts
            foreach ($obj->parts as $count => $part) {
                // Iterate here again to compensate for the broken way that imap_fetchbody() handles attachments
                if (sizeof($part->parts) > 0) {
                    foreach ($part->parts as $count2 => $part2) {
                        add_part_to_array($part2, $partno.".".($count2+1), $part_array);
                    }
                }else{    // Attached email does not have a seperate mime attachment for text
                    $part_array[] = array('part_number' => $partno.'.'.($count+1), 'part_object' => $obj);
                }
            }
        } else {    // Not sure if this is possible
            $part_array[] = array('part_number' => $prefix.'.1', 'part_object' => $obj);
        }
    } else {    // If there are more sub-parts, expand them out.
        if (sizeof($obj->parts) > 0) {
            foreach ($obj->parts as $count => $p) {
                add_part_to_array($p, $partno.".".($count+1), $part_array);
            }
        }
    }
}

function writeAttachmentsToDisk($mailbox, $msg_number, $dir){
   if (!file_exists($dir)){
    mkdir($dir);
  }
  $filename = "tmp.eml";
  $email_file = $dir."/".$filename;
  // write the message body to disk
  imap_savebody  ($mailbox, $email_file, $msg_number);
  $command = "munpack -C $dir -fq $email_file";
  // invoke munpack which will
  // write all the attachments to $dir
  exec($command,$output);

  // if($output[0]!='Did not find anything to unpack from $filename') {
  $found_file = false;
  foreach ($output as $attach) {
    $pieces = explode(" ", $attach);
    $part = $pieces[0];
    if (file_exists($dir.$part)){
      $found_file = true;
      $files[] = $part;
    }
  }
  if (!$found_file){
    //echo ("\nMail.php : no files found - cleaning up. ");
    // didn't find any output files - delete the directory and email file
    unlink($email_file);
//    rmdir($dir);
    return false;
  }
  else {
    // found some files-  just delete the email file
    unlink($email_file);
    return $files;
  }
}

function header_quoted_printable_encode($string, $encoding='UTF-8') {
  $string = str_replace(" ", "_", trim($string)) ;
  // We need to delete "=\r\n" produced by imap_8bit() and replace '?'
  $string = str_replace("?", "=3F", str_replace("=\r\n", "", imap_8bit($string))) ;
   
  // Now we split by \r\n - i'm not sure about how many chars (header name counts or not?)
  $string = chunk_split($string, 73);
  // We also have to remove last unneeded \r\n :
  $string = substr($string, 0, strlen($string)-2);
  // replace newlines with encoding text "=?UTF ..."
  $string = str_replace("\r\n", "?=".HEAD_CRLF." =?".$encoding."?Q?", $string) ;
   
  return '=?'.$encoding.'?Q?'.$string.'?=';
}

//return supported encodings in lowercase.

function mb_list_lowerencodings() {
  $r = mb_list_encodings();
  for($n=sizeOf($r); $n--; ) {
    $r[$n]=strtolower($r[$n]);
  }
  return $r;
}

//  Receive a string with a mail header and returns it
// decoded to a specified charset.
// If the charset specified into a piece of text from header
// isn't supported by "mb", the "fallbackCharset" will be
// used to try to decode it.

function decodeMimeString($mimeStr, $inputCharset='utf-8', $targetCharset='utf-8', $fallbackCharset='iso-8859-1') {
  $encodings = mb_list_lowerencodings(); // above function
  $inputCharset = strtolower($inputCharset);
  $targetCharset = strtolower($targetCharset);
  $fallbackCharset = strtolower($fallbackCharset);

  $decodedStr='';
  $mimeStrs = imap_mime_header_decode($mimeStr);

  for($n=sizeOf($mimeStrs), $i=0; $i<$n; $i++) {
    $mimeStr = $mimeStrs[$i];
    //echo "$mimeStr->charset<br>";
    $mimeStr->charset = strtolower($mimeStr->charset);

    if(($mimeStr == 'default' && $inputCharset == $targetCharset) || $mimStr->charset == $targetCharset) {
      $decodedStr .= $mimStr->text;
    } else {
      $decodedStr .= mb_convert_encoding($mimeStr->text,
                                         $targetCharset,
                                         (in_array($mimeStr->charset, $encodings) ?
                                          $mimeStr->charset : $fallbackCharset)
                                        );
    }
  } return $decodedStr;
}  
?>
