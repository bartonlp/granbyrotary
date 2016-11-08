<?php

$lastmod = date("M j, Y H:i", getlastmod());

return <<<EOF
<footer>
{$arg['msg']}
{$arg['msg1']}
$counterWigget
<div style="text-align: center;">
<p id='lastmodified'>Last Modified:&nbsp;$lastmod</p>
{$arg['msg2']}
</div>
</footer>
<!-- Start of StatCounter Code for Default Guide -->
<script>
var sc_project=10201350; 
var sc_invisible=0; 
var sc_security="e9d09be3"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="site stats"
href="http://statcounter.com/free-web-stats/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/10201350/0/e9d09be3/0/"
alt="site stats"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
</body>
</html>
EOF;
