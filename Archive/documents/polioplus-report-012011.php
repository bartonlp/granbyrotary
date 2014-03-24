<?php
require_once("/home/bartonlp/includes/granbyrotary.conf");

$S = new GranbyRotary;
$h->banner = "<h1>Polio Plus Report for January 2011</h1>";
list($top, $footer) = $S->getPageTopBottom($h);

echo <<<EOF
$top
<p>This is a spread sheet from the Polio Plus committee. It shows the standing of each club in the district.
Our club (Rotary Club of Granby) is highlighted in <span style="color: green">GREEN</span>. We are one of the only 25 clubs
out of the 65 shown that have met their giving goal. That's pretty good for a small mountain club.</p>
<div>
<a name="0.1_00000001"></a>
<div align="left">
<table border="2" cellspacing="0" width="100%">
<tr valign="bottom"><th bgcolor="#B0B0B0" width="22"> </th>
  <th align="center" bgcolor="#B0B0B0" width="48"><b>A</b></th>
  <th align="center" bgcolor="#B0B0B0" width="218"><b>B</b></th>
  <th align="center" bgcolor="#B0B0B0" width="73"><b>C</b></th>
  <th align="center" bgcolor="#B0B0B0" width="93"><b>D</b></th>
  <th align="center" bgcolor="#B0B0B0" width="120"><b>E</b></th>
  <th align="center" bgcolor="#B0B0B0" width="106"><b>F</b></th>
  <th align="center" bgcolor="#B0B0B0" width="127"><b>G</b></th>
  <th align="center" bgcolor="#B0B0B0" width="87"><b>H</b></th>
  <th align="center" bgcolor="#B0B0B0" width="73"><b>J</b></th></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>1</b></th>
  <td width="39"> </td>
  <th align="center" width="175"><font size="2" face="Tahoma"><b>Club Name</b></font></td>
  <td align="center" width="59"><font size="2" face="Tahoma"><b>#  
  Members</b></font></td>
  <td align="center" width="75"><font size="2" face="Tahoma"><b>Goal/Member</b></font></td>
  <td align="center" width="96"><font size="2" face="Tahoma"><b>Giving</b></font></td>
  <td align="center" width="85"><font size="2" face="Tahoma"><b>Giving</b></font></td>
  <td align="center" width="102"><font size="2" face="Tahoma"><b>Total 
  Balance</b></font></td>
  <td align="center" valign="middle" width="70" rowspan="2"><font size="2" face="Tahoma"><b>Gap 
  to the Goal</b></font></td>
  <td align="center" valign="middle" bgcolor="#FFFF99" width="59" rowspan="2"><font size="2" face="Tahoma"><b>Giving as % of the Goal</b></font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>2</b></th>
  <td width="39"> </td>
  <td width="175"> </td>
  <td width="59"> </td>
  <td align="center" width="75"><font size="2" face="Tahoma"><b>$ 175</b></font></td>
  <td align="center" width="96"><font size="2" face="Tahoma"><b>&#39;07-&#39;10 
  FYE</b></font></td>
  <td align="center" width="85"><font size="2" face="Tahoma"><b>&#39;10-&#39;11 
  YTD</b></font></td>
  <td align="center" width="102"><font size="2" face="Tahoma"><b>YTD from 
  &#39;07 </b></font></td>
  <td width="9"> </td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>
  3</b></th>
  <td width="39"> </td>
  <td width="175"> </td>
  <td width="59"> </td>
  <td width="75"> </td>
  <td width="96"> </td>
  <td width="85"> </td>
  <td width="102"> </td>
  <td width="70"> </td>
  <td bgcolor="#FFFF99" width="59"> </td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>4</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">5450</font></td>
  <td width="175"><font size="2" face="Tahoma">District</font></td>
  <td width="59"> </td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,664.85</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,010.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,674.85</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 4,674.85</font></td>
  <td bgcolor="#FFFF99" width="59"> </td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>5</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1089</font></td>
  <td width="175"><font size="2" face="Tahoma">Arvada</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">30</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,250.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 13,042.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 3,255.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 16,297.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 11,047.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">310%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>6</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">27920</font></td>
  <td width="175"><font size="2" face="Tahoma">Arvada Sunrise</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">36</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,300.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,135.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 3,000.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 7,135.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 835.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">113%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>7</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1090 </font></td>
  <td width="175"><font size="2" face="Tahoma">Aurora</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">88</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 15,400.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 11,145.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 11,145.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (4,255.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">72%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>8</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">64714</font></td>
  <td width="175"><font size="2" face="Tahoma">Aurora Fitzsimons</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">21</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 3,675.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,544.16</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 264.32</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,808.48</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 133.48</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">104%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>9</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">50290</font></td>
  <td width="175"><font size="2" face="Tahoma">Aurora Gateway</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">33</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,775.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 530.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 2,965.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,495.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,280.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">61%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>10</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1091 </font></td>
  <td width="175"><font size="2" face="Tahoma">Boulder</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">266</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 46,550.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 31,108.99</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 18,577.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 49,685.99</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 3,135.99</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">107%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>11</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">50833</font></td>
  <td width="175"><font size="2" face="Tahoma">Boulder Flatirons</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">34</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,950.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,060.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,060.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,890.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">68%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>12</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">22628</font></td>
  <td width="175"><font size="2" face="Tahoma">Boulder Valley</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">66</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 11,550.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,060.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,060.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (6,490.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">44%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>13</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">78591</font></td>
  <td width="175"><font size="2" face="Tahoma">Breckenridge-Mountain</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">31</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,425.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,200.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,200.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,225.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">77%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>14</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1092 </font></td>
  <td width="175"><font size="2" face="Tahoma">Brighton</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">32</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,600.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,500.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,500.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,100.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">45%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>15</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">68075</font></td>
  <td width="175"><font size="2" face="Tahoma">Brighton Early</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">28</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,900.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,918.45</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 330.68</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 6,249.13</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,349.13</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">128%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>16</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1093 </font></td>
  <td width="175"><font size="2" face="Tahoma">Broomfield</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">62</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 10,850.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 10,425.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 10,425.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (425.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">96%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>17</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">52634</font></td>
  <td width="175"><font size="2" face="Tahoma">Broomfield Crossing</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">14</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,450.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,675.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,675.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,225.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">150%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>18</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1094 </font></td>
  <td width="175"><font size="2" face="Tahoma">Brush</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">29</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,294.65</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,088.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,382.65</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (692.35)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">86%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>19</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">71794</font></td>
  <td width="175"><font size="2" face="Tahoma">Carbon Valley</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">15</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,625.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,004.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 100.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,104.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (521.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">80%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>20</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">23607</font></td>
  <td width="175"><font size="2" face="Tahoma">Castle Rock</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">39</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,825.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 7,812.50</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,533.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 9,345.50</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 2,520.50</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">137%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>21</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">52962</font></td>
  <td width="175"><font size="2" face="Tahoma">Castle Rock High Noon</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">25</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,375.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,757.87</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 100.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,857.87</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,517.13)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">65%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>22</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">55547</font></td>
  <td width="175"><font size="2" face="Tahoma">Centennial</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">29</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 6,945.14</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 382.71</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 7,327.85</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 2,252.85</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">144%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>23</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">52963</font></td>
  <td width="175"><font size="2" face="Tahoma">Clear Creek County</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">12</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,100.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 100.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 100.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,000.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">5%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>24</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">27606</font></td>
  <td width="175"><font size="2" face="Tahoma">Coal Creek</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">40</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 7,000.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,624.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 100.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,724.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (5,276.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">25%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>25</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1095 </font></td>
  <td width="175"><font size="2" face="Tahoma">Commerce City</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">36</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,300.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,700.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 2,625.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 7,325.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,025.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">116%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>26</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">31748</font></td>
  <td width="175"><font size="2" face="Tahoma">Conifer</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">42</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 7,350.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,209.36</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 200.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,409.36</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (5,940.64)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">19%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>27</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1097 </font></td>
  <td width="175"><font size="2" face="Tahoma">Denver</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">304</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 53,200.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 28,928.34</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 4,914.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 33,842.34</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (19,357.66)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">64%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>28</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">25051</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Cherry Creek</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">31</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,425.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,426.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,426.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">100%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>29</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">30423</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Lodo</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">32</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,600.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,925.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,925.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,675.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">52%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>30</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">75863</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Metro North</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">14</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,450.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,600.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,600.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (850.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">65%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>31</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">26370</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Mile High</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">79</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 13,825.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 11,347.43</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 2,450.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 13,797.43</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (27.57)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">100%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>32</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">78835</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Sky High</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">17</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,975.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 820.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 820.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,155.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">28%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>33</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">23165</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Southeast</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">108</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 18,900.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 20,225.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 20,225.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,325.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">107%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>34</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">23063</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Stapleton</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">16</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,800.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,916.50</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,916.50</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (883.50)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">68%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>35</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">50423</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver Tech Center</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">30</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,250.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 6,406.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,600.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 8,006.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 2,756.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">152%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>36</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">81051</font></td>
  <td width="175"><font size="2" face="Tahoma">Denver West (Golden)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">18</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 3,150.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 250.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 250.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,900.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">8%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>37</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1098 </font></td>
  <td width="175"><font size="2" face="Tahoma">Englewood</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">45</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 7,875.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,215.75</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 800.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 6,015.75</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,859.25)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">76%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>38</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">72979</font></td>
  <td width="175"><font size="2" face="Tahoma">Erie</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">15</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,625.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,385.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,385.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (240.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">91%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>39</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">22493</font></td>
  <td width="175"><font size="2" face="Tahoma">Evergreen</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">115</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 20,125.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,722.98</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 2,474.98</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 8,197.96</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (11,927.04)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">41%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>40</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">82734</font></td>
  <td width="175"><font size="2" face="Tahoma">Five Points Cultural District</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">24</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,200.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 300.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 300.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,900.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">7%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>41</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1103 </font></td>
  <td width="175"><font size="2" face="Tahoma">Fort Morgan</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">30</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,250.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,000.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,000.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,250.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">38%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>42</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">53203</font></td>
  <td width="175"><font size="2" face="Tahoma">Gilpin County Peak to Peak</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">25</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,375.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,758.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 250.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,008.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 633.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">114%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>43</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">23535</font></td>
  <td width="175"><font size="2" face="Tahoma">Golden</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">55</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 9,625.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 9,433.25</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 675.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 10,108.25</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 483.25</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">105%</font></td></tr>
<tr valign="bottom" style="background: green; color: white"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>44</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">24942</font></td>
  <td width="175"><font size="2" face="Tahoma">Granby</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">26</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,550.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,670.93</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,670.93</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,120.93</font></td>
  <td align="right" style="color: black" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">125%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>45</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1104 </font></td>
  <td width="175"><font size="2" face="Tahoma">Grand Lake</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">22</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 3,850.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,175.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,175.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,675.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">56%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>46</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">23064</font></td>
  <td width="175"><font size="2" face="Tahoma">Highlands Ranch (Littleton)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">52</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 9,100.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 8,621.14</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,900.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 10,521.14</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,421.14</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">116%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>47</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1108 </font></td>
  <td width="175"><font size="2" face="Tahoma">Kremmling</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">9</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 1,575.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,000.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,000.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (575.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">63%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>48</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1109 </font></td>
  <td width="175"><font size="2" face="Tahoma">Lakewood</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">29</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 8,350.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 8,350.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 3,275.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">165%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>49</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1110 </font></td>
  <td width="175"><font size="2" face="Tahoma">Lakewood Foothills</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">32</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,600.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,429.60</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,429.60</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,170.40)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">61%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>50</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1111 </font></td>
  <td width="175"><font size="2" face="Tahoma">Littleton</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">86</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 15,050.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 17,580.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 3,112.69</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 20,692.69</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 5,642.69</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">137%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>51</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">69639</font></td>
  <td width="175"><font size="2" face="Tahoma">Littleton Sunrise</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">10</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 1,750.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,435.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,435.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (315.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">82%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>52</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1112 </font></td>
  <td width="175"><font size="2" face="Tahoma">Longmont</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">153</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 26,775.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 12,657.64</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,700.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 14,357.64</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (12,417.36)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">54%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>53</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">50920</font></td>
  <td width="175"><font size="2" face="Tahoma">Longmont Saint Vrain</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">22</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 3,850.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,825.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,825.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,025.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">47%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>54</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">22543</font></td>
  <td width="175"><font size="2" face="Tahoma">Longmont Twin Peaks</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">69</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 12,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 15,122.71</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 3,280.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 18,402.71</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 6,327.71</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">152%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>55</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">77425</font></td>
  <td width="175"><font size="2" face="Tahoma">Mead</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">27</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,725.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 5,144.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,144.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 419.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">109%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>56</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">56721</font></td>
  <td width="175"><font size="2" face="Tahoma">Mountain Foothills of Evergreen</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">38</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,650.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 50.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 50.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (6,600.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">1%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>57</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">81052</font></td>
  <td width="175"><font size="2" face="Tahoma">Niwot</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">26</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 4,550.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,659.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 662.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,321.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,229.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">73%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>58</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1114 </font></td>
  <td width="175"><font size="2" face="Tahoma">Northglenn-Thornton</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">29</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,075.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 700.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,775.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,300.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">35%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>59</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">29730</font></td>
  <td width="175"><font size="2" face="Tahoma">Parker</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">72</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 12,600.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 16,688.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 109.09</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 16,797.09</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 4,197.09</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">133%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>60</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">73366</font></td>
  <td width="175"><font size="2" face="Tahoma">Parker-Cherry Creek Valley</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">36</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,300.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,987.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,024.09</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,011.09</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,288.91)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">64%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>61</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">57094</font></td>
  <td width="175"><font size="2" face="Tahoma">Rotary E-Club One </font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">56</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 9,800.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 10,955.56</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 700.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 11,655.56</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 1,855.56</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">119%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>62</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1116 </font></td>
  <td width="175"><font size="2" face="Tahoma">Smoky Hill (Aurora)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">36</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 6,300.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 2,652.70</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 2,652.70</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,647.30)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">42%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>63</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">21398</font></td>
  <td width="175"><font size="2" face="Tahoma">South Jefferson County 
  (Jeffco)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">16</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 2,800.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 1,469.24</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 1,469.24</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (1,330.76)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">52%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>64</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1186 </font></td>
  <td width="175"><font size="2" face="Tahoma">Summit County (Frisco)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">111</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 19,425.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 6,682.21</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,200.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 7,882.21</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (11,542.79)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">41%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>65</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1119 </font></td>
  <td width="175"><font size="2" face="Tahoma">University Hills (Denver)</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">79</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 13,825.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 16,800.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,500.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 18,300.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 4,475.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">132%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>66</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1120 </font></td>
  <td width="175"><font size="2" face="Tahoma">Westminster</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">62</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 10,850.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 8,165.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 8,165.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,685.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">75%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>67</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">30412</font></td>
  <td width="175"><font size="2" face="Tahoma">Westminster 7:10</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">40</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 7,000.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 3,300.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 1,194.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 4,494.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,506.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">64%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>68</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1121 </font></td>
  <td width="175"><font size="2" face="Tahoma">Wheat Ridge</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">31</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,425.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 360.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 2,850.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 3,210.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (2,215.00)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">59%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>69</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">72714</font></td>
  <td width="175"><font size="2" face="Tahoma">Winter Park-Fraser Valley</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">29</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 5,075.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 4,425.00</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ 825.00</font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 5,250.00</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ 175.00</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">103%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>70</b></th>
  <td width="39"><font color="#808080" size="2" face="Tahoma">1123 </font></td>
  <td width="175"><font size="2" face="Tahoma">Wray</font></td>
  <td align="center" width="59"><font size="2" face="Tahoma">21</font></td>
  <td align="right" width="75"><font size="2" face="Tahoma">$ 3,675.00</font></td>
  <td align="right" width="96"><font size="2" face="Tahoma">$ 214.55</font></td>
  <td align="right" width="85"><font size="2" face="Tahoma">$ -  </font></td>
  <td align="right" width="102"><font size="2" face="Tahoma">$ 214.55</font></td>
  <td align="right" width="70"><font size="2" face="Tahoma">$ (3,460.45)</font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma">6%</font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18" height="17"><b>71</b></th>
  <td width="39"> </td>
  <td align="right" width="175"><font size="2" face="Tahoma"><b>Totals</b></font></td>
  <td align="center" width="59"><font size="2" face="Tahoma"><b>3,185</b></font></td>
  <td align="right" width="75"><font size="2" face="Tahoma"><b>$ 557,375</b></font></td>
  <td align="right" width="96"><font size="2" face="Tahoma"><b>$ 403,459.50</b></font></td>
  <td align="right" width="85"><font size="2" face="Tahoma"><b>$ 70,601.56</b></font></td>
  <td align="right" width="102"><font size="2" face="Tahoma"><b>$ 474,061.06</b></font></td>
  <td align="right" width="70"><font size="2" face="Tahoma"><b>$ (83,314)</b></font></td>
  <td align="right" bgcolor="#FFFF99" width="59"><font size="2" face="Tahoma"><b>85%</b></font></td></tr>
<tr valign="bottom"><th align="center" bgcolor="#B0B0B0" width="18"><b>72</b></th>
  <td width="39"> </td>
  <td width="175"> </td>
  <td width="59"> </td>
  <td width="75"> </td>
  <td width="96"> </td>
  <td width="85"> </td>
  <td width="102"> </td>
  <td width="70"> </td>
  <td width="59"> </td></tr>
</table>
</div>
</div>
</div>
$footer
EOF;
?>