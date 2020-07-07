<?php
$myVer = file_get_contents("http://audimate.me/current/index.php");
$myDL = ('http://audimate.me/past/audiMatev'.$myVer.'.dmg');
header('Location: '.$myDL);
//echo $myDL;
?>