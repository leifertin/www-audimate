<?php
		
			//session_start();
			$link = mysql_connect('localhost','audimate8952','kr4pp33K0ala'); 
			if (!$link) { 
    			die('Could not connect.'); 
			} 
			
			mysql_select_db(audimate_db);
			function compare_artists($myArtist){ 
				
				
				$sql = 'SELECT entryID,alias FROM artistLists WHERE MATCH (alias,entry) AGAINST(\''.$myArtist.'\n\')';
				
				$sql_result = mysql_query($sql);
				//echo $sql_result;
				
				//check to see how many rows were returned 
				$rows = mysql_num_rows($sql_result); 
				//echo $rows;
				
				$count = 0;
				while ($count < $rows) {
					$row = mysql_fetch_array($sql_result);
 					$userAlias = $row["alias"];

  					echo ("<--audiMateUserAlias-->".$userAlias);
  					$count++ ;
  				}
				
				
			}
			
			if (isset($_POST['artist'])) {
				if (($_SERVER['HTTP_REFERER']) == ('audiMate.compareMe')){
					compare_artists($_POST['artist']);
				} else {
					echo "0";
				}
			}

?>