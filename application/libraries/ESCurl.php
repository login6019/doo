<?php
header("Content-Type: text/html; charset=UTF-8");


class ESCurl {
	function __construct(){
		require_once 'lib'.DIRECTORY_SEPARATOR.'curl.php';
		require_once 'lib'.DIRECTORY_SEPARATOR.'curl_response.php';
	}
}