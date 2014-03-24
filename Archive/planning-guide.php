<?php
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");

$S = new GranbyRotary;

$h->title = "Planning Guide";
$h->extra = <<<EOF
<script type="text/javascript">
jQuery(document).ready(function($) {
  $("textarea").css({'width': '800px', 'height': '100px'});
});
</script>
EOF;
$S->top = $S->getPageTop($h);
$S->footer = $S->getFooter("<hr>");

if($S->id == 98) {
  // Assistant Governor Mark Lund 98
  // Fack up the info
  $_POST['clubname'] = "Granby";
  $_POST['officeyear'] = "2010";
  $_POST['presidentname'] = "Barton Phillips";
  printReport($S);
  exit();
}

if(!$S->isAdmin($S->id)) {
  echo <<<EOF
$S->top
<h2>This page is only for club administrators</h2>
<p><a href="/">Return to the home page </a>
$S->footer
EOF;
  exit();
}

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case "POST":
    switch($_POST['page']) {
      case "page1":
        page1($S);
        break;
      case "page2":
        page2($S); // Membership
        break;
      case "page3":
        page3($S);
        break;
      case "page4":
        page4($S);
        break;
      case "page5":
        page5($S);
        break;
      case "page6":
        page6($S);
        break;
      case "page7":
        page7($S);
        break;
      case "page8":
        page8($S);
        break;
      case "printreport":
        printReport($S);
        break;
      case "copyrecord":
        copyRecord($S);
        break;
      default:
        throw(new Exception("default POST"));
    }
    break;
   
  case "GET":
    switch($_GET['page']) {
      case "inittables":
        inittables($S);
        break;
      default:
        startPage($S);
        break;
    }
    break;

  default:
    throw(new Exception("Not GET or POST"));
}

// ********************************************************************************
// Initialize the table

function inittables($S) {
  $query = "drop table planning_guide";
  $S->query($query);

  $query = <<<EOF
create table planning_guide (
  clubname varchar(255) not null,
  officeyear int not null,
  presidentname varchar(255) not null,
  address varchar(255),
  phone varchar(20),
  email varchar(255),
  page2 text,
  page3 text,
  page4 text,
  page5 text,
  page6 text,
  page7 text,
  primary key(clubname, officeyear, presidentname)
) ENGINE=InnoDB
EOF;
  $S->query($query);
  echo "<h1>Created</h1>";
}

// ********************************************************************************
// Start Page

function startPage($S) {
  // Show the availabe datasets

  $query = "select * from planning_guide";

  $n = $S->query($query);
  while($row = $S->fetchrow()) {
    extract($row);
    $rows .= <<<EOF
<tr><td><input type="radio" name="select" value="$clubname:$officeyear:$presidentname:$address:$phone:$email"/></td>
<td>$clubname</td>
<td>$officeyear</td>
<td>$presidentname</td>
</tr>

EOF;
  }

  echo <<<EOF
$S->top
<h2>You can select the dataset you want to start with, or a <i>New Dataset</i></h2>

<form action="$S->self" method="post">
<table border="1">
<thead>
<tr><th>Select</th><th>Club Name</th><th>Term Year</th><th>President</th></tr>
</thead>
<tbody>
$rows
<tr><td><input type="radio" name="select" value=""/></td><td colspan="3">New Dataset</td></tr>
</tbody>
</table>
<input type="submit" value="Continue">
<input type="hidden" name="page" value="page1"/>
</form>
$S->footer
EOF;
}


// ********************************************************************************
// First Page asks for Club Date President

function page1($S) {
  list($clubname, $officeyear, $presidentname, $address, $phone, $email) = explode(":", $_POST['select']);

  echo <<<EOF
$S->top
<h2>Planning Guide for Effective Rotary Clubs</h2>

<p>This <i>Planning Guide for Effective Rotary Clubs</i> is a tool to help clubs assess their current state and establish goals
for the coming year. It is based on the Club Leadership Plan. The strategies listed in each section are common ways clubs might
choose to pursue goals.
Clubs are encouraged to develop alternative strategies to achieve their goals when appropriate.  Presidents-elect should complete
this form in cooperation with their club and submit a copy of it to their assistant govenrnor by 1 July.</p>

<form action="$S->self" method="post">
Rotary Club of <input type="text" name="p1_clubname" value="$clubname" /><br>
Rotary Year of office (the year your term started): <input type="text" name="p1_officeyear" value="$officeyear" /><br>
Name of President: <input type="text" name="p1_presidentname" value="$presidentname"/><br>
Mailing Address: <input type="text" name="p1_address" value="$address"/><br>
Phone: <input type="text" name="p1_phone" value="$phone"/><br>
Email: <input type="text" name="p1_email" value="$email" /><br>
<input type="radio" name="select" value="data"/> Select Data<br>
<input type="radio" name="select" value="print"/> Print<br>
<input type="radio" name="select" value="enterdata" checked/> Enter Data<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page2"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function selectRecord($S) {
  extract($_POST);

  $query = "select clubname, officeyear, presidentname from planning_guide" .
           "where not(clubname = '$clubname' and officeyear = '$officeyear' and presidentname = '$presidentname')";

  $n = $S->query($query);
  if(!$n) {
    echo "$S->top\nThere are no records for other data sets\n$S->footer";
    exit();
  }

  while($row = $S->fetchrow()) {
    extract($row, EXTR_PREFIX_ALL, 'c');
    $rows .= <<<EOF
<tr><td><input type="radio" name="select" value="$c_clubname:$c_officeyear:$c_presidentname"/></td>
<td>$c_clubname</td>
<td>$c_officeyear</td>
<td>$c_presidentname</td>
</tr>

EOF;
  }

  echo <<<EOF
$S->top
<h2>You can select the data you want to start with.</h2>
<p>When you select a dataset it will be copied to the pages in your dataset (any data already in your set will be lost).</p>
<form action="$S->self" method="post">
<table border="1">
<thead>
<tr><th>Select</th><th>Club Name</th><th>Term Year</th><th>President</th></tr>
</thead>
<tbody>
$rows
</tbody>
</table>
<input type="submit" value="Submit">
<input type="hidden" name="page" value="copyrecord"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function copyRecord($S) {
  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  list($c_clubname, $c_officeyear, $c_presidentname) = explode(":", $_POST['select']);

  $query = "select page2, page3, page4, page5, page6, page7 from planning_guide ".
           "where clubname='$c_clubname' and officeyear='$c_officeyear' and presidentname='$c_presidentname'";

  $n = $S->query($query, true);
  if(!$n) {
    echo "No records found";
    exit();
  }
  $row = $S->fetchrow();
  extract($row);

  $query = "insert into planning_guide ".
           "(clubname, officeyear, presidentname, address, phone, email, page2, page3, page4, page5, page6, page7) ".
           "values('$clubname', '$officeyear', '$presidentname', '$address', '$phone', '$email', ".
           "'$page2', '$page3', '$page4', '$page5', '$page6', '$page7') ".
           "on duplicate key update address='$address', phone='$phone', email='$email', ".
           "page2='$page2', page3='$page3', page4='$page4', page5='$page5', page6='$page6', page7='$page7'";

  $S->query($query);

echo "query=$query<br>";

}

// ********************************************************************************

function page2($S) {
  foreach($_POST as $key=>$value) {
    if(preg_match("/p1_(.*)/", $key, $m)) {
      $$m[1] = $value;
      $_POST[$m[1]] = $value;
    }
  }

  switch($_POST['select']) {
    case 'data':
      selectRecord($S);
      exit();
    case 'print':
      printReport($S);
      exit();
  }
  $query = "select page2 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page2) = $S->fetchrow();

    if($page2) {
      $ar = unserialize($page2);

      foreach($ar as $key=>$value) {
        if(preg_match("/p2_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p2_r(.*)/", $key, $m)) {
          $ar["p2_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  } else {
    // insert new entry
    $query = "insert into planning_guide (clubname, officeyear, presidentname, address, phone, email) ".
             "values('$clubname', '$officeyear', '$presidentname', '$address', '$phone', '$email')";
    //echo "$query<br>";
    $S->query($query);
  } 

  // This is p2 section. p2_n
  echo <<<EOF
$S->top
<h3>Membership</h3>
<form action="$S->self" method="post">
Current number of members: <input type="text" name="p2_1" value="$p2_1"/><br>
Membership goal for the upcoming Rotay year <input type="text" name="p2_1a" value="$p2_1a".<br>
Number of members as of 30 June last year: <input type="text" name="p2_2" value="$p2_2"/><br>
Number of members as of 30 June five years ago: <input type="text" name="p2_3" value="$p2_3"/><br>
Number of male members: <input type="text" name="p2_4" value="$p2_4"/><br>
Number of female members: <input type="text" name="p2_5" value="$p2_5"/><br>
Average age of members: <input type="text" name="p2_6" value="$p2_6"/><br>
Number of Rotary alumni members: <input type="text" name="p2_7" value="$p2_7"/><br>
Number of members that have been members for 1-3 years <input type="text" name="p2_8" value="$p2_8"/>
3-5 years <input type="text" name="p2_8a" value="$p2_8a"/> more than 5 years <input type="text" name="p2_9" value="$p2_9"/><br>
Number of members who have proposed a new member in the past two years <input type="text" name="p2_10" value="$p2_10"/><br>
Check the aspect of your community's deversity that your membership reflects:
<input type="checkbox" name="p2_c1" $p2_c1/>Profession <input type="checkbox" name="p2_c2" $p2_c2/>Age
<input type="checkbox" name="p2_c3" $p2_c3/>Gender <input type="checkbox" name="p2_c4" $p2_c4/>Ethnicity<br>
Our club survey was updated on <input type="text" name="p2_15" value="$p2_15"/>
and contains <input type="text" name="p2_16" value="$p2_16"/>,
of which <input type="text" name="p2_17" value="$p2_17"/> are unfilled.<br>
<br>
Describe the club's current member orientation program:<br>
<textarea name="p2_18">$p2_18</textarea><br>
<br>
Describe the club's continuing education programs for both new and established members:<br>
<textarea name="p2_19">$p2_19</textarea><br>
<br>
Our clug has sponsored a new club within the last 24 months: <input type="radio" name="p2_r20" value="yes" $p2_r20yes/>Yes
<input type="radio" name="p2_r20" value="no" $p2_r20no/>No<br>
Number of Rotary Fellowship and Rotarian Action Groups that club members participated in
<input type="text" name="p2_21" value="$p2_21"/><br>
<br>
What makes this club attractive to new members?<br>
<textarea name="p2_22">$p2_22</textarea><br>
<br>
What asspect of this club could pose a barrier to attracting new member?<br>
<textarea name="p2_23">$p2_23</textarea><br>
<br>
Our club has identified the following sources of potential members within the community:<br>
<textarea name="p2_25">$p2_25</textarea><br>
<br>
How does the club plan to acheve its membership goals? (check all that apply)<br>
<input type="checkbox" name="p2_c26" $p2_c26/> Develop a retention plan that focuses on maintaining high levels of enthusiasm
through participation in interesting programs, project, continuing education, and fellowship activitis<br>
<input type="checkbox" name="p2_c27" $p2_c27/> Ensure the membership committee is aware of effective recrutment techniques<br>
<input type="checkbox" name="p2_c28" $p2_c28/> Develop a recrutment plan to have the club reflect
the diversity of the community<br>
<input type="checkbox" name="p2_c29" $p2_c29/> Explain the expectations of membership to potential Rotarians<br>
<input type="checkbox" name="p2_c30" $p2_c30/> Implement an orientation program for new members<br>
<input type="checkbox" name="p2_c31" $p2_c31/> Create a brochure that provides general information about Rotary as
well as specific information about the club for prospective members<br>
<input type="checkbox" name="p2_c32" $p2_c32/> Assign an experienced Rotarian mentor to every new club member<br>
<input type="checkbox" name="p2_c33" $p2_c33/> Recognize those Rotarians who sponsor new members<br>
<input type="checkbox" name="p2_c34" $p2_c34/> Encorage members to join a Rotary Fellowship or Rotarian Action Group<br>
<input type="checkbox" name="p2_c35" $p2_c35/> Participate in the RI membership development award programs<br>
<input type="checkbox" name="p2_c36" $p2_c36/> Sponsor a new club<br>
Other (please describe)<br>
<textarea name="p2_38">$p2_38</textarea><br>
<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page3"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page3($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p2_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page2='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  $query = "select page3 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page3) = $S->fetchrow();
    if($page3) {
      $ar = unserialize($page3);
      foreach($ar as $key=>$value) {
        if(preg_match("/p3_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p3_r(.*)/", $key, $m)) {
          $ar["p3_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  }
  echo <<<EOF
$S->top
<h3>Service Projects</h3>
<form action="$S->self" method="post">
Number of Rotary Youth Exchange students: Hosted <input type="text" name="p3_1" value="$p3_1"/>
Sponsored <input type="text" name="p3_2" value="$p3_2"/><br>
Number of sponsored Interact clubs: <input type="text" name="p3_3" value="$p3_3"/>
Rotaract clubs: <input type="text" name="p3_4" value="$p3_4"/>
Rotary Community Corps <input type="text" name="p3_5" value="$p3_5"/><br>
Number of Rotary Youth Leadership Awards (RYLA) events: <input type="text" name="p3_6" value="$p3_6"/><br>
Number of Rotary Friendship Exchanges: <input type="text" name="p3_7" value="$p3_7"/><br>
Number of registered Rotary Volunteers: <input type="text" name="p3_8" value="$p3_8"/><br>
Number of World Communit Service (WCS) projects: <input type="text" name="p3_9" value="$p3_9"/><br>
Number of other current club service projects: <input type="text" name="p3_10" value="$p3_10"/><br>
<br>
Our club has established the following goals for the upcoming Rotary year:<br>
<br>
For our community:<br>
<textarea name="p3_11">$p3_11</textarea><br>
<br>
For communities in other contries:<br>
<textarea name="p3_12">$p3_12</textarea><br>
<br>
How does the club plan to achieve its service goals? (check all that apply)<br>
<input type="checkbox" name="p3_c13" $p3_c13/> Ensure the service projects committee is aware of how to plan
and conduct a service project<br>
<input type="checkbox" name="p3_c14" $p3_c14/> Conduct a needs assessment of the communit to identify possible projects<br>
<input type="checkbox" name="p3_c15" $p3_c15/> Review current service projects to confirm that they meet a need
and are of interset to the menbers<br>
<input type="checkbox" name="p3_c16" $p3_c16/> Identify the social issures in the ommunity that the club wants to
address through its service goals<br>
<input type="checkbox" name="p3_c17" $p3_c17/> Assess the club's fundraising activities to determine if they meet project
funding needs<br>
<input type="checkbox" name="p3_c18" $p3_c18/> Involve all menbers in the club's service projects<br>
<input type="checkbox" name="p3_c19" $p3_c19/> Recoginze club members who participate and provide leadership in the club's
service projects<br>
Participate in:<br>

<ul>
<li><input type="checkbox" name="p3_c20" $p3_c20/> Interact</li>
<li><input type="checkbox" name="p3_c21" $p3_c21/> Rotary Friendship Exchange</li>
<li><input type="checkbox" name="p3_c22" $p3_c22/> World Community Service</li>
<li><input type="checkbox" name="p3_c23" $p3_c23/> Rotaract</li>
<li><input type="checkbox" name="p3_c24" $p3_c24/> Rotary Volunteers</li>
<li><input type="checkbox" name="p3_c25" $p3_c25/> Rotary Youth Exchange</li>
<li><input type="checkbox" name="p3_c26" $p3_c26/> Rotary Community Corps</li>
<li><input type="checkbox" name="p2_c27" $p2_c27/> RYLA</li>
</ul>

<input type="checkbox" name="p3_c28" $p3_c28/> Use a grant from the Rotary Foundation to suppoirt a club project<br>
<input type="checkbox" name="p3_c29" $p3_c29/> Register a project in need of funding, goods, or volunteers on the ProjectLINK database<br>
Other (please describe):<br>
<textarea name="p3_30">$p3_30</textarea><br>
<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page4"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page4($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p3_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page3='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  $query = "select page4 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page4) = $S->fetchrow();
    if($page4) {
      $ar = unserialize($page4);
      foreach($ar as $key=>$value) {
        if(preg_match("/p4_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p4_r(.*)/", $key, $m)) {
          $ar["p4_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  }

  echo <<<EOF
$S->top
<h3>The Rotary Foundation</h3>
<form action="$S->self" method="post">
Number of grands awarded: district Simplified Grants: <input type="text" name="p4_1" value="$p4_1"/>
Matching Grants: <input type="text" name="p4_2" value="$p4_2"/><br>
Number of Ambassadorial Scholars: Nominated <input type="text" name="p4_3" value="$p4_3"/>
Selected <input type="text" name="p4_4" value="$p4_4"/>
Hosted <input type="text" name="p4_5" value="$p4_5"/><br>
Number of Groups Study Echange (GSE) team members: Nominated <input type="text" name="p4_6" value="$p4_6"/>
Selected <input type="text" name="p4_7" value="$p4_7"/>
Hosted <input type="text" name="p4_8" value="$p4_8"/><br>
Number of Rotary World Peace Fellows: Nominated <input type="text" name="p4_9" value="$p4_9"/>
Selected <input type="text" name="p4_10" value="$p4_10"/>
Hosted <input type="text" name="p4_11" value="$p4_11"/><br>
Current year's contributions to PolioPlus activities: <input type="text" name="p4_12" value="$p4_12"/><br>
Current year's contributions to Annual Programs Fund: <input type="text" name="p4_13" value="$p4_13"/><br>
Current year's contributions to Permanent Fund: <input type="text" name="p4_14" value="$p4_14"/><br>
Number of club members who are:<br>
<ul>
<li>Paul Harris Fellows: <input type="text" name="p4_15" value="$p4_15"/></li>
<li>Benefactors: <input type="text" name="p4_16" value="$p4_16"/></li>
<li>Major Donnors: <input type="text" name="p4_17" value="$p4_17"/></li>
<li>Rotary foundation Sustaining Members: <input type="text" name="p4_18" value="$p4_18"/></li>
<li>Bequest Society members: <input type="text" name="p4_19" value="$p4_19"/></li>
</ul>
Number of Foundation alumni tracked by your club: <input type="text" name="p4_20" value="$p4_20"/><br>
Our club has established the following Rotary Foundation goals (as reported on the Fund Development Club Goal Report Form)
for the upcoming Rotary year:<br>
<ul>
<li>Polio fundraising: <input type="text" name="p4_21" value="$p4_21"/></li>
<li>Annual Programs Fund Continuations: <input type="text" name="p4_22" value="$p4_22"/></li>
<li>Major gifts: <input type="text" name="p4_23" value="$p4_23"/></li>
<li>Benefactors: <input type="text" name="p4_24" value="$p4_24"/></li>
<li>Bequest Society members: <input type="text" name="p4_25" value="$p4_25"/></li>
</ul>
Our club will participate in the following Rotary Foundation programs:<br>
<textarea name="p4_26">$p4_26</textarea><br>
<br>
How does the club plan to achieve its Rotary Foundation goals? (check all the apply)<br>
<input type="checkbox" name="p4_c27" $p4_c27 /> Ensure the club's Rotary Foundation committee understands
the programs of the Rotary Foundation and is committed to promoting financial support of the Foundation<br>
<input type="checkbox" name="p4_c28" $p4_c28 /> Help club members understand the relationship between Foundation
giving and Foundation programs<br>
<input type="checkbox" name="p4_c29" $p4_c29 /> Plan a club program about the Rotary Foundation every quarter,
especially in November, Rotary Foundation Month<br>
<input type="checkbox" name="p4_c30" $p4_c30 /> Include a brief story about the Rotary Foundation in every club program<br>
<input type="checkbox" name="p4_c31" $p4_c32 /> Schedule presentations that inform club members about the Rotary Foundation<br>
<input type="checkbox" name="p4_c32" $p4_c32 /> Ensure the club's Rotary Foundation committee chair attends the district
Rotary Foundation seminar<br>
<input type="checkbox" name="p4_c33" $p4_c33 /> Use Rotary Foundation grants to support the club's international projects<br>
<input type="checkbox" name="p4_c34" $p4_c34 /> Recognize club member's financial contributions to the Rotary Foundation
and their participation in Foundation programs<br>
<input type="checkbox" name="p4_c35" $p4_c35 /> Encourage each club member to contribute to the Foundation every year<br>
Participate in:<br>
<ul>
<li><input type="checkbox" name="p4_c36" $p4_c36/> GSE</li>
<li><input type="checkbox" name="p4_c37" $p4_c37/> PolipPlus</li>
<li><input type="checkbox" name="p4_c38" $p4_c38/> Matching Grants</li>
<li><input type="checkbox" name="p4_c39" $p4_c39/> Ambassadorial Scholarships</li>
<li><input type="checkbox" name="p4_c40" $p4_c40/> District Simplfied Grants</li>
<li><input type="checkbox" name="p4_c41" $p4_c41/> Rotary World Peace Fellowships</li>
</ul>
<input type="checkbox" name="p4_c42" $p4_c42 /> Invite Foundation program participants and alumni to be are of club
programs and activities<br>
Other (please describe)<br>
<textarea name="p4_43">$p4_43</textarea><br>
<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page5"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page5($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p4_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page4='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  $query = "select page5 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page5) = $S->fetchrow();
    if($page5) {
      $ar = unserialize($page5);
      foreach($ar as $key=>$value) {
        if(preg_match("/p5_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p5_r(.*)/", $key, $m)) {
          $ar["p5_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  }

  echo <<<EOF
$S->top
<h3>Leadership Development</h3>
<form action="$S->self" method="post">
Number of club leaders who attend<br>
<ul>
<li>District assembly: <input type="text" name="p5_1" value="$p5_1"/></li>
<li>District Rotary Foundation seminar: <input type="text" name="p5_2" value="$p5_2"/></li>
<li>District membership seminar: <input type="text" name="p5_3" value="$p5_3"/></li>
<li>District leadership seminar: <input type="text" name="p5_4" value="$p5_4"/></li>
<li>District conference: <input type="text" name="p5_5" value="$p5_5"/></li>
</ul>
Number of club members involverd at the district level: <input type="text" name="p5_6" value="$p5_6"/><br>
Number of visits from the assistand governor this Rotary year: <input type="text" name="p5_7" value="$p5_7"/><br>
<br>
Our club has established the following goals for developing Rotary leaders for the upcoming Rotary year:<br>
<textarea name="p5_8">$p5_8</textarea><br>
<br>
How does the club plan to develop Rotary leaders? (check all that apply)<br>
<input type="checkbox" name="p5_c9" $p5_c9 /> Have the president-elect attend PETS and the district assembly<br>
<input type="checkbox" name="p5_c10" $p5_c10 /> Have all committee chairs attend the district assembly<br>
<input type="checkbox" name="p5_c11" $p5_c11 /> Encourage interested past presidents to attend the district leadership seminare<br>
<input type="checkbox" name="p5_c12" $p5_c12 /> Appoint a club trainer to develop club member's knowledge and skills<br>
<input type="checkbox" name="p5_c13" $p5_c13 /> Conduct a leadership development program<br>
<input type="checkbox" name="p5_c14" $p5_c14 /> Use the expertise of the club's assistant governor<br>
<input type="checkbox" name="p5_c15" $p5_c15 /> Engourage new menbers to assume leadership positions through participation in club committees<br>
<input type="checkbox" name="p5_c16" $p5_c16 /> Ask members to visit other clubs to exchange ideas and then share what they have learned withthe club<br>
Other (please describe)<br>
<textarea name="p5_17">$p5_17</textarea><br>
<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page6"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page6($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p5_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page5='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  $query = "select page6 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page6) = $S->fetchrow();
    if($page6) {
      $ar = unserialize($page6);
      foreach($ar as $key=>$value) {
        if(preg_match("/p6_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p6_r(.*)/", $key, $m)) {
          $ar["p6_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  }
  echo <<<EOF
$S->top
<h3>Public Relations</h3>
<form action="$S->self" method="post">
List the activities covered by the media and the type of media (TV, radio, print, Internet, etc) involved:<br>
<textarea name="p6_1">$p6_1</textarea><br>
<br>
Our club has established the following public relations goals for the upcoming Rotary year:<br>
<textarea name="p6_2">$p6_2</textarea><br>
<br>
How does the club plan to achieve its public relation goals? (check all the apply))<br>
<input type="checkbox" name="p6_c3" $p6_c3/> Ensure the public relations committee is trained in conducting a multimedia campaign<br>
<input type="checkbox" name="p6_c4" $p6_c4/> Plan public relations efforts around all service projects<br>
<input type="checkbox" name="p6_c5" $p6_c5/> Conduct a public awareness program targeted at the business and professional community
that explains what Rotary is an what Rotary does<br>
<input type="checkbox" name="p6_c6" $p6_c6/> Arrange for a public service announcement to be broadcast on a local TV channel,
aired on a local radio station or placed in a lical newspaper or magazine<br>
Other (please describe)<br>
<textarea name="p6_7">$p6_7</textarea><br>
<br>
Is your club operating under a Club Leadership Plan? <input type="radio" name="p6_r8" value="yes" $p6_r8yes/>Yes
<input type="radio" name="p6_r8" value="no" $p6_r8no/>No<br>
How often and when does the club board meet? <input type="text" name="p6_9" value="$p6_9"/><br>
When are club assemblies help? <input type="text" name="p6_10" value="$p6_10"/><br>
How is the club budget prepared? <input type="text" name="p6_11" value="$p6_11"/><br>
Is the budget independently reviewed by a qualified accountant? <input type="radio" name="p6_r12" value="yes" $p6_r12yes/>Yes
<input type="radio" name="p6_r12" value="no" $p6_r12no/>No<br>
Does the club have a strategic plan in place? <input type="radio" name="p6_r13" value="yes" $p6_r13yes/>Yes
<input type="radio" name="p6_r13" value="no" $p6_r13no/>No<br>
Has the club developed a system for ensuring conttinuity of leadership on its board, committees, etc.?
<input type="radio" name="p6_r14" value="yes" $p6_r14yes/>Yes
<input type="radio" name="p6_r14" value="no" $p6_r14no/>No<br>
Has the club developed a system for keeping all members involved?
<input type="radio" name="p6_r15" value="yes" $p6_r15yes/>Yes
<input type="radio" name="p6_r15" value="no" $p6_r15no/>No<br>
Does the club use Member Access at www.rotary.org to update its membership list?
<input type="radio" name="p6_r16" value="yes" $p6_r16yes/>Yes
<input type="radio" name="p6_r16" value="no" $p6_r16no/>No<br>
How often is the club's bulletin published? <input type="text" name="p6_17" value="$p6_17"/><br>
<br>
Describe how weekly club programs are organized:<br>
<textarea name="p6_18">$p6_18</textarea><br>
<br>
Does the club have its own Web site?
<input type="radio" name="p6_r19" value="yes" $p6_r19yes/>Yes
<input type="radio" name="p6_r19" value="no" $p6_r19no/>No.
If yes, how often is the site updated?  <input type="text" name="p6_20" value="$p6_20"/><br>
Does the club observe the special months of the Rotary Calendar such as Rotary Foundation Month and Magazine Month: 
<input type="radio" name="p6_r21" value="yes" $p6_r21yes/>Yes
<input type="radio" name="p6_r21" value="no" $p6_r21no/>No<br>
How often does your club conduct fellowship activities?  <input type="text" name="p6_22" value="$p6_22"/><br>
<br>How does the club involve the familites of Rotarians?<br>
<textarea name="p6_23">$p6_23</textarea><br>
<br>
How does the club carry out the administrative tasks of the club? (check all that apply)<br>
<input type="checkbox" name="p6_c24" $p6_c24/> Regular board meetings have been scheduled<br>
<input type="checkbox" name="p6_c25" $p6_c25/> The club will review the Club Leadership Plan on the following dates:
<input type=text name="p6_26" value="$p6_26"/><br>
<input type="checkbox" name="p6_c27" $p6_c27/> The club's strategic and communication plans will be
updated on the following dates: <input type=text name="p6_28" value="$p6_28"/><br>
<input type="checkbox" name="p6_c29" $p6_c29 /> <input type=text name="p6_30" value="$p6_30"/>
club assemblies have been scheduled on the following dates: <input type=text name="p6_31" value="$p6_31"/><br>
<input type="checkbox" name="p6_c32" $p6_c32/> The club has either adopted the latest version of the Recommended Rotary Club By Laws
or revised its own bylaws (recommended after each Council on Legislation)<br>
<input type="checkbox" name="p6_c33" $p6_c33/> Club elections will be help on
<input type=text name="p6_34" value="$p6_34"/><br>
<input type="checkbox" name="p6_c35" $p6_c35/> at least
<input type=text name="p6_36" value="$p6_36"/> delegates will be sent to the district confrencee<br>
<input type="checkbox" name="p6_c37" $p6_c37/> A club bulletin will be produced to provide information to club members<br>
<input type="checkbox" name="p6_c38" $p6_c38 /> The club's Web site will be updated
<input type=text name="p6_39" value="$p6_39"/> times per year<br>
<input type="checkbox" name="p6_c40" $p6_40/> A plan has been developed to ensure interesting and relevant weekly club programs<br>
<input type="checkbox" name="p6_c41" $p6_c41/> Member Access will be used to maintain club records by 1 June and 1 December
to ensure accurate semiannual reports<br>
<input type="checkbox" name="p6_c42" $p6_c42/> Membership changes will be reported to RI within
<input type=text name="p6_43" value="$p6_43"/> days<br>
<input type="checkbox" name="p6_c44" $p6_c44/> Reports to RI, including the semiannual report, will be competed on a timely basis<br>
<br>
The following fellowship activities for all club members are planned for the year:<br>
<textarea name="p6_45">$p6_45</textarea><br>
<br>
Other (please describe)<br>
<textarea name="p6_46">$p6_45</textarea><br>
<br>
Our club would like assistance from the governor or assistant governor with the following:<br>
<textarea name="p6_47">$p6_47</textarea><br>
<br>
Our club would like to discuss the following issures with the governor or assistant governor during a visit to our club:<br>
<textarea name="p6_48">$p6_48</textarea><br>
<br>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page7"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page7($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p6_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page6='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  $query = "select page7 from planning_guide where clubname='$clubname' and officeyear='$officeyear' and presidentname='$presidentname'";

  $n = $S->query($query);

  if($n) {
    list($page7) = $S->fetchrow();
    if($page7) {
      $ar = unserialize($page7);
      foreach($ar as $key=>$value) {
        if(preg_match("/p7_c/", $key)) {
          if($value == "on") $ar[$key] = "checked";
        }
        if(preg_match("/p7_r(.*)/", $key, $m)) {
          $ar["p7_r$m[1]$value"] = "checked";
        }
      }
      extract($ar);
    }
  }
  echo <<<EOF
$S->top
<form action="$S->self" method="post">
Summary of goals for our Rotary Club:<br>
Membership Goal: <input type=text name="p7_1"/> members by end of term<br>
Service goals:<br>
<ul>
<li>For our community:<br>
<textarea name="p7_2">$p7_2</textarea></li>
<li>For communities in other countries:<br>
<textarea name="p7_3">$p7_3</textarea></li>
</ul>
Rotary Foundation goals:<br>
<ul>
<li>Our club's PolioPlus contribution goal is <input type=text name="p7_4" value="$p7_4"/></li>
<li>Our club's Annual Program Fund contribution goal is <input type=text name="p7_5" value="$p7_5"/></li>
<li>Our club's Permanent Fundcontribution goal is <input type=text name="p7_6" value="$p7_6"/></li>
<li>Our club will participate in the following Rotary Foundation programs:<br>
<textarea name="p7_7">$p7_7</textarea></li>
</ul>
Leadership development goals:<br>
<textarea name="p7_8">$p7_8</textarea><br>
<br>
Public relations goals:<br>
<textarea name="p7_9">$p7_9</textarea><br>
<br>
Club adminstration goals:<br>
<textarea name="p7_10">$p7_10</textarea><br>
<br>
Other goals:<br>
<textarea name="p7_11">$p7_11</textarea><br>
<hr>
<input type="submit" value="Continue"/>
<input type="hidden" name="page" value="page8"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>
$S->footer
EOF;
}

// ********************************************************************************

function page8($S) {
  $ar = array();
  foreach($_POST as $key=>$value) {
    if(preg_match("/p7_/", $key)) {
      $ar[$key]=$value;
    }
  }

  $clubname = $_POST['clubname'];
  $officeyear = $_POST['officeyear'];
  $presidentname = $_POST['presidentname'];

  $s = serialize($ar);
  $s = $S->escape($s);
  $query = "update planning_guide set page7='$s' " .
           "where clubname='{$_POST['clubname']}' and officeyear='{$_POST['officeyear']}' ".
           "and presidentname='{$_POST['presidentname']}'";

  $S->query($query);

  echo <<<EOF
$S->top
<h2>Finished</h2>
<form action="$S->self" method="post">
<p>Display Report for printing? <input type="radio" name="print_it" value="yes"/>Yes
<input type="radio" name="print_it" value="no"/>No<br>
<input type="submit" value="submit"/>
<input type="hidden" name="page" value="printreport"/>
<input type="hidden" name="clubname" value="$clubname"/>
<input type="hidden" name="officeyear" value="$officeyear"/>
<input type="hidden" name="presidentname" value="$presidentname"/>
</form>

$S->footer
EOF;
}

// ********************************************************************************

function printReport($S) {
  if($_POST['print_it'] == 'no') {
    echo <<<EOF
$S->top
<h2>All Data Captured. Thank You</h2>
$S->footer
EOF;
    exit();
  }

  $h->title = "Planning Guide";
  $h->extra = <<<EOF
<script type="text/javascript">
jQuery(document).ready(function($) {
  //$("input[type='radio']").attr('disabled', 'disabled');
  $("input").attr('disabled', 'disabled');
  //$("input[type=radio][checked]").wrap('<span style="border: 1px solid black"/>');
  //$("input").css({'background-color':'white', 'color':'black'});
  $("div").css({'background-color' : 'white', 'padding': '4px', 'border': '1px solid black'});

  var once;
  $("input[type='checkbox']").click(function() {
    if(once == true) {
      once = false;
      return;
    }
    once = true;
    $(this).click();
  });
});
</script>
EOF;

  $S->top = $S->getPageHead($h);
  $S->footer = "</body>\n</html>\n";

  // Get the data for this club etc.

  extract($_POST);

  $query = "select clubname, officeyear, presidentname, address, phone, email, page2, page3, page4, page5, page6, page7 from planning_guide ".
           "where clubname='$clubname' and officeyear='$officeyear' ".
           "and presidentname='$presidentname'";

  //echo "Query: $query<br>";

  $n = $S->query($query);

  if(!$n) {
    echo "Error No Data Found";
    exit();
  }

  list($clubname, $officeyear, $presidentname, $address, $phone, $email, $page2, $page3, $page4, $page5, $page6, $page7) =
    $S->fetchrow();

  if($page2) {
    $ar = unserialize($page2);
    foreach($ar as $key=>$value) {
      if(preg_match("/p2_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p2_r(.*)/", $key, $m)) {
        $ar["p2_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }
  if($page3) {
    $ar = unserialize($page3);
    foreach($ar as $key=>$value) {
      if(preg_match("/p3_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p3_r(.*)/", $key, $m)) {
        $ar["p3_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }
  if($page4) {
    $ar = unserialize($page4);
    foreach($ar as $key=>$value) {
      if(preg_match("/p4_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p4_r(.*)/", $key, $m)) {
        $ar["p4_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }
  if($page5) {
    $ar = unserialize($page5);
    foreach($ar as $key=>$value) {
      if(preg_match("/p5_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p5_r(.*)/", $key, $m)) {
        $ar["p5_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }
  if($page6) {
    $ar = unserialize($page6);
    foreach($ar as $key=>$value) {
      if(preg_match("/p6_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p6_r(.*)/", $key, $m)) {
        $ar["p6_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }
  if($page7) {
    $ar = unserialize($page7);
    foreach($ar as $key=>$value) {
      if(preg_match("/p7_c/", $key)) {
        if($value == "on") $ar[$key] = "checked";
      }
      if(preg_match("/p7_r(.*)/", $key, $m)) {
        $ar["p7_r$m[1]$value"] = "checked";
      }
    }
    extract($ar);
  }

  $reportHeader = <<<EOF
<h2>Planning Guide for Effective Rotary Clubs</h2>

<p>This <i>Planning Guide for Effective Rotary Clubs</i> is a tool to help clubs assess their current state and establish goals
for the coming year. It is based on the Club Leadership Plan. The strategies listed in each section are common ways clubs might
choose to pursue goals.
Clubs are encouraged to develop alternative strategies to achieve their goals when appropriate.  Presidents-elect should complete
this form in cooperation with their club and submit a copy of it to their assistant govenrnor by 1 July.</p>

Rotary Club of $clubname<br>
Rotary Year of office (the year your term started): $officeyear<br>
Name of President: $presidentname<br>
Mailing Address: $address<br>
Phone: $phone<br>
Email: $email<br>
<hr>
EOF;

  echo <<<EOF
$S->top
<form action="$S->self" method="post">
$reportHeader
<h3>Membership</h3>
Current number of members: $p2_1<br>
Number of members as of 30 June last year: $p2_2<br>
Number of members as of 30 June five years ago: $p2_3<br>
Number of male members: $p2_4<br>
Number of female members: $p2_5<br>
Average age of members: $p2_6<br>
Number of Rotary alumni members: $p2_7<br>
Number of members that have been members for 1-3 years $p2_8
3-5 years $p2_8a
more than 5 years $p2_9<br>
Number of members who have proposed a new member in the past two years $p2_10<br>
Check the aspect of your community's deversity that your membership reflects:
<input type="checkbox" name="p2_c1" $p2_c1/>Profession <input type="checkbox" name="p2_c2" $p2_c2/>Age
<input type="checkbox" name="p2_c3" $p2_c3/>Gender <input type="checkbox" name="p2_c4" $p2_c4/>Ethnicity<br>
Our club survey was updated on $p2_15
and contains $p2_16,
of which $p2_17 are unfilled.<br>
Describe the club's current member orientation program:<br>
<div>$p2_18</div><br>
Describe the club's continuing education programs for both new and established members:<br>
<div>$p2_19</div><br>
<br>
Our club has sponsored a new club within the last 24 months: <input type="radio" name="p2_r20" value="yes" $p2_r20yes/>Yes
<input type="radio" name="p2_r20" value="no" $p2_r20no/>No<br>
Number of Rotary Fellowship and Rotarian Action Groups that club members participated in
$p2_21<br>
<br>What makes this club attractive to new members?<br>
<div>$p2_22</div><br>
<br>
What asspect of this club could pose a barrier to attracting new member?<br>
<div>$p2_23</div><br>
<br>
Membership goal for the upcoming Rotay year:<br>
Our club has identified the following sources of potential members within the community:<br>
<div>$p2_25</div><br>
<br>
How does the club plan to acheve its membership goals? (check all that apply)<br>
<input type="checkbox" name="p2_c26" $p2_c26/> Develop a retention plan that focuses on maintaining high levels of enthusiasm
through participation in interesting programs, project, continuing education, and fellowship activitis<br>
<input type="checkbox" name="p2_c27" $p2_c27/> Ensure the membership committee is aware of effective recrutment techniques<br>
<input type="checkbox" name="p2_c28" $p2_c28/> Develop a recrutment plan to have the club reflect
the diversity of the community<br>
<input type="checkbox" name="p2_c29" $p2_c29/> Explain the expectations of membership to potential Rotarians<br>
<input type="checkbox" name="p2_c30" $p2_c30/> Implement an orientation program for new members<br>
<input type="checkbox" name="p2_c31" $p2_c31/> Create a brochure that provides general information about Rotary as
well as specific information about the club for prospective members<br>
<input type="checkbox" name="p2_c32" $p2_c32/> Assign an experienced Rotarian mentor to every new club member<br>
<input type="checkbox" name="p2_c33" $p2_c33/> Recognize those Rotarians who sponsor new members<br>
<input type="checkbox" name="p2_c34" $p2_c34/> Encorage members to join a Rotary Fellowship or Rotarian Action Group<br>
<input type="checkbox" name="p2_c35" $p2_c35/> Participate in the RI membership development award programs<br>
<input type="checkbox" name="p2_c36" $p2_c36/> Sponsor a new club<br>
Other (please describe)<br>
<div>$p2_38</div><br>

<hr>

<h3>Service Projects</h3>
Number of Rotary Youth Exchange students: Hosted $p3_1
Sponsored $p3_2<br>
Number of sponsored Interact clubs: $p3_3
Rotaract clubs: $p3_4
Rotary Community Corps $p3_5<br>
Number of Rotary Youth Leadership Awards (RYLA) events: $p3_6<br>
Number of Rotary Friendship Exchanges: $p3_7<br>
Number of registered Rotary Volunteers: $p3_8<br>
Number of World Communit Service (WCS) projects: $p3_9<br>
Number of other current club service projects: $p3_10<br>
<br>
Our club has established the following goals for the upcoming Rotary year:<br>
For our community:<br>
<div>$p3_11</div><br>
For communities in other contries:<br>
<div>$p3_12</div><br>
How does the club plan to achieve its service goals? (check all that apply)<br>
<input type="checkbox" name="p3_c13" $p3_c13/> Ensure the service projects committee is aware of how to plan
and conduct a service project<br>
<input type="checkbox" name="p3_c14" $p3_c14/> Conduct a needs assessment of the communit to identify possible projects<br>
<input type="checkbox" name="p3_c15" $p3_c15/> Review current service projects to confirm that they meet a need
and are of interset to the menbers<br>
<input type="checkbox" name="p3_c16" $p3_c16/> Identify the social issures in the ommunity that the club wants to
address through its service goals<br>
<input type="checkbox" name="p3_c17" $p3_c17/> Assess the club's fundraising activities to determine if they meet project
funding needs<br>
<input type="checkbox" name="p3_c18" $p3_c18/> Involve all menbers in the club's service projects<br>
<input type="checkbox" name="p3_c19" $p3_c19/> Recoginze club members who participate and provide leadership in the club's
service projects<br>
Participate in:<br>

<ul>
<li><input type="checkbox" name="p3_c20" $p3_c20/> Interact</li>
<li><input type="checkbox" name="p3_c21" $p3_c21/> Rotary Friendship Exchange</li>
<li><input type="checkbox" name="p3_c22" $p3_c22/> World Community Service</li>
<li><input type="checkbox" name="p3_c23" $p3_c23/> Rotaract</li>
<li><input type="checkbox" name="p3_c24" $p3_c24/> Rotary Volunteers</li>
<li><input type="checkbox" name="p3_c25" $p3_c25/> Rotary Youth Exchange</li>
<li><input type="checkbox" name="p3_c26" $p3_c26/> Rotary Community Corps</li>
<li><input type="checkbox" name="p2_c27" $p2_c27/> RYLA</li>
</ul>

<input type="checkbox" name="p3_c28" $p3_c28/> Use a grant from the Rotary Foundation to suppoirt a club project<br>
<input type="checkbox" name="p3_c29" $p3_c29/> Register a project in need of funding, goods, or volunteers on the ProjectLINK database<br>
Other (please describe):<br>
<div>$p3_30</div><br>

<hr>
<h3>The Rotary Foundation</h3>
Number of grands awarded: district Simplified Grants: $p4_1
Matching Grants: $p4_2<br>
Number of Ambassadorial Scholars: Nominated $p4_3
Selected $p4_4
Hosted $p4_5<br>
Number of Groups Study Echange (GSE) team members: Nominated $p4_6
Selected $p4_7
Hosted $p4_8<br>
Number of Rotary World Peace Fellows: Nominated $p4_9
Selected $p4_10
Hosted $p4_11<br>
Current year's contributions to PolioPlus activities: $p4_12<br>
Current year's contributions to Annual Programs Fund: $p4_13<br>
Current year's contributions to Permanent Fund: $p4_14<br>
Number of club members who are:<br>
<ul>
<li>Paul Harris Fellows: $p4_15</li>
<li>Benefactors: $p4_16</li>
<li>Major Donnors: $p4_17</li>
<li>Rotary foundation Sustaining Members: $p4_18</li>
<li>Bequest Society members: $p4_19</li>
</ul>
Number of Foundation alumni tracked by your club: $p4_20<br>
Our club has established the following Rotary Foundation goals (as reported on the Fund Development Club Goal Report Form)
for the upcoming Rotary year:<br>
<ul>
<li>Polio fundraising: $p4_21</li>
<li>Annual Programs Fund Continuations: $p4_22</li>
<li>Major gifts: $p4_23</li>
<li>Benefactors: $p4_24</li>
<li>Bequest Society members: $p4_25</li>
</ul>
Our club will participate in the following Rotary Foundation programs:<br>
<div>$p4_26</div><br>
How does the club plan to achieve its Rotary Foundation goals? (check all the apply)<br>
<input type="checkbox" name="p4_c27" $p4_c27 /> Ensure the club's Rotary Foundation committee understands
the programs of the Rotary Foundation and is committed to promoting financial support of the Foundation<br>
<input type="checkbox" name="p4_c28" $p4_c28 /> Help club members understand the relationship between Foundation
giving and Foundation programs<br>
<input type="checkbox" name="p4_c29" $p4_c29 /> Plan a club program about the Rotary Foundation every quarter,
especially in November, Rotary Foundation Month<br>
<input type="checkbox" name="p4_c30" $p4_c30 /> Include a brief story about the Rotary Foundation in every club program<br>
<input type="checkbox" name="p4_c31" $p4_c32 /> Schedule presentations that inform club members about the Rotary Foundation<br>
<input type="checkbox" name="p4_c32" $p4_c32 /> Ensure the club's Rotary Foundation committee chair attends the district
Rotary Foundation seminar<br>
<input type="checkbox" name="p4_c33" $p4_c33 /> Use Rotary Foundation grants to support the club's international projects<br>
<input type="checkbox" name="p4_c34" $p4_c34 /> Recognize club member's financial contributions to the Rotary Foundation
and their participation in Foundation programs<br>
<input type="checkbox" name="p4_c35" $p4_c35 /> Encourage each club member to contribute to the Foundation every year<br>
Participate in:<br>
<ul>
<li><input type="checkbox" name="p4_c36" $p4_c36/> GSE</li>
<li><input type="checkbox" name="p4_c37" $p4_c37/> PolipPlus</li>
<li><input type="checkbox" name="p4_c38" $p4_c38/> Matching Grants</li>
<li><input type="checkbox" name="p4_c39" $p4_c39/> Ambassadorial Scholarships</li>
<li><input type="checkbox" name="p4_c40" $p4_c40/> District Simplfied Grants</li>
<li><input type="checkbox" name="p4_c41" $p4_c41/> Rotary World Peace Fellowships</li>
</ul>
<input type="checkbox" name="p4_c42" $p4_c42 /> Invite Foundation program participants and alumni to be at our club
programs and activities<br>
Other (please describe)<br>
<div>$p4_43</div><br>

<hr>
<h3>Leadership Development</h3>
Number of club leaders who attend<br>
<ul>
<li>District assembly: $p5_1</li>
<li>District Rotary Foundation seminar: $p5_2</li>
<li>District membership seminar: $p5_3</li>
<li>District leadership seminar: $p5_4</li>
<li>District conference: $p5_5</li>
</ul>
Number of club members involverd at the district level: $p5_6<br>
Number of visits from the assistand governor this Rotary year: $p5_7<br>
Our club has established the following goals for developing Rotary leaders for the upcoming Rotary year:<br>
<div>$p5_8</div><br>
How does the club plan to develop Rotary leaders? (check all that apply)<br>
<input type="checkbox" name="p5_c9" $p5_c9 /> Have the president-elect attend PETS and the district assembly<br>
<input type="checkbox" name="p5_c10" $p5_c10 /> Have all committee chairs attend the district assembly<br>
<input type="checkbox" name="p5_c11" $p5_c11 /> Encourage interested past presidents to attend the district leadership seminare<br>
<input type="checkbox" name="p5_c12" $p5_c12 /> Appoint a club trainer to develop club member's knowledge and skills<br>
<input type="checkbox" name="p5_c13" $p5_c13 /> Conduct a leadership development program<br>
<input type="checkbox" name="p5_c14" $p5_c14 /> Use the expertise of the club's assistant governor<br>
<input type="checkbox" name="p5_c15" $p5_c15 /> Engourage new menbers to assume leadership positions through participation in club committees<br>
<input type="checkbox" name="p5_c16" $p5_c16 /> Ask members to visit other clubs to exchange ideas and then share what they have learned withthe club<br>
Other (please describe)<br>
<div>$p5_17</div><br>

<hr>
<h3>Public Relations</h3>
List the activities covered by the media and the type of media (TV, radio, print, Internet, etc) involved:<br>
<div>$p6_1</div><br>
Our club has established the following public relations goals for the upcoming Rotary year:<br>
<div>$p6_2</div><br>
How does the club plan to achieve its public relation goals? (check all the apply))<br>
<input type="checkbox" name="p6_c3" $p6_c3/> Ensure the public relations committee is trained in conducting a multimedia campaign<br>
<input type="checkbox" name="p6_c4" $p6_c4/> Plan public relations efforts around all service projects<br>
<input type="checkbox" name="p6_c5" $p6_c5/> Conduct a public awareness program targeted at the business and professional community
that explains what Rotary is an what Rotary does<br>
<input type="checkbox" name="p6_c6" $p6_c6/> Arrange for a public service announcement to be broadcast on a local TV channel,
aired on a local radio station or placed in a lical newspaper or magazine<br>
Other (please describe)<br>
<div>$p6_7</div><br>
Is your club operating under a Club Leadership Plan? <input type="radio" name="p6_r8" value="yes" $p6_r8yes/>Yes
<input type="radio" name="p6_r8" value="no" $p6_r8no/>No<br>
How often and when does the club board meet? $p6_9<br>
When are club assemblies help? $p6_10<br>
How is the club budget prepared? $p6_11<br>
Is the budget independently reviewed by a qualified accountant? <input type="radio" name="p6_r12" value="yes" $p6_r12yes/>Yes
<input type="radio" name="p6_r12" value="no" $p6_r12no/>No<br>
Does the club have a strategic plan in place? <input type="radio" name="p6_r13" value="yes" $p6_r13yes/>Yes
<input type="radio" name="p6_r13" value="no" $p6_r13no/>No<br>
Has the club developed a system for ensuring conttinuity of leadership on its board, committees, etc.?
<input type="radio" name="p6_r14" value="yes" $p6_r14yes/>Yes
<input type="radio" name="p6_r14" value="no" $p6_r14no/>No<br>
Has the club developed a system for keeping all members involved?
<input type="radio" name="p6_r15" value="yes" $p6_r15yes/>Yes
<input type="radio" name="p6_r15" value="no" $p6_r15no/>No<br>
Does the club use Member Access at www.rotary.org to update its membership list?
<input type="radio" name="p6_r16" value="yes" $p6_r16yes/>Yes
<input type="radio" name="p6_r16" value="no" $p6_r16no/>No<br>
How often is the club's bulletin published? $p6_17<br>
Describe how weekly club programs are organized:<br>
<div>$p6_18</div><br>
Does the club have its own Web site?
<input type="radio" name="p6_r19" value="yes" $p6_r19yes/>Yes
<input type="radio" name="p6_r19" value="no" $p6_r19no/>No.
If yes, how often is the site updated?  $p6_20<br>
Does the club observe the special months of the Rotary Calendar such as Rotary Foundation Month and Magazine Month: 
<input type="radio" name="p6_r21" value="yes" $p6_r21yes/>Yes
<input type="radio" name="p6_r21" value="no" $p6_r21no/>No<br>
How often does your club conduct fellowship activities?  $p6_22<br>
How does the club involve the familites of Rotarians?<br>
<div>$p6_23</div><br>
How does the club carry out the administrative tasks of the club? (check all that apply)<br>
<input type="checkbox" name="p6_c24" $p6_c24/> Regular board meetings have been scheduled<br>
<input type="checkbox" name="p6_c25" $p6_c25/> The club will review the Club Leadership Plan on the following dates:
$p6_26<br>
<input type="checkbox" name="p6_c27" $p6_c27/> The club's strategic and communication plans will be
updated on the following dates: $p6_28<br>
<input type="checkbox" name="p6_c29" $p6_c29 /> $p6_30
club assemblies have been scheduled on the following dates: $p6_31<br>
<input type="checkbox" name="p6_c32" $p6_c32/> The club has either adopted the latest version of the Recommended Rotary Club By Laws
or revised its own bylaws (recommended after each Council on Legislation)<br>
<input type="checkbox" name="p6_c33" $p6_c33/> Club elections will be help on
$p6_34<br>
<input type="checkbox" name="p6_c35" $p6_c35/> at least
$p6_36 delegates will be sent to the district confrencee<br>
<input type="checkbox" name="p6_c37" $p6_c37/> A club bulletin will be produced to provide information to club members<br>
<input type="checkbox" name="p6_c38" $p6_c38 /> The club's Web site will be updated
$p6_39 times per year<br>
<input type="checkbox" name="p6_c40" $p6_40/> A plan has been developed to ensure interesting and relevant weekly club programs<br>
<input type="checkbox" name="p6_c41" $p6_c41/> Member Access will be used to maintain club records by 1 June and 1 December
to ensure accurate semiannual reports<br>
<input type="checkbox" name="p6_c42" $p6_c42/> Membership changes will be reported to RI within
$p6_43 days<br>
<input type="checkbox" name="p6_c44" $p6_c44/> Reports to RI, including the semiannual report, will be competed on a timely basis<br>
<br>
The following fellowship activities for all club members are planned for the year:<br>
<div>$p6_45</div><br>
<br>
Other (please describe)<br>
<div>$p6_45</div><br>
<br>
Our club would like assistance from the governor or assistant governor with the following:<br>
<div>$p6_47</div><br>
<br>
Our club would like to discuss the following issures with the governor or assistant governor during a visit to our club:<br>
<div>$p6_48</div><br>

<hr>
<h3>Summary of goals for our Rotary Club</h3>
Membership Goal: $p7_1 members by end of term<br>
Service goals:<br>
<ul>
<li>For our community:<br>
<div>$p7_2</div></li>
<li>For communities in other countries:<br>
<div>$p7_3</div></li>
</ul>
Rotary Foundation goals:<br>
<ul>
<li>Our club's PolioPlus contribution goal is: $p7_4</li>
<li>Our club's Annual Program Fund contribution goal is: $p7_5</li>
<li>Our club's Permanent Fundcontribution goal is: $p7_6</li>
<li>Our club will participate in the following Rotary Foundation programs:<br>
<div>$p7_7</div></li>
</ul>
Leadership development goals:<br>
<div>$p7_8</div><br>
<br>
Public relations goals:<br>
<div>$p7_9</div><br>
<br>
Club adminstration goals:<br>
<div>$p7_10</div><br>
<br>
Other goals:<br>
<div>$p7_11</div><br>
<hr>
<br>
<p>Club President's Signature   _____________________________________ <br>
Date ____________________</p>
<br><p>
Assistant Governor's Signature  _____________________________________ <br>
Date ____________________<br>
</p>
</form>
$S->footer
EOF;
}
?>