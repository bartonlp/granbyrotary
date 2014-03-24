#!/usr/bin/php -q
<?php
// THIS IS A CLI program run from CRON.

// Create two files once per night after the cntagentsrunlength.new.php runs
// Creates the lamphostrunlength.txt file which is used in hits.php for the "OS and Browsers for
// Lamphost.net" section.
// Creates the countwindow.php file which can be viewed from the above section.

// NOTE: The definition of TOPFILE for a CLI program does NOT use DOC_ROOT as the prefix!
// For a WEB program it would be: $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php"
define('TOPFILE', "/home/bartonlp/granbyrotary.org/htdocs/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;
$tbl = showlamphost(30, $S);

if(file_put_contents("/home/bartonlp/granbyrotary.org/htdocs/lamphostrunlength.txt", $tbl) === false) {
  echo "Error writing lamphostrunlength.txt\n";
} else {
  echo "lamphostrunlength.txt Written OK\n";
  chmod("/home/bartonlp/granbyrotary.org/htdocs/lamphostrunlength.txt", 0655);
}

$win = showwindows($S);

if(file_put_contents("/home/bartonlp/granbyrotary.org/htdocs/countwindows.php", $win) === false) {
  echo "Error writing countwindows.php\n";
} else {
  echo "countwindows.php Written OK\n";
  chmod("/home/bartonlp/granbyrotary.org/htdocs/countwindows.php", 0655);
}

//  ---------------------------------------------------------------------------

function showwindows($S) {
  $h->extra = <<<EOF
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

  $date = date("D F d Y");

  $h->title = "Web Site Statistics: count windows types";
  $h->banner =<<<EOF
<h2>Major Windows Types for $date</h2>
EOF;

  $h->preheadcomment =<<<EOF

<!--
This page is created by a CRON job and should not be edited manually.
It will be rewritten every night. The 'scripts/createrunlentable.php' creates this file.
-->

EOF;
  
  list($top, $footer) = $S->getPageTopBottom($h);

  $windows = array('6.2'=>'Windows 8',
                   '6.1'=>'Windows 7, 22 October 2009',
                   '6.0'=>'Windows Vista, 30 November 2006',
                   '5.2'=>'Windows Server 2003; Windows XP x64 Edition, 28 March 2003',
                   '5.1'=>'Windows XP, 25 October 2001',
                   '5.01'=>'Windows 2000, Service Pack 1 (SP1)',
                   '5.0'=>'Windows 2000, 17 February 2000',
                   '95'=>'Windows 95, 24 August 1995',
                   '98'=>'Windows 98, 25 June 1998; Windows 98 Second Edition, 5 May 1999');

  $S->query("select sum(count) from runlength");
  list($total) = $S->fetchrow('num');
  $S->query("select sum(count) from runlength where agent like('%Win%')");
  list($totalwin) = $S->fetchrow('num');

  $S->query("select agent, count, counts from runlength where agent regexp ".
                      "'(Windows NT (6\\.2|6\\.1|6\\.0|5\\.2|5\\.1|5\\.0))|Windows 9[58]'");

  while(list($agent, $count, $counts) = $S->fetchrow('num')) {
    if(preg_match("/(?|(?:Windows\s+NT\s+(\d*\.?\d*))|(?:Windows\s+(9[58])))/", $agent, $m)) {
      $win[$m[1]][0] = $m[0]; // entire match
      $win[$m[1]][1] += $count;
      $x = array_sum(explode(',', $counts));
      $winr[$m[1]] += $x;
      $rcnt += $x;
    } 
  }

  ksort($win, SORT_STRING);
  ksort($winr, SORT_STRING);

  $table = "";

  foreach($win as $k=>$v) {
    $pwin = number_format(($v[1] / $totalwin * 100), 2, ".", ",");
    $pall = number_format(($v[1] / $total * 100), 2, ".", ",");
    $vv = number_format($v[1], 0, "", ",");
    $rl = number_format(($winr[$k] / $rcnt * 100), 2, ".", ",");

    $table .= <<<EOF
<tr>
<th>$v[0]</th>
<td style='text-align: right; padding: 5px'>$vv</td>
<td style='padding: 5px'>$windows[$k]</td>
<td style='text-align: right'>$pwin</td>
<td style='text-align: right'>$rl</td>
<td style='text-align: right'>$pall</td>
</tr>

EOF;
  }

  $total = number_format($total, 0, "", ",");
  $totalwin = number_format($totalwin, 0, "", ",");
  $totalr = number_format($rcnt, 0, "", ",");

  return <<<EOF
$top
<p>&quot;% Win&quot; shows the version as a percent of the total number of windows records.</p>
<p>&quot;% Win 30 days&quot; show the version as a percent of the total number of windows records for the last 30 days.</p>
<p>&quot;% All&quot; shows the version as a percent of the total number of records.</p>
<p>Total Number of Windows Records: $totalwin</p>
<p>Total Number of Windows Records for the past 30 days: $totalr</p>
<p>Total Number of Records: $total</p>
<table id='wintypes' border="1">
<thead>
<tr>
<th>Name</th><th>Count</th><th>Windows Name</th><th>% Win</th><th>% Win<br>30 days</th><th>% All</th>
</tr>
</thead>
<tbody>
$table
</tbody>
</table>
<p>The column labeled &quot;Name&quot; is how the Windows version is refered to in the &quot;User Agent String&quot; while
the column labeled &quot;Windows Name&quot; is the common name and the date the version was released. The &quot;Count&quot;
and &quot;% Win&quot; 
columns are the total number of times this agent has been used and percentage of all Windows version. The
&quot;% Win 30 Days&quot; shows the last 30 days percentage. Finaly, the column &quot;% All&quot; show the percent
of this agent compaired to all other Windows and non-Windows agents.</p>
<p>As you can see there are still some people using Windows 95 and 98. Fortunately not many. There are many
other versions of Windows NT but they are very small.</p>
<p>Unfortunately the &quot;User Agent String&quot; that a browser supplies is not very standardized or accurate so
there are often browsers that are lying about who they really are.</p>
<hr/>
$footer
EOF;
}

//  ---------------------------------------------------------------------------

function showlamphost($days, $S) {
  $ar = array('OS'=>array('types'=>array(), 'table'=>""),
              'Browser'=>array('types'=>array(), 'table'=>""));
  
  $types = array('Windows', 'Linux', 'Macintosh', 'Android', 'iPhone');
  $ar['OS']['types'] = $types;

  $types = array('Opera', 'Safari', 'Firefox', 'MSIE', 'Chrome');
  $ar['Browser']['types'] = $types;

  $table = <<<EOF

<!--
The file 'lamphostrunlength.txt' is created by a CRON job. It will be rewritten
every night. The 'scripts/createrunlentable.php' creates this file.
-->

<h2>Major OS Types</h2>
<table id="oslamphost1" border="1">
<thead>
<tr><th>OS</th><th>Count</th><th>%</th><th>Last $days</th><th>%</th></tr>
</thead>
<tbody>

EOF;
  $ar['OS']['table'] = $table;

  $table = <<<EOF
<h2>Major Browser Types</h2>
<table id="oslamphost2" border="1">
<thead>
<tr><th>Browser</th><th>Count</th><th>%</th><th>Last $days</th><th>%</th></tr>
</thead><tbody>

EOF;

  $ar['Browser']['table'] = $table;

  $S->query("select sum(count) as count from runlength");
  list($total) = $S->fetchrow('num');
  $S->query("select count(*) from runlength");
  list($totalagents) = $S->fetchrow('num');

  $totalrunlen =  0;
  $n = $S->query("select counts from runlength ".
                 "where counts!='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'");
  if($n) {
    while(list($c) = $S->fetchrow('num')) {
      $totalrunlen += array_sum(explode(",", $c));
    }
  }

  foreach($ar as $k=>$t) {
    $cnt = 0;
    $cntrunlen = 0;
    
    foreach($t['types'] as $type) {
      switch($type) {
        case "Chrome":
          $stype = "Chrome%Safari";
          break;
        case "Safari":
          $stype = "Safari%') and agent not like('%Chrome";
          break;
        default:
          $stype = $type;
          break;
      }

      $S->query("select agent, count, counts from runlength where agent like('%$stype%')");
      
      while(list($agent, $count, $counts) = $S->fetchrow('num')) {
        $ccount += $count;
        $countrunlen += array_sum(explode(',', $counts));
      }

      $cnt += $ccount;
      $cntrunlen += $countrunlen;

      if($total) {
        $percent = number_format($ccount / $total * 100, 2, ".", ",");
      } else {
        $percent = 0;
      }

      if($totalrunlen) {
        $percentrunlen = number_format($countrunlen / $totalrunlen * 100, 2, ".", ",");
      } else {
        $percentrunlen = 0;
      }
      
      $ccount = number_format($ccount, 0, "", ",");
      $countrunlen = number_format($countrunlen, 0, "", ",");

      $ar[$k]['table'] .= "<tr><th style='text-align: left'>$type</th>".
                          "<td style='text-align: right'>$ccount</td>".
                          "<td style='text-align: right'>$percent</td>".
                          "<td style='text-align: right'>$countrunlen</td>".
                          "<td style='text-align: right'>$percentrunlen</td></tr>\n";
    }
    $cnt = $total - $cnt;
    $cntrunlen = $totalrunlen - $cntrunlen;

    if($total) {
      $percent = number_format($cnt / $total * 100, 2, ".", ",");
    } else {
      $percent = 0;
    }
    
    $cnt = number_format($cnt, 0, "", ",");

    if($totalrunlen) {
      $percentrunlen = number_format($cntrunlen / $totalrunlen * 100, 2, ".", ",");
    } else {
      $percentrunlen = 0;
    }
    
    $cntrunlen = number_format($cntrunlen, 0, "", ",");
      
    $ar[$k]['table'] .= <<<EOF
<tr>
<th style='text-align: left'>Other</th>
<td style='text-align: right'>$cnt</td>
<td style='text-align: right'>$percent</td>
<td style='text-align: right'>$cntrunlen</td>
<td style='text-align: right'>$percentrunlen</td>
</tr>
</tbody>
</table>

EOF;
  }
  $ftotal = number_format($total, 0, "", ",");
  $ftotalrunlen = number_format($totalrunlen, 0, "", ",");
  $ftotalagents = number_format($totalagents, 0, "", ",");
  // number of days since June 3, 2010

  $S->query("select datediff(curdate(), '2010-06-02')");
  list($timediff) = $S->fetchrow('num');  

  $date = date("D F d Y");

  $tbl = <<<EOF
<p>From <i>runlength</i> table for $date.<br/>
Total Unique Agents: $ftotalagents<br/>
Total Hits: $ftotal<br/>
Total Hits for $days days: $ftotalrunlen (starting June 3, 2010, collecting for $timediff days)<br>
This represents about 300 different web sites hosted by Lamphost.net.</p>

<div id='lamphost' class='wrapper'>
<div id='OSLamphostCnt' class='left'>
{$ar['OS']['table']}
</div>
<div id='BrowserLamphostCnt' class='right'>
{$ar['Browser']['table']}
</div>
</div>
<br style='clear: both'/>
<p><a href="http://www.granbyrotary.org/countwindows.php">More Information about Windows Versions</a>.</p>

EOF;
  return $tbl;
}
?>
