<?php
$showAlias = $_GET["alias"];

if (isset($showAlias)){
	if ($showAlias != ""){
	
$parsedMime = "audio/x-mpegurl";
$mimeType = ("Content-type: ".$parsedMime);
header($mimeType);
echo ("http://audimate.me/displaySoundbite.php?alias=".$showAlias);

	} else {
		goHome();
	}
} else {
	goHome();
}

function goHome(){
	$endURL = ("Location: http://audimate.me/");
	header($endURL);
}

?>