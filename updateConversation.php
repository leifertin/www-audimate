<?php


global $strDesc;

global $fileContent;
global $fileUpload;
global $fileUpload_name;
global $fileUpload_size;
global $fileUpload_type;

$fileUpload = $_FILES['fileUpload'];
$fileUpload_name = $_FILES['fileUpload']['name'];
$fileUpload_size = $_FILES['fileUpload']['size'];
$fileUpload_type = $_FILES['fileUpload']['type'];
$fileUpload_temp = $_FILES['fileUpload']['tmp_name'];
$fileUpload_error = $_FILES['fileUpload']['error'];
$fileContent = file_get_contents($fileUpload_temp);
$fileContent = addslashes($fileContent);


if (($_SERVER['HTTP_REFERER']) == ('audiMate.updateConversation')){

	// Database connection variables

	$dbServer = "localhost";
	$dbDatabase = "audimate_db";
	$dbUser = "audimate8952";
	$dbPass = "kr4pp33K0ala";

	$sConn = mysql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to database server.");
	
	$dConn = mysql_select_db($dbDatabase, $sConn)
	or die("Couldn't connect to database.");

	$userEmail = $_POST['email'];
	$userPassword = $_POST['password'];
	$strDesc = $_POST['strDesc'];

	$strDesc = mysql_real_escape_string($strDesc); 
	$userEmail = mysql_real_escape_string($userEmail); 
	$userPassword = mysql_real_escape_string($userPassword); 
	
	//begin the query 

	$sql = 'SELECT `alias`, `email`, `password` FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 

	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		echo "0";
	} else { 
	
		$sqlB = 'SELECT * FROM `conversationsTable` WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
		$sqlRes = mysql_query($sqlB);

		$rowsz = mysql_num_rows($sqlRes); 
		if ($rowsz<=0 ){ 
			echo $strDesc;
		} else { 
			if ($rows<=0 ){ 
				echo "0";
			} else { 
				$row = mysql_fetch_array($sqlRes);
 				$deletedAlias = $row["deletedAlias"];
		
				if ($deletedAlias == "0") {
					//Content
					$dbQuery = 'UPDATE conversationsTable SET `conversationText` = \''.$fileContent.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
					mysql_query($dbQuery) or die("error:content.");
	
					//Activity	
					$dbQuery = 'UPDATE conversationsTable SET `lastActivity` = NOW() WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
					mysql_query($dbQuery) or die("error:activity.");
		
					echo ($deletedAlias." done!");
			
				} else {
					//Check if deletedAlias exists in usersTable
					$sqlC = 'SELECT * FROM `usersTable` WHERE `alias` = \''.$deletedAlias.'\' LIMIT 1;';
					$sqlResin = mysql_query($sqlC);
					$rowszy = mysql_num_rows($sqlResin); 
			
					if ($rowszy<=0 ){ 
						//if not:
				
						echo $deletedAlias." does not exist.";	
					} else {
						//if so:
				
						//Reset Deleted
						$dbQuery = 'UPDATE conversationsTable SET `deletedAlias` = \'0\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("error:deleted.");
	
						//Content
						$dbQuery = 'UPDATE conversationsTable SET `conversationText` = \''.$fileContent.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("error:content.");
	
						//Activity	
						$dbQuery = 'UPDATE conversationsTable SET `lastActivity` = NOW() WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("error:activity.");
		
						echo ($deletedAlias." done!");
					}
				}
			}
		}
	}

} else {
	echo "0";
}


?>