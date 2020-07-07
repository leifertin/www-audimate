 <?php


global $strDesc; 

$urlInfo = parse_url($_SERVER['HTTP_REFERER']);
$myDomain = $urlInfo['host'];



if ($myDomain == ('audimate.me')){

	// Database connection variables

	$dbServer = "localhost";
	$dbDatabase = "audimate_db";
	$dbUser = "audimate8952";
	$dbPass = "kr4pp33K0ala";

	$sConn = mysql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to database server.");
	
	$dConn = mysql_select_db($dbDatabase, $sConn)
	or die("Couldn't connect to database.");

	$userEmail = $_COOKIE["audiMateemail"];
	$userPassword = $_COOKIE["audiMatepassword"];
	
	$profileAlias = $_GET["alias"];
	//$convoText = $_COOKIE["convoData"];
	
	$userEmail = mysql_real_escape_string($userEmail); 
	$userPassword = mysql_real_escape_string($userPassword); 
	
	//begin the query 

	$sql = 'SELECT `alias`, `email`, `password` FROM `usersTable` WHERE `email` = \''.$userEmail.'\' AND `password` = SHA1(\''.$userPassword.'\')';
	$sql_result = mysql_query($sql);
	//check to see how many rows were returned 
	$rows = mysql_num_rows($sql_result); 
	
	
	//$count = 1 + $rows;
	if ($rows<=0 ){ 
		echo "0";
	} else { 
		
		$rowso = mysql_fetch_array($sql_result);
		
		////////
		$userAlias = $rowso["alias"];
			
			$curl_connection = curl_init('http://audimate.me/displayConversation.php');

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.displayConversation');


			$post_data['alias'] = $userAlias;
			$post_data['otherAlias'] = $profileAlias;
			
			

			foreach ( $post_data as $key => $value) 
			{
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);

			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

			$myConversationData = curl_exec($curl_connection);
			curl_close($curl_connection);
			
			
			$myExplodeString = ("<--StrtMssg_{".$userAlias."}:{".$profileAlias."}.[");
			$myConversationData = explode($myExplodeString, $myConversationData);
			$myConvoCount = count($myConversationData);
			
			
			if ($myConvoCount > 1){
			//convo exists - go to it!
			$convoURL = ('http://audimate.me/web/home/conversations/view.php?alias='.$profileAlias);
			header('Location: '.$convoURL);
			
			} else {
				//convo does not exist - create it!
				
				$curl_connection = curl_init('http://audimate.me/createConversation.php');

				curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.createConversation');


				$post_data['theirAlias'] = $profileAlias;
				$post_data['email'] = $userEmail;
				$post_data['password'] = $userPassword;
			
			

				foreach ( $post_data as $key => $value) 
				{
					$post_items[] = $key . '=' . $value;
				}
				$post_string = implode ('&', $post_items);

				curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

				$myConversationData = curl_exec($curl_connection);
				curl_close($curl_connection);
			
				if ($myConversationData=="conversation created!"){
					$convoURL = ('Location: http://audimate.me/web/home/conversations/view.php?alias='.$profileAlias);
					header($convoURL);
				}else{
					//echo $myConversationData;
					header('Location: http://audimate.me/web/login/');
				}
				
				
			
			
			}
			
			/////////////////
	}	

} else {
	header('Location: http://audimate.me/web/login/');
}



?>