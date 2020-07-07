<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Home: Mates</title>

<?php 
include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
include("../../includeglobals/getLocation.php");
include("../../includeglobals/getMates.php");

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
			

			$myMates = getMates($userAlias);
			
			if ($myMates != "1"){
			
			$myMates = explode("\n", $myMates);
			//$myMates = sort($myMates);
			
			sort($myMates);
			
			$i = 2;
			$myBigMate = "";
			
			while ($i <= (count($myMates))):
				
				$myCurrentMateItem = $myMates[$i];
				
				$myBigMate = ($myBigMate."<br>
				<a href=\"view.php?alias=".$myCurrentMateItem."\">".$myCurrentMateItem."</a><br>");
				
				$i++;
	
			endwhile;
			
			
			$myMates = $myBigMate;
			} else {
				$myMates = "<br>Locate, view, then remember mates to save them to this list.";
			}
			
			
			$myLocation = getLocation($userAlias);
			/////////////////
			
			
			
			printHeader("home", "Mates", $userAlias, $myLocation);
			
			echo '
'.$myMates.'</td>';


			
			
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



<?php include("../../includeglobals/printFooter.php"); ?>
