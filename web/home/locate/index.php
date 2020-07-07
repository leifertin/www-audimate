<?php


// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);

// Then do everything you want to do on the page
////
////
////

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

	<head>
		<title>audiMate - Home: Locate</title>
		
		<?php

if (($_GET["waitLonger"]) == "1"){
	$echoMsg = "Until you activate yourself, you are only allowed to locate once per hour.";
	echo '<script type="text/javascript">alert(\''.$echoMsg.'\');</script>';
	
	echo '<meta http-equiv="refresh" content="0;url=http://audimate.me/web/home/locate" />';

}

?>
	</head>
	<body bgcolor="#000000">

<?php

include("../../includeglobals/printMainCSS.php");
include("../../includeglobals/printHeader.php");
include("../../includeglobals/timeFunctions.php");
include("../../includeglobals/getMates.php");

?>





<?php
//include ("http://www.audimate.me/web/home/locate/progressBar.php?prog=".$myProgressIndicator);
function matchMaker($userEmail, $userPassword, $myStatus){ 

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
			$lastLocate = $row["lastLocate"];
		}
	}
	
	
	$myLocation = getMyDetails($userAlias);
	
	
	
	if ($myStatus == "356a192b7913b04c54574d18c28d46e6395428ab"){
			//Status == 1
			$lastLocate = rightNow();
			
			
	} else if ($myStatus == "b6589fc6ab0dc82cf12099d1c2d40ab994e8410c"){
			//Status == 0
			
			if ($lastLocate == ""){
				$lastLocate = rightNow();
			}else{
				if (($lastLocate+3600) <= (rightNow())){
					//you've waited long enough
					$lastLocate = rightNow();
				} else {
					
					header("Location: http://audimate.me/web/home/locate/?waitLonger=1");
				}
			}
			
	} else {
			$lastLocate = "";
			$errorText = "error";
	}
	
	$sqloo = 'UPDATE `usersTable` SET `lastLocate` = \''.$lastLocate.'\' WHERE `alias` = \''.$userAlias.'\' LIMIT 1;';
	$sql_resultoo = mysql_query($sqloo);
				
	
	$myMates = getMates($userAlias);
	$myArtists = getMyArtists($userAlias);
			
	$myArtists = explode("\n", $myArtists);
			
			
	/////////////////
	////GET COUNT
	$myARTISTScount = count($myArtists);			
	
	
	printHeader("home", "Locate", $userAlias, $myLocation);
	
	echo '
<iframe scrolling="no" marginwidth="0" marginheight="0" frameborder="0" src="http://www.audimate.me/web/home/locate/progressBar.php?prog=0" height="50" width="100%" id="progressBar" name="progressBar" ></iframe>
';		
			
	//////////
	///COMPARE ME.start
	//////////
	$myCompare_initialBatch = "";
	$myArtistLoop = 0;
	$mySkipperVar = 0;
	
	
	$mySkipperVarLimit = 5;
	
		
	while ($myArtistLoop <= $myARTISTScount){
		
			
		if ($mySkipperVar == $mySkipperVarLimit){
		$mySkipperVar = 0;
		
		ob_end_flush();
		ob_start();	
		
		
		
		
		
		$myProgressIndicator = (($myArtistLoop/$myARTISTScount)*100);
		$myProgressIndicator = round($myProgressIndicator, 0);
		
		//echo $myProgressIndicator;
		//include ("http://www.audimate.me/web/home/locate/progressBar.php?prog=".$myProgressIndicator);
		
		
		
		$myProgressURL = ("http://www.audimate.me/web/home/locate/progressBar.php?prog=".$myProgressIndicator);
		//echo 
		echo ('<script type="text/javascript">document.getElementById("progressBar").src = "'.$myProgressURL.'";</script>
');
		
		ob_flush();
		flush();	
			
		}
		
		$mySkipperVar++;
		
		
		
		$curl_connection = curl_init('http://audimate.me/compareMe.php');
				
		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.compareMe');
				
		$currentArtist = $myArtists[$myArtistLoop];
		$post_data['artist'] = $currentArtist;
			

		foreach ( $post_data as $key => $value) {
			$post_items[] = $key . '=' . $value;
		}
		$post_string = implode ('&', $post_items);
	
		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
		$myCurrentCompare = curl_exec($curl_connection);
		curl_close($curl_connection);
				
				
		if (($myCurrentCompare == "") || ($myCurrentCompare == "0")){
				//
		} else {
			//
			$myCompare_initialBatch = ($myCompare_initialBatch.$myCurrentCompare."<--audiMateUserAlias-->");
		}
				
		$myArtistLoop++;
		
		
				
	}
	
	
	
					
	//////////
	///COMPARE ME.end
	//////////	
		
	$myCompare_initialList = explode("<--audiMateUserAlias-->",$myCompare_initialBatch);
	$errorText = "";
	
	
	
	
	$myARTISTScount = count($myCompare_initialList);
	
	$list2 = array();
	$myArtistLoop = 1;
			
	while ($myArtistLoop <= ($myARTISTScount)){
		$currArtVar = $myCompare_initialList[$myArtistLoop];
		if (in_array($currArtVar,$list2)){
			//do nothing
		} else {
			if (($currArtVar != "") && ($currArtVar != "1")){
	 		array_push($list2,$currArtVar);
			}
		}
	
		$myArtistLoop++;
	}
	sort($list2);
		
	$matchedUsers = $list2;	
	

	$myARTISTScount = count($matchedUsers);
		
		
	$myArtistLoop = 0;
	$finishedMatchedUserString = "";
	$myInitialListCount = count($myCompare_initialList);	
	$mySkipperVar = 0;
	
	
	//$mySkipperVarLimit = round(($myInitialListCount/4));
	//if ($mySkipperVarLimit < 1){
	
	$mySkipperVarLimit = 5;
	
	//}
	
	while ($myArtistLoop <= ($myInitialListCount)){
	
		
		
		if ($mySkipperVar == $mySkipperVarLimit){
			$mySkipperVar = 0;
			
			ob_end_flush();
			ob_start();
			
			$myProgressIndicator = 100-((($myArtistLoop/$myInitialListCount)*100));
			$myProgressIndicator = round($myProgressIndicator, 0);
	
		
		
			$myProgressURL = ("http://www.audimate.me/web/home/locate/progressBar.php?prog=".$myProgressIndicator);
	
			echo ('<script type="text/javascript">document.getElementById("progressBar").src = "'.$myProgressURL.'";</script>
');
		ob_flush();
		flush();
		
		}
		$mySkipperVar++;
		
		

	
		$matchedUser = $matchedUsers[$myArtistLoop];
		$myCommonArtistsCURLed = findDuplicates($myCompare_initialList,$matchedUser);
		
		if ($myCommonArtistsCURLed > 99999){
			//do nothing
			//six digits
		} else if ($myCommonArtistsCURLed > 9999){
			//five digits
			$myCommonArtistsCURLed = ("0".$myCommonArtistsCURLed);
		} else if ($myCommonArtistsCURLed > 999){
			$myCommonArtistsCURLed = ("00".$myCommonArtistsCURLed);
		} else if ($myCommonArtistsCURLed > 99){
			$myCommonArtistsCURLed = ("000".$myCommonArtistsCURLed);
		} else if ($myCommonArtistsCURLed > 9){
			$myCommonArtistsCURLed = ("0000".$myCommonArtistsCURLed);
		} else {
			$myCommonArtistsCURLed = ("00000".$myCommonArtistsCURLed);
		}
		//echo $myCommonArtistsCURLed."<br>";
		
		$finishedMatchedUserString = ($finishedMatchedUserString."<--audiMateUserCommonAmnt-->".$myCommonArtistsCURLed."<--audiMateUserCommonAmnt-->");
		$finishedMatchedUserString = ($finishedMatchedUserString."<--audiMateUserAlias-->".$matchedUser."<--audiMateUserAlias-->\n");
			
		$myArtistLoop++;
		
		///end of loop
		//ob_flush();
		//flush();
		
	}
	
	
	
	
	
	
	$myMatchedUsers = explode("\n",$finishedMatchedUserString);
	$seekAndDestroy = array_most_common($myMatchedUsers);
	sort($myMatchedUsers);
	$myMatchedUsers_st = implode($myMatchedUsers);
	$myMatchedUsers_st = str_replace($seekAndDestroy,"",$myMatchedUsers_st);
	
	
	
	
	$myMatchedUsers = $myMatchedUsers_st;
	
	//////REMOVE MATES FROM OUTPUT
	if ($myMates != "1"){
			
		$myMates = explode("\n", $myMates);
		//$myMates = sort($myMates);
			
		sort($myMates);
		
		$i = 0;
		$myBigMate = "";
			
			
		while ($i <= (count($myMates))):
				
			$myCurrentMateItem = $myMates[$i];
			
			if ($myCurrentMateItem != ""){
			$myMatchedUsers = str_replace(("<--audiMateUserAlias-->".$myCurrentMateItem."<--audiMateUserAlias-->"), ("<--audiMateUserAlias-->0<--audiMateUserAlias-->"),$myMatchedUsers);
			}
			
			$i++;
	
		endwhile;
			
	} else {
		$myMates = "Locate, view, and remember mates to save them to this list.";
	}
	
	
	//////REMOVE SELF FROM OUTPUT
		
	$myMatchedUsers = str_replace(("<--audiMateUserAlias-->".$userAlias."<--audiMateUserAlias-->"), ("<--audiMateUserAlias-->0<--audiMateUserAlias-->"),$myMatchedUsers);
	
	//echo "hey<br>".$myMatchedUsers."<br>hey";
	
	
	
	
	
	
	
	//FINAL FORMATTING
	$myMatchedUsers = explode("<--audiMateUserAlias-->", $myMatchedUsers);
	
	
	$myMatchedUsers = implode($myMatchedUsers);
	$myMatchedUsers = explode("<--audiMateUserCommonAmnt-->", $myMatchedUsers);
	
	$matchedUsers = $myMatchedUsers;
	array_shift($matchedUsers);
	
		
	$myARTISTScount = count($matchedUsers);
		
	$myMatchedUsers = array();
	$myMatchedAmnt = array();
		
	$myArtistLoop = 0;
	
	
	
	$mySkipperVar = 0;
	while ($myArtistLoop <= $myARTISTScount){ 
	
	
		if (empty($matchedUsers)){
			//it's empty
			break;
		} 
		$currArtVar = $matchedUsers[$myArtistLoop];
		$currArtVar2 = $matchedUsers[$myArtistLoop+1];		
		
		if ($currArtVar2 == "0" || $currArtVar2 == "") {
			$myArtistLoop++;
		} else {
			
			array_push($myMatchedUsers,$currArtVar);
			//$matchedUsers = array_shift($matchedUsers);
		}
		$myArtistLoop++;
	}
	
	//print_r ($myMatchedUsers);
	//echo "<br><br>";	
				
	/////FINISHED SPLITTING INTO COLUMNS
		
		
		
	$myProgressURL = ("http://www.audimate.me/web/home/locate/progressBar.php?prog=0");
	echo ('<script type="text/javascript">document.getElementById("progressBar").src = "'.$myProgressURL.'";
document.getElementById("progressBar").height = "0";</script>');	
		
		
		
	if (empty($myMatchedUsers)){
		//No Matches
		$errorText =  ("There were no matches.\nMaybe if you try again later...");
	} else { 
		
		//LIMIT RESULTS
		$confirmTruncate = false;
		$mySearchMax = 25;
		
		
		
		
		
		///CLEAN myMatched
		
		
		$element = $myMatchedUsers[0];
		
		
			$myMatchedUsers = array_reverse($myMatchedUsers);
		
		
		
		////////////////////
		
		if ((count($myMatchedUsers)) > ($mySearchMax*2)){
			$myMatchedUsers = array_slice($myMatchedUsers, 0, ($mySearchMax*2));
			

		}
		
		
		
		
		
		//FINISHED LIMITING
			
		///OUTPUT FINAL!
		
			
			
		
			
		$finalMatchedOut = '<table width="100%">
				<th width="80" align="center">Rank</th>
				<th align="left">Alias</th>
				<tr><td colspan="2">
				<br></td></tr>';
				
		$rank = 1;
		
		
		$myARTISTScount = count($myMatchedUsers);
		$myArtistLoop = 0;
		
		
		
		//print_r ($myMatchedUsers);
		while ($myArtistLoop <= $myARTISTScount){ 
			
			
			$currArtItem = $myMatchedUsers[$myArtistLoop];
			if ($currArtItem != ""){
			$finalMatchedOut = ($finalMatchedOut."<tr><td width=\"50\" align=\"center\">".$rank."</td>");
			$finalMatchedOut = ($finalMatchedOut."<td><a href=http://audimate.me/web/home/mates/view.php?alias=".$currArtItem.">".$currArtItem."</a></td></tr>");
			}
			
			$myArtistLoop++;
			$myArtistLoop++;
			$rank++;
		}
				
		$finalMatchedOut = ($finalMatchedOut."</table></td></tr><tr><td align=\"right\" colspan=\"2\">");
		
		echo $errorText;
		echo ('<div style="width:100%;height:450px;overflow:auto;"><br>'.$finalMatchedOut);
		

echo '</div>
				</td></tr>

</table></tr>';

include("../../includeglobals/printFooter.php");

		
	}
	
ob_end_flush();	
}
			
	
	
include("getArtists.php");
include("getDetails.php");			



function array_most_common($input) 
{ 
  $counted = array_count_values($input); 
  arsort($counted); 
  return(key($counted));     
}

function fullArrayDiff($left, $right) 
{ 
    return array_diff(array_merge($left, $right), array_intersect($left, $right)); 
} 

function findDuplicates($data,$dupval) {
	$nb= 0;
	foreach($data as $key => $val)
	if ($val==$dupval) $nb++;
	return $nb;
}

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
			
			
			$myLocation = getMyDetails($userAlias);
			/////////////////
			
			
			
			
			if (($_POST["initiate"]) != "123"){
			
			printHeader("home", "Locate", $userAlias, $myLocation);


$myArtists = getMyArtists($userAlias);



if($myArtists!="0"){
	
	echo '<form action="http://audimate.me/web/home/locate/" name="locateForm" method="POST">
<input type="hidden" name="initiate" value="123">

<font size=2>There are currently ';
		
$curl_connection = curl_init('http://audimate.me/getRows.php');

$myVar = curl_exec($curl_connection);
curl_close($curl_connection);
echo $myVer;

echo ' mates in existence.</font><br><br>

<a href="javascript: void(0);" onclick="document.locateForm.submit();return false;">Locate my matches</a></form></td>';

} else {
	echo '<br>You need interests to locate mates.</td>';
}



echo '</tr>';
include("../../includeglobals/printFooter.php");

}
			///////////
			
			
	
			//////////
			
			
			
		}	
	}
				
}

$myEmail = $_COOKIE["audiMateemail"];
$myPassword = $_COOKIE["audiMatepassword"];
$myStatus = $_COOKIE["audiMatestatus"];

//$urlInfo = parse_url($_SERVER['HTTP_REFERER']);
//$myDomain = $urlInfo['host'];

if(isset($myEmail) && isset($myPassword)){
	
	user_login($myEmail, $myPassword);
	
	if (($_POST["initiate"]) == "123"){
		matchMaker($myEmail, $myPassword, $myStatus);
	}
} else {
	header('Location: http://audimate.me/web/login/');
}

?>




</center>
	</body>
</html>
<?php
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
