<?php
// This file is 'included' in new.php and articles/createrss.php
// as such the $S, $pageHead, and $banner are set in those files.
//$SQLDEBUG = true; $ERRORDEBUG=true; // no error messages sent via email!

$t = new dbTables($S);
$query = "select date, name, subject from meetings where date > curdate() and date < date_add(curdate(), interval 4 week)";
$rowdesc = "<li>%date% %name% <b>%subject%</b></li>";

$ulbody = $t->makeresultrows($query, $rowdesc, array(delim=>"%"));

echo <<<EOF
$pageHead
$banner
<a name="upcoming"></a>
<h2>Up Coming Meetings at Maverick's</h2>
<p>Our Weekly Wednesday Noon Lunch Meetings will be held at Maverick's restaurant in down town Granby.</p>
<ul>
$ulbody
</ul>
<hr/>

EOF;

?>
