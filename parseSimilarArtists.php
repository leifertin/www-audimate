<?php

function findDuplicates($data,$dupval) {
$nb= 0;
foreach($data as $key => $val)
if ($val==$dupval) $nb++;
return $nb;
}

$myUser_alias = $_POST['alias'];
$myUser_as = $_POST['artistParse'];
$myUser_a = explode("<--audiMateUserAlias-->", $myUser_as);

echo findDuplicates($myUser_a,$myUser_alias);

?>