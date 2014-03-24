<?php

require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;
$h->title = "Governor's June Newsletter";
$h->banner = "<h1>Governor's June Newsletter</h1>";
echo $S->getPageTop($h);

$data = <<<EOF
<body topmargin=3D"0" leftmargin=3D"0" rightmargin=3D"0"><!--Copyright (c) =
1996-2011 Constant Contact. All rights reserved.  Except as permitted under=
 a separate
written agreement with Constant Contact, neither the Constant Contact softw=
are, nor any content that appears on any Constant Contact site,
including but not limited to, web pages, newsletters, or templates may be r=
eproduced, republished, repurposed, or distributed without the
prior written permission of Constant Contact.  For inquiries regarding repr=
oduction or distribution of any Constant Contact material, please
contact legal@constantcontact.com.--><div><table width=3D"100%" cellpadding=
=3D"0" cellspacing=3D"0" border=3D"0"><tr><td align=3D"center" valign=3D"to=
p"><table cellpadding=3D"0" cellspacing=3D"0" border=3D"0" width=3D"600"><t=
r><td align=3D"left" valign=3D"top"><table cellpadding=3D"0" cellspacing=3D=
"0" class=3D"shr-btn-tbl"><tr><td><img src=3D"http://img.constantcontact.co=
m/ui/images1/shr_drw_left.png" /></td><td><a href=3D"http://s.rs6.net/t?e=
=3D7ZR2qlnAiWg&c=3D1&r=3D1" title=3D"Share with Facebook"><img src=3D"http:=
//img.constantcontact.com/ui/images1/shr_drw_fb.png" style=3D"border:0;" />=
</a></td><td><a href=3D"http://s.rs6.net/t?e=3D7ZR2qlnAiWg&c=3D3&r=3D1" tit=
le=3D"Share with Twitter"><img src=3D"http://img.constantcontact.com/ui/ima=
ges1/shr_drw_twit.png" style=3D"border:0;" /></a></td><td><a href=3D"http:/=
/s.rs6.net/t?e=3D7ZR2qlnAiWg&c=3D4&r=3D1" title=3D"Share with LinkedIn"><im=
g src=3D"http://img.constantcontact.com/ui/images1/shr_drw_linked.png" styl=
e=3D"border:0;" /></a></td><td><img src=3D"http://img.constantcontact.com/u=
i/images1/shr_drw_divider.png" style=3D"border:0;" /></td><td><a href=3D"ht=
tp://s.rs6.net/t?e=3D7ZR2qlnAiWg&c=3D5&r=3D1" title=3D"More Share Options">=
<img src=3D"http://img.constantcontact.com/ui/images1/shr_drw_more.png" sty=
le=3D"border:0;" /></a></td><td><img src=3D"http://img.constantcontact.com/=
ui/images1/shr_drw_right.png" style=3D"border:0;" /></td><td class=3D"like-=
this" style=3D"padding-left:5px;"><a href=3D"http://myemail.constantcontact=
.com/JUNE-2011-News-Rotary-District-5450.html?soid=3D1102180066109&aid=3D7Z=
R2qlnAiWg#fblike" title=3D"Like This"><img src=3D"http://img.constantcontac=
t.com/ui/images1/shr_btn_like_sm.png" style=3D"border:0;" /></a></td></tr><=
/table></td></tr></table></td></tr></table></div>
<div id=3D"rootDiv" align=3D"center">
<table style=3D"background-color:#999999;margin:0px 0px 0px 0px;" bgcolor=
=3D"#999999" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0=
">
<tr>
=09<td valign=3D"top" rowspan=3D"1" colspan=3D"1" align=3D"center">
<table style=3D"width:600px;" border=3D"0" width=3D"600" cellspacing=3D"0" =
cellpadding=3D"1">
<tr>
  <td width=3D"100%" rowspan=3D"1" colspan=3D"2" align=3D"left">
=09<table style=3D"background-color:#ffffff" width=3D"100%" id=3D"content_L=
ETTER.BLOCK1" aria-posinset=3D"0" aria-setsize=3D"0" border=3D"0" hidefocus=
=3D"true" tabindex=3D"0" cellspacing=3D"0" cols=3D"0" cellpadding=3D"0" ari=
a-level=3D"0"  datapagesize=3D"0" bgcolor=3D"#ffffff"><tr><td valign=3D"bot=
tom" rowspan=3D"1" colspan=3D"1" align=3D"middle"><img height=3D"108" name=
=3D"ACCOUNT.IMAGE.49" border=3D"0" width=3D"624"  alt=3D"2010-11 header" sr=
c=3D"http://ih.constantcontact.com/fs023/1102180066109/img/49.jpg" /></td><=
/tr></table>
    </td>
</tr>
<tr>
  <td style=3D"background-color:#4C3F36;padding:1px;" bgcolor=3D"#4C3F36" r=
owspan=3D"1" colspan=3D"1" align=3D"left">
  <table border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  <tr>
    <td style=3D"background-color:#285685;" bgcolor=3D"#285685" valign=3D"t=
op" width=3D"100%" rowspan=3D"1" colspan=3D"2" align=3D"left">
=09 =20
=09 =20
=09 =20
      <table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=
=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table></td>
  </tr>
  <tr>
    <td style=3D"background-color:#FFFFFF;" bgcolor=3D"#FFFFFF" valign=3D"t=
op" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09<table border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"7">
    <tr>
      <td style=3D"width:440px;" width=3D"440" rowspan=3D"1" colspan=3D"1" =
align=3D"left">
=09=09
       =20
       =20
       =20
       =20
        <table style=3D"margin-bottom:10px;" border=3D"0" width=3D"100%" ta=
bindex=3D"0" cellspacing=3D"0" cellpadding=3D"5" id=3D"content_LETTER.BLOCK=
3"><tr><td style=3D"text-align: left; font-family: Verdana,Geneva,Arial,Hel=
vetica,sans-serif; color: rgb(0, 0, 0); font-size: 8pt;" color=3D"#285685" =
rowspan=3D"1" colspan=3D"1" align=3D"left">
<div><span style=3D"font-family: Trebuchet MS; font-size: 12pt;" size=3D"3"=
 face=3D"Trebuchet MS"><strong>JUNE 2011 NEWSLETTER</strong></span>&nbsp;</=
div>
<div style=3D"color: rgb(0, 0, 255); font-size: 10pt;"><strong>A MESSAGE FR=
OM DISTRICT GOVERNOR KAREN</strong></div>
<div style=3D"font-family: Calibri,Helvetica,Arial,sans-serif; color: rgb(5=
1, 51, 51); font-size: 12pt;"><span><span>Dear Rotarians</span>,</span></di=
v>
<p style=3D"font-size: 10pt; margin-top: 0px; margin-bottom: 0px;">June 201=
1! I can't believe my time as your District Governor is almost over. We hav=
e had such a successful year in District 5450 "Building Communities and Bri=
dging Continents" and here are some of the reasons why:<br /><br /><strong>=
Membership</strong>: District 5450 is up 102 members. We have 70 new member=
s of existing Rotary Clubs and we chartered the Rotary Club of Castle Pines=
, adding another 32 Rotarians. Rotary International (RI) announced new thre=
e-year club pilots to promote membership and we had three clubs named as pa=
rt of the pilot program: The Rotary Club of Boulder Valley was selected to =
participate in the RI Associate Member pilot program beginning 1 July 2011.=
The Rotary Club of Boulder was selected to participate in the RI Satellite =
Club pilot program beginning 1 July 2011.The Rotary Club of Peak to Peak wa=
s selected to participate in the RI Innovation & Flexibility pilot program =
beginning 1 July 2011. Only 200 out of 1300 applicants were chosen out of e=
ach category. Thanks to our wonderful Membership and Extension committees u=
nder the direction of Bob Walsh (Membership) and Mary Kay Hasz (Extension).=
</p>
<p style=3D"margin-top: 0px; font-family: Verdana,Geneva; margin-bottom: 0p=
x; font-size: 10pt;">&nbsp;<strong><a style=3D"color: blue; text-decoration=
: underline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp=
?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001hFWJSphYp58CVCyrraciOLU=
mjhDMaXvfh-mID7dQSkUwYDab_8Jle9Olr9AoX4x4fBTbZRB2YX1HlPgkn4A7NWENisqohj_mOZ=
-RVDbO8IPuXhBT6OiuCb4vuLpGnwGi-DWGEE2gtUkuW6RbVl11_qd5OhejU6dZZ59Js7Dh9p0qG=
YJmy-mHPw=3D=3D" linktype=3D"link" target=3D"_blank">COMPLETE MESSAGE...</a=
></strong>&nbsp;</p>
</td></tr></table><table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CC=
CCCC" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK5" /><table style=3D"margin-bottom:10p=
x;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellpaddin=
g=3D"2" id=3D"content_LETTER.BLOCK5"><tr><td style=3D"background-color:#D4D=
DE6;text-align: left; font-family: Arial,Helvetica,sans-serif; color: rgb(0=
, 0, 128); font-size: 12pt;" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1=
" align=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><span><strong>DISTRICT GO=
VERNOR ELECT MESSAGE&nbsp; &nbsp;</strong></span></p>
</td></tr><tr><td style=3D"text-align: left; font-size: 10pt; font-family: =
Verdana,Geneva; color: rgb(0, 0, 0);" rowspan=3D"1" colspan=3D"1" align=3D"=
left">
<div>
<div>
<div>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">The judge called the defe=
ndant to the stand to plead his case. The defendant gave clear, convincing =
evidence of his innocence, justifying his location, inabilities, and lack o=
f motive. Upon hearing his the judge said: " You're right", at which the cl=
erk pointed out he had not heard the plaintiff. Quickly the plaintiff was b=
rought forward and with very convincing evidence make it clear that, in fac=
t, the defendant had to be guilty. There were witnesses, clear motive, and =
firm evidence incriminating the defendant, upon which the judge said: "You'=
re right". The clerk strongly informed the judge that they both can't be ri=
ght at which point the judge said" "you're right".</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp5-oiKsIYzS-8NWAuLuRgmZppcVXFYAh9zflI6Plg8GOSBshS4ZnjVD55uS2JIdwkNM=
iWcRvOr8-E0ZB4-I1EQetB9wMZPnx37gQWlSiil8ghPhBa3VFNk0QTURHx8exJBmDyN8mVIcYTB=
Bd3mJQ4YtlzxasuRESFDxgOpyi0Zs5u02jZa4V8R6k" linktype=3D"link" target=3D"_bl=
ank">COMPLETE MESSAGE...</a></strong></p>
</div>
</div>
</div>
</td></tr></table><a name=3D"LETTER.BLOCK6" /><table style=3D"margin-bottom=
:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellpa=
dding=3D"2" id=3D"content_LETTER.BLOCK6"><tr><td style=3D"color:#000066;fon=
t-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgroun=
d-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspa=
n=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-ser=
if; color: rgb(0, 0, 128);"><strong>AREA 10 ROTARY CLUBS ARE ACTIVE!</stron=
g></span><br /></td></tr><tr><td style=3D"text-align: left; font-family: Ve=
rdana,Geneva; color: rgb(0, 0, 0); font-size: 10pt;" rowspan=3D"1" colspan=
=3D"1" align=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;">Also, as you know, <stron=
g>Arvada Sunrise Rotary</strong> celebrated their 20th Anniversary a couple=
 of weeks ago right after the District Conference. The high light of their =
year was Don Howard's "Service Above Self" award for his diligent and untir=
ing work in the African nation of Kenya.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">The <strong>Westminster 7=
:10 club</strong>, besides earning the Presidential Citation also received =
the Presidential Citation With Distinction award! Also, their C4K ( compute=
rs for kids) donated 359 reconditioned computers for kids for a total of 6,=
777 since the program began.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">The <strong>Westminster R=
otary Club</strong> has a great scholarship program for local high school s=
tudents to further their education.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>Wheat Ridge Rotar=
y&nbsp;</strong> - The City of Wheat Ridge is pleased to announce that the =
Wheat Ridge Rotary Club has generously donated $15,000 to the City which wi=
ll be paid in $3,000 dollar increments over the next five years.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp59P66kBvXoTo4JNRPj_GJqyodMpt1TDIAaqmnPk-tRwqFi1dY1ou6EjbtFx7N3TTSg=
9vaBu3hlFCTSv4MUZYr78IBQ8cbx8yRVPrRoBIowVste2q3v-5ReLoztsfQ8VW1fhurw1rE5qS9=
vtk0icNamMy1l2hyMwU_KjkJW7uyMvESVvdUocd2SAkWdq3FFLrsg=3D" linktype=3D"link"=
 target=3D"_blank">COMPLETE STORIES..</a>.&nbsp;</strong></p>
</td></tr></table><a name=3D"LETTER.BLOCK7" /><table style=3D"margin-bottom=
:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellpa=
dding=3D"2" id=3D"content_LETTER.BLOCK7"><tr><td style=3D"color:#000066;fon=
t-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgroun=
d-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspa=
n=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-ser=
if; color: rgb(0, 0, 128);"><strong>HEALTH AND HUNGER COMMITTEE</strong></s=
pan><br /></td></tr><tr><td style=3D"text-align: left; font-family: Tahoma,=
Arial,Helvetica,sans-serif; color: rgb(0, 0, 0); font-size: 10pt;" rowspan=
=3D"1" colspan=3D"1" align=3D"left">
<div>
<p style=3D"font-family: Verdana,Geneva; margin-top: 0px; margin-bottom: 0p=
x;">It has been a busy year for the Health & Hunger Committee, co-chaired b=
y Dr. Mike Hitchcock and Nan Jarvis. The goal has been to be a resource cen=
ter for information and support to Rotary Clubs in efforts to address healt=
h and hunger concerns both in their communities and around the world. An in=
teresting trend this year has been an increased focus by 5450 clubs to iden=
tify and initiate projects within their own communities. This focus has als=
o increased awareness and education of Rotary and served to attract new mem=
bers.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp5-Pp5eBCxh5JCEprr7Hhq1r7aEN0nBjippLhM9_bVYaC4NXZ1t3LnAU9zpn-4vGjfx=
uHdTcg7qD6OsSFL0YVTz_cH8FaoGy9KG3IqEFksXdoxVEPDGFhCrnL6QqsD9DLdLnDdyzdGwuS3=
WvS8ATSSoURg9bPNAAq43SdUO43tu7Sl-LNkf0HKm4" linktype=3D"link" target=3D"_bl=
ank">READ MORE...</a></strong>&nbsp;</p>
</div>
</td></tr></table><a name=3D"LETTER.BLOCK8" /><table style=3D"margin-bottom=
:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellpa=
dding=3D"2" id=3D"content_LETTER.BLOCK8"><tr><td style=3D"color:#000066;fon=
t-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgroun=
d-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspa=
n=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-ser=
if; color: rgb(0, 0, 128);"><strong>STRATEGIC PLANNING FOR ROTARY&nbsp;</st=
rong></span></td></tr><tr><td style=3D"color:#000000;font-family:Calibri, H=
elvetica, Arial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1"=
 colspan=3D"1" align=3D"left">
<div>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">Strategic Planning is the "in thing." RI is doing it. =
Clubs are doing it. And, starting in 2005, District 5450 is doing it. Here =
is why we are doing it. One of the great strengths of Rotary is its infusio=
n of new leadership every year. At every level in Rotary,</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">new leaders have the freedom to propose new initiative=
s, new ways to organize to achieve them and new actions for their accomplis=
hment. At the same time, this freedom raises&nbsp; concerns about continuit=
y and communication and raises the possibility that frequent changes in dir=
ection may detract from the effectiveness of the organization.</p>
<span style=3D"font-family: Verdana,Geneva; font-size: 10pt;">
<p style=3D"margin-top: 0px; margin-bottom: 0px; color: rgb(51, 51, 51);"><=
strong><a style=3D"color: blue; text-decoration: underline;" track=3D"on" s=
hape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D110602=
5110297&s=3D2914&e=3D001hFWJSphYp580TBtIyVELT2XZ16RVSvWcqoyIGSxuyIGMl1S7hL3=
BN-Qd4kPd1aKWPYTnpSagHEIRyILnJr6sfw89_Y1rxukrVYy_GYoW0WMyQzwGA2wSD3_x-dxAIq=
g2rdK52eGRhf1G7ZxDT_H2NaSebqjQ378AKB7uDwJl8AKHq1nb9hvP2LobbzdUKYpd" linktyp=
e=3D"link" target=3D"_blank">READ MORE...</a></strong>&nbsp;&nbsp;</p>
</span></div>
</td></tr></table><a name=3D"LETTER.BLOCK9" /><table style=3D"margin-bottom=
:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellpa=
dding=3D"2" id=3D"content_LETTER.BLOCK9"><tr><td style=3D"color:#000066;fon=
t-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgroun=
d-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspa=
n=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-ser=
if; color: rgb(0, 0, 128);"><strong>1OO YEARS OF ROTARY IN COLORADO&nbsp;</=
strong></span></td></tr><tr><td style=3D"text-align: left; font-family: Cal=
ibri,Helvetica,Arial,sans-serif; color: rgb(0, 0, 0); font-size: 12pt;" row=
span=3D"1" colspan=3D"1" align=3D"left">
<div style=3D"font-family: Tahoma,Arial,Helvetica,sans-serif; font-size: 10=
pt;"><span style=3D"color: rgb(51, 51, 51);">
<div><span style=3D"color: rgb(51, 51, 51);">
<div><span style=3D"color: rgb(51, 51, 51);">
<div>
<p style=3D"font-family: Verdana,Geneva; color: rgb(0, 77, 180); margin-top=
: 0px; margin-bottom: 0px;"><strong>100 Years of Rotary in Colorado: High-S=
peed Internet and The Future</strong></p>
<p style=3D"font-family: Verdana,Geneva; margin-top: 0px; margin-bottom: 0p=
x;">What is probably the single most critical item for Colorado's K-12 educ=
ation for future improvement? Laptops, iPads, whiteboards, teacher training=
, internet connectivity, classroom technology? If you are thinking internet=
 connectivity, the least sexy element of the equation, you are right! Inter=
net</p>
<p style=3D"font-family: Verdana,Geneva; margin-top: 0px; margin-bottom: 0p=
x;">connectivity is critical to all other needs and is being addressed by a=
 federal stimulus grant to a Colorado organization by the name of Eagle-Net=
. You may not of heard of Eagle-Net (http://www.coeaglenet.net/), but it ha=
s been operating for years delivering high speed internet to a group of sch=
ool</p>
<p style=3D"font-family: Verdana,Geneva; margin-top: 0px; margin-bottom: 0p=
x;">districts. Eagle-Net will deliver what's called the "Middle Mile," inte=
rnet to every school district building (not classroom) in the state. As Rot=
arians, we have a challenge - to deliver the key element of the "Last Mile"=
 - from the school district building to the classrooms and, of course, to t=
he students' and teachers'</p>
<p style=3D"font-family: Verdana,Geneva; margin-top: 0px; margin-bottom: 0p=
x;">hands and minds!</p>
</div>
</span></div>
</span></div>
</span></div>
<p style=3D"margin-top: 0px; margin-bottom: 0px; font-family: Verdana,Genev=
a; font-size: 10pt;"><strong><a style=3D"color: blue; text-decoration: unde=
rline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=
=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001hFWJSphYp5-TAQlC3PU5I8QLrQK=
5qHtsAiBlidKlzQK-ISq_N4cHpKtD1eEMw0hO5r379wbcfSZ6Nsvdo-tY9l_JpY2l3cCvtsURWK=
r3-aBexQhULFFumI-ySAk1IO7EO3GRyubclJdZ1GWJARz0oPvlXuCB9chE-s2awbYkO4sqK-2fF=
6Aae6eD9umW-ILL" linktype=3D"link" target=3D"_blank">READ MORE...</a></stro=
ng></p>
</td></tr></table><a name=3D"LETTER.BLOCK10" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellp=
adding=3D"2" id=3D"content_LETTER.BLOCK10"><tr><td style=3D"color:#000066;f=
ont-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgro=
und-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" cols=
pan=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-s=
erif; color: rgb(0, 0, 128);"><strong>ERADICATING POLIO&nbsp;</strong></spa=
n></td></tr><tr><td style=3D"text-align: left; font-family: Calibri,Helveti=
ca,Arial,sans-serif; color: rgb(0, 0, 0); font-size: 11pt;" rowspan=3D"1" c=
olspan=3D"1" align=3D"left">
<div>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; color: rgb(0, 77,=
 180); margin-top: 0px; margin-bottom: 0px;"><span><strong>Eradicating poli=
o will take renewed resolve, says Gates</strong></span></p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">Bill Gates, cochair of the Bill & Melinda Gates Founda=
tion, praised Rotary for its continued success in the effort to eradicate p=
olio, but cautioned that Rotarians will need to redouble their efforts to k=
eep the disease from spreading -- and threatening hundreds of thousands of =
children. Gates, the keynote speaker at the third plenary session of the 20=
11 RI Convention, 24 May in New Orleans, Louisiana, USA, said that because =
of Rotary, there are many places in the world where polio is no longer cons=
idered a threat.</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;"><strong><a style=3D"color: blue; text-decoration: unde=
rline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=
=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001hFWJSphYp5_UWoAgYK4m-zv-Y8W=
dLZNUuKMdSzZpRt3lkMqGw931_sdAo6BPpiyZ7NpQXv1kcEyj5gZJnRxrbJ4egZXQS2Hffodb5x=
cdl3jQ1YcgUec4GHM0mtNtkRnzG3yS_eba-UlseJcuRrw68HVrIaenj8kpDH7xGKJNPhcn9amo0=
agLT-GlDfaegfrrJ0pqpphWFxM=3D" linktype=3D"link" target=3D"_blank">READ MOR=
E...</a></strong>&nbsp;</p>
</div>
</td></tr></table><a name=3D"LETTER.BLOCK11" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellspacing=3D"0" cellp=
adding=3D"2" id=3D"content_LETTER.BLOCK11"><tr><td style=3D"color:#000066;f=
ont-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;backgro=
und-color:#D4DDE6;text-align: left;" bgcolor=3D"#D4DDE6" rowspan=3D"1" cols=
pan=3D"1" align=3D"left"><span style=3D"font-family: Arial,Helvetica,sans-s=
erif; color: rgb(0, 0, 128);"><strong>THE PEACE CORP - ROTARY CONNECTION: A=
 NATURAL FIT</strong></span><br /></td></tr><tr><td style=3D"text-align: le=
ft; font-family: Verdana,Geneva; color: rgb(0, 0, 0); font-size: 10pt;" row=
span=3D"1" colspan=3D"1" align=3D"left">
<div><span style=3D"color: rgb(51, 51, 51);">
<div>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">Sue Fox had an idea. As a=
 Returned Volunteer from Liberia (68-70) and a past-president of the Denver=
 Rotary Club, she knew that there was a natural connection between Rotary a=
nd Peace Corps. She felt that "RPCVs and Rotarians are kindred spirits, see=
king the same goals embodied in Rotary's motto,</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">'Service Above Self.'" Sh=
e followed up on that hunch by bringing together a group of Rotarians and R=
PCVs at her home on November 21, 2009 with the encouragement of then-Rotary=
 District 5450 District Governor, Mike Oldham, who has been a huge and acti=
ve supporter of the committee.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp5-bvLnne4a5fxBBOEbUSIZN5xPqzYij7Ag2DdHs7h3r0sBtizVzKjX8jfQx5S_3N5F=
3yjiMDJowaHnjtLW7lUD-ULUKdreBXeatn8Xg1ImBaLu4HCd-IUWHpTL_ZPak-s5SsWow6ZBuPP=
2ENGJSzuApsgooZvz2xuJMQLNpy-tQslPdALy0aAEYRNzy2kahjt8=3D" linktype=3D"link"=
 target=3D"_blank">READ MORE... &nbsp;</a></strong></p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
</div>
</span></div>
</td></tr></table><a name=3D"LETTER.BLOCK19" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"2" id=
=3D"content_LETTER.BLOCK19"><tr><td style=3D"background-color:#D4DDE6;text-=
align: left; font-size: 12pt; font-family: Arial,Helvetica,sans-serif; colo=
r: rgb(0, 0, 102);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=
=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>TRF CHAIR URGES R=
OTARY TO SHAKE UP THE KETCH UP</strong></p>
</td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Ar=
ial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"=
1" align=3D"left">
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">Everyone knows if you want to get ketch-up out of a bo=
ttle you</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">have to shake it vigorously if you want it to flow. Th=
at is the</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">analogy 2010 - 2011 Rotary Foundation chair Karl-Wilhe=
lm</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">Stenhammar believes is necessary if RI is going to suc=
ceed &#x02d9;in</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">changing with the times."</p>
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;"><strong><a style=3D"color: blue; text-decoration: unde=
rline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=
=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001hFWJSphYp58l_2fXAm69_pexvsB=
WspE95AUrqMKU3mbB8qjEtQAhgpz2XexnQFvTwuxMFtjCRFeT-XzWieITB9Nc_MQSbsdEyN0xev=
K13QC6YfVD9mTW61t7-CtWQN0s1YN3ALhMVRbkavBX6Rhc_lMRjNzP9kWcLC4HdOYtQ9FjEmJ1e=
DVj1ZH_uHeVTTThgGYnAzN3yxo=3D" linktype=3D"link" target=3D"_blank">READ MOR=
E...</a></strong>&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
</td></tr></table><a name=3D"LETTER.BLOCK24" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"2" id=
=3D"content_LETTER.BLOCK24"><tr><td style=3D"background-color:#D4DDE6;text-=
align: left; font-size: 12pt; font-family: Arial,Helvetica,sans-serif; colo=
r: rgb(0, 0, 102);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=
=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>ROTARY 3H GRANT 6=
6890 ZIMBABWE</strong></p>
</td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Ar=
ial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"=
1" align=3D"left">
<p style=3D"font-family: Verdana,Geneva; font-size: 10pt; margin-top: 0px; =
margin-bottom: 0px;">In May, Stella Dongo, the District Governor Elect for =
Zimbabwe and neighboring countries, travelled to Colorado to thank Rotarian=
s for their support of the Rotary 3H Grant that has changed lives in her co=
mmunity. Stella has been personally involved with Carolyn Schrader from Den=
ver Mile High Rotary and District 5450's Grants Chair. Carolyn and other Ro=
tarians have made numerous trips to Zimbabwe to initiate the 3H Grant appli=
cation and to get the approved grant started in the right direction. Talkin=
g to 25 Rotarians in Carolyn's home, Stella could not speak highly enough a=
bout the positive impacts of this project and the grant.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><span style=3D"fo=
nt-family: Verdana,Geneva; font-size: 10pt;"><a style=3D"color: blue; text-=
decoration: underline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.=
net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001hFWJSphYp5-LK=
7lfa7PF_ec-ureZnspybTHls3LchBcB-6soDD3CXxbUZSeFQVDF3BgZ2Xg6zcwiaR4IxQO8qRZP=
203b07dL--rkmx8HQVIW1AuaQckI9oieCkn3kKDH02Vzfd-JPtxHcuC5otTZzjv4D4JglilVcfp=
ea8haUGWJU6-YBMolV_nZ5LLComxh" linktype=3D"link" target=3D"_blank">READ MOR=
E...</a></span></strong>&nbsp;</p>
</td></tr></table><a name=3D"LETTER.BLOCK23" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"2" id=
=3D"content_LETTER.BLOCK23"><tr><td style=3D"background-color:#D4DDE6;text-=
align: left; font-size: 12pt; font-family: Arial,Helvetica,sans-serif; colo=
r: rgb(0, 0, 102);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=
=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>THE GOVERNOR'S (S=
TEEL TRAP) MIND</strong></p>
</td></tr><tr><td style=3D"font-size: 10pt; font-family: Verdana,Geneva; co=
lor: rgb(0, 0, 0); text-align: left;" rowspan=3D"1" colspan=3D"1" align=3D"=
left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;">The event was the April 2=
9, 2011, keynote address for 2011 Rotary International District 5450 Confer=
ence, and the place</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">was the grand conference =
room at the Embassy Suites & Conference Center in Loveland, Colorado. Gover=
nor John W.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">Hickenlooper had come to =
address the gathering, and he chose to speak at some length about the lesso=
ns learned by word and example from his mother, upon whom the hardships of =
the Great Depression had seared values of thrift, hard work, and cautious i=
nvesting. He had come to speak to Rotarians about attitudes that they would=
 relate to.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp58ZRk4EqqNLK0v2X7b610wiblhENMSe5ZheHbzsgWKp73to5VSSe5UfdsyIZea2XHu=
nvX2pdUgyi__dKGzFLpcdk0kR4TN3j2IF97YFyTgVQXfALhI_8fv40jsH9yOO4VmJLGO8LfKxYH=
7u0RSW2pMC863DIzAODiQ7UHXouKOsZw=3D=3D" linktype=3D"link" target=3D"_blank"=
>READ MORE...</a>&nbsp;</strong></p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
</td></tr></table><a name=3D"LETTER.BLOCK22" /><table style=3D"margin-botto=
m:10px;" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"2" id=
=3D"content_LETTER.BLOCK22"><tr><td style=3D"background-color:#D4DDE6;text-=
align: left; font-size: 12pt; font-family: Arial,Helvetica,sans-serif; colo=
r: rgb(0, 0, 102);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=
=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>NEW ORLEANS CONVE=
NTION HIGHLIGHTS</strong></p>
</td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Ar=
ial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"=
1" align=3D"left">"I will go back to my first Rotary job, which was and sti=
ll is the most important job in all of Rotary: the job of being a Rotarian.=
.. I've had the honor and the satisfaction of being a part of so<br />many =
of Rotary's accomplishments: Future Vision, our strategic plan, and our tra=
nsformation from a valuable -- but sometimes undervalued -- community servi=
ce organization into a key<br />player in the world of international health=
 and development," Futa said. "PolioPlus has put us in this new position." =
John Hewko will replace Futa as the incoming general secretary on July 1, 2=
011.
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp5-CDPkqVkaLoFeYFIQfGSwH5BG-wjanmNk65IdyoeFxi8VIxrYRpiPrHTRQnEsNOyK=
GmqKC8ivjM_ul_nW1mzVWV49hQnlzr0H22ndoqAJow6gjHjTdK2Lu8iBiNRZa7_fG6uMAHOQpp2=
0vRo7syw2l0eFPdGctYrM25oqi6-_rbG6pHx8nFM1y" linktype=3D"link" target=3D"_bl=
ank">READ MORE... </a></strong>&nbsp;</p></td></tr></table><a name=3D"LETTE=
R.BLOCK21" /><table style=3D"margin-bottom:10px;" border=3D"0" width=3D"100=
%" cellspacing=3D"0" cellpadding=3D"2" id=3D"content_LETTER.BLOCK21"><tr><t=
d style=3D"background-color:#D4DDE6;text-align: left; font-size: 12pt; font=
-family: Arial,Helvetica,sans-serif; color: rgb(0, 0, 102);" bgcolor=3D"#D4=
DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>ROTARY "101" AT A=
RVADA MEMBERSHIP NIGHT</strong></p>
</td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Ar=
ial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"=
1" align=3D"left">The Arvada Rotary club held its second "Membership Night"=
 for the Rotary year 2010-11 on Wednesday, May 25 at Indian Tree Golf Cours=
e Restaurant. Speaker Len Brass, a long time Rotarian and Assistant Governo=
r, both in California and Colorado, told the<br />attendees about the Basic=
s of Rotary through his subject matter of Rotary 101. Len included the 4-Wa=
y test, the Object of Rotary, and Service above Self, all Rotary precepts a=
nd tenets, in his talk. The audience, both Rotarians and prospective member=
s of Rotary, listened attentively as he spoke to the fact of how great an o=
rganization we belong to by being Rotarians.<br /><strong><a style=3D"color=
: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"ht=
tp://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&e=3D001=
hFWJSphYp5_SMMOXCCKFC7VGNzQnlRnt8xOzY-L8limOvQSmK7Tdxu_lnZ5YQElcyMTaaGmfeYl=
MoAXEZKbwXGwATq4Va20VbYy8R9e9fy-qTW5_XSC7fKSalEqYILbfLcklDprmuSuknhduS9MDKj=
tOpGs7YdLtWQQQ76hlhRCATJhKjvFMFQ=3D=3D" linktype=3D"link" target=3D"_blank"=
>READ MORE...</a></strong><br /><p style=3D"margin-top: 0px; margin-bottom:=
 0px;">&nbsp;</p></td></tr></table><a name=3D"LETTER.BLOCK20" /><table styl=
e=3D"margin-bottom:10px;" border=3D"0" width=3D"100%" cellspacing=3D"0" cel=
lpadding=3D"2" id=3D"content_LETTER.BLOCK20"><tr><td style=3D"background-co=
lor:#D4DDE6;text-align: left; font-size: 12pt; font-family: Arial,Helvetica=
,sans-serif; color: rgb(0, 0, 102);" bgcolor=3D"#D4DDE6" rowspan=3D"1" cols=
pan=3D"1" align=3D"left">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><strong>TANAKA ELECTED RI=
 PRSIDENT 2012-13</strong></p>
</td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Ar=
ial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"=
1" align=3D"left">Sakuji Tanaka, a member of the Rotary Club of Yashio, Sai=
tama, Japan, was elected president of Rotary International for 2012-13 by d=
elegates during the fourth plenary session at the 2011 RI Convention in New=
 Orleans, Louisiana, USA. Watch a video highlight from the plenary, which i=
ncludes a clip of Tanaka's&nbsp; speech, or download his speech.<br /><stro=
ng><a style=3D"color: blue; text-decoration: underline;" track=3D"on" shape=
=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110=
297&s=3D2914&e=3D001hFWJSphYp5-7kzxLKFsquYpt-_ruoni_XQZAcCrDnbpuYrRroP-yoFs=
3scm8yW6bNUBOsqmJkFQX3UtKjmwRKsvg4Yq3ydaf8_4SxEhJHn4nqeCXN2lLLbW_M0rDGOo0XG=
Il2cHNH5v_8ujxkzy3Bl524xtpSj9FZwr30VHeFwNlTaaw18Hw-xuzDTvD6k-PR00ZDmHoymc=
=3D" linktype=3D"link" target=3D"_blank">READ MORE...</a></strong></td></tr=
></table></td>
    </tr>
    <tr>
      <td style=3D"background-color:#FFFFFF;" bgcolor=3D"#FFFFFF" valign=3D=
"top" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09
=09=09
        </td>
    </tr>
    </table></td>
    <td style=3D"background-color:#D4DDE6;" bgcolor=3D"#D4DDE6" valign=3D"t=
op" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09<table style=3D"width:160px;" border=3D"0" width=3D"160" cellspacing=3D"=
0" cellpadding=3D"0">
    <tr>
      <td valign=3D"top" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=
=3D"left">
=09=09
        <table style=3D"margin-bottom:10px;" border=3D"0" width=3D"100%" ce=
llspacing=3D"0" cellpadding=3D"2">
          <tr>
            <td style=3D"color:#000066;font-family:Verdana,Geneva,Arial,Hel=
vetica,sans-serif;font-size:12pt;background-color:#D4DDE6;" bgcolor=3D"#D4D=
DE6" valign=3D"top" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"lef=
t">
=09=09=09In This Issue
            </td>
          </tr>
          <tr><td style=3D"padding-top:10px;padding-left:3px;padding-right:=
3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK5">DISTR=
ICT GOVERNOR ELECT MESSAGE</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK6">AREA =
10 ROTARY CLUBS ARE ACTIVE!</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK7">HEALT=
H AND HUNGER COMMITTEE</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK8">STRAT=
EGIC PLANNING FOR ROTARY</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK9">1OO Y=
EARS OF ROTARY IN COLORADO</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK10">ERAD=
ICATING POLIO</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK11">THE =
PEACE CORP - ROTARY</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK19">TRF =
CHAIR URGES ROTARY TO SHAKE UP</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK24">ROTA=
RY 3H GRANT 66890 ZIMBABWE</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK23">THE =
GOVERNOR'S (STEEL TRAP) MIND</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK22">NEW =
ORLEANS CONVENTION HIGHLIGHTS</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK21">ROTA=
RY "101" AT ARVADA MEMBERSHIP NIGHT</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK20">TANA=
KA ELECTED RI PRSIDENT 2012-13</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK15">CREA=
TING ROTARY AWARENESS</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK18">Edit=
arian Message</a></b>
=09=09=09  </td></tr>
          </table><table style=3D"margin-bottom:10px;" border=3D"0" width=
=3D"100%" tabindex=3D"0" datapagesize=3D"0" cols=3D"0" cellspacing=3D"0" ce=
llpadding=3D"2" id=3D"content_LETTER.BLOCK13"><tr><td style=3D"text-align: =
center; color: rgb(0, 0, 0); font-family: Verdana,Geneva,Arial,Helvetica,sa=
ns-serif; font-size: 10pt; background-color: rgb(102, 153, 204);" color=3D"=
#ededed" valign=3D"center" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=
=3D"center"><span style=3D"color: rgb(0, 0, 51); font-family: &quot;Tahoma&=
quot;,&quot;Arial&quot;,&quot;Helvetica&quot;,&quot;sans-serif&quot;; font-=
size: 8pt;"><b>RESOURCE LINKS</b></span></td></tr><tr><td style=3D"text-ali=
gn: center; color: rgb(0, 0, 0); padding-top: 10px; padding-right: 3px; pad=
ding-left: 3px; font-family: Verdana,Geneva,Arial,Helvetica,sans-serif; fon=
t-size: 8pt;" color=3D"#285685" width=3D"100%" rowspan=3D"1" colspan=3D"1" =
align=3D"center">
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><a style=3D"color: rgb(51=
, 102, 255); font-family: &quot;Arial Narrow&quot;,&quot;Arial MT Condensed=
 Light&quot;,&quot;sans-serif&quot;; font-size: 8pt;" track=3D"off" shape=
=3D"rect" href=3D"http://www.rotary5450.org/communication/calendar.htm" lin=
ktype=3D"link" target=3D"_blank">IMPORANT DATES</a>&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><a style=3D"color: rgb(51=
, 102, 255); font-family: &quot;Arial Narrow&quot;,&quot;Arial MT Condensed=
 Light&quot;,&quot;sans-serif&quot;; font-size: 8pt;" track=3D"off" shape=
=3D"rect" href=3D"http://www.rotary5450.org/admin/excom.htm" linktype=3D"li=
nk" target=3D"_blank">DISTRICT ORGANIZATION </a>&nbsp;</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;"><a style=3D"color: rgb(51=
, 102, 255); font-family: &quot;Arial Narrow&quot;,&quot;Arial MT Condensed=
 Light&quot;,&quot;sans-serif&quot;; font-size: 8pt;" track=3D"off" shape=
=3D"rect" href=3D"http://www.rotary5450.org/pdf/Abuse-Harrassment%20Policy%=
20D5450%206-08.pdf" linktype=3D"link" target=3D"_blank">ABUSE/HARRASSMENT P=
OLICY</a>&nbsp;</p>
<p style=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quot;=
,&quot;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: 8p=
t; margin-top: 0px; margin-bottom: 0px;"><span style=3D"color: rgb(51, 102,=
 255);">ONLINE</span> <span style=3D"color: rgb(51, 102, 255);">MAKEUP</spa=
n></p>
<p style=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quot;=
,&quot;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: 8p=
t; margin-top: 0px; margin-bottom: 0px;"><span style=3D"color: rgb(51, 102,=
 255);">MEMBERSHIP</span>/<span style=3D"color: rgb(51, 102, 255);">FOUNDAT=
IONREPORT</span></p>
<p style=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quot;=
,&quot;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: 8p=
t; margin-top: 0px; margin-bottom: 0px;"><a track=3D"on" shape=3D"rect" hre=
f=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025110297&s=3D2914&=
e=3D001hFWJSphYp5-9Z01Ph-ERxaM3aXm4gZoVSL_Y9MaoOeamw0I89L50d28MoIM14_uOkhtO=
f3TDLS1yP4ZIMpcqrgv9VLtUIrj3oL1qprvofhk9wLyj6YNyAEpywX8-h1I9WssBS5NtL1I=3D"=
 linktype=3D"link" target=3D"_blank"><span style=3D"color: rgb(51, 102, 255=
);">FULL</span> <span style=3D"color: rgb(51, 102, 255);">NEWSLETTER</span>=
 <span style=3D"color: rgb(51, 102, 255);">PDF</span></a>&nbsp;&nbsp;</p>
<span style=3D"color: rgb(153, 51, 0);"><strong>&nbsp;&nbsp;&nbsp;</strong>=
</span></td></tr></table>
       =20
       =20
=09=09
        <table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" bord=
er=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK15" /><table style=3D"margin-bottom:10=
px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspaci=
ng=3D"0" id=3D"content_LETTER.BLOCK22"><tr><td style=3D"color:#000066;font-=
family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;background-=
color:#D4DDE6;TEXT-ALIGN: left" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=
=3D"1" align=3D"left"><span style=3D"FONT-FAMILY: Calibri, Helvetica, Arial=
, sans-serif; FONT-SIZE: 12pt"><strong>CREATING ROTARY AWARENESS...</strong=
></span></td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helve=
tica, Arial, sans-serif;font-size:12pt;TEXT-ALIGN: left" rowspan=3D"1" cols=
pan=3D"1" align=3D"left">
<div style=3D"FONT-FAMILY: Trebuchet MS, Verdana, Helvetica, sans-serif"><s=
pan style=3D"FONT-FAMILY: Calibri, Helvetica, Arial, sans-serif; FONT-SIZE:=
 12pt"><a style=3D"COLOR: blue; TEXT-DECORATION: underline" track=3D"on" sh=
ape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1106025=
110297&s=3D2914&e=3D001hFWJSphYp5_oxNEKYOFWMDqqoBNCBAbOPFPjQlYg5L1CV99k9g2X=
qIwR2-QiLlozH6dfPm1RBg7XHQfFrBwfagGFZdHWhwJ1zt0PaPaDdPPIrCuJZio195UUeyRWlyE=
1b8oyCrSV6adOD8sqzf9XGiAfAgeZjSGxuspVbZofOoClZ2heqn7wvA=3D=3D" linktype=3D"=
link" target=3D"_blank">IMPORTANT TOOLS</a></span></div></td></tr></table><=
table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0" =
width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK18" /><table style=3D"margin-bottom:10=
px;" aria-posinset=3D"0" id=3D"content_LETTER.BLOCK22" width=3D"100%" aria-=
setsize=3D"0" border=3D"0" tabindex=3D"0" hidefocus=3D"true" cellspacing=3D=
"0" cols=3D"0" cellpadding=3D"2" aria-level=3D"0"  datapagesize=3D"0">
<tr>
<td style=3D"color:#000066;font-family:Verdana,Geneva,Arial,Helvetica,sans-=
serif;font-size:12pt;background-color:#D4DDE6;" bgcolor=3D"#D4DDE6" stylecl=
ass=3D"style_ArticleHeading LeftColHeadBG" rowspan=3D"1" colspan=3D"1" alig=
n=3D"left">EDITARIAN MESSAGE</td></tr>
<tr>
<td style=3D"color:#000000;font-family:Calibri, Helvetica, Arial, sans-seri=
f;font-size:12pt;" styleclass=3D"style_ArticleText" rowspan=3D"1" colspan=
=3D"1" align=3D"left">&nbsp;=20
<div><img vspace=3D"5" border=3D"0" hspace=3D"5"  alt=3D"Spence The Editari=
an" src=3D"http://rotaryview.ssi-rotary.com/D5450/0708/0708-Spence-tmb.jpg"=
 align=3D"left">
<div><font face=3D"Trebuchet MS, Verdana, Helvetica, sans-serif"><font size=
=3D"2">Got a Rotary story to tell? How about an interesting Rotarian in you=
r Rotary Club, others would like to know more about? It's all really easy t=
o do, just email your inspiration to me, I'll help compose the article for =
you and get it in the next issue of YOUR District Newsletter. <br />Don't f=
orget to attach any pictures you have to go along with the article.<br />My=
 email is: mamber888@msn.com&nbsp; Thanks ahead of time! Spencer</font><br =
/></font></div></img></div></td></tr></table></td>
    </tr>
    </table></td>
  </tr>
  </table></td>
</tr>
<tr>
=09<td height=3D"10" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"le=
ft">
=09
=09</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
EOF;

echo quoted_printable_decode($data);
?>
