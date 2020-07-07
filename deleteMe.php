<?php

global $myEmail;
global $myAlias;
global $myPassword;


if (($_SERVER['HTTP_REFERER']) == ('audiMate.deleteMe')){

// Database connection variables

$dbServer = "localhost";
$dbDatabase = "audimate_db";
$dbUser = "audimate8952";
$dbPass = "kr4pp33K0ala";


$sConn = mysql_connect($dbServer, $dbUser, $dbPass)

or die("Couldn't connect to database server");

$dConn = mysql_select_db($dbDatabase, $sConn)

or die("Couldn't connect to database $dbDatabase");

$myEmail = $_POST['email'];
$myPassword = $_POST['password'];

$myEmail = mysql_real_escape_string($myEmail); 
$myPassword = mysql_real_escape_string($myPassword);
 
//begin the query 

//Check if user exists
$sql_a = 'SELECT * FROM `usersTable` WHERE `email` = \''.$myEmail.'\' AND `password` = SHA1(\''.$myPassword.'\')';
$sql_result_a = mysql_query($sql_a);
$rows_a = mysql_num_rows($sql_result_a); 

	if ($rows_a<=0 ){ 

		//User doesn't exist!
		//Why delete?
		echo "wtf?";
	
	} else { 
		$row = mysql_fetch_array($sql_result_a);
		$myAlias = $row["alias"];
		
		//Good, This email is in use.
		finishRegistration($myAlias);
	}

} else {
	echo "0";
}

function finishRegistration($myAlias){


	//USERS
	$sqly = 'DELETE from usersTable where alias=\''.$myAlias.'\'';
	mysql_query($sqly) or die("Couldn't delete from users.");
	
	//SOUNDBITES
	$sqly = 'DELETE from soundbitesTable where alias=\''.$myAlias.'\'';
	mysql_query($sqly) or die("Couldn't delete from soundbites.");
	
	//PORTRAITS
	$sqly = 'DELETE from portraitsTable where alias=\''.$myAlias.'\'';
	mysql_query($sqly) or die("Couldn't delete from portraits.");
	
	//MATES
	$sqly = 'DELETE from matesTable where alias=\''.$myAlias.'\'';
	mysql_query($sqly) or die("Couldn't delete from mates.");
	
	//ARTISTS
	$sqly = 'DELETE from artistLists where alias=\''.$myAlias.'\'';
	mysql_query($sqly) or die("Couldn't delete from artists.");
	
	//FORGET CONVERSATIONS from Applescript!!
	
	echo ("You now cease to exist.");
}


?>