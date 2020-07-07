<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<?php
	if (isset ($_GET['alias'])){
		echo "<title>audiMate - ".$_GET['alias']."'s profile</title>";
	} else {
		header("Location: http://audimate.me/web/login/");
	}
	?>
	


<?php 
include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
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
			$profileAlias = $_GET['alias'];
			
			$disableMyLinks = false;
			if ($userAlias == $profileAlias){
					//header('Location: http://audimate.me/web/home/');
					$disableMyLinks = true;
			}
				
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$profileAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			
			
			//////////
			
			$curl_connection = curl_init('http://audimate.me/getMyArtists.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyArtists');


			$post_data['alias'] = $profileAlias;
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myArtists = curl_exec($curl_connection);
			curl_close($curl_connection);
			
			$myArtists = str_replace("\n", "<br />", $myArtists);
			
			
			
			/////////////////
			
			
			//////////
			
			$curl_connection = curl_init('http://audimate.me/getMyDetails.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyDetails');


			$post_data['alias'] = $profileAlias;
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myDetails = curl_exec($curl_connection);
			
			$myLocation = explode("<--userLocation-->", $myDetails);
			$myLocation = $myLocation[1];
			
			$myGender = explode("<--userGender-->", $myDetails);
			$myGender = $myGender[1];
			
			//$myArtists = str_replace("\n", "<br />", $myArtists);
			
			curl_close($curl_connection);
			
			
			
			
			
			
			
			////////////////////////////////////
			///DETERMINE IF USER IS A MATE
			///
			//////////
			

			$myMates = getMates($userAlias);
			
			
			$rememberForget = "Remember";
			if ($myMates != "1"){
			
			$myMates = explode("\n", $myMates);
			//$myMates = sort($myMates);
			
			sort($myMates);
			
			$i = 2;
			$myBigMate = "";
			
			
			while ($i <= (count($myMates))):
				
				$myCurrentMateItem = $myMates[$i];
				
				if ($myCurrentMateItem == $profileAlias) {
				
					$rememberForget = "Forget";
					break;
					
				}
				
				$i++;
	
			endwhile;
			
				if ($rows_b<=0 ){ 
					//$profileAlias does not exist
				
					if ($rememberForget == "Forget"){
						//remove $profileAlias from mates
						$endurl = ("Location: http://audimate.me/web/home/mates/update.php?updAction=".$rememberForget."&alias=".$profileAlias);
						//echo $endurl;
						header($endurl);
					} else {
						header('Location: http://audimate.me/web/home/mates/');
					}
				}
			
				
			
			} else {
				
				if ($rows_b<=0 ){ 
					//$profileAlias does not exist
				
					header('Location: http://audimate.me/web/home/mates/');
				}
			
				$myMates = "Locate, view, and remember mates to save them to this list.";
			}
			
			/////////////////
			
			
			if($myArtists=="0"){
			
			$myArtists=($profileAlias." has no interests.");
			
			}
			
			
			
		echo '<br><br><br>'.$profileAlias.'\'s profile</td><td colspan="1" style="background-image:url(\'http://audimate.me/audimatePurple.png\');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;" valign="bottom" align="right"><br><br><br>
		<a href="http://audimate.me/web/home/">Interests</a> | <a href="http://audimate.me/web/home/conversations/">Conversations</a> | <a href="http://audimate.me/web/home/mates/">Mates</a> | <a href="http://audimate.me/web/home/locate/">Locate</a></td>
		
		
</table>
<table width="100%" height="60" border="0" align="center" cellpadding="15" cellspacing="8">

			<tr>
			<td align="left" width="200" height="300" valign="top"><img alt="No Portrait" width="200" height="300" src="http://audimate.me/displayPortrait.php?alias='.$profileAlias.'"></td>';


			echo '<td align="right" valign="top">'.$myGender.'<br><br>'.$myLocation.'</td></tr>


<tr>

<th colspan="2" align="left" height="50">';

$myProfileURL = ("http://audimate.me/web/home/conversations/create.php?alias=".$profileAlias);


$mySoundURL = ("http://audimate.me/displaySoundbite.php?alias=".$profileAlias);
$mySoundExists = file_get_contents($mySoundURL);


if ($mySoundExists != "zeroSound"){

	//echo ('<object height="30" width="200" align="right"> 
//<param name="kioskmode" value="true"> 
//<param name="src" value="'.$mySoundURL.'"> 
//<param name="autoplay" value="false"> 
//<param name="controller" value="true"> 
//<embed height="30" align="right" src="'.$mySoundURL.'" type="audio/mpeg" width="200" controller="true" autoplay="false" kioskmode="true"> 
//</object>');

	$myHearURL = ("http://audimate.me/web/home/mates/hear?alias=".$profileAlias);
	echo ('<a href="'.$myHearURL.'" type="audio/x-mpegurl">Hear</a>');
	
	if ($disableMyLinks == false){

		echo ' | ';

	}
}

if ($disableMyLinks == false){

	echo '<a href="update.php?updAction='.$rememberForget.'&alias='.$profileAlias.'">'.$rememberForget.'</a> | <a href="'.$myProfileURL.'">Converse</a>';

}

echo '</th></tr>


<tr>
<th colspan="2" align="center" height="50">Interests
</tr>
<tr>
<td height="500" align="left" valign="top" colspan="2" style="background-image:url(http://audimate.me/audimatePurple.png);background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;"><br>
<div style="width:100%;height:95%;overflow:auto;">
'.$myArtists.'</div></td>';


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




<?php include("../../includeglobals/printFooter.php"); ?>

