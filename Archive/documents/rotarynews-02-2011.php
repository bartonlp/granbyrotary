<?php

require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;
$h->title = "Governor's February Newsletter";
$h->banner = "<h1>Governor's February Newsletter</h1>";
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
=3DnM_uFVf8thA&c=3D1&r=3D1" title=3D"Share with Facebook"><img src=3D"http:=
//img.constantcontact.com/ui/images1/shr_drw_fb.png" style=3D"border:0;" />=
</a></td><td><a href=3D"http://s.rs6.net/t?e=3DnM_uFVf8thA&c=3D3&r=3D1" tit=
le=3D"Share with Twitter"><img src=3D"http://img.constantcontact.com/ui/ima=
ges1/shr_drw_twit.png" style=3D"border:0;" /></a></td><td><a href=3D"http:/=
/s.rs6.net/t?e=3DnM_uFVf8thA&c=3D4&r=3D1" title=3D"Share with LinkedIn"><im=
g src=3D"http://img.constantcontact.com/ui/images1/shr_drw_linked.png" styl=
e=3D"border:0;" /></a></td><td><img src=3D"http://img.constantcontact.com/u=
i/images1/shr_drw_divider.png" style=3D"border:0;" /></td><td><a href=3D"ht=
tp://s.rs6.net/t?e=3DnM_uFVf8thA&c=3D5&r=3D1" title=3D"More Share Options">=
<img src=3D"http://img.constantcontact.com/ui/images1/shr_drw_more.png" sty=
le=3D"border:0;" /></a></td><td><img src=3D"http://img.constantcontact.com/=
ui/images1/shr_drw_right.png" style=3D"border:0;" /></td><td class=3D"like-=
this" style=3D"padding-left:5px;"><a href=3D"http://myemail.constantcontact=
.com/FEBRUAR-2011-News-Rotary-District-5450.html?soid=3D1102180066109&aid=
=3DnM_uFVf8thA#fblike" title=3D"Like This"><img src=3D"http://img.constantc=
ontact.com/ui/images1/shr_btn_like_sm.png" style=3D"border:0;" /></a></td><=
/tr></table></td></tr></table></td></tr></table></div>
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
bindex=3D"0" cellpadding=3D"5" cellspacing=3D"0" id=3D"content_LETTER.BLOCK=
3"><tr><td style=3D"text-align: left; color: rgb(0, 0, 0); font-family: Ver=
dana, Geneva, Arial, Helvetica, sans-serif; font-size: 8pt;" color=3D"#2856=
85" rowspan=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D"font-fa=
mily: Trebuchet MS; font-size: 12pt;" size=3D"3" face=3D"Trebuchet MS"><str=
ong>FEBRUARY 2011 NEWSLETTER</strong></span>&nbsp;</div><div style=3D"color=
: rgb(0, 0, 255); font-size: 10pt;"><strong>A MESSAGE FROM DISTRICT GOVERNO=
R KAREN</strong></div> <div style=3D"color: rgb(0, 0, 0);"><span style=3D"f=
ont-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;">Dear R=
otarians,</span></div> <div style=3D"color: rgb(0, 0, 0);"><span style=3D"f=
ont-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;">Februa=
ry is World Understanding Month and a perfect opportunity to look at our Fo=
urth Avenue of Service.<br />International Service encompasses efforts to e=
xpand Rotary's humanitarian reach around the world and to<br />promote worl=
d understanding and peace. It includes everything from contributing to Poli=
oPlus to helping Rotary<br />Youth Exchange students adjust to their host c=
ountries.</span></div><br />&nbsp;<a style=3D"color: blue; font-size: 10pt;=
 text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"http://r2=
0.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5A=
uhvESY2_XBBiSjiSUqScbfr8C_MhMRMmgL4LctB0CPazP0CGMtz_O0uEv4VTjvre6tpdEuMI3sd=
HxAI81go5K5iOC14Bri1DV5LeY_GTZ2SF439-B6bS0pS_jxNHWctr6y5oyNpV7mUx020VzU-lgl=
9n5" linktype=3D"link" target=3D"_blank">MORE...</a></td></tr></table><tabl=
e style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0" widt=
h=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK5" /><table style=3D"margin-bottom:10p=
x;" border=3D"0" width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacin=
g=3D"0" id=3D"content_LETTER.BLOCK7"><tr><td style=3D"color:#000066;font-fa=
mily:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;background-co=
lor:#D4DDE6;text-align: left; background-color: rgb(212, 221, 230);" bgcolo=
r=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left"><span style=3D"col=
or: rgb(51, 51, 153);"><strong>HELP TELL THE STORY</strong></span></td></tr=
><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, Arial, sans=
-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D"1" align=
=3D"left"> <div> <div> <div> <div><span style=3D"color: rgb(51, 51, 51); fo=
nt-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"> <div><=
span style=3D"color: rgb(51, 51, 51); font-family: Calibri, Helvetica, Aria=
l, sans-serif; font-size: 12pt;">The Public Relations and Communications co=
mmittees of District 5450 want to gather articles and stories from clubs in=
 the District that can be published in our newsletter, on our website, and =
perhaps elsewhere. Each of the sixty-eight clubs in our District has storie=
s about club projects and activities that will make good reading for our va=
rious audiences.</span>&nbsp;</div> <div><span style=3D"color: rgb(51, 51, =
51); font-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;">=
&nbsp;</span></div></span></div></div></div></div><a style=3D"color: blue; =
text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"http://r20=
.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5Au=
hvGNIUdMYcm0b3cb-t4B2zmFnIdXKoubl16eQglfvXf_YkpwbCFE6WrmXgL6U58ANDTBv4vH3Pw=
PqL1tskhr0m6q8rAigeAMKaepUpWljr8AFl2yDha3hGAbipQS0TlOPWepa5Xca9jKLnozeYOvIE=
rOjKgfOrO2BwY=3D" linktype=3D"link" target=3D"_blank">MORE...</a></td></tr>=
</table><a name=3D"LETTER.BLOCK6" /><table style=3D"margin-bottom:10px;" bo=
rder=3D"0" width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacing=3D"0=
" id=3D"content_LETTER.BLOCK8"><tr><td style=3D"color:#000066;font-family:V=
erdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;background-color:#D=
4DDE6;text-align: left; background-color: rgb(212, 221, 230);" bgcolor=3D"#=
D4DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left"><span style=3D"color: rg=
b(0, 0, 128); font-family: Arial, Helvetica, sans-serif;"><strong>MARCH IS =
LITERACY MONTH</strong></span></td></tr><tr><td style=3D"color:#000000;font=
-family:Calibri, Helvetica, Arial, sans-serif;font-size:12pt;text-align: le=
ft;" rowspan=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D"color:=
 rgb(51, 51, 51); font-family: Calibri, Helvetica, Arial, sans-serif; font-=
size: 12pt;"> <div> <p style=3D"margin-top: 0px; margin-bottom: 0px;"><span=
 style=3D"color: rgb(51, 51, 51);">Please consider having a Literacy Progra=
m or Speaker<br />for your club meetings during the month of March<br />NOT=
E: The Literacy Committee is available to do presentations for individual c=
lubs. For club presentations, please<br />contact Hal Kuczwara, 303-421-574=
7 (<a shape=3D"rect" href=3D"mailto:hal@funlosophy.com" target=3D"_blank">h=
al@funlosophy.com</a></span></p></div></span></div></td></tr></table><a nam=
e=3D"LETTER.BLOCK7" /><table style=3D"margin-bottom:10px;" border=3D"0" wid=
th=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacing=3D"0" id=3D"conten=
t_LETTER.BLOCK27"><tr><td style=3D"color:#000066;font-family:Verdana,Geneva=
,Arial,Helvetica,sans-serif;font-size:12pt;background-color:#D4DDE6;text-al=
ign: left; background-color: rgb(212, 221, 230);" bgcolor=3D"#D4DDE6" rowsp=
an=3D"1" colspan=3D"1" align=3D"left"><span style=3D"color: rgb(0, 0, 128);=
 font-family: Arial, Helvetica, sans-serif;"><strong>LOOKING FOR NOMINATION=
S</strong></span></td></tr><tr><td style=3D"color:#000000;font-family:Calib=
ri, Helvetica, Arial, sans-serif;font-size:12pt;text-align: left;" rowspan=
=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D"color: rgb(51, 51,=
 51); font-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"=
> <div> <p style=3D"margin-top: 0px; margin-bottom: 0px;"><span style=3D"co=
lor: rgb(51, 51, 51);">The District 5450 Rotary World Peace Programs Subcom=
mittee is now taking nominations for Rotary International's World Peace Fel=
lowship Scholarship Program. It is also time for Ambassadorial Scholarship =
Applications. This program sponsors academic scholarships for undergraduate=
 and graduate students as well as for qualified professionals pursuing voca=
tional studies. For more information please contact Rotarian Harriet Downer=
 at <a shape=3D"rect" href=3D"mailto:hsdowner@logicalconnections.org" targe=
t=3D"_blank">hsdowner@logicalconnections.org</a><br /><br />These are two e=
xcellent opportunities for young people who care about the world! More info=
rmation can be found at <a shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp=
?llr=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvGMTTO8xE0EcY4=
rPAlP7pX9NpE_-QcA0pPF_YNnEf7iW00yMn72Vwdhx61aDTVXoBe972CG3Ykp4Bohf5v_pDygst=
pLXFTNw6Y=3D" target=3D"_blank">www.rotary.org</a></span></p></div><div>&nb=
sp;&nbsp;</div></span></div></td></tr></table><a name=3D"LETTER.BLOCK8" /><=
table style=3D"margin-bottom:10px;" border=3D"0" width=3D"100%" tabindex=3D=
"0" cellpadding=3D"2" cellspacing=3D"0" id=3D"content_LETTER.BLOCK9"><tr><t=
d style=3D"color:#000066;font-family:Verdana,Geneva,Arial,Helvetica,sans-se=
rif;font-size:12pt;background-color:#D4DDE6;text-align: left; background-co=
lor: rgb(212, 221, 230);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" a=
lign=3D"left"><span style=3D"color: rgb(0, 0, 128); font-family: Arial, Hel=
vetica, sans-serif;"><strong>ROTARY INTERNATIONAL FELLOWSHIP</strong></span=
></td></tr><tr><td style=3D"color:#000000;font-family:Calibri, Helvetica, A=
rial, sans-serif;font-size:12pt;text-align: left;" rowspan=3D"1" colspan=3D=
"1" align=3D"left"> <div><span style=3D"color: rgb(51, 51, 51); font-family=
: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"> <div><span styl=
e=3D"color: rgb(51, 51, 51); font-family: Calibri, Helvetica, Arial, sans-s=
erif; font-size: 12pt;"> <div><span style=3D"color: rgb(51, 51, 51); font-f=
amily: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"> <div>Learn=
 more about the many Rotary Fellowships! They are fun.</div></span></div></=
span></div></span></div><a style=3D"color: blue; text-decoration: underline=
;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9=
qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvHgBxk0uEb2KwqXYVYoho-_Lw=
x4L2LEI3cEOOgYhUjr-f9nmCY7HTPIyBSBd72R71HdrJDdtHH0guH_7f-5UbxC3OuD5OrlNLwjN=
lrcn4r2ccmh5f6AibzzQj_OGckBPIwpS3cKvV1E-WEAcByURRoGdZB5ZAwhL1BuOPgRGtou7A=
=3D=3D" linktype=3D"link" target=3D"_blank">MORE...</a></td></tr></table><a=
 name=3D"LETTER.BLOCK9" /><table style=3D"margin-bottom:10px;" border=3D"0"=
 width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacing=3D"0" id=3D"co=
ntent_LETTER.BLOCK10"><tr><td style=3D"color:#000066;font-family:Verdana,Ge=
neva,Arial,Helvetica,sans-serif;font-size:12pt;background-color:#D4DDE6;tex=
t-align: left; background-color: rgb(212, 221, 230);" bgcolor=3D"#D4DDE6" r=
owspan=3D"1" colspan=3D"1" align=3D"left"><span style=3D"color: rgb(0, 0, 1=
28); font-family: Arial, Helvetica, sans-serif;"><strong>SOME THOUGHTS ON M=
ADISON</strong></span></td></tr><tr><td style=3D"color:#000000;font-family:=
Calibri, Helvetica, Arial, sans-serif;font-size:12pt;text-align: left;" row=
span=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D"color: rgb(51,=
 51, 51); font-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12=
pt;"> <div><span style=3D"color: rgb(51, 51, 51); font-family: Calibri, Hel=
vetica, Arial, sans-serif; font-size: 12pt;"> <p style=3D"margin-top: 0px; =
margin-bottom: 0px;">Madison was a sophomore at the University of Northern =
Colorado in Greely, Colorado. Madision Middleton was killed in an auto accc=
ident 19/Dec/2010, at 6:30 am. She was on her way back to her home in Brigh=
ton, Colorado, to be with her family; Becky Middlton, her mother; Tom Middl=
eton, her father and Austin Middleton, her younger brother. The tradgedy oc=
cured about 5 miles North of Fort Lupton, Colroado.</p>
<p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p>
</span></div></span></div><a style=3D"color: blue; text-decoration: underli=
ne;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6z=
i9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvFiAK5lMohvs8r1AsqrlFYz=
qtr6-vUf_urIlNVCwvTa8KuSqH-QUAZioZhbZsefrS3lroex-kxagfgp48DN40HX73zUn30Pbyh=
fuMypmeGqSUrXVvw22TFU5Un-ypdVz4AsXzy1DWcZLg31K78DV1r1dxQvlapS1fQ=3D" linkty=
pe=3D"link" target=3D"_blank">MORE...</a></td></tr></table><a name=3D"LETTE=
R.BLOCK10" /><table style=3D"margin-bottom:10px;display: table;" border=3D"=
0" width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacing=3D"0" id=3D"=
content_LETTER.BLOCK23"><tr><td style=3D"color:#000066;font-family:Verdana,=
Geneva,Arial,Helvetica,sans-serif;font-size:12pt;background-color:#D4DDE6;t=
ext-align: left; background-color: rgb(212, 221, 230);" bgcolor=3D"#D4DDE6"=
 rowspan=3D"1" colspan=3D"1" align=3D"left"><span style=3D"color: rgb(0, 0,=
 128); font-family: Arial, Helvetica, sans-serif;"><strong>TEMPLE BUELL SCH=
OLAR MAKES THE ROUND</strong></span></td></tr><tr><td style=3D"color:#00000=
0;font-family:Calibri, Helvetica, Arial, sans-serif;font-size:12pt;text-ali=
gn: left;" rowspan=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D"=
color: rgb(51, 51, 51); font-family: Calibri, Helvetica, Arial, sans-serif;=
 font-size: 12pt;"> <div><span style=3D"color: rgb(51, 51, 51); font-family=
: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"> <p style=3D"mar=
gin-top: 0px; margin-bottom: 0px;">On January 21, Abby Stangl, a Temple Bue=
ll Ambassadorial Scholar sponsored by our district spoke at the Rarotonga<b=
r />Rotary Club, New Zealand. Below is an excerpt from that club's bulletin=
 regarding her talk. Abby will be back in Boulder<br />on Feb. 1, and may b=
e joining the new Rotaract Club at its initial meeting the following day. <=
/p> <p style=3D"margin-top: 0px; margin-bottom: 0px;">&nbsp;</p> </span></d=
iv></span></div><a style=3D"color: blue; text-decoration: underline;" track=
=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=
=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvGCxDyYmkxW9Effm4OIqEySDbVvPGOhyQ=
Q2Qhh5xD5NVvgAsDH_tf1wybmPBjCeAmttK9rBFthC0jPbPSqJxknhElsrV5wBO8EWCaz-EQgzX=
XvTg7JLvdWESYNhmna5_RPLJWoHNeDzIpwi3AVx5e13fc2IUmXWaianN2vFQF9qjV0oWnliKpOk=
" linktype=3D"link" target=3D"_blank">MORE...</a></td></tr></table><a name=
=3D"LETTER.BLOCK11" /><table style=3D"margin-bottom:10px;" border=3D"0" wid=
th=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspacing=3D"0" id=3D"conten=
t_LETTER.BLOCK24"><tr><td style=3D"color:#000066;font-family:Verdana,Geneva=
,Arial,Helvetica,sans-serif;font-size:12pt;background-color:#D4DDE6;text-al=
ign: left; background-color: rgb(212, 221, 230);" bgcolor=3D"#D4DDE6" rowsp=
an=3D"1" colspan=3D"1" align=3D"left"><span style=3D"color: rgb(0, 0, 128);=
 font-family: Arial, Helvetica, sans-serif;"><strong>WORLD UNDERSTANDING AN=
D 4-2AY TEST: A LINK?</strong></span></td></tr><tr><td style=3D"color:#0000=
00;font-family:Calibri, Helvetica, Arial, sans-serif;font-size:12pt;text-al=
ign: left;" rowspan=3D"1" colspan=3D"1" align=3D"left"> <div><span style=3D=
"color: rgb(51, 51, 51); font-family: Calibri, Helvetica, Arial, sans-serif=
; font-size: 12pt;"> <div><span style=3D"color: rgb(51, 51, 51); font-famil=
y: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"> <p style=3D"ma=
rgin-top: 0px; margin-bottom: 0px;">Conflict between people and nations has=
 been a part of the human condition since recorded history began. Will this=
 ever change? I can't answer that question! But I do believe that working t=
o improve how we relate to each other, no matter how difficult the struggle=
, is not only a worthy activity, but it<br />is essential if we are to prog=
ress as a world society and find our way to peace.</p> <p style=3D"margin-t=
op: 0px; margin-bottom: 0px;">&nbsp;</p> </span></div></span></div><a style=
=3D"color: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" h=
ref=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D291=
4&e=3D001V6gZV5AuhvEGXhKUORqntgC2gnBqIgTTz21PZ1MfO9-_IoGG--byO6X5yCGeLvOqUP=
dhW3VV1zPoxlpBIrhs-IzNABWgWLMhgbVbrmsc2Ym02tgDHn0xrziCuY6ntbH_OaG2AzgH4SXEO=
w-1h7QzWdSJo1YoAeQDG00WmWn9es-2GOtk_AmZlnRQpjZwxGLr" linktype=3D"link" targ=
et=3D"_blank">MORE...</a></td></tr></table></td>
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
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK5">HELP =
TELL THE STORY</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK6">MARCH=
 IS LITERACY MONTH</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK7">LOOKI=
NG FOR NOMINATIONS</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK8">ROTAR=
Y INTERNATIONAL FELLOWSHIP</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK9">SOME =
THOUGHTS ON MADISON</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK10">TEMP=
LE BUELL SCHOLAR MAKES ROUNDS</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK21">TOP =
10 REASONS TO GO TO RI CONVENTION</a></b>
=09=09=09  </td></tr><tr><td style=3D"padding-top:10px;padding-left:3px;pad=
ding-right:3px;" width=3D"100%" rowspan=3D"1" colspan=3D"1" align=3D"left">
=09=09=09  <b><a style=3D"color:#000099;font-family:Calibri, Helvetica, Ari=
al, sans-serif;font-size:8pt;" shape=3D"rect" href=3D"#LETTER.BLOCK25">Edit=
arian Message</a></b>
=09=09=09  </td></tr>
          </table><table style=3D"margin-bottom:10px;" border=3D"0" width=
=3D"100%" tabindex=3D"0" datapagesize=3D"0" cols=3D"0" cellpadding=3D"2" ce=
llspacing=3D"0" id=3D"content_LETTER.BLOCK12"> <tr> <td style=3D"text-align=
: center; color: rgb(0, 0, 0); font-family: Verdana,Geneva,Arial,Helvetica,=
sans-serif; font-size: 10pt; background-color: rgb(102, 153, 204);" color=
=3D"#ededed" valign=3D"center" width=3D"100%" rowspan=3D"1" colspan=3D"1" a=
lign=3D"center"><span style=3D"color: rgb(0, 0, 51); font-family: &quot;Tah=
oma&quot;,&quot;Arial&quot;,&quot;Helvetica&quot;,&quot;sans-serif&quot;; f=
ont-size: 8pt;"><b>RESOURCE LINKS</b></span></td></tr> <tr> <td style=3D"te=
xt-align: center; color: rgb(0, 0, 0); padding-top: 10px; padding-right: 3p=
x; padding-left: 3px; font-family: Verdana,Geneva,Arial,Helvetica,sans-seri=
f; font-size: 8pt;" color=3D"#285685" width=3D"100%" rowspan=3D"1" colspan=
=3D"1" align=3D"center"> <p style=3D"margin-top: 0px; margin-bottom: 0px;">=
<a style=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quot;=
,&quot;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: 8p=
t;" track=3D"off" shape=3D"rect" href=3D"http://www.rotary5450.org/communic=
ation/calendar.htm" linktype=3D"link" target=3D"_blank">IMPORANT DATES</a> =
</p><p style=3D"margin-top: 0px; margin-bottom: 0px;"> <a style=3D"color: r=
gb(51, 102, 255); font-family: &quot;Arial Narrow&quot;,&quot;Arial MT Cond=
ensed Light&quot;,&quot;sans-serif&quot;; font-size: 8pt;" track=3D"off" sh=
ape=3D"rect" href=3D"http://www.rotary5450.org/admin/excom.htm" linktype=3D=
"link" target=3D"_blank">DISTRICT ORGANIZATION </a> </p><p style=3D"margin-=
top: 0px; margin-bottom: 0px;"> <a style=3D"color: rgb(51, 102, 255); font-=
family: &quot;Arial Narrow&quot;,&quot;Arial MT Condensed Light&quot;,&quot=
;sans-serif&quot;; font-size: 8pt;" track=3D"off" shape=3D"rect" href=3D"ht=
tp://www.rotary5450.org/pdf/Abuse-Harrassment%20Policy%20D5450%206-08.pdf" =
linktype=3D"link" target=3D"_blank">ABUSE/HARRASSMENT POLICY</a> </p><p sty=
le=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quot;,&quot=
;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: 8pt; mar=
gin-top: 0px; margin-bottom: 0px;"> <span style=3D"color: rgb(51, 102, 255)=
;">ONLINE</span> <span style=3D"color: rgb(51, 102, 255);">MAKEUP</span> </=
p><p style=3D"color: rgb(51, 102, 255); font-family: &quot;Arial Narrow&quo=
t;,&quot;Arial MT Condensed Light&quot;,&quot;sans-serif&quot;; font-size: =
8pt; margin-top: 0px; margin-bottom: 0px;"> <span style=3D"color: rgb(51, 1=
02, 255);">MEMBERSHIP</span>/<span style=3D"color: rgb(51, 102, 255);">FOUN=
DATIONREPORT</span> </p><p style=3D"color: rgb(51, 102, 255); font-family: =
&quot;Arial Narrow&quot;,&quot;Arial MT Condensed Light&quot;,&quot;sans-se=
rif&quot;; font-size: 8pt; margin-top: 0px; margin-bottom: 0px;"> <a track=
=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=
=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvHcbIFhMMB0t74MW-p4WgXm3C7oWRE3Yx=
EP-GE2M8vlhs9XSvPke372mIbfCD8gtZrIdP0MWKVYeqk62m0lWQthZIKqrS2187K4EJub0f5hx=
M7hs11noyApAYkux2zxbrk=3D" linktype=3D"link" target=3D"_blank"><span style=
=3D"color: rgb(51, 102, 255);">FULL</span> <span style=3D"color: rgb(51, 10=
2, 255);">NEWSLETTER</span> <span style=3D"color: rgb(51, 102, 255);">PDF</=
span></a><br />&nbsp;</p><span style=3D"color: rgb(153, 51, 0);"><strong><a=
 style=3D"text-decoration: underline;" track=3D"on" shape=3D"rect" href=3D"=
http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D0=
01V6gZV5AuhvHcVSlxBcnoyS90KYs73bSyC45iB05v18y_TOemf4bG1H53mZomCsK0rnmwmOgCr=
2doHB1XCFhQLroGa3o1mPIbzTIg4bEtuKL9bwTv9wuIeejTL3Fo1-Zam_TuCqXP28sVMka-mJT9=
p39bYNSw8LyRdzODIRdv47g=3D" linktype=3D"link" target=3D"_blank">REGISTER FO=
R DISTRICT CONFERENCE</a></strong></span></td></tr></table>
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
ng=3D"0" id=3D"content_LETTER.BLOCK14"><tr style=3D"TEXT-ALIGN: center" ali=
gn=3D"middle"><td style=3D"color:#000066;font-family:Verdana,Geneva,Arial,H=
elvetica,sans-serif;font-size:12pt;background-color:#D4DDE6;TEXT-ALIGN: lef=
t" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left">
<p style=3D"TEXT-ALIGN: center; MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px" align=
=3D"center"><span style=3D"FONT-FAMILY: Calibri, Helvetica, Arial, sans-ser=
if; FONT-SIZE: 12pt"><strong>2010-2011 Presidential Citation Form for Clubs=
</strong></span></p></td></tr><tr><td style=3D"color:#000000;font-family:Ca=
libri, Helvetica, Arial, sans-serif;font-size:12pt;TEXT-ALIGN: center" rows=
pan=3D"1" colspan=3D"1" align=3D"center">
<div style=3D"FONT-FAMILY: Trebuchet MS, Verdana, Helvetica, sans-serif"><s=
pan style=3D"FONT-FAMILY: Calibri, Helvetica, Arial, sans-serif; FONT-SIZE:=
 12pt"><a style=3D"COLOR: blue; TEXT-DECORATION: underline" track=3D"on" sh=
ape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450=
242794&s=3D2914&e=3D001V6gZV5AuhvFA6_8CPi5LpghetMphEHyUwZeUVLNFpTZibkxcMc02=
MQVmYPv7Pb0oN_IlukCMxZu248i6YGUsbxSpgEOz8fW5IiMVOit3R4ivCXqbV7Osqlne4spz6m3=
_LmD0NBv6--MLMPOMLmVM8GXmCqfb511EKl6CWkbD8_GREZ6pnnS9ttVNbQRqrgoG" linktype=
=3D"link" target=3D"_blank">DOWNLOAD FORM</a></span></div></td></tr></table=
><table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0=
" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK17" /><table style=3D"margin-bottom:10=
px;" border=3D"0" width=3D"100%" tabindex=3D"0" cellpadding=3D"2" cellspaci=
ng=3D"0" id=3D"content_LETTER.BLOCK28"><tr><td style=3D"color:#000066;font-=
family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12pt;background-=
color:#D4DDE6;text-align: left; background-color: rgb(212, 221, 230);" bgco=
lor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left"><span style=3D"f=
ont-family: Calibri, Helvetica, Arial, sans-serif; font-size: 12pt;"><stron=
g>CONTAINER FULL OF CRUTCHES HEADED FOR AFRICA - Pictures worth 1000 words.=
..</strong></span></td></tr><tr><td style=3D"color:#000000;font-family:Cali=
bri, Helvetica, Arial, sans-serif;font-size:12pt;text-align: left;" rowspan=
=3D"1" colspan=3D"1" align=3D"left"> <div style=3D"font-family: Trebuchet M=
S, Verdana, Helvetica, sans-serif;"><span style=3D"font-family: Calibri, He=
lvetica, Arial, sans-serif; font-size: 12pt;">&nbsp;</span></div><a style=
=3D"color: blue; text-decoration: underline;" track=3D"on" shape=3D"rect" h=
ref=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D291=
4&e=3D001V6gZV5AuhvHpSufuwaJyIdzVuvyizxdKkrvu__5N3ZSddu37ftaKopymdF1ep0UXwb=
YORZLHZLJjIo8xVJN7o8uUWxs5lNEhaSeL5EoKi67dxOv5jFBnVTPv3ghaRlx5Zli-fkKBiq0vs=
2Jr_r2sL_P3dljj67jKPIXPfWC0OdI0r-Aed6OymQ=3D=3D" linktype=3D"link" target=
=3D"_blank">VIEW PHOTOS</a></td></tr></table><table style=3D"background-col=
or:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0" width=3D"100%" cellspacing=3D=
"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK19" /><table style=3D"margin-bottom:10=
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
ape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=3D6zi9qpcab&et=3D1104450=
242794&s=3D2914&e=3D001V6gZV5AuhvElyDVS5L2g29hlCsOHwM6T25U87bnayF6fv5c4P8Ji=
k7auT5abZAsTaGGnMljt6NQWxdqBAD7EWLzxB5hSSv_cu5Uk3wWIqJB_q4EaABQ7kXatVqnVBNl=
ey3AdflWLkqcS-Zh9hTJ2lNoEHxBDbTkWMhg9Ew6uWwI4wGWVpUdw2g=3D=3D" linktype=3D"=
link" target=3D"_blank">IMPORTANT TOOLS</a></span></div></td></tr></table><=
table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0" =
width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK21" /><table style=3D"margin-bottom:10=
px;display: table;" border=3D"0" width=3D"100%" tabindex=3D"0" cellpadding=
=3D"2" cellspacing=3D"0" id=3D"content_LETTER.BLOCK16"><tr><td style=3D"col=
or:#000066;font-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:=
12pt;background-color:#D4DDE6;text-align: left; background-color: rgb(212, =
221, 230);" bgcolor=3D"#D4DDE6" rowspan=3D"1" colspan=3D"1" align=3D"left">=
<span style=3D"font-family: &quot;Helvetica&quot;, &quot; Calibri&quot;, &q=
uot; Arial&quot;, &quot; sans-serif&quot;; font-size: 10pt;"><strong>REASON=
 #5 TO GO TO ROTARY INTERNATIONAL CONVENTION IN NEW ORLEANS MAY 21-25, 2011=
</strong></span></td></tr><tr><td style=3D"color:#000000;font-family:Calibr=
i, Helvetica, Arial, sans-serif;font-size:12pt;text-align: left;" rowspan=
=3D"1" colspan=3D"1" align=3D"left"> <div style=3D"font-family: Trebuchet M=
S, Verdana, Helvetica, sans-serif;"><span style=3D"font-size: 10pt;"><stron=
g>&nbsp;</strong></span></div><a style=3D"color: blue; text-decoration: und=
erline;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.jsp?llr=
=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvEH40qURDxaXBjHhHS=
GggESGTloPfWRbyormsV8lvGNT7cR3UW3_ge5sxbEVrjehqUPWwwrEq1Fflz8s99yVN_mgZH0TH=
TeQC40NpQblGnw7AvN6r8v9mWCYA_nQMYjnoJfmLiXdTBHnCywG1WFIs0REmLV0vargn09chuXX=
JcKsJ3YeOdgYWqk" linktype=3D"link" target=3D"_blank">READ FULL STORY</a></t=
d></tr></table><table style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCC=
C" border=3D"0" width=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><table style=3D"margin-bottom:10px;" aria-posinset=3D"0" id=
=3D"content_LETTER.BLOCK14" width=3D"100%" aria-setsize=3D"0" border=3D"0" =
hidefocus=3D"true" tabindex=3D"0" cellspacing=3D"0" cols=3D"0" cellpadding=
=3D"10" aria-level=3D"0"  datapagesize=3D"0"><tr><td style=3D"color:#000000=
;font-family:Calibri, Helvetica, Arial, sans-serif;font-size:12pt;" stylecl=
ass=3D"style_MainText" rowspan=3D"1" colspan=3D"1" align=3D"left"><div><str=
ong><font size=3D"2"><span style=3D"font-family: Trebuchet MS,Verdana,Helve=
tica,sans-serif;">Abuse & Harassment Protection Officer &nbsp;- Status Repo=
rt and Training</span></font>
<div><font size=3D"2"><a style=3D"font-family: Trebuchet MS,Verdana,Helveti=
ca,sans-serif;" track=3D"on" shape=3D"rect" href=3D"http://r20.rs6.net/tn.j=
sp?llr=3D6zi9qpcab&et=3D1104450242794&s=3D2914&e=3D001V6gZV5AuhvFYkSGfShZuh=
3ejvfsu8EbZhjehwDVwXgtQkE9xvRz9dzVTvKxBU5ELK0cAj8EXJkGJCWo-7-oMDYdJLV9pLrKA=
ag4ZyaygNEgG14jEMHEsXmQ25B9PhBTGptnO2A8tBhFJMMt0N5Afe-PSjDrAlpWu6t7a9mcBlJ4=
tpN3JtthCO5GSlcStDXnK22dQTU5_DXk=3D" linktype=3D"link" target=3D"_blank">VI=
EW INFORMATION</a></font>&nbsp;</div></strong></div></td></tr></table><tabl=
e style=3D"background-color:#CCCCCC;" bgcolor=3D"#CCCCCC" border=3D"0" widt=
h=3D"100%" cellspacing=3D"0" cellpadding=3D"0">
  =09=09<tr>
    =09=09<td height=3D"6" rowspan=3D"1" colspan=3D"1" align=3D"left" />
  =09=09</tr>
=09=09</table><a name=3D"LETTER.BLOCK25" /><table style=3D"margin-bottom:10=
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
