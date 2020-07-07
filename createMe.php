<?php

global $myEmail;
global $myAlias;
global $myPassword;
global $myGender;


if (($_SERVER['HTTP_REFERER']) == ('audiMate.makeYourself')){

// Database connection variables

$dbServer = "localhost";

$dbDatabase = "audimate_db";

$dbUser = "audimate8952";

$dbPass = "kr4pp33K0ala";


$sConn = mysql_connect($dbServer, $dbUser, $dbPass)

or die("Couldn't connect to database server");

$dConn = mysql_select_db($dbDatabase, $sConn)

or die("Couldn't connect to database $dbDatabase");

//$userEmail = $_POST['email'];
//$userPassword = $_POST['password'];

$myEmail = $_POST['myEmail'];
$myPassword = $_POST['myPassword'];
$myAlias = $_POST['myAlias'];
$myGender = $_POST['myGender'];

$myEmail = mysql_real_escape_string($myEmail); 
$myPassword = mysql_real_escape_string($myPassword);
$myAlias = mysql_real_escape_string($myAlias); 
$myGender = mysql_real_escape_string($myGender);
 
//begin the query 


//Check if email is in use
$sql_a = 'SELECT * FROM `usersTable` WHERE `email` = \''.$myEmail.'\'';
$sql_result_a = mysql_query($sql_a);
$rows_a = mysql_num_rows($sql_result_a); 

if ($rows_a<=0 ){ 

	//Good, this email isn't in use.
	if (validEmail($myEmail)){
		//Check if alias is in use
		$sql_b = 'SELECT * FROM `usersTable` WHERE `alias` = \''.$myAlias.'\'';
		$sql_result_b = mysql_query($sql_b);
		$rows_b = mysql_num_rows($sql_result_b);
	
		if ($rows_b<=0 ){ 
	
			//Good, this alias isn't in use.
			if (validAlias($myAlias)){
				finishRegistration($myEmail,$myAlias,$myGender,$myPassword);
			} else {
				echo "Your alias had weird characters in it, or it was not the right length.";
			}
	
		} else{
	
			//Bad, This alias is in use.
			echo "This alias is in use.";
	
		}
		
	} else {
		echo "Your email had weird characters in it.";
	}
	
} else { 
	
	//Bad, This email is in use.
	echo "This email is in use.";

}

} else {
	echo "0";
}

function finishRegistration($myEmail,$myAlias,$myGender,$myPassword){
$myLocationResult = file_get_contents("http://audimate.me/myIPLocate.php");
	
	//USERS
	$sqly = 'INSERT INTO `usersTable` (`userID`, `email`, `alias`, `password`, `gender`, `location`, `status`) VALUES (NULL, \''.$myEmail.'\', \''.$myAlias.'\', SHA1(\''.$myPassword.'\'), \''.$myGender.'\', \''.$myLocationResult.'\', \'cc.309\')';
	
	mysql_query($sqly) or die("Couldn't add to users.");
	
	//SOUNDBITES
	$sqly = 'INSERT INTO `soundbitesTable` (`soundbiteID`, `alias`, `soundbiteContent`) VALUES (NULL, \''.$myAlias.'\', \'\');';
	
	mysql_query($sqly) or die("Couldn't add to soundbites.");
	
	//PORTRAITS
	$sqly = 'INSERT INTO `portraitsTable` (`portraitID`, `alias`, `portraitContent`) VALUES (NULL, \''.$myAlias.'\', \'\');';
	
	mysql_query($sqly) or die("Couldn't add to portraits.");
	
	//MATES
	$sqly = 'INSERT INTO `matesTable` (`userID`, `alias`, `mates`) VALUES (NULL, \''.$myAlias.'\', \'\');';
	
	mysql_query($sqly) or die("Couldn't add to mates.");
	
	//ARTISTS
	$sqly = 'INSERT INTO `artistLists` (`entryID`, `alias`, `entry`, `posted`) VALUES (NULL, \''.$myAlias.'\', \'\', \'\');';
	
	mysql_query($sqly) or die("Couldn't add to artists.");
	
	echo ("You now exist.\n\nClick cancel, then login with your new credentials.");
}



////http://www.linuxjournal.com/article/9585?page=0,3
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || 
 ↪checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}





function validAlias($email)
{
	$isValid = true;
	$aliasLen = strlen($email);
      
	if ($aliasLen < 3 || $aliasLen > 50)
      {
         // length is wrong
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $email))
      {
         // character not valid in domain part
         $isValid = false;
      }
   return $isValid;
}

?>