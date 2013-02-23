<?php

$milliSecond = strftime("%H%M%S",time());
$rndFileName = "../tmpfile/".$milliSecond.".jpg";
file_put_contents($rndFileName, file_get_contents($_GET["url"]));

?>