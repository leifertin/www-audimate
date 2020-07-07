<?php

function hourFromNow(){
	$now = time();
	$inAnHour = $now + 3600;
	return $inAnHour;
}

function rightNow(){
	$now = time();
	return $now;
}

?>