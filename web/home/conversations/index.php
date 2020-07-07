<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Home: Conversations</title>
	</head>
	<body bgcolor="#000000">

<?php

include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");

?>

<?php

function user_login($userEmail, $userPassword){ 

$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
if (!$link) { 
    die('Could not connect.'); 
} 
			
mysql_select_db(audimate_db);


	//take the username and prevent SQL injections 
	$userEmail = mysql_real_escape_string($userEmail); 
	$userPassword = mysql_real_escape_string($userPassword); 
	//begin the query 
	//$sql= 'SELECT * FROM `usersTable`';
	$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
	//echo $sql;
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
	
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		header('Location: http://audimate.me/web/login/');
	} else { 
		while ($row = mysql_fetch_array($sql_result)) {
 			$userAlias = $row["alias"];

			//////////
			
			$curl_connection = curl_init('http://audimate.me/getMyConversations.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyConversations');


			$post_data['alias'] = $userAlias;
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myConversations = curl_exec($curl_connection);
			if ($myConversations == "0"){
			
			$myConversations = "<br>Locate someone to converse with them!";
			
			} else {
			
				$myConversations = explode("\n", $myConversations);
				
				
				$myBigConvo = '<table width="100%">
				<th width="35%" align="left">Activity</th>
				<th align="left">Alias</th>
				<tr><td colspan="2">
				<br></td><tr>';
				
				$i = 0;
				$ib = 0;
				
				while ($i <= (count($myConversations))):
					
					
					$myCurrentConvoItem = $myConversations[$i];
					
						
					if ($ib == 1){
						if ($myConversations[($i+1)] != $userAlias) {
						$myBigConvo = ($myBigConvo."<td>");
						$myBigConvo = ($myBigConvo."<a href=view.php?alias=".$myCurrentConvoItem.">".$myCurrentConvoItem."</a></td>");
						
						}
						
					}else if ($ib == 0){
						
						if ($myConversations[($i+2)] != $userAlias) {
						$myBigConvo = ($myBigConvo."<td>");
						$myBigConvo = ($myBigConvo.$myCurrentConvoItem."</td>");
						}
						
    				} else{

						if ($myCurrentConvoItem != $userAlias) {
						$myBigConvo = ($myBigConvo."<td>");
						$myBigConvo = ($myBigConvo.$myCurrentConvoItem."</td>");
						} else {
						 
						}
						
					}


					
					
						
					
					
					
					$i++;
					$ib++;
		
					if ($ib == 2){
					$ib = 0;
					$i++;
					$myBigConvo = ($myBigConvo."<tr>");
					
					
					}
				endwhile;
				
				
				
				$myConversations = ($myBigConvo."</table>");
				$emptyConvos = '<table width="100%">
				<th width="35%" align="left">Activity</th>
				<th align="left">Alias</th>
				<tr><td colspan="2">
				<br></td></table>';
				
				
				if ($myConversations == $emptyConvos){
					$myConversations = "Locate someone to converse with them!";
				}
			}
			
			curl_close($curl_connection);
			/////////////////
			
			
			//////////
			
			$curl_connection = curl_init('http://audimate.me/getMyDetails.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyDetails');


			$post_data['alias'] = $userAlias;
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myLocation = curl_exec($curl_connection);
			$myLocation = explode("<--userLocation-->", $myLocation);
			$myLocation = $myLocation[1];
			
			//$myArtists = str_replace("\n", "<br />", $myArtists);
			
			curl_close($curl_connection);
			/////////////////
			
			
			
			printHeader("home", "Conversations", $userAlias, $myLocation);
			
			
			echo '

'.$myConversations.'

</td>';


			///////////
			
			
	
			//////////
			
			
			
		}	
	}
				
}

$myEmail = $_COOKIE["audiMateemail"];
$myPassword = $_COOKIE["audiMatepassword"];

if(isset($myEmail) && isset($myPassword)){
	
	user_login($myEmail, $myPassword);


} else {
	header('Location: http://audimate.me/web/login/');
}

?>



</tr><?php include("../../includeglobals/printFooter.php"); ?>






</center>
	</body>
</html>