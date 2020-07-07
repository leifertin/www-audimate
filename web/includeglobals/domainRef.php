<?php
function domainRef(){
if (isset($_SERVER['HTTP_REFERER'])) { // check there was a referrer

	$uri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
	return $uri['host']; // echo the host

}
}
?>