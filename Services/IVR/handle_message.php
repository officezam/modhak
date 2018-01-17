<?php

require_once("../../connect/connect.php");	
require('../Twilio.php');
require('function.php');	
	

$ivr_id = $_REQUEST['exten'];
$recording_url = $_REQUEST['RecordingUrl'];
$caller_id = $_REQUEST['From'];
$message_to = $_REQUEST['To'];

if (strlen($ivr_id) && strlen($recording_url)) {
	
	//save recording url and callerid as a message for that mailbox extension
	mysql_query("insert into call_ivr_messages  set ivr_id =$ivr_id,message_date=now(),	message_from='$caller_id',	message_to='$message_to',
	message_flag=0,message_audio_url='$recording_url'");
		

	
	$response = new Services_Twilio_Twiml();
	
	if ( isset($_REQUEST['wId']) ) 
	{
		$response->redirect('ivrmenu.php?campaignId='.$ivr_id.'&wId='. $_REQUEST['wId']);
	}else{
		$response->say('Thank you, good bye');
	}
	print $response;
}

?>