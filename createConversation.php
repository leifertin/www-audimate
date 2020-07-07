<?php

global $theirAlias;

if (($_SERVER['HTTP_REFERER']) == ('audiMate.createConversation')){

	// Database connection variables
	$dbServer = "localhost";
	$dbDatabase = "audimate_db";
	$dbUser = "audimate8952";
	$dbPass = "kr4pp33K0ala";

	$sConn = mysql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to database server");
	$dConn = mysql_select_db($dbDatabase, $sConn)
	or die("Couldn't connect to database $dbDatabase");

	$userEmail = $_POST['email'];
	$userPassword = $_POST['password'];
	
	$theirAlias = $_POST['theirAlias'];
	

	$userEmail = mysql_real_escape_string($userEmail); 
	$userPassword = mysql_real_escape_string($userPassword); 
	//begin the query 

	$sql = 'SELECT `alias`, `email`, `password` FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
	$sql_result = mysql_query($sql);
	$rows = mysql_num_rows($sql_result); 

	if ($rows<=0 ){ 
		echo "0";
	} else { 

		$row = mysql_fetch_array($sql_result);
 		$myAlias = $row["alias"];
 	
		$sqlB = 'SELECT * FROM `conversationsTable` WHERE `aliasi` = \'<--audiMateUser-->'.$myAlias.'<--audiMateUser-->'.$theirAlias.'<--audiMateUser-->\'';
		$sqlRes = mysql_query($sqlB);

		$rowsz = mysql_num_rows($sqlRes); 
		if ($rowsz<=0 ){ 
	
			//GOOD
			//CHECK FOR OTHER VARIATION
			$sqlC = 'SELECT * FROM `conversationsTable` WHERE `aliasi` = \'<--audiMateUser-->'.$theirAlias.'<--audiMateUser-->'.$myAlias.'<--audiMateUser-->\'';
			$sqlResC = mysql_query($sqlC);
			$rowszb = mysql_num_rows($sqlResC);
			if ($rowzb<=0 ){ 
				//GOOD!
				//CREATE CONVO!
				$sql = 'INSERT INTO `conversationsTable` (`conversationID`, `conversationText`, `aliasi`, `lastActivity`, `deletedAlias`) VALUES (NULL, \'\', \'<--audiMateUser-->'.$myAlias.'<--audiMateUser-->'.$theirAlias.'<--audiMateUser-->\', NOW(), \'0\');';
				$sql = mysql_query($sql);
				echo "conversation created!";
			
			} else { 
		 		//ALREADY EXISTS
				$rowYA = mysql_fetch_array($sqlRes);
 				$convoID = $rowYA["conversationID"];
				$deletedAlias = $rowYA["deletedAlias"];
			
				if ($deletedAlias == $myAlias){
					//SET TO "0"
					$dbQuery = 'UPDATE conversationsTable SET `deletedAlias` = \'0\' WHERE `conversationID` = \''.$convoID.'\' LIMIT 1;';
					mysql_query($dbQuery) or die("Couldn't add file to database");
					echo "un-hidden.";
				} else {
					echo $convoID;
				}
			}
		} else { 
			//ALREADY EXISTS
			$rowYA = mysql_fetch_array($sqlRes);
 			$convoID = $rowYA["conversationID"];
			$deletedAlias = $rowYA["deletedAlias"];
			
			if ($deletedAlias == $myAlias){
				//SET TO "0"
				$dbQuery = 'UPDATE conversationsTable SET `deletedAlias` = \'0\' WHERE `conversationID` = \''.$convoID.'\' LIMIT 1;';
				mysql_query($dbQuery) or die("Couldn't add file to database");
				echo "un-hidden.";
			} else {
				echo $convoID;
			}
		}
	}

} else {
	echo "0";
}
?>