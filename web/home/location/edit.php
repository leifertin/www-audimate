<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<title>audiMate - Home: Edit Location</title>	


<?php 
include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
?>

<?php

//global $myArtists;


 

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


			//$profileAlias = $_GET['alias'];
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$userAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			if ($rows_b<=0 ){ 
				header('Location: http://audimate.me/web/login/');
			}
			
			
			if (isset($_POST['locContent'])){
			
				//update location
				//////////
				
				$userLocationU = $_POST['locContent'];
				$userLocationU = str_replace("<","less than",$userLocationU);
				$userLocationU = str_replace(">","greater than",$userLocationU);
				
				$userLocationU = mysql_real_escape_string($userLocationU);
				//$userArtistList = stripslashes($userArtistList);
	
	
				//begin the query 
				$sql = 'UPDATE `usersTable` SET `location` = \''.$userLocationU.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
				//echo $sql;
				$sql_result = mysql_query($sql);
				//check to see how many rows were returned 
				//$rows = mysql_num_rows($sql_result); 
	
				echo $sql_result;
				header('Location: http://audimate.me/web/home/');
				
				////////////
			
			}
			
			
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

			$myDetails = curl_exec($curl_connection);
			curl_close($curl_connection);
			
			$myLocation = explode("<--userLocation-->", $myDetails);
			$myLocation = $myLocation[1];
			
			$myGender = explode("<--userGender-->", $myDetails);
			$myGender = $myGender[1];
			
			//$myArtists = str_replace("\n", "<br />", $myArtists);
			
			
			
			////////////
			printHeader("home", "", $userAlias, $myLocation);
			
			echo '

Where are you?<br><br>



<form action="edit.php" name="editLocationForm" method="POST">


<input type="text" size="70" maxlength="130" name="locContent" value="'.$myLocation.'">
';

			
			
			
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

</form>

</td>


<?php include("../../includeglobals/printFooter.php"); ?>

