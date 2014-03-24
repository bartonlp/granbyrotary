<?php
define('TOPFILE', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT'] . "/siteautoload.php");
if(file_exists(TOPFILE)) {
  include(TOPFILE);
} else throw new Exception(TOPFILE . "not found");


$S = new GranbyRotary;

if(!$S->isAdmin($S->id)) {
  $errorhdr = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;

  echo <<<EOF
$errorhdr
<body>
<h1>Sorry This Is Just For Admin Members</h1>
</body>
</html>
EOF;

  exit();
}

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case 'POST':
    switch($_POST['page']) {
      default:
        throw(new Exception("POST invalid page: {$_POST['page']}"));
        break;
    }
    break;
    
  case 'GET':
    switch($_GET['page']) {
      case 'show':
        show($S);
        break;
      default:
        start($S);
        break;
    }
    break;
  default:
    // Main page
    throw(new Exception("Not GET or POST: {$_SERVER['REQUEST_METHOD']}"));
    break;
}
exit();

// ********************************************************************************

function start($S) {
  $h->title = "Admin Show Rotary Survery Summary";
  $h->banner = "<h1>Admin Show Granby Rotary Club Survery Info</h1>";

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr>");

  $n = $S->query("select memberid, concat(FName, ' ', LName) as name from survey ".
                                "left join rotarymembers on memberid=id where memberid=id");
  if(!$n) {
    echo "NO RECORDS FOUND";
    exit();
  }

  $tbl = '';
  
  while($row = $S->fetchrow()) {
    extract($row);
    
    $tbl .= <<<EOF
<tr>
<td><a href="$S->self?page=show&memberid=$memberid&name=$name">$name</a></td>
</tr>

EOF;
  }
  echo <<<EOF
$top
<table border="1">
$tbl
</table>
$footer
EOF;
}
// ********************************************************************************

function show($S) {
  $h->title = "Admin Rotary Survery Summary";
  $h->banner = "<h1>Admin Granby Rotary Club Survery Detail</h1>";

  $h->extra = <<<EOF
  <style type="text/css">
table.title {
  width: 100%;

}
.titlerow {
  background-color: white;
}
.title th, .title td {
  border: 1px solid black;
}
.subtitle {
  width: 100%;
  border: 1px solid white;
}
.option {
  width: 100%;
}
.subtitle th {
  width: 50%;
}
.option th {

}
#comments * {
  padding: 5px;
}
  </style>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr>");

  $items = mkarray(); // an array of headings, options etc.

  $memberid = $_GET['memberid'];
  $name = $_GET['name'];

  $n = $S->query("select * from survey where memberid=$memberid");

  if(!$n) {
    echo "NO RECORDS FOUND";
    exit();
  }

  $totals = array();
  $cnt = 0;
  $tbl = "";

  $comments = '';

  // Loop over entire survey table

  while($row = $S->fetchrow()) {
    extract($row);

    ++$cnt;

    // Grab the data item and unserialize it. It is an array
    $data = unserialize($data);

    unset($data['page']); // Don't need this.

    foreach($data as $k=>$v) {
      // $k is the item with name_n where n is 0-n
      // $v is a number 0-n which corresponds to the options

      // Put <textarea> comments in a separate table.

      if(preg_match("/_comment|local_projects/", $k)) {
        if($v) {
          preg_match("/(.*?)_/", $k, $m);
          $k = ucfirst($m[1]);
          $v = preg_replace("/\n+/", "<br>", $v); // replace lf with <br> so sort later on will work on multiline text          
          $comments .= "<tr><th>$k</th><td>$v</td></tr>\n";
        }
        continue;
      }

      // separate the item name from the item number

      if(preg_match("/(.*?)_(\d+)/", $k, $m)) {
        $itemname = $m[1];
        $iteminx = $m[2];
      } else {
        echo "preg_match failed";
        exit();
      }

      $totals[$itemname][$iteminx][$v]++;
    }
  }

  // loop over totals and create tables

  foreach($totals as $k=>$v) {
    // $k is the itemname and $v is the item array 0-xx
    
    $item = $items[$k]; // Get item info

    $title = $item['title'];
    $ar = $item['ar'];
    $options = $item['options'];

    // The top including table is the title
    // It has the title and then a table for the subtitle

    $tbl .= <<<EOF
<table class="title">
<tr class="titlerow"><th>$title</th></tr>
<tr class="subtitlerow"><td>

EOF;

    foreach($v as $k1=>$v1) {
      // $k1 is 0-n which are index into $ar. $v1 is an array of option counts.

      $subtitle = $ar[$k1];
     $tbl .= <<<EOF
<table class="subtitle">
<tr class="subtitletablerow"><th>$subtitle</th>
<td>
<table class="option">

EOF;

      foreach($v1 as $k2=>$v2) {
         // $k2 is 0-n which are the index into $options. $v2 is the count
         $option = $options[$k2];
         $tbl .= <<<EOF
<tr class="optrow"><th>$option</th></tr>

EOF;
      }
      $tbl .= <<<EOF
</table>
</td></tr>

EOF;

    }
    $tbl .= <<<EOF
</table>
</td></tr>
</table>
<br>

EOF;
  }

  function sortit($a, $b) {
    preg_match("~<tr><th>(.*?)</th>~", $a, $m1);
    preg_match("~<tr><th>(.*?)</th>~", $b, $m2);
    //echo "m1=$m1[1], m2=$m2[1]<br>";
    
    if($m1[1] == $m2[1]) {
        return 0;
    }
    return ($m1[1] < $m2[1]) ? -1 : 1;
  }

  $comments = explode("\n", $comments);
  
  usort($comments, sortit);

  $comments = implode("\n", $comments);
  
  echo <<<EOF
$top
<h2>Survey Results for $name</h2>
$tbl
<h2>Comments by section</h2>
<table border="1">
<tr><th>Section</th><th>Comment</th></tr>
$comments
</table>
$footer
EOF;
}

function mkarray() {
  $items = array();

  $ar = array("How long have you been a member of this club");
  $optiontext = array("Less than a year",
                      "1-2 years",
                      "3-5 years",
                      "6-14 years",
                      "15 plus years");

  list($name, $item) = makeSelectionsItems($ar , $optiontext, "howlongmember", "Years with club");

  $items[$name] = $item;

  $ar = array("Amount of Rotary Business", "Amount of time allotted for business",
              "Quality of Speakers", "Amount of time allocated for speakers",
              "Amount of time allocated to fellowship and networking",
              "Meal Quality", "Meal Menu", "Total length of meeting",
              "Overall how satisfied are you with meeting format");

  $optiontext1 = array("Excellent", "Good", "Fair", "Poor", "Awful");

  list($name, $item) = makeSelectionsItems($ar, $optiontext1, "weekly_meetings", "Meetings");
  $items[$name] = $item;

   $ar = array("Which do you prefer?");
   $optiontextx = array("Program first",
               "Rotary business first",
               "No Change");

  list($name, $item) = makeSelectionsItems($ar, $optiontextx, "meetingorder", "Meetings Order");
  $items[$name] = $item;


  $ar = array("Pledge of elegance",
              "Invocation",
              "Happy Bucks",
              "Basket Bucks",
              "Polio Jar",
              "Business Issues",
              "Speaker");
  $optiontexty = array("Yes", "No");

  list($name, $item) = makeSelectionsItems($ar, $optiontexty, "meetingagenda", "Meeting Agenda");
  $items[$name] = $item;

  $ar = array("Meet people and make new friends",
              "Get involved in the community",
              "Network for business",
              "Social activities beyond weekly meetings",
              "Provides volunteer opportunities",
              "Local service projects and fund raising",
              "International service projects and fund raising");

  $optiontext2 = array("Extremely", "Very", "Somewhat", "A little", "Not at all");

  list($name, $item) = makeSelectionsItems($ar, $optiontext2, "activities_importance", "Activities Importance");
  $items[$name] = $item;

  list($name, $item) = makeSelectionsItems($ar, $optiontext1, "activities_score", "Activites Score");
  $items[$name] = $item;

  $ar = array("Community Service",
              "International Service",
              "New Generation Service",
              "Club Service",
              "Vocational Service",
              "Fund Raising");

  list($name, $item) = makeSelectionsItems($ar, $optiontext1, "area_of_service", "Areas of service");
  $items[$name] = $item;

  $ar = array("The club has a good mentoring program for new members", 
              "My sponsor or mentor helped me feel welcomed and comfortable right from the first weekly meeting",
              "My sponsor or mentor helped me get involved with committees and activities",
              "Meetings seem a little cliquish. It was hard to feel comfortable initially",
              "Overall the club is meeting my expectations and desires",
              "I wish there were more ways for me to become more involved",
              "Club requirements of involvement are more time-consuming than I expected",
              "Club and other Rotary expenses are a problem",
              "I am satisfied with the club",
              "I am satisfied with this years club leaders",
              "I am satisfied with the clubs committees and structure");

  $optiontext3 = array("Completely", "Pretty Much", "Somewhat", "A little", "Not at all");

  list($name, $item) = makeSelectionsItems($ar, $optiontext3, "membership", "Membership");
  $items[$name] = $item;

  $ar = array("Boy and Girl Scouts",
              "Little League baseball program",
              "High School After Prom Party",
              "Granby Library Summer Reading Program",
              "Granby Elementary Halloween Party");

  list($name, $item) = makeSelectionsItems($ar, $optiontext2, "foundationmoney", "Foundation");
  $items[$name] = $item;
  
  $ar = array("Dictionaries for 3rd graders in Grand County",
              "Dolly Parton Reading Program",
              "H.S. College Scholarships",
              "Earth Day with the EGMS 6th grade",
              "9 Health Fair",
              "International Student Exchange",
              "Polio Plus",
              "RYLA and Young RYLA",
              "Interact at MPHS",
              "Food Bank for Mountain Family Center",
              "Computers for Seniors");

  list($name, $item) = makeSelectionsItems($ar, $optiontext2, "projects", "Local Service");
  $items[$name] = $item;

  $ar = array("Shelter Boxes",
              "Entro Educativo La Minga, Inc. with Pamela Gilbert",
              "Sand Water Filters",
              "Computer Batteries for school in South America");

  list($name, $item) = makeSelectionsItems($ar, $optiontext2, "international", "International Service");
  $items[$name] = $item;

  return $items;
}

function makeSelectionsItems($ar, $optiontext, $itemname, $title) {
  $item = array();

  $item['options'] = $optiontext; // array
  $item['ar'] = $ar; // array
  $item['title'] = $title;

  return array($itemname, $item);
}
?>