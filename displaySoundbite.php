<?php

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
	$sql = 'SELECT * FROM `soundbitesTable` WHERE `alias` = \''.$myAlias.'\'';
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
			
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		echo "0";
	} else { 
		
		while ($row= mysql_fetch_array($sql_result)) {
 			$mySoundbiteCont = $row["soundbiteContent"];
			$mySoundbiteMIME = $row["mime"];
		
			if ($mySoundbiteCont == ""){
		
				echo "zeroSound";
		
			} else {
		
				if ($mySoundbiteMIME == ""){
					$mimeString = "Content-type: audio/mpeg";
				} else {
					$mimeString = $mySoundbiteMIME;
				}
				header($mimeString);
  				echo $mySoundbiteCont;
		
			}
  		
		}	
	}
					
}

if (isset($_GET['alias'])) {
	//if (($_SERVER['HTTP_REFERER']) == ('audiMate.displaySoundbite')){
		compare_artists($_GET['alias']);
	//} else {
		//echo "0";
	//}
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