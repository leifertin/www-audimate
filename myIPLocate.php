<?php

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function justShowCN($xml){

preg_match("@<CountryName>(.*?)</CountryName>@si",$xml,$matches);
$ipDetail = $matches[1];
return $ipDetail;

}

$myRealIP = getRealIpAddr();
$apiKey = "2ac75584f8737ce1cd07e699051bae12ea471670647e9bd3f52b8045de799d25";
$fullURL = ("http://api.ipinfodb.com/v2/ip_query_country.php?key=".$apiKey."&ip=".$myRealIP);
$myLocationResult = file_get_contents($fullURL);


//$curl_connection = curl_init($fullURL);
//$myLocationResult = curl_exec($curl_connection);
//curl_close($curl_connection);



$myLocationResult = justShowCN($myLocationResult);
echo $myLocationResult;

?>