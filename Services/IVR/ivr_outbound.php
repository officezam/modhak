<?php
	// include path
	include_once('../../connect/connect.php');

	//this line loads the library 
	require('../../Services/Twilio.php');
	
	echo 'Current Date  : '.$curdate     = date('m-d-Y');
	echo '<br>';
	//get current day of week
	echo 'Current Day of Week : '.$currentDay  = strtolower (jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")) , 1 )); 	echo '<br>';
	echo 'Current Time : '.$time     = date("h:i:s"); 
	echo '<br>';
	//get current time
	$time = "SELECT NOW() as time";
	$row  = $dbo->get_row_array($dbo->do_query($time));
	echo 'Current Server Time : '.$currentTime = $row['time'];
	echo '<br>';
	$use_query = "SELECT * FROM user";	
	$qry = $dbo->do_query($use_query);
	$result_array = $dbo->get_result_array($qry);
	//echo '<pre>';print_r($result_array);
	
  ////// Get Authentication Token And Account Sid //////
  $apiQry	   = "select account_sid, auth_token from user where type = 'a' ";
  $exeqry      = $dbo->do_query($apiQry);
  $apiArray    = $dbo->get_row_array($exeqry);

 // $account_sid = $apiArray['account_sid'];
 // $auth_token  = $apiArray['auth_token']; 
  $client 	   = new Services_Twilio($account_sid, $auth_token);  
  //$account_sid = 'AC833871da91a9710f274634eab9459810';
  //$auth_token  = '9aa980b7d0c0fcf51668b07bf147df9c';
	
  
  
  /// random Number get From Twillio Account
/*  $phoneNumbers = $client->account->incoming_phone_numbers->getIterator(0, 10, array());
  $items = array();
  foreach ($phoneNumbers as $phoneNumber) 
   {
  	 $items[] = $phoneNumber->phone_number;
   } 
  
  echo 'twillio number random ==== '.$comp_phone = $items[array_rand($items)];
  echo '</br>';*/

	
	
	foreach($result_array as $key => $value)
	{ 
	
	
		$user_id		  = $value['userId'];
		$from_phone   	  = $value['twillio_number'];
		$user_type   	  = $value['type'];
		
/*		if($user_type == 'a'){
			$account_sid  	  = $value['account_sid'];
			$auth_token   	  = $value['auth_token'];
			
			$client = new Services_Twilio($account_sid, $auth_token); 
		}
			*/	
		$qry_ivr_flow = "SELECT * FROM ivr_flow ivr, ivr_flow_schedule ivrSch
			WHERE ivr.flow_id = ivrSch.flow_id AND flow_start_date <= '$curdate' AND flow_type = 'voice' AND flow_direction = 'outbound' AND flow_status = 'Active' AND ivr.user_id = '$user_id' AND ivrSch.schedule_day = '".$currentDay."'";
		$exe_ivr_flow = $dbo->do_query($qry_ivr_flow);
		$result_ivr_flow = $dbo->get_result_array($exe_ivr_flow);
		
		echo $qry_ivr_flow.'<br>';
		echo '<pre>';print_r($result_ivr_flow);
		
		foreach($result_ivr_flow as $ivrkey => $ivrvalue){
			
			$flow_id 		 = $ivrvalue['flow_id'];
			$flow_name 		 = $ivrvalue['flow_name'];
			$comp_phone 	 = $ivrvalue['flow_number'];
			$record_campaign = $ivrvalue['record_campaign'];
			$schedule_start  = $ivrvalue['schedule_start'];
			$schedule_end    = $ivrvalue['schedule_end'];
			$timezone_option = $ivrvalue['timezone_option'];
			$group_id    	 = $ivrvalue['group_id'];
			$flow_start_date = $ivrvalue['flow_start_date'];
			$selection	     = $ivrvalue['selection'];
			
			echo "<pre>";
			echo "CAMPAIGN ID  IS : " . $flow_id ."<br>";
			echo "FROM PHONE # IS : " . $comp_phone ."<br>";
			echo "Start Time   IS : " . $schedule_start ."<br>";
			echo "End Time     IS : " . $schedule_end ."<br>";
			echo "Flow Start Date : " . $flow_start_date ."<br>";

			echo "<br>";
			echo "==========================================";
			echo "<br>";
			echo "</pre>";
			
			$dataArray =  explode("-", $flow_start_date);
			$newDate  =$dataArray[2] .'-' . $dataArray[0] .'-' . $dataArray[1] .' '  .$schedule_start;
			$schedule_start = strtotime ( $newDate );
			
			$newDate  =$dataArray[2] .'-' . $dataArray[0] .'-' . $dataArray[1] .' '  .$schedule_end;
			$schedule_end = strtotime ( $newDate );
			$currentTime= strtotime ( 'now' );
			
		
		// Get contact of this campaign
		 if($selection == 'all')
		   {
		   	  $contactQry  = "SELECT * FROM contacts WHERE  group_id = $group_id AND status ='verify' ";
		   }
		   else
		   {
			  $contact_id	 = $ivrvalue['contact_id'];
			  $array=array_map('intval', explode(',', $contact_id));
			  $array = implode("','",$array);
			  $contactQry  = "SELECT * FROM contacts WHERE  group_id = $group_id And id IN ('".$array."') AND status ='verify' "; 
		   }
			echo $contactQry.'<br>';
	   
			// Get contact of this campaign
			//$contactQry  = "SELECT * FROM contacts WHERE group_id = '".$group_id."'";
			
			
			$contact_run = $dbo->do_query($contactQry);
			$get_contact = $dbo->get_result_array($contact_run);
			
			//echo "<pre>";
			//print_r($get_contact);exit;
			
			if($dbo->num_rows($contact_run)){
				
				
				foreach($get_contact as $keys => $contact_value){
					
					$contact_id	   = $contact_value['id'];
					$contact_phone = $contact_value['phone_number'];
					$contact_fname = $contact_value['first_name'];
					$contact_lname = $contact_value['last_name'];
					$raw_contact_number_sms = $contact_phone;
					
					/*=======================================================
					========================================================*/
					if ( substr ($contact_phone, 0, 2) != "+1" && strlen($contact_phone) == 10 ) {
						
						$contact_phone		=	'+1'.trim($contact_phone);
						
					}elseif(substr ( $contact_phone, 0, 1) != "+" && strlen($contact_phone) == 11){
						
						$contact_phone		=	'+'.trim($contact_phone);
					}
					
					$contctPhone = str_replace("+1", "", $contact_phone);
					//$area_code 	   = substr($contctPhone, 0,3);
					/*=======================================================
					========================================================*/
					
					echo "<pre>";
					echo "CONTACT ID  	IS   : 	" . $contact_id ."<br>";
					echo "CONTACT PHONE # IS :  " . $contact_phone ."<br>";
					echo "<br>";
					echo "==========================================";
					echo "</pre>";
			
					//Get current time of specific timezone
					date_default_timezone_set(timezone_name_from_abbr($timeZone));
					$currTimeByZone = date('H:i:s');
					
					echo "\nCurrent Time By Timezone: <br>";
					echo date_default_timezone_get()." => ".$currentTime ."\n";
					echo "<br>";
					echo "==========================================";
					echo "<br>";
					echo 'Start time  ====='.$schedule_start.'<br>';
					echo 'Current time ==== '.$currentTime.'<br>';
					
					
					
					
					
					
					if(($schedule_start <= $currentTime) and ($schedule_end >= $currentTime))
					{	
			
						
						
					//Get Twillio Number From table compaign_number 
					//pool number  Get 
					$from_number_query 		= "SELECT from_number FROM compaign_number WHERE compaign_id = '$flow_id' AND type = 'ivr' order by n_id ASC";
					$row_from_number_query  = $dbo->do_query($from_number_query);
					$pool_numberArray  		= $dbo->get_result_array($row_from_number_query);
					$total_number 			=  count($pool_numberArray);
					echo 'From Number Query =====' .$from_number_query.'<br>';			    
					//Get Last inserted index from current-index table
					$index_qry 	   = "SELECT index_id FROM current_index WHERE compaign_id = '$flow_id' AND type = 'ivr' ";
					$row_index_qry = $dbo->do_query($index_qry);
					$rs_index_qry  = $dbo->get_row_array($row_index_qry);
					$index    	   = $rs_index_qry['index_id'];
					
					echo 'Index Query ===='.$index_qry.'<br>';
					echo 'Index value ===='.$index.'<br>';
					
					if($index == '' || $index == null || $total_number <= $index){ $last_index = 0; }else{$last_index    = intval($index);}
					if($total_number <= $last_index){ $last_index = 0; }
					$comp_phone = $pool_numberArray[$last_index]['from_number'];	
					echo 'Last Index ========= '.$last_index.'<br>';;
					//exit;
					
						
						
						/////////// update index 
		$last_index++;
		if($total_number <= $last_index){ $last_index = 0; }
		$dbo->delete_data('current_index','compaign_id',$flow_id);
		$Insert_index = array('index_id'   =>  $last_index , 'compaign_id'	   => $flow_id,'type'	    => 'ivr');
		$dbo->insert_data('current_index' , $Insert_index);
						
						  echo 'Twillio Phone Change By index '.$comp_phone.'<br';
						
						
						
						/** 
						================================================================
						======= Oubound call genrate For Individual number =============
						================================================================ **/
						
						echo "<br><h3>Call Script</h3>";
						echo $voice_url    = ru."Services/IVR/index.php";
						echo '<br>';
						echo $voice_url;
						//$callback_url = ru."Services/IVR/callback_status.php";
						//$contact_phone = '+12149894300';
						//$contact_phone = '+12248756945';
						//$comp_phone = '+12248756945';
						
						
						

							
				$call = $client->account->calls->create($comp_phone, $contact_phone,$voice_url);
									
									
						/*=============== Insert Query for Ivr Log ================*/

						
							$table_name = 'ivr_log';
							$Data = array(
										  'user_id' 			=> $user_id ,
										  'voice_log_from'      => $comp_phone ,
										  'ivr_flow_id' 		=> $comp_id,
										  'campaign_title' 		=> $flow_name,
										  'voice_log_to' 		=> $contact_phone,
										  'group_id'			=> $group_id,
										 // 'start_date'			=> $flow_start_date
										  );
							$dbo->insert_data($table_name , $Data);

					
						
					} // end check current time by zone condition
					
				
				} //end contacts foreach loop
			
			}// end of contact size if condition
		
		
		}//end sms campaign schdule loop


	}//End Get User Credential Foreach Loop

?>