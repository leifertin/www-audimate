<?php
function randomGreeting($userAlias){
	//Random Greeting
	$helloWords = array();
	$helloWords[0] = 'Hello';
	$helloWords[1] = 'Welcome';
	$helloWords[2] = 'What\'s up';
	$helloWords[3] = '¿Que pasa';
	$helloWords[4] = 'Greetings';
	$helloWords[5] = 'What\'s good';
	$helloWords[6] = 'Hey';
	$helloWords[7] = 'How goes it';
	$helloWords[8] = 'Sup';
	$helloWords[9] = '';
	
	$helloEnds = array();
	$helloEnds[0] = '.';
	$helloEnds[1] = '!';
	$helloEnds[2] = '?';
	$helloEnds[3] = '?';
	$helloEnds[4] = '.';
	$helloEnds[5] = '?';
	$helloEnds[6] = '!';
	$helloEnds[7] = '?';
	$helloEnds[8] = '?';
	$helloEnds[9] = '!';

	$helloWordsTotal = (count($helloWords))-1;
	
	$randNo = rand(0, $helloWordsTotal);
	$helloWord = $helloWords[$randNo];
	$helloEnd = $helloEnds[$randNo];
	
	return ($helloWord.' '.$userAlias.$helloEnd);
}


function printHeader($windowType, $selTab, $userAlias, $myLocation, $myGender, $profileAlias){

	
	$portWidth = 200;
	$portHeight = 300;
	
	echo '<br><br><b>';
	
	//$myGreeting = randomGreeting($userAlias);
	//$myGreeting = $userAlias;
	if ($selTab != "HomeHome"){
		echo '<a href="http://audimate.me/web/home/mates/view.php?alias='.$userAlias.'">'.$userAlias.'</a>';
	} else {
		echo $userAlias;
	}
	
	echo '</b></td><td colspan="1" style="background-image:url(\'http://audimate.me/audimatePurple.png\');background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;" valign="bottom" align="right"><br><br>';


////
	if ($selTab == "Interests"){

	echo '<a href="http://audimate.me/web/home/conversations/">Conversations</a> | <a href="http://audimate.me/web/home/mates/">Mates</a> | <a href="http://audimate.me/web/home/locate/">Locate</a>';
	
} else if ($selTab == "Conversations"){

	echo '<a href="http://audimate.me/web/home/">Interests</a> | <a href="http://audimate.me/web/home/mates/">Mates</a> | <a href="http://audimate.me/web/home/locate/">Locate</a>';
	
} else if ($selTab == "Mates"){

	echo '<a href="http://audimate.me/web/home/">Interests</a> | <a href="http://audimate.me/web/home/conversations/">Conversations</a> | <a href="http://audimate.me/web/home/locate/">Locate</a>';
	
} else if ($selTab == "Locate"){

	echo '<a href="http://audimate.me/web/home/">Interests</a> | <a href="http://audimate.me/web/home/conversations/">Conversations</a> | <a href="http://audimate.me/web/home/mates/">Mates</a>';
	
} else if ($selTab == "HomeHome"){

	echo '<a href="http://audimate.me/web">Login</a> | <a href="http://audimate.me/web/make_yourself">Make Yourself</a>';
	
} else {

	echo '<a href="http://audimate.me/web/home/">Interests</a> | <a href="http://audimate.me/web/home/conversations/">Conversations</a> | <a href="http://audimate.me/web/home/mates/">Mates</a> | <a href="http://audimate.me/web/home/locate/">Locate</a>';
}
//


if (isset($profileAlias)){
	$portraitAlias = $profileAlias;
} else {
	$portraitAlias = $userAlias;
}

echo '</td>
</table><table width="100%" border="0" align="center" cellpadding="15" cellspacing="8">';

if ($selTab != "HomeHome"){

	echo '<tr>
			<td align="left" width="'.$portWidth.'" height="'.$portHeight.'" valign="top">';
			
			if ($windowType == "view"){
				echo '<img alt="No Portrait" width="'.$portWidth.'" height="'.$portHeight.'" src="http://audimate.me/displayPortrait.php?alias='.$portraitAlias.'">';
			} else {
				echo '<a href="http://audimate.me/web/home/portrait/edit.php"><img alt="No Portrait" width="'.$portWidth.'" height="'.$portHeight.'" src="http://audimate.me/displayPortrait.php?alias='.$portraitAlias.'"></a>';
			}
			
			echo '</td>';


			echo '<td align="right" valign="top">';
			
			if (isset($myGender)){
				echo ($myGender.'<br><br>');
			}
			
			if ($windowType == "view"){
				echo $myLocation;
			} else {
				echo '<a href="http://audimate.me/web/home/location/edit.php">'.$myLocation.'</a>';
			}
			echo '</td></tr>';
}
//////

echo '
<tr>
<td height="500" align="left" valign="top" colspan="2" style="background-image:url(http://audimate.me/audimatePurple.png);background-repeat:repeat-x;background-attachment:scroll; background-position:bottom;"><br>';


}

?>