<?php

/*
function domainRef(){
if (isset($_SERVER['HTTP_REFERER'])) { // check there was a referrer

	$uri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
	return $uri['host']; // echo the host

}
}
*/

//This function separates the extension from the rest of the file name and returns it 
function findexts ($filename) 
{ 
$filename = strtolower($filename) ; 
$exts = split("[/\\.]", $filename) ; 
$n = count($exts)-1; 
$exts = $exts[$n]; 
return $exts; 
} 

//$myDomain = domainRef();
//if ($myDomain == "audimate.me"){
//This applies the function to our file 
$ext = findexts ($_FILES['soundbiteContent']['name']) ; 


$fileUpload = $_FILES['soundbiteContent'];
				$fileUpload_name = $_FILES['soundbiteContent']['name'];
				$fileUpload_size = $_FILES['soundbiteContent']['size'];
				$fileUpload_type = $_FILES['soundbiteContent']['type'];
				$fileUpload_temp = $_FILES['soundbiteContent']['tmp_name'];
				$fileUpload_error = $_FILES['soundbiteContent']['error'];

				
				if ($fileUpload_type == "audio/mpeg"){
					$soundType = "mp3";
					
				} else if ($fileUpload_type ==  "audio/ogg") {
					$soundType = "ogg";
					
				} else { 
					header('Location: http://audimate.me/web/home/soundbite/edit.php?err=badtype');
				}
				
				if ($fileUpload_size > "1589864"){
				
					header('Location: http://audimate.me/web/home/soundbite/edit.php?err=toobig');
				
				} else {
//This line assigns a random number to a variable. You could also use a timestamp here if you prefer. 
$ran = rand () ;

//This takes the random number (or timestamp) you generated and adds a . on the end, so it is ready of the file extension to be appended.
$ran2 = $ran.".";

//This assigns the subdirectory you want to save into... make sure it exists!
$target = "uploads/";
//This combines the directory, the random file name, and the extension
$target = $target . $ran2.$ext;

if(move_uploaded_file($_FILES['soundbiteContent']['tmp_name'], $target)) 
{
//echo $ran2.$ext;
header("Location: http://audimate.me/web/home/soundbite/edit.php?finishUp=".$ran2.$ext);
} 
else
{
echo( 'There was an error uploading your file.' );
}
//}
} else {
	
	header("Location: http://audimate.me/");
}
?>

<br><br>
<form enctype="multipart/form-data" action="upload.php" method="POST" align="left">
			
			<input name="uploaded" type="file" />
			<input type="submit" value="Upload" />
			</form>