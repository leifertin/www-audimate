<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php

if(isset($_COOKIE['audiMateemail']) && isset($_COOKIE['audiMatepassword'])){

	header('Location: http://audimate.me/web/home/');
}

?>


<html>

	<head>
		<title>audiMate - Login</title>
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

<?php
if (isset($_GET['act'])){
	$errorMessage = ($_GET['act']);
	
	$outputErrorMessage = "dummy";
	
	if ($errorMessage == "created"){
		$outputErrorMessage = "You now exist.";
	} else if ($errorMessage == "del.success"){
		$outputErrorMessage = "You now cease to exist.";
	} else if ($errorMessage == "del.othererror"){
		$outputErrorMessage = "An error occurred while trying to remove you from existence.";
	} else if ($errorMessage == "newpw"){
		$outputErrorMessage = "Your password has been changed.";
	}
	
	
	if ($outputErrorMessage != "dummy"){
		echo '<script>alert(\''.$outputErrorMessage.'\');</script>';
	}
	
	
}
?>

<center>
<table width="300" border="0" align="center" cellpadding="5" cellspacing="2" style="background-image:url('http://audimate.me/audimatePurple.png');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;">
<tr>
<form name="form1" method="post" action="enter.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td colspan="3"><strong>Who are you?<br><br></strong></td>
</tr>
<tr>
<td width="78" align="left">Email</td>
<td>:</td>
<td align="right"><input size="30" name="myemail" type="text" maxlength="150" id="myusername"></td>
</tr>
<tr>
<td align="left">Password</td>
<td>:</td>
<td align="right"><input size="30" name="mypassword" maxlength="40" type="password" id="mypassword"></td>
</tr>
<tr>
<td height="80"></td>
<td height="80"></td>
<td align="right" valign="top" ><br><input type="submit" name="submit" value="Login"></td>

</tr>
</table></td></form>
</tr>
</table>
<br><br>

<a href="http://www.audimate.me/web/reset_pw">Forgot your password?</a>
<a href="http://audimate.me"><h3>audiMate</h3></a>

</center>
	</body>
</html>