<?php

$date = date("M d, Y H:i:s", getlastmod());
$counterWigget = $this->getCounterWigget($arg['ctrmsg']); // ctrmsg may be null which is OK

$pageFooterText = <<<EOF
<footer>
$counterWigget
<div style="text-align: center;">
<p id='lastmodified'>Last Modified&nbsp;$date</p>
{$arg['msg2']}
</div>
</footer>
</body>
</html>
EOF;
?>