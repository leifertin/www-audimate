	<?php
$myStatus = $_COOKIE["audiMatestatus"];

if ($myStatus == sha1('1')){
	$echoActivateAlias = "";
} else {
	$echoActivateAlias = "<a href=\"http://audimate.me/activate\">Activate</a> |";
}

echo '<tr>
<td height="50" colspan="2" align="center" valign="bottom">'.$echoActivateAlias.'
<a href="http://audimate.me/web/annihilate">Delete</a> |

<a href="http://audimate.me/web/logout">Logout</a>
</td>
</table>';

	?>