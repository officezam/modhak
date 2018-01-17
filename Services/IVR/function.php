<?php 

function getStarted( $cId ) {
	$query  = "SELECT * FROM call_ivr_widget WHERE companyId = '$cId' AND pId = '0'";
	global $dbo;
	$result = $dbo->do_query($query);
	if( $dbo->num_rows($result)) {
	    $data = $dbo->get_row_array( $result );
		return	$data['wId'];
	}
	else {
		return '';
	}				
}
	
function getWidget($wId,$keypress) {
	global $dbo;	
	$Query 	= "SELECT * FROM call_ivr_widget WHERE wId = '$wId' and keypress ='$keypress'";
	$res	= $dbo->do_query( $Query );
	$data 	= $dbo->get_row_array($res);
	//print_r($data);exit;
	return $data;
		
	/*
	$widget['wId'] 				= $rs_nxt['wId'];
	$widget['pId'] 				= $rs_nxt['pId'];
	$widget['flowtype'] 		= $rs_nxt['flowtype'];
	$widget['content_type'] 	= $rs_nxt['content_type'];
	$widget['content'] 			= $rs_nxt['content'];
	$widget['nId'] 				= $rs_nxt['nId'];
	$widget['data'] 			= $rs_nxt['data']
	$widget['keypress'] 		= $rs_nxt['keypress'];
	*/	
		
}
	
function getMenuItems($pId) {
	global $dbo;
	$result =  $dbo->do_query("SELECT * FROM call_ivr_widget WHERE keypress != '-' and pId = $pId");
	$data 	= array();
	$dataArray = $dbo->get_result_array($result);
	
	if(!empty($dataArray)){
		foreach($dataArray as $k => $row ) {
			$data[$row['keypress']]	= $row;
		}
	}
	return $data;
}

//function for retrieving voicemail box by exten
function getMailbox($voicemail_exten) {
	//make sure inputs are db safe
	$voicemail_exten 	= mysql_escape_string($voicemail_exten);
	$result 			= mysql_query("select * from voicemailbox where vmb_extension='$voicemail_exten'");
	$data 				= $result->fetch();
	$mailbox 			= false;
	if ( !empty($data) ) {
		$mailbox 				= array();
		$mailbox['exten'] 		= $data['vmb_extension'];
		$mailbox['desc'] 		= $data['vmb_description'];
		$mailbox['passcode'] 	= $data['vmb_passcode'];
	}
	return $mailbox;
}

function addMessage($voicemail_exten, $caller_id, $recording_url) {

	$voicemail_exten 	= mysql_escape_string($voicemail_exten);
	$caller_id 			= mysql_escape_string($caller_id);
	$recording_url 		= mysql_escape_string($recording_url);
	
	mysql_query( 
		"insert into messages  set ivr_id =$voicemail_exten,message_date=now(),message_from='$caller_id'
		,message_flag=0,message_audio_url='$recording_url'");	

	$id = mysql_insert_id();
	return $id;
}

function updateMessageFlag( $msg_id, $flag=0 ) {
	
	//make sure inputs are db safe
	$msg_id = mysql_escape_string($msg_id);
	$flag 	= mysql_escape_string($flag);
	
	// Performing SQL query
	$query = sprintf("update messages set message_flag=%d where message_id=%d",
		$flag, $msg_id);
	$this->db->query($query);
}

function getMessages($voicemail_exten,$flag=0){

	//make sure inputs are db safe
	$voicemail_exten = mysql_escape_string($voicemail_exten);
	$flag = mysql_escape_string($flag);
	
	// Performing SQL query
	$query = sprintf("select * from messages where message_flag=%d and "
		. "message_frn_vmb_extension='%s' order by message_date", $flag,
		$voicemail_exten);
	
	$result = mysql_query("select * from voicemailbox where vmb_extension='$voicemail_exten'");
	
	 $data = $result->fetchAll();
	
	$messages = array();
	foreach ( $data  as $mk=>$mv) {
		$messages[]=$mv['message_id'];
	}	
	return $messages;
}

function getMessage( $msg_id ){
	
	//make sure inputs are db safe
	$msg_id = mysql_escape_string($msg_id);
	
	// Performing SQL query
	$query 		= sprintf("select * from messages where message_id=%d",$msg_id);
	$result 	= mysql_query($query);
	$data 		= $result->fetch();	
	$message 	= array();
	
	if(!empty($data)) {
		$message['id']=$data['message_id'];
		$message['date']=$data['message_date'];
		$message['from']=$data['message_from'];
		$message['url']=$data['message_audio_url'];
	}
	return $message;
}

function getIVR_welcome( $company_id ) {
	
	//make sure inputs are db safe
	$company_id = mysql_escape_string($company_id);
	
	// Performing SQL query
	$query 	= sprintf("select * from call_ivr  where company_id=%d",$company_id);
	$result = mysql_query($query);	
	$data 	= $result->fetch();	
	$ivr 	= array();
	
	if(!empty($data)) {
		$ivr['read_txt']=$data['read_txt'];
		$ivr['upload_mp3']=$data['upload_mp3'];
		$ivr['repeat_time']=$data['repeat_time'];
		$ivr['id']=$data['id'];

	}
	return $ivr;
}
?>