<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<title>audiMate - Home: Edit Soundbite</title>	

<?php 
include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
?>


<?php



//global $myArtists;



function user_login($userEmail, $userPassword){ 

$portStatus = $_GET['err'];

	if ($portStatus == "badtype"){
		$outputErrorMessage = "Invalid file type.";
		
	} else if ($portStatus == "toobig"){
		$outputErrorMessage = "Please limit your soundbite size to 1.5 MB";
		
	} else {
		$outputErrorMessage = "";
	}
	
	
	if ($outputErrorMessage != ""){
	
		echo '<script>alert(\''.$outputErrorMessage.'\');</script>';
	
	}
	
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
			$row = mysql_fetch_array($sql_result);
 			$userAlias = $row["alias"];
			
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$userAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			if ($rows_b<=0 ){ 
				header('Location: http://audimate.me/web/login/');
			}
			
			
			if (isset($_GET['finishUp'])){
				
				
					//$fileContent = file_get_contents($fileUpload_temp);
					//$fileContent = addslashes($fileContent);
					$fileContent = mysql_real_escape_string($_GET['finishUp']); 

					//$theAliasi = mysql_real_escape_string($strDesc);
				
					$sqlB = 'SELECT * FROM `soundbitesTable` WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
					$sqlRes = mysql_query($sqlB);

					$rowsz = mysql_num_rows($sqlRes); 
					if ($rowsz<=0 ){ 
						header('Location: http://audimate.me/web/home/soundbite/edit.php?err=notInSoundbites');
					} else { 
						
						//header('Location: http://google.com');
						
						
						
						$dbQuery = 'UPDATE soundbitesTable SET `mime` = \''.$fileUpload_type.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("Couldn't add file to database");
						
						$dbQuery = 'UPDATE soundbitesTable SET `soundbiteContent` = \''.$fileContent.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("Couldn't add file to database");
						
						//echo "done!";
						header('Location: http://audimate.me/web/home/');
					}
				
				
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
<form enctype="multipart/form-data" name="editSoundbiteForm" action="upload.php" method="POST">
Upload an OGG soundbite:<br><br>
<font size="2">
Please limit your soundbite size to 1.5 MB.<br><br>
</font>


';
			
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
<br><br><center>
<input name="soundbiteContent" type="file" />

<a href="javascript: void(0);" onclick="document.editSoundbiteForm.submit();return
false;">Save</a></center>
</form>

</td>


<?php include("../../includeglobals/printFooter.php"); ?>
