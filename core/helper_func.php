<?php 

function sanitize($var) {
	return htmlentities(trim(strip_tags($var)));
}
function restrict_without_login($location)
{
	if ( empty($_SESSION['username']) ) {
		header('Location: ' . $location);
	}
}

function restrict_with_login($location)
{
	if ( isset($_SESSION['username']) ) {
		header('Location: ' . $location);
	}
}

/*
* @dir = return the root folder name
* @example = Linkr-clone
**/
$dir = __DIR__ . '<br>';
$dir = explode('/', $dir);
$dir = array_slice($dir, -3, -2)[0];
$dir;

/*
* @url = echo path up to root folder name
* @example = http://localhost/Linkr-clone
**/
function url($insert_url)
{	
	global $dir;
	$url = "http";
	if ( isset( $_SERVER["HTTPS"]) ) {
		$url .= "s";
	}
	$url .= "://";
	if ( $_SERVER["SERVER_PORT"] != 80 ) {
		$url .= $_SERVER["SERVER_NAME"].$_SERVER["SERVER_PORT"].'/' . $dir . $insert_url;
	} else {
		$url .= $_SERVER["SERVER_NAME"].'/' . $dir . $insert_url;
	}
	return $url;
}







/*
 * @param $date string
 * @param $time string
 * return string
 */
function fullDate($date, $time)
{
	try {
		$str_to_time = strtotime($date . $time);
		$full_date = date("l, F d, Y h:i A", $str_to_time);

		return $full_date;
	} catch(NotFoundException $e)  {
		return false;
	}
}



//making another url function with return key for certain needs-Arif
function url_return($insert_url)
{
	global $dir;
	$url = "http";
	if ( isset( $_SERVER["HTTPS"]) ) {
		$url .= "s";
	}
	$url .= "://";
	if ( $_SERVER["SERVER_PORT"] != 80 ) {
		$url .= $_SERVER["SERVER_NAME"].$_SERVER["SERVER_PORT"].'/' . $dir . $insert_url;
	} else {
		$url .= $_SERVER["SERVER_NAME"].'/' . $dir . $insert_url;
	}
	return $url;
}










/*
|------------------------------------------------------------------------------------------------
| 2. Ariful Islam
|------------------------------------------------------------------------------------------------
*/
//return timestamp to textual date
function textDate($timestamp)
{
	return date('M j Y g:i A', strtotime($timestamp));
}
function textDateEvent($timestamp)
{
	$date = date('Y M j', strtotime($timestamp));
	$time = date('g:i A', strtotime($timestamp));
	return $date . ', ' . $time;
}

function returnDateOnly($timestamp)
{
	$date = date('Y M j', strtotime($timestamp));
	return $date;
}

//get actual extension regardless of the file type
function get_file_extension($f)
{
//	if( file_exists($f) ) {
	$ftype = pathinfo($f);
	return $extension = strtolower($ftype['extension']);
//	}
//	return false;
}
//only gets 3 character long extension
function getImageExtension($str) {
	return strtolower( substr($str, -3) );
}










//returns true if finds https
function https_checker($url) {
	$https = substr($url, 0, 5);

	if ($https === 'https') {
		return true;
	}

	return false;
}

//checks the checkboxes and return 0 if the checkbox is unchecked @Arif
function undefineCheck($paramOne) {
	if (empty($paramOne)) {
		$Nval = 0;
	} else {
		$Nval = $paramOne;
	}
	return $Nval;  //either 0 or 1
}



//generates eight characheter long random number @Arif

function randomNumber() {
	$result = '';

	for($i = 0; $i < 8; $i++) {
		$result .= mt_rand(0, 9);
	}

	return $result;
}


function authenticate($session, $redirect) {
	if (!isset($session)) {
		header("Location: $redirect");
	}
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}