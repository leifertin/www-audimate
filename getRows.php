<?php
		
			//session_start();
			$link = mysql_connect('localhost', 'audimate8952', 'kr4pp33K0ala'); 
			if (!$link) { 
    			die('Could not connect.'); 
			} 
			
			mysql_select_db(audimate_db);
			$sql = 'SELECT * FROM usersTable';
				$sql = mysql_query($sql);
				//check to see how many rows were returned 
				$rows = mysql_num_rows($sql); 
				echo $rows; 
			

?>