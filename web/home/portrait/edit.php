<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
	
	<title>audiMate - Home: Edit Portrait</title>	

<?php 
include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
?>


<?php



//global $myArtists;

function user_login($userEmail, $userPassword){ 

$portStatus = $_GET['err'];

	if ($portStatus == "nojpeg"){
		$outputErrorMessage = "Only JPEG images are allowed.";
		
	} else if ($portStatus == "toobig"){
		$outputErrorMessage = "Please limit your image size to 150 KB.";
		
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
		while ($row = mysql_fetch_array($sql_result)) {
 			$userAlias = $row["alias"];

			
			
			
			
			$sql_b = ('SELECT * FROM `usersTable` WHERE `alias` = \''.$userAlias.'\'');
			$sql_result_b = mysql_query($sql_b);
			$rows_b = mysql_num_rows($sql_result_b);
			if ($rows_b<=0 ){ 
				header('Location: http://audimate.me/web/login/');
			}
			
			
			if (isset($_FILES['portContent'])){
				
				$fileUpload = $_FILES['portContent'];
				$fileUpload_name = $_FILES['portContent']['name'];
				$fileUpload_size = $_FILES['portContent']['size'];
				$fileUpload_type = $_FILES['portContent']['type'];
				$fileUpload_temp = $_FILES['portContent']['tmp_name'];
				$fileUpload_error = $_FILES['portContent']['error'];
				
				
				if ($fileUpload_type != "image/jpeg"){
				
					header('Location: http://audimate.me/web/home/portrait/edit.php?err=nojpeg');
				
				} else if ($fileUpload_size > "153800"){
				
					header('Location: http://audimate.me/web/home/portrait/edit.php?err=toobig');
				
				} else {
					//echo "FILEsize:".$fileUpload_size;
				
					$fileContent = file_get_contents($fileUpload_temp);
					$fileContent = addslashes($fileContent);


					//$theAliasi = mysql_real_escape_string($strDesc);
				
					$sqlB = 'SELECT * FROM `portraitsTable` WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
					$sqlRes = mysql_query($sqlB);

					$rowsz = mysql_num_rows($sqlRes); 
					if ($rowsz<=0 ){ 
						header('Location: http://audimate.me/web/home/portrait/edit.php?err=notInPortraits');
					} else { 
	
						$dbQuery = 'UPDATE portraitsTable SET `portraitContent` = \''.$fileContent.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("Couldn't add file to database");
		
						echo "done!";
						header('Location: http://audimate.me/web/home/');
					}
				
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
<form enctype="multipart/form-data" name="editPortraitForm" action="edit.php" method="POST">
Upload a JPEG image:<br><br>
<font size="2">
Portraits are displayed at 200x300, regardless of actual dimensions.<br>
Please limit your image size to 150 KB.<br><br>
</font>


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
<br><br><center>
<input name="portContent" type="file" accept="image/jpeg" />

<a href="javascript: void(0);" onclick="document.editPortraitForm.submit();return
false;">Save</a></center>
</form>

</td>


<?php include("../../includeglobals/printFooter.php"); ?>
