 <?php


global $strDesc;
global $replyContent;
global $convoText;
global $userAlias;
 

$urlInfo = parse_url($_SERVER['HTTP_REFERER']);
$myDomain = $urlInfo['host'];



if ($myDomain == ('audimate.me')){

	// Database connection variables

	$dbServer = "localhost";
	$dbDatabase = "audimate_db";
	$dbUser = "audimate8952";
	$dbPass = "kr4pp33K0ala";

	$sConn = mysql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to database server.");
	
	$dConn = mysql_select_db($dbDatabase, $sConn)
	or die("Couldn't connect to database.");

	$userEmail = $_COOKIE["audiMateemail"];
	$userPassword = $_COOKIE["audiMatepassword"];
	//$userAlias = $_COOKIE["audiMatealias"];
	$strDesc = $_COOKIE["convoID"];
	//$convoText = $_COOKIE["convoData"];
	
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
				$rowso = mysql_fetch_array($sql_result);
				$userAlias = $rowso["alias"];
				
				$row = mysql_fetch_array($sqlRes);
 				$deletedAlias = $row["deletedAlias"];
				$convoText = $row["conversationText"];
				
				
				
				$replyContent = $_POST['repContent'];
				//$replyContent = mysql_real_escape_string($replyContent); 
				//$replyContent = addslashes($replyContent);
				
				
				if ($replyContent == "") {
				
					$finalConvoText = ($convoText);
				
				} else {
				
					$finalConvoText = ($convoText."\n\n".$userAlias.": ".$replyContent);
				
				}
				
				
				$finalConvoText = addslashes($finalConvoText);
				//$finalConvoText = "garrrrrr";
				
				echo $finalConvoText;
				if ($deletedAlias == "0") {
					//Content
					$dbQuery = 'UPDATE conversationsTable SET `conversationText` = \''.$finalConvoText.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
					mysql_query($dbQuery) or die("error:content.");
	
					//Activity	
					$dbQuery = 'UPDATE conversationsTable SET `lastActivity` = NOW() WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
					mysql_query($dbQuery) or die("error:activity.");
		
					echo ($deletedAlias." done!");
					
					$urlInfo = $_SERVER['HTTP_REFERER'];
					header("Location: ".$urlInfo);
			
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
						$dbQuery = 'UPDATE conversationsTable SET `conversationText` = \''.$finalConvoText.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("error:content.");
	
						//Activity	
						$dbQuery = 'UPDATE conversationsTable SET `lastActivity` = NOW() WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
						mysql_query($dbQuery) or die("error:activity.");
						
						echo ($deletedAlias." done!");
						
						$urlInfo = $_SERVER['HTTP_REFERER'];
						header("Location: ".$urlInfo);
					}
				}
			}
		}
	}

} else {
	echo "0";
}



?>