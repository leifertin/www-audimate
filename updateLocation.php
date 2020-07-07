<?php


global $strDesc;

if (($_SERVER['HTTP_REFERER']) == ('audiMate.updateLocation')){

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

$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
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
	$myID = $row["userID"];
	
	//remove html tags
	$strDesc = str_replace("<","[",$strDesc);
	$strDesc = str_replace(">","]",$strDesc);
	
	$dbQuery = 'UPDATE usersTable SET `location` = \''.$strDesc.'\' WHERE `userID` = \''.$myID.'\' LIMIT 1;';
	mysql_query($dbQuery) or die("Couldn't update location.");
		
	echo "done!";

}

} else {
	echo "0";
}
?>