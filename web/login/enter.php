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
		header('Location: http://audimate.me/web/login/');
	} else { 
		while ($row = mysql_fetch_array($sql_result)) {
 			$userAlias = $row["alias"];

			$userStatus = $row["status"];
			if ($userStatus == "b0i9") {
				$userStatus = sha1("1");
			} else if ($userStatus == "cc.309") {
				$userStatus = sha1("0");
			} else {
				$userStatus = sha1("void");
			}
			
				
			//logged in
			
			//Calculate 3 hours in the future
			//seconds * minutes * hours * days + current time
			$inOneDay = (60 * 60 * 3 * 1) + time();
			//setcookie('audiMateemail', $userEmail, $inOneDay); 
			//setcookie('audiMatepassword', $userPassword, $inOneDay); 
			setcookie('audiMateemail', $userEmail, $inOneDay,'/' ,'.audimate.me');
			setcookie('audiMatepassword', $userPassword, $inOneDay,'/' ,'.audimate.me');
			setcookie('audiMatestatus', $userStatus, $inOneDay,'/' ,'.audimate.me');
			
			//echo "hey";
			header('Location: http://audimate.me/web/home/');
			
			//echo '<html><head><meta http-equiv="REFRESH" content="1;url=http://audimate.me/web/home/"></head><body></body></html>';
		}	
	}
				
}
			
if (isset($_POST['myemail']) && isset($_POST['mypassword'])) {
	$myRef = $_SERVER['HTTP_REFERER'];
	$myRefQ = $_SERVER['QUERY_STRING'];
	
	$myRef_a = explode("?",$myRef);
	$myRef_a = $myRef_a[0];
	
	$myRef_b = explode("audimate.me/",$myRef_a);
	$myRef_b = $myRef_b[1];
	
	$myRef = $myRef_b;
	
	if (($myRef) == ('web/login/index.php') || ($myRef) == ('web/login/')){
		user_login($_POST['myemail'], $_POST['mypassword']);
	} else {
		header('Location: http://audimate.me/web/login/');
	}
} else {
		header('Location: http://audimate.me/web/login/');
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
