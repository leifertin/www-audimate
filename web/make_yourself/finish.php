<?php
function user_login($myEmail, $myPassword, $myAlias, $myGender, $myPassword_c, $myAge_c){
			
			
	//////////
			
	$curl_connection = curl_init('http://audimate.me/createMe.php');

	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.makeYourself');


	$post_data['myEmail'] = $myEmail;
	$post_data['myPassword'] = $myPassword;
	$post_data['myAlias'] = $myAlias;
	$post_data['myGender'] = $myGender;
			
	
	foreach ( $post_data as $key => $value) 
	{
		$post_items[] = $key . '=' . $value;
	}
	$post_string = implode ('&', $post_items);

	curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

	$myConfirmationReturn = curl_exec($curl_connection);
	curl_close($curl_connection);
						
	/////////////////
	
	
	if ($myConfirmationReturn == "Your alias had weird characters in it, or it was not the right length."){
		header('Location: http://audimate.me/web/make_yourself/index.php?err=badalias');
		
	} else if ($myConfirmationReturn == "This alias is in use."){
		header('Location: http://audimate.me/web/make_yourself/index.php?err=aliasinuse');
		
	} else if ($myConfirmationReturn == "Your email had weird characters in it."){
		header('Location: http://audimate.me/web/make_yourself/index.php?err=bademail');
		
	} else if ($myConfirmationReturn == "This email is in use."){
		header('Location: http://audimate.me/web/make_yourself/index.php?err=emailinuse');
		
	} else if ($myConfirmationReturn == "You now exist.\n\nClick cancel, then login with your new credentials."){
		header('Location: http://audimate.me/web/make_yourself/index.php?err=created');
		
	} else {
		header('Location: http://audimate.me/web/make_yourself/index.php?err=othererror');
		
	}	
		
	//echo $myConfirmationReturn;
	
}
				
$myEmail = $_POST['myemail'];
$myPassword = $_POST['mypassword'];
$myAlias = $_POST['myalias'];
$myGender = $_POST['mygender'];
$myPassword_c = $_POST['mycpassword'];
$myAge_c = $_POST['over18'];
			
if(isset($myEmail) && isset($myPassword) && isset($myAlias) && isset($myGender) && isset($myPassword_c) && isset($myAge_c)){
	
	if ($myPassword == ""){
	 	//Password missing
		header('Location: http://audimate.me/web/make_yourself/index.php?err=nopassword');
	} else {
	
		if ($myPassword != $myPassword_c){
			 //Passwords don't match
			 header('Location: http://audimate.me/web/make_yourself/index.php?err=passwordnomatch');
		} else {
			
			if ($myAge_c != "correct"){
				//Under 18
				header('Location: http://audimate.me/web/make_yourself/index.php?err=under18');
			} else {
				user_login($myEmail, $myPassword, $myAlias, $myGender, $myPassword_c, $myAge_c);
			}
		}
	
	
	}

} else {
	header('Location: http://audimate.me/web/make_yourself/');
}

?>
