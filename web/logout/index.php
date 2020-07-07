<?php


// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);

// Then do everything you want to do on the page
////
////
////

//session_start();

function user_login($userEmail, $userPassword){ 

$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
if (!$link) { 
    die('Could not connect.'); 
} 
			
mysql_select_db(audimate_db);


	//take the username and prevent SQL injections 
	$userEmail = mysql_real_escape_string($userEmail); 
	$userPassword = mysql_real_escape_string($userPassword); 
	//begin the query 
	//$sql= 'SELECT * FROM `usersTable`';
	$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
	//echo $sql;
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
	
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		header('Location: http://www.audimate.me/');
	} else { 
		while ($row = mysql_fetch_array($sql_result)) {
 			$userAlias = $row["alias"];
				
			//logged in
			
			//Calculate 1 day in the future
			//seconds * minutes * hours * days + current time
			//$inOneDay = 1 * 0 * 0 * 0 + time();
			$inOneDay = (time() - 3600);
			
			//setcookie ("TestCookie", "", , "/~rasmus/", ".example.com", 1);
			//setcookie('audiMateemail', $userEmail, $inOneDay); 
			setcookie('audiMatestatus', '', $inOneDay,'/' ,'.audimate.me'); 
			setcookie('audiMateemail', '', $inOneDay,'/' ,'.audimate.me');
			setcookie('audiMatepassword', '', $inOneDay,'/' ,'.audimate.me');
			setcookie('convoID', '', $inOneDay,'/' ,'.audimate.me');
			
			//setcookie('convoData', $myConversationData, $inOneDay,'/');
			
			//header('Location: http://audimate.me/');
			echo ('<html><head>
			<title>Bye</title>
			<meta http-equiv="REFRESH" content="3;url=http://www.audimate.me">
			</head>
			<body>
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
<font color="#a54c99">You have been logged out.</font><br>In three seconds you will return to the homepage.</center>
			</body></html>');
			
		}	
	}
				
}


$myEmail = $_COOKIE["audiMateemail"];
$myPassword = $_COOKIE["audiMatepassword"];

if(isset($myEmail) && isset($myPassword)){
	
	user_login($myEmail, $myPassword);


} else {
	header('Location: http://www.audimate.me/');
}
			


//displayGZpage();

////
////
////
// Call this function to output everything as gzipped content.
print_gzipped_page();



// Include this function on your pages
function print_gzipped_page() {

    global $HTTP_ACCEPT_ENCODING;
    if( headers_sent() ){
        $encoding = false;
    }elseif( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ){
        
    }elseif( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ){
        $encoding = 'x-gzip';
    }else{
        $encoding = false;
    }

    if( $encoding ){
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 6);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit();
    }else{
        ob_end_flush();
        exit();
    }
}


?>
