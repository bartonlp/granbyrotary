<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;


if(!$S->GrIsMember) {
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
<h1>Sorry This Is Just For Members</h1>
<a href="/login.php">Login</a><br>
<a href="/index.php">Return to Welcome Page</a>
</body>
</html>
EOF;

  exit();
}

switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
  case 'POST':
    switch($_POST['page']) {
      case 'post':
        post($S);
        break;
    }        
    break;
  default:
    throw(new Exception("POST invalid page: {$_POST['page']}"));
    break;
    
  case 'GET':
    switch($_GET['page']) {
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
  global $data;
  
  $h->title = "Rotary Survery";
  $h->banner = "<h1>Granby Rotary Club Survery</h1>";
  $h->extra = <<<EOF
  <style type="text/css">
tbody td, tbody th {
  padding: 5px;
}
textarea {
  width: 100%;
  height: 100px;
}
  </style>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getFooter("<hr>");

  // Get the previous survey if any
  list($result, $n) = $S->query("select data from survey where memberid='$S->id'", true);
  if($n) {
    list($data) = mysql_fetch_row($result);
    
    $data = unserialize($data);
    //vardump($data, "data");
    //extract($data);
  }

  $ar = array("How long have you been a member of this club");
  $optiontext = array("Less than a year",
                      "1-2 years",
                      "3-5 years",
                      "6-14 years",
                      "15 plus years");

  $items = makeSelectionsItems($ar , $optiontext, "howlongmember", "");
  
  echo <<<EOF
$top
<p>This survey is an attempt to understand our members and their desires for Rotary and our club. Please take a few minutes
to answer the questions and give your feedback. This is you opportunity to let the board and the district know what you
want from your club and Rotary.</p>
<p>You can take this survey as many times as you want. Your previous answers will be displayed and you can change them.</p>

<form action="$S->self" method="post">
$items
<hr>
<h3>Weekly Meetings</h3>
<p>Let us know what you think about our weekly meetings:</p>
EOF;

  $ar = array("Amount of Rotary Business", "Amount of time allotted for business",
              "Quality of Speakers", "Amount of time allocated for speakers",
              "Amount of time allocated to fellowship and networking",
              "Meal Quality", "Meal Menu", "Total length of meeting",
              "Overall how satisfied are you with meeting format");

  $optiontext1 = array("Excellent", "Good", "Fair", "Poor", "Awful");

  $header1 = "<tr><th>Item</th><th>Score</th></tr>";

  $items = makeSelectionsItems($ar, $optiontext1, "weekly_meetings", $header1);

  echo <<<EOF
$items
<br>
EOF;
   $ar = array("Which do you prefer?");
   $optiontextx = array("Program first",
               "Rotary business first");

   $items = makeSelectionsItems($ar, $optiontextx, "meetingorder", "");
   echo <<<EOF
$items
<p>Should we have these activities at each meeting?</p>
EOF;
  $ar = array("Pledge of elegance",
              "Invocation",
              "Happy Bucks",
              "Basket Bucks",
              "Polio Jar",
              "Business Issues",
              "Speaker");
  $optiontexty = array("Yes", "No");
  $items = makeSelectionsItems($ar, $optiontexty, "meetingagenda", "<tr><th>Item</th><th>Yes or No</th></tr>");

  echo <<<EOF
$items
<p>Please enter any further comments you have about our weekly meeting:</p>
<textarea name="meeting_comments">${data['meeting_comments']}</textarea>
<hr>

<h3>Your thoughts about activities and other aspects of the club</h3>
<p>This is your chance to telll us which activities and aspects you feel are most important to you and which are less personally
important</p>
EOF;

  $ar = array("Meet people and make new friends",
              "Get involved in the community",
              "Network for business",
              "Social activities beyond weekly meetings",
              "Provides volunteer opportunities",
              "Local service projects and fund raising",
              "International service projects and fund raising");

  $optiontext2 = array("Extremely", "Very", "Somewhat", "A little", "Not at all");

  $header2 = "<tr><th>Item</th><th>Importance</th></tr>";

  $items1 = makeSelectionsItems($ar, $optiontext2, "activities_importance", $header2);

  $items2 = makeSelectionsItems($ar, $optiontext1, "activities_score", $header1);
  echo <<<EOF
$items1
<hr>
<h3>How are we doing on the items you think are important?</h3>
$items2
<p>Any additional comments about activities. For example any activities or fund raisers you would like to see added to our
schedule. </p>
<textarea name="activities_comments">${data['activities_comments']}</textarea>

<hr>
<h3>The five areas of Service</h3>
<p>Our club has traditionally add a sixth area: <b>fund raising</b>. Here are the six:</p>
<ol>
<li>Community Service (Provides service to the local community such as raising funds for local activities
and providing support services for organizations or needy individuals in the community.)
<li>International Service (Provides services to needy organizations and individuals in foreign countries.)
<li>New Generation or Youth Service (Interact, RYLA, Rotaract, Youth Exchange.)
<li>Club Service (Provides service to the club itself such as recruiting new members, organizing social events
and even establishing a web site such as this one.)
<li>Vocational Service (Provides guidelines and suggestions for members to incorporate the ideals of Rotary into their
business and professional lives.)
<li>Fund Raising
</ol>
<p>How do you think our club is doing in each area of service.</p>
EOF;
  $ar = array("Community Service",
              "International Service",
              "New Generation Service",
              "Club Service",
              "Vocational Service",
              "Fund Raising");

  $items = makeSelectionsItems($ar, $optiontext1, "area_of_service", $header1);

  echo <<<EOF
$items
<hr>
<h3>Recruitment and New Member mentoring</h3>
<p>Indicate if you agree or disagree with these statements.</p>
EOF;
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
  $header3 = "<tr><th>Item</th><th>I agree</th></tr>";
  $items = makeSelectionsItems($ar, $optiontext3, "membership", $header3);

  echo <<<EOF
$items
<hr>
<h3>Foundation money</h3>
<p>How important do you think these are? Try to indicate the most and least important.</p>
EOF;
  $ar = array("Boy and Girl Scouts",
              "Little League baseball program",
              "High School After Prom Party",
              "Granby Library Summer Reading Program",
              "Granby Elementary Halloween Party");
  $items = makeSelectionsItems($ar, $optiontext2, "foundationmoney", $header2);

  echo <<<EOF
$items
<p>What other things do you think we should use Foundation money for?</>
<textarea name="foundationmoney_comments">${data['foundationmoney_comment']}</textarea>
<hr>
<h3>Local Projects</h3>
<p>How important do you think these Local projects are? Try to indicate the most and least important.</p>
EOF;

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

  $items = makeSelectionsItems($ar, $optiontext2, "projects", $header2);
  echo <<<EOF
$items
<p>What other Local projects would you like to see us undertake?</p>
<textarea name="local_projects_comments">${data['local_projects']}</textarea>
<hr>
<h3>International Projects</h3>
<p>How important do you think these International Projects are? Try to indicate the most and least important.</p>
EOF;
  $ar = array("Shelter Boxes",
              "Entro Educativo La Minga, Inc. with Pamela Gilbert",
              "Sand Water Filters",
              "Computer Batteries for school in South America");
  $items = makeSelectionsItems($ar, $optiontext2, "international", $header2);

  echo <<<EOF
$items
<p>What additional International projects would you like to see us undertake?</p>
<textarea name="international_comments">${data['international_comments']}</textarea>
<hr>
<br><br>
<input type="hidden" name="page" value="post"/>
<input type="submit" value="Submit Survey"/>
</form>
$footer
EOF;
  
}

// ********************************************************************************
/* CREATE TABLE `survey` (
  `memberid` int(11) NOT NULL,
  `data` longtext,
  PRIMARY KEY  (`memberid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
*/

function post($S) {
  $s = $S->escape(serialize($_POST));
  $S->query("insert into survey (memberid, data) values('$S->id', '$s') ".
            "on duplicate key update data='$s'");

  header("refresh:3;url=$S->self"); // Go back to admin page ASAP

  $h->banner = <<<EOF
<h1>Thunk You</h1><p>Your survey answers have been recorded</p>
<p>You will return to the survey in three seconds.</p>
EOF;

  $top = $S->getPageTop($h);
  $footer = $S->getFooter();
  echo <<<EOF
$top
$footer
EOF;
}

// ********************************************************************************
// $ar is an array of text for items
// $optiontext is an array with the <option value=n>text
// $itemname is the name of the select item
// $headerrow is the <thead>row

function makeSelectionsItems($ar, $optiontext, $itemname, $headerrow) {
  global $data;

  $items = <<<EOF
<table border="1" id="$itemname">
<thead>
$headerrow
</thead>
<tbody>
EOF;

  foreach($ar as $i=>$item) {
    $selected = $data["${itemname}_$i"];

    //echo "itemname=${itemname}_$i, selected=$selected<br>";
    $items .= <<<EOF
<tr>
<td>$item</td>
<th><select name="${itemname}_${i}">
EOF;

  foreach($optiontext as $k=>$v) {
    if($k == $selected) {
      $items .= "\n<option value='$k' selected>$v</option>";
    } else {
      $items .= "\n<option value='$k'>$v</option>";
    }
  }

  $items .= <<<EOF
</select>
</th>
</tr>
EOF;
  }

  $items .= <<<EOF
</tbody>
</table>
EOF;

  return $items;
}

?>   