<?php

require_once("../../connect/connect.php");	
require('../Twilio.php');
require('function.php');	
	
	

if (strlen($_REQUEST['exten'])) {
	$exten = $_REQUEST['exten'];
	$mailbox = $db->getMailbox($exten);

	//output TwiML to record the message
	$response = new Services_Twilio_Twiml();
//	$response->say('Leave a message for ' . $mailbox['desc'] . ' at the beep');
	$response->say('Leave a message for at the beep');
	$response->record(
		array(
			'action' => "handle_message.php?exten=$exten",
			'maxLength' => '120',
			'playBeep' => 'true')
	);

	// record will post to this url if it receives a message
	// otherwise it falls through to the next verb
	
	$response->gather()
		->say("A message was not received, press any key to try again");

	print $response;
}

?>
