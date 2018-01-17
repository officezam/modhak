<?php
include ("../../connect/connect.php");
require('../Twilio.php');
include(ru_common. 'encription_function.php');

// Included for cost charges functions
require_once(ru_dir . 'common/rizwan_custom_function.php');

$to_phone = $_REQUEST['To'];
$from_phone = $_REQUEST['From'];
$CallSid = $_REQUEST['CallSid'];
$CallStatus  = $_REQUEST['CallStatus'];
$CallDuration = $_REQUEST['CallDuration'];
$Direction  = $_REQUEST['Direction'];
//$to_phone = '+12248756945';

/*$postData = '';
foreach($_REQUEST as $key => $val)
{
	$postData .= $key."  => ".$val ."\n" . "\r";	
}
mail("rizwan@zamsol.com", "Call Responce", $postData);*/


	
	
	/*
	=============================================================
	======= Fetch user id and campaign id fron Voice Log ========
	=============================================================*/
	$getVoiceUserId = User_qury::get_user_by_id("voice_log", "voice_log_callSid_uuid", $CallSid);
	$userId 	 	   = $getVoiceUserId['user_id'];
	$campaign_id	   = $getVoiceUserId['ivr_flow_id'];
	
	// Fetch available balance and spent balance from user setting tbl
	$getUserSetting    = User_qury::get_user_by_id("user_setting", "userId", $userId);
	
	$monthly_max_sms   = zs_decrypt($getUserSetting['monthly_max_min']);
	$account_sid  	   = zs_decrypt($getUserSetting['account_sid']);
	$auth_token   	   = zs_decrypt($getUserSetting['auth_token']);
	$available_balance = $getUserSetting['available_balance'];
	$spent_balance 	   = $getUserSetting['spent_balance'];
	
	// Convert seconds into minutes 
	$CallDurtn = gmdate("i.s", $CallDuration);
	$totalCallDuration = ceil($CallDurtn);
	
	$monthly_fee_option	= $getCallRate['monthly_fee_option'];
	
	if(zs_decrypt($monthly_fee_option) == "yes"){
		$per_min_fee    = zs_decrypt($getUserSetting['per_min_fee']);
		$callCost 	= $totalCallDuration*$per_min_fee;
		
	}else{
		
		$glb_sms_rate       = "SELECT * FROM setting";
		$global_stng_arr    = User_qury::custom_users($glb_sms_rate);
		$incomingCallCost   = zs_decrypt($global_stng_arr[0]['per_min_fee']);
		
		$callCost 		= $totalCallDuration*$incomingCallCost;
	}
	
	/*=============== Insert Query for Voice Log ================*/
	
	
	$client = new Services_Twilio($account_sid, $auth_token);
	
	// Get an object from its sid. If you do not have a sid,
	// check out the list resource examples on this page
	$call = $client->account->calls->get($CallSid);
	
	$sql_insert = "UPDATE voice_log SET voice_log_duration 	 = '".$CallDuration."',
	voice_log_callStatus = '".$CallStatus."',
	date_created = '".$call->date_created."',
	start_time = '".$call->start_time."',
	end_time = '".$call->end_time."',
	forwarded_from = '".$call->forwarded_from."',
	voice_log_billRate     = '".$callCost."' WHERE 
	voice_log_callSid_uuid = '$CallSid'";
	mysql_query($sql_insert);
	
	//get current month and year
	$currntMonth = date("m");
	$currntYear = date("Y");
	
	
	
	/*****************************************************************/
	
	//Fetch total cost of current flow and plus currant call cost into it
	$CaMp_QrY = "SELECT * FROM ivr_flow WHERE flow_id = '$campaign_id'";	
	$GeT_CaMp = User_qury::custom_users($CaMp_QrY);
						
	$flow_list_id  = $GeT_CaMp[0]['flow_list_id'];
	$flow_number  = $GeT_CaMp[0]['flow_number'];
	
	$campaignCost  = $GeT_CaMp[0]['flow_cost'];
	
	/****************************************************************/
	
	
	// just for outbound calls 
	if($Direction == 'outbound-api'){
		
		
		
		/**
		 * Getting SMS charges according to new requirements
		 * New Cost Charges Block 05-Oct-2015
		 */
		 
		$matched_prefix_sms     = get_match_prefix($from_phone);
		if ( $matched_prefix_sms == true and !empty($matched_prefix_sms) ){
			// now get the the charge rate
			$charges_array   = get_call_charges($matched_prefix_sms);
			if ( is_array($charges_array) and !empty($charges_array)){
				$per_min_fee 	    = $charges_array['call_charges'];
			}else{
				// if no rate found against call prefiex then we will use the global settings rate
				$glb_sms_rate           = "SELECT * FROM setting";
				$global_stng_arr        = User_qury::custom_users($glb_sms_rate);
				$per_min_fee     	    = zs_decrypt($global_stng_arr[0]['per_min_fee']);
			}
		}
		else{
			// if no rate found against call prefiex then we will use the global settings rate
			$glb_sms_rate           = "SELECT * FROM setting";
			$global_stng_arr        = User_qury::custom_users($glb_sms_rate);
			$per_min_fee     	    = zs_decrypt($global_stng_arr[0]['per_min_fee']);
		}
		
		
		$callCost = $per_min_fee * $totalCallDuration;
		$updCostQry = "UPDATE voice_log SET voice_log_billRate = '".$callCost."' WHERE 
		voice_log_callSid_uuid = '$CallSid'";
		mysql_query($updCostQry);
		
		/*
		===============================================================
		===============================================================
		===============================================================*/
		
		$sql_countsms = "SELECT * FROM voice_log WHERE user_id = '".$userId."' 
		AND ivr_flow_id = '".$campaign_id."'";
		$res_countsms = mysql_query($sql_countsms);
		$countsms = mysql_num_rows($res_countsms, 0);
		
		
		$sql_countlist  = "SELECT * FROM select_contacts WHERE 
		list_id = '".$flow_list_id."'";
		$res_countlist = mysql_query($sql_countlist);
		$countlist = mysql_num_rows($res_countlist, 0);
		
		if($countlist == $countsms){
			//echo "Campaign Completed";
			
			mysql_query("UPDATE ivr_flow SET flow_status = 'Completed' 
			WHERE flow_id = '".$campaign_id."'");
			
			$numbr_qry = "UPDATE numbers set status = 'free', 
			assign_compaign_name = '', comaign_type = '' where 
			number = '".$flow_number."' AND userId = '$userId'";
			mysql_query($numbr_qry) or die (mysql_error());
			
			//Update twilio or plivo number URL 
			$getNumberSid = User_qury::get_user_by_id("numbers", "number", $flow_number);
			$phoneSid = $getNumberSid['number_sid'];
			$userId	  = $getNumberSid['userId'];
			$app_id	  = $getNumberSid['app_id'];
			
			//http://zs-dev.com/twilio-survey/
			$voice_url = ru.'twilio/twilio_call_menu.php';
			
			$getUserSettingInfo = User_qury::get_user_by_id("user_setting", "userId", $userId);
			
			if($getUserSettingInfo['api_type'] == zs_encrypt('twilio'))
			{
				$account_sid = zs_decrypt($getUserSettingInfo['account_sid']);
				$auth_token  = zs_decrypt($getUserSettingInfo['auth_token']);
				//print_r($client);
				$client = new Services_Twilio($account_sid, $auth_token); 
				//exit;
				$number = $client->account->incoming_phone_numbers->get($phoneSid);
				//echo "<pre>";print_r($number);exit;
				$number->update(array( "VoiceUrl" => $voice_url ));
				
			}
			
		}
		
		/*
		===============================================================
		===============================================================
		===============================================================*/
		
	}//End of diractions condition
	
	
	
	// Fetch total usege of current month and update with current value 
	$getMonthCountQry = mysql_query("SELECT * FROM account_payment_monthly WHERE 
	mp_userId = '$userId' AND MONTH(mp_date) = '".$currntMonth."'
	AND YEAR(mp_date) = '".$currntYear."'");
	$getMonthCount = mysql_fetch_array($getMonthCountQry);
							
	$totalVoiceCount = $getMonthCount['mp_voice_count'];
	$totalVoiceCount = $totalVoiceCount + $totalCallDuration;
	mysql_query("UPDATE account_payment_monthly SET mp_voice_count = '$totalVoiceCount'
	WHERE mp_userId = '$userId' AND MONTH(mp_date) = '".$currntMonth."'
	AND YEAR(mp_date) = '".$currntYear."'");
	
	//Maintain available balance nd spent balance
	$remain_blance 	   = $available_balance - $callCost;
	$new_spent_balance = $spent_balance + $callCost;
	
	/***************************************************/
	$totalCampCost = $campaignCost + $callCost;
	
	//update user setting and ivr flow after cost  and balnce calculation
	mysql_query("UPDATE user_setting SET available_balance = '$remain_blance', 
	spent_balance = '$new_spent_balance' WHERE userId = '$userId'");
							
	mysql_query("UPDATE ivr_flow SET flow_cost = '$totalCampCost' 
	WHERE flow_id = '$campaign_id'");
	
?>