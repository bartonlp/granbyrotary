<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;

$extra = <<<EOF
<link rel="stylesheet"  href="/css/tablesorter.css" type="text/css" />
  <link rel="stylesheet" href="/css/hits.css" type="text/css" />
  <script type='text/javascript' src='/js/hits.js'></script>

  <script type="text/javascript"
           src="/js/tablesorter/jquery.metadata.js"></script>
   
  <script type="text/javascript"
           src="/js/tablesorter/jquery.tablesorter.js"></script>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  $("#wintypes").addClass("tablesorter");
  $("#wintypes").tablesorter({headers: { 1: {sorter: "currency"}, 3: {sorter: "currency"}, 4: {sorter: "currency"}}});
});
  </script>
EOF;

$top = $S->getPageTop(array(title=>"Web Site Statistics: count windows types", extra=>$extra),
                      "<h2>Web Site Statistics</h2>");

$footer = $S->getFooter();

$windows = array('6.1'=>'Windows 7, 22 October 2009',
'6.0'=>'Windows Vista, 30 November 2006',
'5.2'=>'Windows Server 2003; Windows XP x64 Edition, 28 March 2003',
'5.1'=>'Windows XP, 25 October 2001',
'5.01'=>'Windows 2000, Service Pack 1 (SP1)',
'5.0'=>'Windows 2000, 17 February 2000',
'4.0'=>'Microsoft Windows NT 4.0 29 July 1996',
'3.1'=>'Windows NT 3.1 	Workstation (named just Windows NT), 27 July 1993',
'3.5'=>'Windows NT 3.5 	Workstation, 21 September 1994',
'3.51'=>'Windows NT 3.51 	Workstation, 30 May 1995');

$result = $S->query("select sum(count) as sum from runlength");
$row = mysql_fetch_assoc($result);
$total = $row[sum];
$result = $S->query("select sum(count) as sum from runlength where agent like('%Win%')");
$row = mysql_fetch_assoc($result);
$totalwin = $row[sum];

$result = $S->query("select * from runlength where agent like('%Windows NT%')");

while($row = mysql_fetch_assoc($result)) {
  extract($row);
  if(preg_match("/Windows\s+NT\s+(\d*\.?\d*)/", $agent, $m)) {
    $win[$m[1]] += $count;
  } 
}

ksort($win, SORT_STRING);
$table = "";

foreach($win as $k=>$v) {
  $pwin = number_format(($v / $totalwin * 100), 2, ".", ",");
  $pall = number_format(($v / $total * 100), 2, ".", ",");
  $v = number_format($v, 0, "", ",");
  $table .= <<<EOF
<tr>
<th>Windows NT $k</th>
<td style='text-align: right; padding: 5px'>$v</td>
<td style='padding: 5px'>$windows[$k]</td>
<td style='text-align: right'>$pwin</td>
<td style='text-align: right'>$pall</td>
</tr>

EOF;
}
$total = number_format($total, 0, "", ",");
$totalwin = number_format($totalwin, 0, "", ",");

echo <<<EOF
$top
<p>Total Number of Records: $total</p>
<p>Total Number of Windows Records: $totalwin</p>
<table id='wintypes'>
<thead>
<tr>
<th>Name</th><th>Count</th><th>Windows Name</th><th>% of Windows</th><th>% of All</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>
<p>Unfortunatly the &quot;User Agent String&quot; that a browser supplies is not very standardized or accurate. As you can
see there are Windows Versions that have not happened yet, like: <i>Windows NT 9.9</i>. It will probably take a while before
Microsoft come out with version 9 of Windows. With any luck it will never happen.</p>
<hr/>
$footer
EOF;
?>
