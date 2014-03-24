<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");
$S = new GranbyRotary;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>  
<head>
  <title>May 19 Newsletter</title>
  <meta name="description"
     content="Name: Rotary Club of Granby Colorado, Page: May 19 News Letter" />
  <meta name="keywords" content="rotary" />

   <!-- Link our custom CSS -->
   <link rel="stylesheet" title="Rotary Style Sheet"
        href="/rotary.css" type="text/css" />

   <!-- Inline Javascript if any -->
   <script type="text/javascript">
   </script>

   <!-- Inline CSS if any -->
   <style type='text/css'>

.img {
        margin: 0 20px 20px 20px;
        border: 10px groove gray;
}
div, hr {
        clear: left;
}
   </style>

</head>
<body>

<!-- Header Goes Here -->
<?php
echo $S->getBanner("<h2>May 19, 2009</h2>");
?>
<!-- End Header -->


<h2>Earth Day Was A Success -- Photos Tell the Story</h2

<div>
<img class='img' src='/earthday/eday, rot tom.jpg'
     alt='Tom Chaffin and kids' style='float: left'/>
<p>Rotarian Tom Chaffin spruced up an alley behind Granby's main street.
Students are: Lily Dines, Renato Ruiz, Abe Lietzke, Meme Mattison.
</p>
</div>
<div>
<img class='img' src='/earthday/eday, rot jeff.jpg' alt='Jeff Versosky and kids'
   style='float: left'/>
<p>Jeff Verosky, on Granby's main street with students are Tommy Evans,
Nik Seemann, Brandon Wylie and J. D. Webb. Jeff Verosky, East Grand
Middle School Principal, helped and supervised the students while they
picked up trash along Granby's main street.
</p>
</div>
<div>
<img class='img' src='/earthday/eday, r, ka.jpg' alt='Kids planting trees' style='float: left'/>
<p>Trees were planted near East Grand Middle School, with help from Neils
Lunceford nurseries and Rotarians. In the tree-planting pic the adult
is Jim Karas, teacher at East Grand Middle School. Two of the students
helping out are Alex Bloomfield and Beto Perez, with other students.
</p>
</div>

<p>Thanks Rhonda! Good Job! And thanks to all the Rotarians who
  helped out too!
</p>  

<h4>You can see more <a href="/earthday/earthday.php">photos</a> form Earth Day</h4>
<hr/>

<!-- Footer Goes Here -->
<?php
echo $S->getFooter();
?>
<!-- End Footer -->

</body>
</html>
