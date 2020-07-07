<?php


// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);

// Then do everything you want to do on the page
////
////
////

//session_start();
$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
if (!$link) { 
    die('Could not connect.'); 
} 
			
mysql_select_db(audimate_db);
function user_login($userAlias, $userArtistList){ 
	//take the username and prevent SQL injections 
	$userAlias = mysql_real_escape_string($userAlias); 
	//$userArtistList = mysql_real_escape_string($userArtistList); 
	//echo $userArtistList;
	
	$userArtistList = mysql_real_escape_string($userArtistList);
	//$userArtistList = stripslashes($userArtistList);
	
	
	//begin the query 
	$sql = 'UPDATE `artistLists` SET `entry` = \''.$userArtistList.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
	//echo $sql;
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	//$rows = mysql_num_rows($sql_result); 
	
	echo $sql_result;
	
	
				
}
			
if (isset($_POST['alias']) && isset($_POST['artistList'])) {
	if (($_SERVER['HTTP_REFERER']) == ('audiMate.syncArtists')){
		$time = is_string($date) ? strtotime($date) : (is_int($date) ? $date : time());
		$myMonth = date('n', $time);
		if (($myMonth) == ($_POST['month'])){
			user_login($_POST['alias'], $_POST['artistList']);
		} else {
			echo "0";
		}
	} else {
		echo "0";
	}
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