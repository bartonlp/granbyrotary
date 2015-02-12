<?php
require_once("/var/www/includes/siteautoload.class.php");
$S = new GranbyRotary;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>  
<head>
  <title>[:title:]</title>
  <meta name="description"
     content="Name: Rotary Club of Granby Colorado, Page: [:desctitle:]" />
  <meta name="keywords" content="rotary" />

   <!-- Link our custom CSS -->
   <link rel="stylesheet" title="Rotary Style Sheet"
        href="/rotary.css" type="text/css" />

   <!-- Inline Javascript if any -->
   <script type="text/javascript">
   </script>

   <!-- Inline CSS if any -->
   <style type='text/css'>
   </style>

</head>
<body>

<!-- Header Goes Here -->
<?php
echo $S->getBanner("<h2>[:headertitle:]</h2>");
?>
<!-- End Header -->

<!-- Body items go here -->

<!-- End Body items -->

<hr/>

<!-- Footer Goes Here -->
<?php
echo $S->getFooter();
?>
