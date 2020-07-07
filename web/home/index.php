<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Home: Interests</title>

<?php 
include("../includeglobals/printMainCSS.php");
include("../includeglobals/getArtists.php");
include("../includeglobals/getLocation.php");
include("../includeglobals/printHeader.php");

			
			
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
			$myLocation = getLocation($userAlias);
			$myArtists = getArtists($userAlias);

			$myArtists = str_replace("\n","<br>", $myArtists);
			if($myArtists=="0"){
				$myArtists=("You have no interests.");
			}
	
			
			printHeader("home", "Interests", $userAlias, $myLocation);
			
			
			
			echo '<div style="width:100%;height:450px;overflow:auto;"><br>'.$myArtists.'</div><br><p align="right"><a href="http://audimate.me/web/home/interests/edit.php">Edit</a></p></td>';

			
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


<?php include("../includeglobals/printFooter.php"); ?>
