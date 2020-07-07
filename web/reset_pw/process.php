<?php


// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);

// Then do everything you want to do on the page
////
////
////

//session_start();



function user_login($userEmail, $userConf, $userPW){ 

$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
if (!$link) { 
    die('Could not connect.'); 
} 
			
mysql_select_db(audimate_db);


	//take the username and prevent SQL injections 
	$userEmail = mysql_real_escape_string($userEmail); 
	$userConf = mysql_real_escape_string($userConf); 
	$userPW = mysql_real_escape_string($userPW);
	
	//begin the query 
	$sql = 'SELECT * FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `emconf` = \''.$userConf.'\'';
	//echo $sql;
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
	
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		header('Location: http://audimate.me/web/reset_pw/');
	} else { 
		while ($row = mysql_fetch_array($sql_result)) {
 			
			//UPDATE PW
			
			include("../includeglobals/generateCheckID.php");
			//$checkID = generateCheckID();
			$checkID = mysql_real_escape_string($checkID);
			
			
			$sqly = 'UPDATE usersTable SET `password` = SHA1(\''.$userPW.'\') WHERE `email` = \''.$userEmail.'\' LIMIT 1;';
			mysql_query($sqly) or die("Failed (1).");
			$sqly = 'UPDATE usersTable SET `emconf` = \''.$checkID.'\' WHERE `email` = \''.$userEmail.'\' LIMIT 1;';
			mysql_query($sqly) or die("Failed (2).");
						
			//echo "hey";
			header('Location: http://audimate.me/web/login/index.php?act=newpw');
			
			//echo '<html><head><meta http-equiv="REFRESH" content="1;url=http://audimate.me/web/home/"></head><body></body></html>';
		}	
	}
				
}
			
if (isset($_POST['em']) && isset($_POST['emconf']) && isset($_POST['pw_new']) && isset($_POST['pw_new_conf'])) {
	$myRef = $_SERVER['HTTP_REFERER'];
	$myRefQ = $_SERVER['QUERY_STRING'];
	
	$myRef_a = explode("?",$myRef);
	$myRef_a = $myRef_a[0];
	
	$myRef_b = explode("audimate.me/",$myRef_a);
	$myRef_b = $myRef_b[1];
	
	$myRef_o = $myRef;
	$myRef = $myRef_b;
	
	if (($myRef) == ('web/reset_pw/index.php') || ($myRef) == ('web/reset_pw/')){
		
		if (($_POST['pw_new']) == ($_POST['pw_new_conf'])){
			user_login($_POST['em'], $_POST['emconf'], $_POST['pw_new']);
		} else {
			header('Location: '.$myRef_o);
		}
		
	} else {
		header('Location: http://audimate.me/web/reset_pw/');
	}
} else {
		header('Location: http://audimate.me/web/reset_pw/');
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


