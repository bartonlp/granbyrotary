<?php
$bad = $_GET['bad'];
if(empty($bad)) {
  echo <<<EOF
<html>
<head>
   <title>BAD FORM</title>
</head>
<body>
<h1>Nothing Here!</h1>
</body>
</html>
EOF;
  exit();
}

echo <<<EOF
<html>
<head>
   <title>BAD FORM</title>
</head>
<body>
<h1>Your Request URL is Not Valid</h1>
<p>Not sure what you are <b>up to</b> but '<i style="color: red">$bad</i>' just isn't a good URL.</p>
<h3 style="color: red">Good By</h3>
</body>
</html>
EOF;
?>
