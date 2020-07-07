<html>
<head>
<title>audiMate - Delete Me</title>
<script language="JavaScript" src="destroy.js" type="text/javascript"></script>
</head>


<body bgcolor="#000000">

<noscript>
<table align="center" height="100%"><td align="center" valign="middle">
<center>
<h3>

<font color="#ffffff" face="verdana">Dude, enable javascript.</font>

</h3>
</center>	
</td></table>
</noscript>

<?php
function user_login($myEmail, $myPassword){
	
	
	//////////
			
	$curl_connection = curl_init('http://audimate.me/deleteMe.php');

	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.deleteMe');


	$post_data['email'] = $myEmail;
	$post_data['password'] = $myPassword;
			
	
	foreach ( $post_data as $key => $value) 
	{
		$post_items[] = $key . '=' . $value;
	}
	$post_string = implode ('&', $post_items);

	curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

	$myConfirmationReturn = curl_exec($curl_connection);
	curl_close($curl_connection);
						
	/////////////////
	
	//Calculate 1 day in the future
	//seconds * minutes * hours * days + current time
	$inOneDay = 1 * 0 * 0 * 0 + time();
	//setcookie('audiMateemail', $userEmail, $inOneDay); 
	setcookie('audiMatestatus', 'off', $inOneDay); 
	setcookie('audiMateemail', $userEmail, $inOneDay,'/');
	setcookie('audiMatepassword', $userPassword, $inOneDay,'/');
	setcookie('convoID', $myConversationID, $inOneDay,'/');
				
	if ($myConfirmationReturn == "You now cease to exist."){
		header('Location: http://audimate.me/web/login/index.php?act=del.success');
		
	} else {
		header('Location: http://audimate.me/web/login/index.php?act=del.othererror');
		
	} 
		
	//echo $myConfirmationReturn;
	
}
				
$myEmail = $_COOKIE['audiMateemail'];
$myPassword = $_COOKIE['audiMatepassword'];
			
if(isset($myEmail) && isset($myPassword)){

	if (($_GET['act']) == "del.confirm.thrice"){
	
		user_login($myEmail, $myPassword);
		
	} else {
	
		echo ('<script language="JavaScript" type="text/javascript">destroyMe();</script>');
	
	}
	
	
	
} else {
	header('Location: http://audimate.me/web/login/');
}

?>
</body></html>