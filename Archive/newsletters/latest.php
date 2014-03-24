<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>  
<head>
  <title>October 8 Newsletter</title>
  <meta name="description"
     content="Name: Rotary Club of Granby Colorado, Page: October 8 Newsletter" />
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
echo $S->getBanner("<h2>October 8, 2009</h2>");
?>

<h2>2009 District 5450 End Polio Campaign</h2>

<p>The District has built a new web site to make donations and
   inviting friends easy and fun. The new site is
<?php
   echo <<<EOF
   <a href="http://www.endpolio.com?id=$S->GrDistrictId">www.endpolio.com</a>.</p>

EOF;
?>

<p>The District's goal is to raise $300,000 this year or about $100
   per District member. Our Granby Rotary club has set a target to match
   the District goal of $100 per member via $10 donated by 10 friends of
   the member.</p>

<p>The 2009 objective is to get the word out to both Rotarians and
   non-Rotarians. Rotarians know about the End Polio projects that Rotary
   has been spearheading over the years.  Rotarians know about the
   ongoing eradication efforts being undertaken in the few countries in
   the world where Polio is still a menace.</p>

<p>But many people in the United States, especially those under 40
   years old, have never know Polio. They think Polio is a thing of
   the past if they know about it at all. Our job as part of the 2009
   End Polio Campaign is to get the word out and help educate those
   Americans who have never know the scourge of Polio.</p>

<p>The
   <?php echo <<<EOF
   <a href="http://www.endpolio.com?id=$S->GrDistrictId">End
   Polio</a> web site can help you. Use the
   <a href="http://www.endpolio.com/endpolio/site/invitefriends.php?id=$S->GrDistrictId">Invite
   a Friend</a>
EOF;
   ?>
   page to send emails or social network messages to your
   friends, family and associates. It is easy. You can download your
   contact lists from an Email account (like GoogleMail, Yahoo, HotMail,
   etc.) or a social network (like FaceBook, Twitter, Plaxo, etc.)
   Then you can use our letter or write one of your own.
   Select your contacts and click &quot;Send&quot;. It is that easy but
   what you have done really matters. Our club and you will receive
   credit the the donations made by your friends and neighbors.</p>

<p>Try the web site and invite a friend to help Rotary eradicate Polio
   once and for all.</b>

<hr/>

<h2>Gary Cooper Bench</h2>

<p>The bench honoring Rotarian Gary Cooper will be dedicated on Wednesday October 21<sup>th</sup> at 13:15 (1:15 PM) after our
lunch meeting (with any luck. We had hoped to dedicate it this coming Wed.  the 14<sup>th</sup> but the bench isn't quite ready
yet.) The dedication is at the gazebo overlooking the Silver Creek Inn and SolVista.</p>

<p>The bench was constructed by Steve Palm. All Steve asked is that our club donate to a youth organization. We have decided to
donate $200 to the RYLA program and $200 to another youth program. <!--  G-CRY (Grand County Resources for Youth) --></p>

<p>Anyone wishing to attend is welcome. Gary had many friends and associates here in Grand County and this tribute to Gary will be
appreciated by county residents and visitors for many years to come. The bench will be placed on Monday (weather permitting.)</p>  

<hr/>

<h2>Results from Soccer and October Fest Pancake Breakfasts</h2>

<p>Our recent fund raisers have brought in over $500. The two fund
   raisers at the soccer field netted over $400 while our October Fest
   breakfast netted $125. The October Fest event was a bit of a
   letdown in part to the cold weather and poor location of our venue.
   Much thanks to Tom Chaffin, Frank DeLay, Mark Krieg, and the rest
   of the volunteers who maned the griddle and helped serve.</p>

<hr/>

<h2>Club Donates $100 to Colorado Mountain College GED Program</h2>

<p>The <a href="http://www.coloradomtn.edu/">Colorado Mountain
   College</a> sponsors a GED program for Grand
   County residents. GED (General Educational Development) provides
   the equivalent of a High School Diploma for people who were unable
   to finish High School. Our club donated $100 to help defray the
   $20 cost of the GED tests.</p>

<hr/>

<h2>Fall Is Upon Us and Our Last Chance for Road Clean Up</h2>

<p>As the weather cools and we get the first few snow flurries we need
   to do our seasons last Road Clean Up. Get your team together and
   get out there while the weather is still somewhat friendly. Don't
   forget to let Jan or Rhonda know that your team has done its
   part.</p>

<hr/>

<h2>Up Coming Club Meeting Presentations</h2>

<p>We have several interesting noon meeting presentation to look
   forward to:</p>
<ul>
   <li>Oct. 14: Sheryl Shuston will talk about Grand Beginnings (<a
   href="http://www.grandbeginnings.org/">www.grandbeginnings.org</a>)</li>
   <li>Oct. 21: Frank DeLay. No topic yet but we are sure it will be
      good</li>
   <li>Oct. 28: Joan Boyle will talk about Upcoming Financial Fitness
      class for current Habitat families as well as families from the
      Mountain Family Center.</li>  
</ul>
<p>These should all be very interesting talks so don't fail to put
   these meeting dates on your calendars. See you all there.</p>

<hr/>

<!-- Footer Goes Here -->
<?php
echo $S->getFooter();
?>
<!-- End Footer -->

</body>
</html>
