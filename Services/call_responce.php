<?php 
$postData = '';
foreach($_REQUEST as $key => $val)
{
	$postData .= $key." => ".$val." \n \r";
}
//mail("amirs@zamsol.com", "Call Responce", $postData);

?>