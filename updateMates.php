<?php

// GrabFile.php: Takes the details

// of the new file posted as part

// of the form and adds it to the

// myBlobs table of our myFiles DB.



global $strDesc;

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

//if(empty($strDesc) || $fileUpload == "none")

//die("You must enter both a description and file");

if (($_SERVER['HTTP_REFERER']) == ('audiMate.updateMates')){

// Database connection variables

$dbServer = "localhost";
$dbDatabase = "audimate_db";

$dbUser = "audimate8952";
$dbPass = "kr4pp33K0ala";

//$fileHandle = fopen($fileUpload, "r");
//$fileContent = fread($fileHandle, $fileUpload_size);
//$fileContent = addslashes($fileContent);

$sConn = mysql_connect($dbServer, $dbUser, $dbPass)

or die("Couldn't connect to database server");

$dConn = mysql_select_db($dbDatabase, $sConn)

or die("Couldn't connect to database $dbDatabase");

$strDesc = $_POST['strDesc'];
$userEmail = $_POST['email'];
$userPassword = $_POST['password'];

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
	
	$dbQuery = 'UPDATE matesTable SET `mates` = \''.$fileContent.'\' WHERE `alias` = \''.$strDesc.'\' LIMIT 1;';

	mysql_query($dbQuery) or die("Couldn't add file to database");

echo "<h1>File Uploaded</h1>";

echo "The details of the uploaded file are shown below:<br><br>";

echo "<b>File name:</b> $fileUpload_name <br>";

echo "<b>File type:</b> $fileUpload_type <br>";

echo "<b>File size:</b> $fileUpload_size <br>";

echo "<b>Uploaded to:</b> $fileUpload <br><br>";

echo "<a href='uploadfile.php'>Add Another File</a>";

}

} else {
	echo "0";
}
?>