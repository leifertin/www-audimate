<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php

if(isset($_COOKIE['audiMateemail']) && isset($_COOKIE['audiMatepassword'])){

	header('Location: http://audimate.me/web/home/');
}

?>


<html>

	<head>
		<title>audiMate - Make Yourself</title>
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
if (isset($_GET['err'])){
	$errorMessage = ($_GET['err']);
	
	if ($errorMessage == "badalias"){
		$outputErrorMessage = "Your alias had weird characters in it, or it was not the right length";
		
	} else if ($errorMessage == "aliasinuse"){
		$outputErrorMessage = "This alias is in use.";
		
	} else if ($errorMessage == "bademail"){
		$outputErrorMessage = "Your email had weird characters in it.";
		
	} else if ($errorMessage == "emailinuse"){
		$outputErrorMessage = "This email is in use.";
		
	} else if ($errorMessage == "created"){
		header('Location: http://audimate.me/web/login/index.php?act=created');
		
	} else if ($errorMessage == "nopassword"){
		$outputErrorMessage = "You did not provide a password.";
		
	} else if ($errorMessage == "passwordnomatch"){
		$outputErrorMessage = "Your password and confirmation password did not match.";
		
	} else if ($errorMessage == "under18"){
		$outputErrorMessage = "You must be at least 18 to register.";
		
	} else if ($errorMessage == "othererror"){
		$outputErrorMessage = "An error occurred during the process of your creation.";
		
	}
	
	echo '<script>alert(\''.$outputErrorMessage.'\');</script>';
	
	
}
?>

<table width="500" border="0" align="center" cellpadding="5" cellspacing="2" style="background-image:url('http://audimate.me/audimatePurple.png');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;">
<tr>
<td>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
<form name="form1" method="post" action="finish.php">
<tr>
<td colspan="3"><strong>Who are you?<br><br></strong></td>
</tr>

<tr>
<td width="180">Email</td>
<td width="20">:</td>
<td align="left"><input size="40" name="myemail" type="text" maxlength="150" id="myusername"></td>
</tr>

<tr>
<td align="left">Alias</td>
<td>:</td>
<td align="left"><input size="40" name="myalias" type="text" maxlength="150" id="myusername"></td>
</tr>


<tr>
<td align="left">Password</td>
<td>:</td>
<td align="left"><input size="40" name="mypassword" maxlength="40" type="password" id="mypassword"></td>
</tr>

<tr>
<td align="left">Confirm Password</td>
<td>:</td>
<td align="left"><input size="40" name="mycpassword" maxlength="40" type="password" id="mypassword"></td>
</tr>

<tr>
<td align="center" valign="top"><br>Gender :<br><br>
F <input type="radio" name="mygender" value="Female" checked>&nbsp;
M <input type="radio" name="mygender" value="Male"><br><br>
</td>
<td></td>
<td align="center" valign="top"><br>I am at least 18:<br><br>
<input type="checkbox" name="over18" value="correct"><br><br>
</td>
</tr>
</form>
</table></td>
</table>

<table width="500" cellpadding="10" cellspacing="3">
</tr>
<td align="center" valign="middle"><br><br>
<a href="javascript: void(0);" onclick="document.form1.submit();return false;">Finish</a>
</td>
</table>



<a href="http://audimate.me"><h3>audiMate</h3></a>

</center>
	</body>
</html>
