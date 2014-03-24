<?php
// Create an article

require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;  // not header etc.

if(!$S->isAdmin($S->id)) {
  echo "<h1>Sorry This Is Just For Designated Admin Members</h1>";
  exit();
}

$footer = $S->getFooter();

$self = $_SERVER['PHP_SELF'];

if($_POST['Submit']) {
  extract(stripSlashesDeep($_POST));

  // echo "articleExpired=$articleExpired<br>\n";
  // echo "rssExpired=$rssExpired<br>\n";
  
  if(!empty($articleName)) {
    $articleName = "<articleName>$articleName</articleName>";
  }
  if(!empty($articleTemplate)) {
    $articleTemplate = "<articleTemplate>$articleTemplate</articleTemplate>";
  }
  if(!empty($articleHeader)) {
    $articleHeader = preg_replace("/\r/sm", "", $articleHeader);
    $articleHeader = "<articleHeader>\n$articleHeader\n</articleHeader>";
  }
  if(empty($articleBody)) {
    echo "Must have a Body!<br>\n";
    exit();
  }
  $articleBody = preg_replace("/\r/sm", "", $articleBody);
  $articleBody = "<articleBody>\n$articleBody\n</articleBody>";

  $article = <<<EOF
$articleName
$articleTemplate
$articleHeader
$articleBody

EOF;

  $f = fopen("/tmp/article-preview.article", "w");
  fwrite($f, $article);
  fclose($f);

  $extra = <<<EOF
<style type="text/css">
body {background-color: white;}
#subButton {
  font-size: 1.5em;
  background-color: green;
  color: white;
  padding: 20px;
}
#reset {
  font-size: 1.5em;
  background-color: red;
  color: white;
  padding: 20px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("#reset").click(function() {
    jQuery.ajax({
type: "POST",
data: "file=/tmp/article-preview.article",
url: "del.php",
error: function(req, status) {
      alert("error:" + status);
   }
    });
    jQuery("#subButton").hide();
    jQuery("#reset").hide();
    jQuery("iframe").fadeOut(1000, function() {
      // The ajax needs time to do its thing so do a 1 second hide
      // before doing the back
      window.history.back();
    });
  });

  jQuery("#subButton").click(function() {
    location.href="mkarticle.php?article=/tmp/article-preview.article&return=$self&articleExpired=$articleExpired&rssExpired=$rssExpired";
  });
});

</script>  

EOF;

  $top = $S->getPageTop(array(title=>"Create News Article", extra=>$extra), "<h1>Preview of Article</h1>");

  echo <<<EOF
$top
<div style="margin-bottom: 20px">
<hr>
<iframe src="showarticle.php?article=/tmp/article-preview.article" width="100%" height="500px">No frames</iframe>
<hr>
<button id="subButton">Create Article</button>
&nbsp;<button id="reset">Discard and return to editor panel</button>
</div>
$footer
EOF;
   
  exit();
}

//--------------------------------------------
// Present form to fill out on initial contact  

$extra = <<<EOF
<script type="text/javascript" src="http://www.bartonphillips.com/ckeditor/ckeditor.js"></script>
<script src="http://www.bartonphillips.com/ckeditor/sample.js" type="text/javascript"></script>
<link href="http://www.bartonphillips.com/ckeditor/sample.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="/js/date_input/jquery.date_input.js"></script>
<link rel="stylesheet" href="/js/date_input/date_input.css" type="text/css">

<script type='text/javascript'>
jQuery(document).ready(function($) {
  $.extend(DateInput.DEFAULT_OPTS, {
    stringToDate: function(string) {
      var matches;
      if(matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
        return new Date(matches[1], matches[2] - 1, matches[3]);
      } else {
        return null;
      };
    },

    dateToString: function(date) {
      var month = (date.getMonth() + 1).toString();
      var dom = date.getDate().toString();
      if (month.length == 1) month = "0" + month;
      if (dom.length == 1) dom = "0" + dom;
      return date.getFullYear() + "-" + month + "-" + dom;
    }
  });
  $.date_input.initialize();

  // add buttons to insert rssfeed stuff

  $("<div id='ctrl' style='border: 1px solid black; padding: 5px; width: 300px; margin-bottom: 30px;'>\
    <button id='rssfeed' style='width: 100%;'>Append Rssfeed tags</button>\
    <button id='commentedrssfeed' style='width: 100%;'>Append Commented Rssfeed tags</button>\
    <input id='inputfile' type='text' name='inputfile' value='tmp' />\
    <button id='loadfile'>Load File</button>\
    <p>Files are loaded from the /other directory on the website. The file is <b>inserted</b>\
    at the cursor location in the <b>Article Body</b>.</p></div>")
  .prependTo("#creatediv");

  $("#rssfeed").click(function() {
    var v = $("#bodyarea").val() + "\\n<!-- <rssfeed>\\n\\n</rssfeed> -->\\n"; 
    $("#bodyarea").text(v);  
  });

  $("#commentedrssfeed").click(function() {
    var v = $("#bodyarea").val() + "\\n<!-- <rssfeed> -->\\n\\n<!-- </rssfeed> -->\\n"; 
    $("#bodyarea").text(v);  
  });

  $("#loadfile").click(function() {
    var v = $("#inputfile").val();
    v = "/other/" + v;
    $("#bodyarea").load(v, function() {
      var sel = this.selectionStart;
      var l = $("#bodyarea").html();
      v = $("#bodyarea").val();
      l = v.substr(0, sel) + "\\n" + l + "\\n" + v.substr(sel, v.length);
      $("#bodyarea").val(l);
    });
  });

  $("#artexp").change(function() {
    if($("#rssexp").val().match(/^\s*$/)) {
      var v = $("#artexp").val();
      $("#rssexp").val(v);
    }
  });
});  
</script>  
<style type='text/css'>
div {
   width: 100%;
}
#formTbl {
   width: 100%;
}
#formTbl th {
   vertical-align: top;
}
#form textarea {
   width: 100%;
}

</style>
  
EOF;

  $top = $S->getPageTop(array(title=>"Create News Article", extra=>$extra), "<hd1>Create Article</hd1>");

  echo <<<EOF
$top
<div id='creatediv'>
<form id='form' action='$self' method='post'>
<table id='formTbl'>
   <tr><th>Article Name</th><td><input type='text' name='articleName'/></td></tr>
   <tr><th>Article Template</th><td><input type='text' name='articleTemplate'/></td></tr>
   <tr><th>Article Expires</th><td><input id='artexp' class='date_input' type='text' name='articleExpired' value='' /></td></tr>
   <tr><th>Rss Expires</th><td><input id='rssexp' class='date_input' type='text' name='rssExpired' value='' /></td></tr>

   <tr>
     <th>Article Body</th>
       <td><textarea id='bodyarea' name='articleBody' cols='100' rows='20'></textarea>
         <script type="text/javascript">
           //<![CDATA[
           CKEDITOR.replace('bodyarea');
           //]]>
         </script>
      </td>
   </tr>

   <tr><th>Article Header</th>
     <td><textarea id='headerarea' name='articleHeader' cols='100' rows='5'></textarea>
    </td>
  </tr>
</table>
   <input type='submit' name='Submit' value='Submit'/>
</form>
</div>

<hr/>
<div>
<a href="/articles/adminrss.php">Rss Admin</a><br/>
<a href="/articles/createarticle.php">Create Article</a><br/>
<a href="/articles/editarticle.php">Edit Article</a><br/>
<a href="/admin/admin.php">Database Admin</a><br/>
<a href="/articles/admin.php">Article Admin</a><br/>
</div>
$footer
EOF;

?>