<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');	
require_once('function.php');	

//mail("rizwan@zamsol.com", "Call Responce", $_GET['poll_id']);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';

echo '<Response>';
 	echo '<Say>Joining a conference room</Say>';
	echo '<Dial>';
    	echo '<Conference>MyConference</Conference>';
  	echo '</Dial>';
echo '</Response>';?>