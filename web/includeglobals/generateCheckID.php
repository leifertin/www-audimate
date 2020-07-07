<?php

//function generateCheckID(){
	
	//Length of CheckID
	if ($_GET["e"] == "1"){
		if (isset($_GET["a"])){
			
			$numOfChrs = $_GET["a"];
			if (is_numeric($numOfChrs)){
				
				if (($numOfChrs < 100) && ($numOfChrs > 1)) {
					//Do nothing
				} else {
					$numOfChrs = rand(10, 20);
				}
				
			} else {
				$numOfChrs = rand(10, 20);
			}
		} else {
			$numOfChrs = rand(10, 20);
		}
	} else {
		$numOfChrs = rand(10, 20);
	}
	
	
	
	$ii = 0;
	$checkID = "";
	while ($ii < $numOfChrs){
	
		$randDet = rand(1, 3);
		
		if ($randDet == 1){
			//Number
			$randData = rand(48, 57);
		} else if ($randDet == 2) {
			//Lower Case Letter
			$randData = rand(97, 122);
		} else {
			//Upper Case Letter
			$randData = rand(65, 90);
		}
		
		$randData = chr($randData);
		$checkID = ($checkID.$randData);
		$ii ++;
	}
	
	if ($_GET["e"] == "1"){
		echo $checkID;
	} else {
		return $checkID;
	}
	
//}

?>