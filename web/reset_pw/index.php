
<?php

if(isset($_COOKIE['audiMateemail']) && isset($_COOKIE['audiMatepassword'])){

	header('Location: http://audimate.me/web/home/');
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Reset Password</title>
	</head>
	<body bgcolor="#000000">
	
<style>
body
{
background-color:black;
color:white;
font-family:verdana;

margin-bottom:0px;
margin-top:70px;
margin-left:30px;
margin-right:30px;
}

A:link {text-decoration: underline; color: white;}
A:visited {text-decoration: none; color: white;}
A:active {text-decoration: none; color: #a54c99;}
A:hover {text-decoration: none; color: #a54c99;}


h3
{
background-color:black;
font-family:verdana;
position:absolute;
top:5px;

}

</style>



<center>



<?php


function drawResetPW(){


echo'
<table width="300" border="0" align="center" cellpadding="5" cellspacing="2" style="background-image:url(\'http://audimate.me/audimatePurple.png\');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;">
<tr>
<form name="reset_pw" method="post" action="index.php">

<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td width="78" align="right">Email:</td>
<td>
<input type="text" size="40" name="em" value="">

</td>
</tr>

<tr>
<td width="78" align="right">Alias:</td>
<td>
<input type="text" size="40" name="alias" value="">

</td>
</tr>

<tr>
<td align="right" valign="top" colspan="2"><input type="submit" name="submit" value="Reset Password"></td>

</tr>
</table></td></form>
</tr>
</table>
';


}

function drawResetPW_checkEM($em){

echo'


<center><table width="55%" align="center"><td align="center">
An email with instructions on how to reset your password has been sent to <font color="#a54c99">'.$em.'</font>.</td></table></center>


';

}


function drawEnterNewPW($em,$emconf){

echo'
<table width="450" border="0" align="center" cellpadding="5" cellspacing="2" style="background-image:url(\'http://audimate.me/audimatePurple.png\');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;">
<tr>
<form name="reset_pw" method="post" action="process.php">
<input type="hidden" name="em" value="'.$em.'">
<input type="hidden" name="emconf" value="'.$emconf.'">


<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td colspan="2"><strong>Change your password<br><br></strong></td>
</tr>
<tr>
<td align="right">New Password:</td>
<td align="left" width="150"><input size="30" name="pw_new" maxlength="40" type="password"></td>
</tr>
<tr>
<td align="right">Confirm New Password:</td>
<td align="left"><input size="30" name="pw_new_conf" maxlength="40" type="password"></td>
</tr>
<tr>
<td align="right" valign="top" colspan="2"><br><input type="submit" name="submit" value="Confirm"></td>

</tr>
</table></td></form>
</tr>
</table>
';

}

////////
////////



if (isset($_GET['em']) && isset($_GET['emconf'])){
	
	$checkEmail = $_GET['em'];
	$checkConfID = $_GET['emconf'];
	//check if em and emconf are the in the same row in usersTable
	
	
	$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
	if (!$link) { 
    	die('Could not connect.'); 
	} 
			
	mysql_select_db(audimate_db);

	//take the username and prevent SQL injections 
	$checkEmail_b = mysql_real_escape_string($checkEmail); 
	$checkConfID_b = mysql_real_escape_string($checkConfID); 
	//begin the query 
	$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$checkEmail_b.'\' AND `emconf` = \''.$checkConfID_b.'\'';
	//echo $sql;
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
	
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		//echo "no!";
		drawResetPW();
	} else { 
		//echo "yes!";
		drawEnterNewPW($checkEmail, $checkConfID);
	}

} else if ((isset($_POST['em'])) && (($_POST['em']) != "")){
	
	if ((isset($_POST['alias'])) && (($_POST['alias']) != "")){
		///CREATE RANDOM NUMBER
		
		include("../includeglobals/generateCheckID.php");
		
		///COPY SAID NUMBER TO emconf column
	
		//echo $checkID;
		$checkEmail = $_POST['em'];
		$checkAlias = $_POST['alias'];
	
	
		//check if em and emconf are the in the same row in usersTable
	
	
		$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
		if (!$link) { 
    		die('Could not connect.'); 
		} 
			
		mysql_select_db(audimate_db);

		//take the username and prevent SQL injections 
		$checkEmail_b = mysql_real_escape_string($checkEmail); 
		$checkAlias_b = mysql_real_escape_string($checkAlias);
		$checkID_b = mysql_real_escape_string($checkID);
		
		
		//begin the query 
		$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$checkEmail_b.'\' AND `alias` = \''.$checkAlias_b.'\'';
		//echo $sql;
		$sql_result = mysql_query($sql);
		//check to see how many rows were returned 
		$rows = mysql_num_rows($sql_result); 
	
		//$count = 1 + $rows;
		if ($rows<=0 ){ 
			drawResetPW();
		} else { 
			
			$sqly = 'UPDATE usersTable SET `emconf` = \''.$checkID_b.'\' WHERE `email` = \''.$checkEmail_b.'\' LIMIT 1;';
			mysql_query($sqly) or die("Failed.");
			
			///SEND EMAIL
			
			$to = $checkEmail;
			$subject = "Reset your audiMate password";
			$body = ($checkAlias.",\nYou recently requested your audiMate password be reset.\n\n
If you wish to continue the process, go to:
http://www.audimate.me/web/reset_pw?em=".$checkEmail."&emconf=".$checkID."\n\n
If you've changed your mind, or you didn't initiate this request, just ignore this email.
			
");
			
			
			if (mail($to, $subject, $body)) {
 				drawResetPW_checkEM($_POST['em']);
			} else {
				drawResetPW();
			}
			
			
			///
			
		}
		
	} else {
			
		drawResetPW();
		
	}
	

} else {

	drawResetPW();

}


?>





<a href="http://audimate.me"><h3>audiMate</h3></a>

</center>
	</body>
</html>