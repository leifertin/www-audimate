<?php

$month = date('n'); 
//echo $month;

if ($month == "2") {
	//$5
	header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ES4JNAHG9YV7S');
} else {
	//$10
	header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PHB6Y59Q8WY26');
}

?>