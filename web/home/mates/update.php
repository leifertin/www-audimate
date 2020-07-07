<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Home: Update Mates</title>

	</head>
	<body bgcolor="#000000">

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
			
			$curl_connection = curl_init('http://audimate.me/getMyMates.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyMates');


			$post_data['alias'] = $userAlias;
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myMates = curl_exec($curl_connection);
			curl_close($curl_connection);
			
			if ($myMates != "1"){
			
				$myMates = explode("\n", $myMates);
				sort($myMates);
			
			
				/////////
			
				$myAction = $_GET["updAction"];
				$profileAlias = $_GET["alias"];
			
			
				if ($myAction == "Forget"){
			
					$i = 2;
					$myBigMate = "";
			
					while ($i <= (count($myMates))):
				
						$myCurrentMateItem = $myMates[$i];
					
						if ($myCurrentMateItem != $profileAlias){
					
						$myBigMate = ($myBigMate.$myCurrentMateItem."\n");
						
						}
						
						$i++;
	
					endwhile;
				
				
				} else if ($myAction == "Remember"){
			
					$i = 2;
					$myBigMate = "";
			
					while ($i <= (count($myMates))):
					
						$myCurrentMateItem = $myMates[$i];
						$myBigMate = ($myBigMate.$myCurrentMateItem."\n");
				
						$i++;
	
					endwhile;
					
					$myBigMate = ($myBigMate.$profileAlias."\n");
				
				} else {
					header('Location: http://audimate.me/web/login/');
				}
			
				$myMates = explode("\n", $myBigMate);
				sort($myMates);
			
				$i = 2;
				$myBigMate = "";
			
				while ($i <= (count($myMates))):
				
					$myCurrentMateItem = $myMates[$i];
					$myBigMate = ($myBigMate.$myCurrentMateItem."\n");
				
					$i++;
	
				endwhile;
			
				trim($myBigMate);
				$myMates = addslashes($myBigMate);
				
			///////////////////
			///copy $myMates to DB
			//////////
			
				$dbQuery = 'UPDATE matesTable SET `mates` = \''.$myMates.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';

				mysql_query($dbQuery) or die("Couldn't add file to database");
				echo "<h1>File Uploaded</h1>";
				
				$endURL = ('Location: http://audimate.me/web/home/mates/view.php?alias='.$profileAlias);
				header($endURL);

			} else {
				$myMates = "Locate, view, and remember mates to save them to this list.";
			}
			
			/////////////////
			
			
			
			
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



	</body>
</html>