<?php


// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);

// Then do everything you want to do on the page
////
////
////


//session_start();
/*
$mainRef = $_SERVER['HTTP_REFERER'];
$urlInfo = parse_url($mainRef);
$myDomain = $urlInfo['host'];
*/

$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala');  
if (!$link) { 
    die('Could not connect.'); 
} 
			
mysql_select_db(audimate_db);
function compare_artists($myAlias){ 
	//take the username and prevent SQL injections 
	//$myAlias_old = $myAlias; 
	//$myAlias = ("<--audiMateUser-->".$myAlias_old."<--audiMateUser-->");
	$myAlias = mysql_real_escape_string($myAlias);
	
	//begin the query 
	//$sql = 'SELECT * FROM `conversationsTable` WHERE `aliasi` LIKE \ '%<--audiMateUserAlias-->Leifertin<--audiMateUserAlias-->%\' LIMIT 0, 30 ';
	$sql = 'SELECT * FROM `portraitsTable` WHERE `alias` = \''.$myAlias.'\'';
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
			
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		echo "0";
	} else { 
		
		//while () {
		$row = mysql_fetch_array($sql_result);
 		$myPortraitCont = $row["portraitContent"];
  		echo $myPortraitCont;
///////////////////////////////
		//header("Content-type: image/jpeg");
        //$im =  imagecreatefromstring(mysql_result($myPortraitCont, 0));
        //imagejpeg($im);
        //imagedestroy($im);
///////////////////////////////
  		//$count++ ;
  		
		//}	
	}
					
}

if (isset($_REQUEST['alias'])) {
	//if ($myDomain == ('.audimate.me') || $mainRef == ('audiMate.displayPortrait')){
		compare_artists($_REQUEST['alias']);
	//} else {
	//	header('Location: http://audimate.me/web/login/');
	//}
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

function stringVal($value)
{
    // We use get_class_methods instead of method_exists to ensure that __toString is a public method
    if (is_object($value) && in_array("__toString", get_class_methods($value)))
        return strval($value->__toString());
    else
        return strval($value);
}

?>