<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');	
require_once('function.php');	



$poll_id		 	= $_GET['poll_id'];
$campaign_id	 	= $_GET['camp_id'];
$widget_id	 		= $_GET['widget_id'];
$query_poll_options = "SELECT * FROM poll_options WHERE poll_id = ". $poll_id;
$exe_qp_opt			= mysql_query ( $query_poll_options );

if ( mysql_num_rows($exe_qp_opt) > 0 ) {
	
	$option_array = array();
	
	while(	$poll_opt_res = mysql_fetch_array( $exe_qp_opt ) ) :	
		//creating array for user feedback
		$option_array[] = $poll_opt_res['option_keyword'];	
	endwhile;
}
//echo "<pre>";print_r($option_array);exit;

//mail("rizwan@zamsol.com", "Call Responce", $_GET['poll_id']." \n \r".$query_poll_options);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response>';

//$response = new Services_Twilio_Twiml;

	$option_feedback = $_REQUEST['Digits'];

	//next we will get the options of that specific poll and put them in say tag	

	if ( array_key_exists( $option_feedback, $option_array ) ) :
	
		// inserting in the poll log table 		
		$query_insert_plog  = " INSERT INTO poll_log ";
		$query_insert_plog .= " SET ";
		$query_insert_plog .= " campaign_id = '" . $campaign_id 		. "',";
		$query_insert_plog .= " call_sid	= '" . $_REQUEST['CallSid']	. "',";
		$query_insert_plog .= " poll_id 	= '" . $poll_id 			. "',";
		$query_insert_plog .= " key_press 	= '" . $option_feedback 	. "' ";
		
		$exec_q_ipl			= @mysql_query ( $query_insert_plog );
						
	
		// getting the latest user response value
		$query_cur_response  = " SELECT option_response FROM poll_options ";
		$query_cur_response .= " WHERE";
		$query_cur_response .= " poll_id =".$poll_id;
		$query_cur_response .= " AND";
		$query_cur_response .= " option_keyword = ".$option_feedback ;
		
		
		$res_poll			= @mysql_query ($query_cur_response );
		$tot_resp   		= mysql_fetch_array($res_poll);
		$total_responces 	= $tot_resp['option_response'];
		$total_responces++;
		
		//updating database response field
		$query_opt_res 	 = "UPDATE poll_options SET "; 
		$query_opt_res 	.= " option_response  = '$total_responces' ";
		$query_opt_res 	.= " WHERE ";
		$query_opt_res 	.= " poll_id  = ".$poll_id;	
		$query_opt_res 	.= " AND ";																	
		$query_opt_res 	.= " option_keyword  = '$option_feedback' ";						
		$exe_qor		 = @mysql_query ( $query_opt_res );
		
		
		
		// getting the latest total responses from ivr_poll_management
		$query_ivr_tr_latest  = " SELECT total_responses FROM ivr_poll_management ";
		$query_ivr_tr_latest .= " WHERE";
		$query_ivr_tr_latest .= " poll_id =".$poll_id;
		
		$res_qitl			= @mysql_query ($query_ivr_tr_latest );
		$tot_resp_ivr  		= mysql_fetch_array($res_qitl);
		$latest_res_ivr 	= $tot_resp_ivr['total_responses'];
		$latest_res_ivr++;		
		
		//updating ivr_poll_management response field
		$query_ivr_res 	 = "UPDATE ivr_poll_management SET "; 
		$query_ivr_res 	.= " total_responses  = '$latest_res_ivr' ";
		$query_ivr_res 	.= " WHERE ";
		$query_ivr_res 	.= " poll_id  = ".$poll_id;	
					
		$exe_ivr		 = @mysql_query ( $query_ivr_res );
		
		echo '<Say>Thanks for participating</Say>';
		
	endif;
	if ( $widget_id == '0') {
		echo '<Hangup/>';
	}
	else {
		echo '<Redirect>ivrmenu.php?campaignId='.$campaign_id.'&amp;wId='.$widget_id.'&amp;tcx='.strtotime('now').'</Redirect>';
		//exit;
	}
	

echo '</Response>';?>