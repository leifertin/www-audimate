<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<title>audiMate - Home: Edit Interests</title>	




<?php 

include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
include("../../includeglobals/getArtists.php");
include("../../includeglobals/getLocation.php"); 

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
			
			
			if (isset($_POST['intContent'])){
			
				//update artists
				//////////
				
				$userArtistList = $_POST['intContent'];
				
				
				
				$userArtistList = explode("\n", $userArtistList);
				
				
				
				
				//$myMates = sort($myMates);
			
				sort($userArtistList);
			
				$i = 2;
				$myBigArtists = "";
			
				while ($i <= (count($userArtistList))):
				
					$myCurrentMateItem = $userArtistList[$i];
				
					$myBigArtists = ($myBigArtists.$myCurrentMateItem."\n");
				
					$i++;
	
				endwhile;
				
				$userArtistList = $myBigArtists;
				
				trim($userArtistList);
				
				
				
				$userArtistList = mysql_real_escape_string($userArtistList);
				//$userArtistList = stripslashes($userArtistList);
	
	
				//begin the query 
				$sql = 'UPDATE `artistLists` SET `entry` = \''.$userArtistList.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
				//echo $sql;
				$sql_result = mysql_query($sql);
				//check to see how many rows were returned 
				//$rows = mysql_num_rows($sql_result); 
	
				echo $sql_result;
				header('Location: http://audimate.me/web/home/');
				
				////////////
			
			}
			
			$myLocation = getLocation($userAlias);
			
			
			
			////////////
			
			
			printHeader("home", "", $userAlias, $myLocation);

echo '
Separate each artist by a new line.<br>
They will be automatically alphabetized.<br><br>
The more you list, the better your results when "Locating" mates.
<br><br>

<form action="edit.php" name="editInterestsForm" method="POST">
<textarea rows="20" cols="70" name="intContent">';


//////////
			
			
			$myArtists = getArtists($userAlias);
			
			$myArtists = trim($myArtists);
			/////////////////

			if($myArtists=="0"){
			
			$myArtists=("");
			
			}
echo $myArtists;
			
			
			
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


</textarea><br><br>
<a href="javascript: void(0);" onclick="document.editInterestsForm.submit();return
false;">Save</a>
</form>

</td>


<?php
$ua = $_SERVER["HTTP_USER_AGENT"];


/* ==== Detect the OS ==== */

// ---- Mobile ----

// Android
$android = strpos($ua, 'Android') ? true : false;

// BlackBerry
$blackberry = strpos($ua, 'BlackBerry') ? true : false;

// iPhone
$iphone = strpos($ua, 'iPhone') ? true : false;

// Palm
$palm = strpos($ua, 'Palm') ? true : false;

// ---- Desktop ----

// Linux
$linux = strpos($ua, 'Linux') ? true : false;

// Macintosh
$mac = strpos($ua, 'Macintosh') ? true : false;

// Windows
$win = strpos($ua, 'Windows') ? true : false;

/* ============================ */


if ($mac) {
	echo '</tr><tr><td style="background-image:url(\'http://audimate.me/audimatePurple.png\'); background-repeat:repeat-x; background-attachment:scroll; background-position:bottom;" colspan="2" align="center" valign="top"><br><a href="http://audimate.me/download"><font size=2>Download</font></a><font size=2> audiMate for mac, to import your entire list from iTunes or MyVinyl in one click.</font>
	</td>';
}
?>

<?php include("../../includeglobals/printFooter.php"); ?>

