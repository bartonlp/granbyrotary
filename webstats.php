<?php
// Display Web Statistics for Granby Rotary
// BLP 2015-03-15 -- check if id is me. Clean up logic so everything is done and put in
// variables and then at end we do 'render page'.
// BLP 2015-02-12 -- use barton.bots
// BLP 2014-11-02 -- tracker average now works with the showing display.

// ********************************************************************************
// Start of Main Page logic

// webstats-new.php sets $top and $footer and more.
ob_start();
$_REQUEST['x'] = 1;
require_once("webstats-new.php");
$content = ob_get_clean();

// $content has the rendered html which has the DOCTYPE and the <header.
// This must be removed because we want this to be an insert in this new page.

$content = preg_replace('~^<!DOCTYPE.*</header>~ims', '', $content);

$sql = "select id, concat(FName, ' ', LName) from rotarymembers";
$S->query($sql);
$members = array();

while(list($id, $name) = $S->fetchrow('num')) {
  $members[$id] = $name;
}

$sql = "select ip, id from memberpagecnt";

$S->query($sql);
$memberbyip = array();

while(list($ip, $id) = $S->fetchrow('num')) {
  $memberbyip[$ip] = $id;
}

$jmembers = json_encode($members);

// Member Hits

// NOTE the last field is San Diego time so in all cases add on hour
// via addtime().

// Get the last person to access the webpage

$S->query("select concat(FName, ' ', LName), addtime(visittime, '1:0') as lastvisit from rotarymembers
where visittime = (select max(visittime) from rotarymembers where visits != 0 and id not in ('25', '$S->id'))");

list($fullName, $lastAccess) = $S->fetchrow('num');

// Get the count of active and honorary members.

$S->query("select status, count(*) as count from rotarymembers where status in ('active', 'honorary') group by status");

while(list($stat, $cnt) = $S->fetchrow('num')) {
  $ar[$stat] = $cnt;
}

$activeMembers = $ar['active'];
$honoraryMembers = $ar['honorary'];

// Count of active and honorary
$totalMembers = $activeMembers + $honoraryMembers;

// Members Who Visited
$query = "select id, FName, LName, visits, status,  addtime(visittime, '1:0') as lastvisit ".
         "from rotarymembers ".
         "where visits != 0 and status in('active','honorary') order by lastvisit desc";

$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%visits%</td><td>%lastvisit%</td><td>%id%</td></tr>";

// function to add '*' to honorary members
function callback2(&$row, &$desc) {
  $row['name'] = ($row['status'] == "honorary") ?
                 "<span style='color: gray; border: 0'>*</span>{$row['FName']} {$row['LName']}" : "{$row['FName']} {$row['LName']}";
  return false;
}

$ar = array('callback'=>'callback2', 'delim'=>"%"); // Use this for all makeresultrows()

$visitedSiteMembers = $T->makeresultrows($query, $rowdesc, $ar);
$memberCnt = $S->getNumRows();

// Others who visited

$query = "select id, FName, LName, visits, status, addtime(visittime, '1:0') as lastvisit, club ".
         "from rotarymembers ".
         "where visits !=0 and status not in ('active', 'honorary') ".
         "and visittime > date_sub(current_date(), interval 1 month) order by lastvisit desc";

$rowdesc = <<<EOF
<tr>
<td id='Id_%id%'>%FName% %LName%</td><td>%visits%</td><td>%status%</td><td>%lastvisit%</td><td>%id%</td><td>%club%</td>
</tr>
EOF;

$otherMemberHits = $T->makeresultrows($query, $rowdesc, $ar);

if(!empty($otherMemberHits)) {
  $otherMemberHits = <<<EOF
<h2>Others Who Visited<br>
Our Site in the Last Month</h2>

<table id='otherMemberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Status</th><th>Last Visit</th><th>Id</th><th>Club</th>
</tr>
</thead>
<tbody>
$otherMemberHits
</tbody>
</table>
EOF;
}

$whos = $S->getWhosBeenHereToday();

// Members who have NOT visited.

$query = "select id, FName, LName, status from rotarymembers where visits = 0 and status in('active','honorary')";
$rowdesc = "<tr><td id='Id_%id%'>%name%</td><td>%id%</td></tr>";

$notVisitedSiteMembers = $T->makeresultrows($query, $rowdesc, $ar);
$memberNotVisitCnt = $S->getNumRows();

echo <<<EOF
$top
<div class='wrapper'>
<h3 style="text-align: center">Total Members: $totalMembers ($activeMembers active
and $honoraryMembers honorary) Members</h3>
<p style="color: gray; text-align: center; margin-top: -15px">Asterisk '*' Denotes Honorary Members</p>
<div class='left'>
<p>Members using web site=$memberCnt.</p>
<h2>Members Who Visited<br>
Our Site</h2>
<table id='memberHits' border="1">
<thead>
<tr>
<th>Name</th><th>Visits</th><th>Last Visit</th><th>Id</th>
</tr>
</thead>
<tbody>
$visitedSiteMembers
</tbody>
</table>
$otherMemberHits
<p>Most recient access (not counting this one):
<ul>
   <li>$lastAccess</li>
   <li>$fullName</li>
</ul>
</p>
</div>
<div class='right'>
<p>Members <b>not</b> using web site=$memberNotVisitCnt.</p>
<h2>Members<br>Who Have Not Visited</h2>
<table id='memberNot' border="1">
<thead>
<tr>
<th>Name</th><th>Id</th>
</tr>
</thead>
<tbody>
$notVisitedSiteMembers
</tbody>
</table>
<br>
$whos
</div>
</div>
<br  style='clear: both'>

$content

EOF;
