<?php

function getMyArtists($userAlias){	
		$curl_connection = curl_init('http://audimate.me/getMyArtists.php');

		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl_connection, CURLOPT_REFERER, 'audiMate.getMyArtists');


		$post_data['alias'] = $userAlias;
			

		foreach ( $post_data as $key => $value) 
		{
			$post_items[] = $key . '=' . $value;
		}
		$post_string = implode ('&', $post_items);

		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
	
		$myArtists = curl_exec($curl_connection);
		curl_close($curl_connection);
	
		return $myArtists;
	}
?>