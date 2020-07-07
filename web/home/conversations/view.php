<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<?php
	global $userAlias;
	
	if (isset ($_GET['alias'])){
		echo "<title>audiMate - Conversation with ".$_GET['alias']."</title>";
	} else {
		header("Location: http://audimate.me/web/login/");
	}
	
	
	?>

<?php include("../../includeglobals/printMainCSS.php"); ?>
<?php include("../../includeglobals/printHeader.php"); ?>
<?php include("../../includeglobals/getMates.php"); ?>


<?php

global $echoReply;


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}


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
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$profileAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			
			
			if ($rows_b<=0 ){ 
				//User has been deleted
				
				$echoReply = "";
				
				//$urlInfo = curPageURL();
				//$urlInfo = $_SERVER['HTTP_REFERER'];
				//header("Location: ".$urlInfo);
			}
			
			
			
			//////////
			
			$curl_connection = curl_init('http://audimate.me/displayConversation.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.displayConversation');


			$post_data['alias'] = $userAlias;
			$post_data['otherAlias'] = $profileAlias;
			
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myConversationData = curl_exec($curl_connection);
			curl_close($curl_connection);
			
			
			$myExplodeString = ("<--StrtMssg_{".$userAlias."}:{".$profileAlias."}.[");
			$myConversationData = explode($myExplodeString, $myConversationData);
			$myConvoCount = count($myConversationData);
			
			
			if ($myConvoCount > 1){
			
			$myConversationData = $myConversationData[1];
			
			$myConversationData = explode(("]-->"), $myConversationData);
			
			
			
			
			
			$myConversationID = $myConversationData[0];
			$myConversationData = $myConversationData[1];
			
			
			$inOneDay = 60 * 60 * 24 * 1 + time();
			setcookie('convoID', $myConversationID, $inOneDay,'/' ,'.audimate.me');
			//setcookie('convoData', $myConversationData, $inOneDay,'/');
			
			} else {
				//echo "convo does not exist.";
				
				header('Location: http://audimate.me/web/login');
			
			}
			
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
			
			
			if ($myMates != "1"){
			
			$myMates = explode("\n", $myMates);
			//$myMates = sort($myMates);
			
			sort($myMates);
			
			$i = 2;
			$myBigMate = "";
			$rememberForget = "Remember";
			
			while ($i <= (count($myMates))):
				
				$myCurrentMateItem = $myMates[$i];
				
				if ($myCurrentMateItem == $profileAlias) {
				
					$rememberForget = "Forget";
					
				}
				
				
				$i++;
	
			endwhile;
			
			} else {
				$myMates = "Locate, view, and remember mates to save them to this list.";
			}
			
			/////////////////

			
			
			
			printHeader("view", "", $userAlias, $myLocation, $myGender, $profileAlias);
			
			echo '
<div style="width:100%;height:95%;overflow:auto;">';

$myConversationData = str_replace("\n", "<br />", $myConversationData);

echo $myConversationData.'</div>
</td></tr>';


			///////////
			
			
	
			//////////
			
			
			
		}	
	}
				
}




function post_replyCont($userEmail, $userPassword){ 

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
 			//$userAlias = $row["alias"];


			$profileAlias = $_GET['alias'];
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$profileAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			
			
			if ($rows_b<=0 ){ 
				//User has been deleted
				
				$echoReply = '';
				
			} else {
				$echoReply = ' &nbsp;|&nbsp; <a href="javascript: void(0);" onclick="document.replyForm.submit();return
false;">Reply</a>';
			}
									
			
			
echo $echoReply;
			
			
			
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


</table>


<table width="100%" height="100%" border="0" align="center" cellpadding="15" cellspacing="8">

<tr>



<td align="right" colspan="2" style="background-image:url('http://audimate.me/audimatePurple.png'); background-repeat:repeat-x; background-attachment:scroll; background-position:bottom;">

<form action="reply.php" name="replyForm" method="POST">
<textarea rows="8" cols="70" name="repContent"></textarea><br><br>
<a href="http://audimate.me/web/home/conversations/forget.php">Forget</a><?php
//global $echoReply;


post_replyCont($_COOKIE["audiMateemail"], $_COOKIE["audiMatepassword"]);

?>
</form>

</td>


<?php include("../../includeglobals/printFooter.php"); ?>