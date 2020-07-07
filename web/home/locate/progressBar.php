<html>
<head>

<style>
.all-rounded {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
 
.spacer {
	display: block;
}
 
#progress-bar {
	width: 400px;
	margin: 0 auto;
	background: #000000;
	border: 3px solid #f2f2f2;
}
 
#progress-bar-percentage {
	background: #a54c99;
	padding: 5px 0px;
 	color: #FFF;
 	font-weight: bold;
 	text-align: center;
	font-family: verdana;
}
</style>
</head>
<body bgcolor="#000000">
<?php

//Progress Bar Code from -- http://riotriot.net/2010/02/simple-php-progress-bar/
function progressBar($percentage) {
	echo "<div id=\"progress-bar\" class=\"all-rounded\">\n";
	echo "<div id=\"progress-bar-percentage\" class=\"all-rounded\" style=\"width: ".$percentage."%\">";
		if ($percentage > 5) {echo $percentage."%";} else {echo "<div class=\"spacer\">&nbsp;</div>";}
	echo "</div></div>";
}


$progy = $_GET["prog"];
if ($progy >= 1){
	progressBar($progy);
	
}


?>

</body></html>
