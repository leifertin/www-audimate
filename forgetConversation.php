<?php


global $strDesc;


if (($_SERVER['HTTP_REFERER']) == ('audiMate.forgetConversation')){

// Database connection variables

$dbServer = "localhost";

$dbDatabase = "audimate_db";

$dbUser = "audimate8952";

$dbPass = "kr4pp33K0ala";

$sConn = mysql_connect($dbServer, $dbUser, $dbPass)

or die("Couldn't connect to database server");

$dConn = mysql_select_db($dbDatabase, $sConn)

or die("Couldn't connect to database $dbDatabase");

$strDesc = $_POST['strDesc'];
$userEmail = $_POST['email'];
$userPassword = $_POST['password'];

$strDesc = mysql_real_escape_string($strDesc); 
$userEmail = mysql_real_escape_string($userEmail); 
$userPassword = mysql_real_escape_string($userPassword); 
//begin the query 

$sql = 'SELECT `alias`, `email`, `password` FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
//echo $sql;
$sql_result = mysql_query($sql);
//check to see how many rows were returned 
$rows = mysql_num_rows($sql_result); 

//$count = 1 + $rows;
if ($rows<=0 ){ 
	echo "0";
} else { 

	//$theAliasi = mysql_real_escape_string($strDesc);
	$row = mysql_fetch_array($sql_result);
 	$myAlias = $row["alias"];
 
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
		
		//} else if ($delAlias == 0) {
		
		//$dbQuery = 'UPDATE conversationsTable SET `deletedAlias` = \''.$myAlias.'\' WHERE `conversationID` = \''.$strDesc.'\' LIMIT 1;';
		//mysql_query($dbQuery) or die("Couldn't update.");
		
		//echo "updated!";
		
		} else if ($delAlias == $myAlias) {
		//Do Nothing
		echo "did nothing!";
		} else {
		//DELETE ROW
		$dbQuery = 'DELETE FROM `conversationsTable` WHERE `conversationID` = '.$strDesc.' LIMIT 1';
		mysql_query($dbQuery) or die("Couldn't delete.");
		
		echo "deleted!";
		}
	}

}

} else {
	echo "0";
}
?>