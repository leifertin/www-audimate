 <?php


global $strDesc; 

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
		
		$rowso = mysql_fetch_array($sql_result);
		
		////////
		$myAlias = $rowso["alias"];
 
		$sqlB = 'SELECT * FROM `conversationsTable` WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
		$sqlRes = mysql_query($sqlB);

		$rowsz = mysql_num_rows($sqlRes); 
		if ($rowsz<=0 ){ 
			echo "$strDesc";
		} else { 
			$row = mysql_fetch_array($sqlRes);
 			$delAlias = $row["deletedAlias"];

			if ($delAlias == "0") {
		
			$dbQuery = 'UPDATE conversationsTable SET `deletedAlias` = \''.$myAlias.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
			mysql_query($dbQuery) or die("Couldn't update.");
		
			echo "updated!";
			header('Location: http://audimate.me/web/home/conversations/index.php?status=updated');
			} else if ($delAlias == $myAlias) {
		
				//Do Nothing
				echo "did nothing!";
				header('Location: http://audimate.me/web/home/conversations/index.php?status=nothing');
			} else {
				//DELETE ROW
				$dbQuery = 'DELETE FROM `conversationsTable` WHERE `conversationID` = '.$strDesc.' LIMIT 1';
				mysql_query($dbQuery) or die("Couldn't delete.");
		
				echo "deleted!";
				header('Location: http://audimate.me/web/home/conversations/index.php?status=deleted');
			}
		}
	}	

} else {
	header('Location: http://audimate.me/web/login/');
}



?>