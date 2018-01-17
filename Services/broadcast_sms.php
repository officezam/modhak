<?php 

include_once('../connect/connect.php');

require_once(ru_dir.'Services/Twilio.php');

  ////// Get Authentication Token And Account Sid //////
  $apiQry	   = "select account_sid, auth_token from user where type = 'a' ";
  $exeqry      = $dbo->do_query($apiQry);
  $apiArray    = $dbo->get_row_array($exeqry);

  $account_sid = $apiArray['account_sid'];
  $auth_token  = $apiArray['auth_token']; 

  $client 	   = new Services_Twilio($account_sid, $auth_token); 


  //Get Server Date & Time  
  $qry_date    = "select NOW() as  serverdate" ;
  $rs_date     = $dbo->do_query($qry_date);
  $Date_time   = $dbo->get_row_array($rs_date);
  $serverdate  = $Date_time['serverdate'];
  $currnettime =  date( 'h:i A' , strtotime($serverdate) );


  
  /// get SMS Compaign Detail From table For Schedule Date & time
 $broadcas_qry	 	 = "select * From sms_voice_broadcast where type_status = 'SMS' AND status ='new' AND `repeat` = '1'  ";
 $exe_broadcas_qry   = $dbo->do_query($broadcas_qry);
 $broadcast_array	 = 	$dbo->get_result_array($exe_broadcas_qry );

if (!empty($broadcast_array) )
foreach($broadcast_array as $keyx => $broadcast_data)
{
			
	$repeat 		 = $broadcast_data['repeat'];
	$user_id 		 = $broadcast_data['user_id'];
	$sv_id 		 	 = $broadcast_data['sv_id'];
	//$comp_phone_from = $broadcast_data['phone'];
	$sms_Text		 = $broadcast_data['sms'];
	$group_id		 = $broadcast_data['group_id'];
	$selection		 = $broadcast_data['selection'];
	$contactIds	 	 = $broadcast_data['contact_id'];
	
	
		
		$broadcas_qry = "select * from time_schedule as t where	t.campaign_id = $sv_id 	 and 	 t.schedule_time <= NOW()";

		$exe_broadcas_qry   = $dbo->do_query($broadcas_qry);
		$broadcas_schedule = $dbo->get_result_array($exe_broadcas_qry);

		if(!empty($broadcas_schedule ))
		{

			foreach($broadcas_schedule as $key => $singleSchedule)
			{

			$s_id	 		 = $singleSchedule['s_id'];

			if($selection == 'all')
			
				$contact_qry  = "SELECT * FROM contacts WHERE  group_id = $group_id AND status ='verify' ";
			
			else
			
				$contact_qry  = "SELECT * FROM contacts WHERE  group_id = $group_id And id IN ($contactIds) AND status ='verify' "; 
			


			$exe_contact_qry  	   = $dbo->do_query($contact_qry);
			$exe_contact_qry_array = $dbo->get_result_array($exe_contact_qry);
			
			
					//Get Twillio Number From table compaign_number 
					//pool number  Get 
					$from_number_query 		= "SELECT from_number FROM compaign_number WHERE compaign_id = '$sv_id' order by n_id ASC";
					$row_from_number_query  = $dbo->do_query($from_number_query);
					$pool_numberArray  		= $dbo->get_result_array($row_from_number_query);
					$total_number 			=  count($pool_numberArray);
									    
					//Get Last inserted index from current-index table
					$index_qry 	   = "SELECT index_id FROM current_index WHERE compaign_id = '$sv_id'";
					$row_index_qry = $dbo->do_query($index_qry);
					$rs_index_qry  = $dbo->get_row_array($row_index_qry);
					$index    	   = $rs_index_qry['index_id'];

					if($index == '' || $index == null || $total_number <= $index){ $last_index = 0; }else{$last_index    = intval($index);}
					
					
			
			

				foreach($exe_contact_qry_array as $key => $contact_row)
				{

					$contact_phone	= $contact_row['phone_number'];	
					$contact_id		= $contact_row['id'];


					
					if ( substr ( $contact_phone, 0, 1 ) != '+' )  {
						 $contact_phone;
						 $contact_phone		=	'+'.trim($contact_phone);
						
					}


					
					
					$comp_phone_from = $pool_numberArray[$last_index]['from_number'];	
					
				
					$SmsUrl = ru.'Services/sms_report.php?user_id='.$user_id;
					///////////////// borad cast  sms to indiviual //////
					try{
						 $client->account->messages->create(array( 
								  'To' => $contact_phone, 
								  'From' => $comp_phone_from, 
								  'StatusCallback'  => $SmsUrl, 
								  'Body' => $sms_Text
						  ));
						}catch(Exception $e){
							//echo '<pre>';print_r($e);
							//$Decode = json_decode($sms);
                            //$STATUS = $Decode->status;
							//$body = $e->getJsonBody();
							//$error=$body['error']['message'];
							//mail("amirs@zamsol.com", "SMS Responce", $body);
							}
					 


		$Scheduledate   = array('schedule_time'   =>  ' DATE_ADD(schedule_time, INTERVAL 1 DAY) ' ) ;
		$Schedule_where = 'WHERE s_id ='. $s_id;
					$dbo->update_data('time_schedule',$Scheduledate,$Schedule_where);

					$Insert_data = array(
						'contact_id'   => $contact_id,
						'sms_id'	   => $sv_id,
						'group_id'     => $group_id,
						'to_number'    => $contact_phone,
						'from_number'  => $comp_phone_from						
					);

					$dbo->insert_data('sms_log' , $Insert_data);

				} //////// 2nd foreach
				
					$last_index++;
					if($total_number <= $last_index){ $last_index = 0; }
							
					$dbo->delete_data('current_index','compaign_id',$sv_id);
					$Insert_index = array('index_id'   =>  $last_index , 'compaign_id'	   => $sv_id);
					$dbo->insert_data('current_index' , $Insert_index);
				
				
			} //////// 1st foreach

		} //////// if not empty
}

$broadcas_qry	 	 = "select * From sms_voice_broadcast where type_status = 'SMS' AND status ='new' AND `repeat` <> '1'  AND  date = DATE_FORMAT(NOW(),'%m-%d-%Y') AND time <= '$currnettime' ";
 $exe_broadcas_qry   = $dbo->do_query($broadcas_qry);
 $broadcast_array	 = 	$dbo->get_result_array($exe_broadcas_qry );


if (!empty($broadcast_array) )
foreach($broadcast_array as $keyx => $broadcast_data)
{
			
	$repeat 		 = $broadcast_data['repeat'];
	$user_id 		 = $broadcast_data['user_id'];
	$sv_id 		 	 = $broadcast_data['sv_id'];
	//$comp_phone_from = $broadcast_data['phone'];
	$sms_Text		 = $broadcast_data['sms'];
	$group_id		 = $broadcast_data['group_id'];
	$selection		 = $broadcast_data['selection'];
	$contactIds	 	 = $broadcast_data['contact_id'];
	
	
			
	
		
		if($selection == 'all')
		
			$contact_qry  = "SELECT * FROM contacts WHERE  group_id = $group_id AND status ='verify' ";
		
		else
		
			$contact_qry  = "SELECT * FROM contacts WHERE  group_id = $group_id And id IN ( $contactIds) AND status ='verify' "; 
		


		$exe_contact_qry  	   = $dbo->do_query($contact_qry);
		$exe_contact_qry_array = $dbo->get_result_array($exe_contact_qry);
		
		
		//Get Last inserted index from current-index table
		$index_qry 	   = "SELECT index_id FROM current_index WHERE compaign_id = '$sv_id'";
		$row_index_qry = $dbo->do_query($index_qry);
		$rs_index_qry  = $dbo->get_row_array($row_index_qry);
		$index    	   = $rs_index_qry['index_id'];
		
		//Get Twillio Number From table compaign_number 
		//pool number  Get 
	    $from_number_query = "SELECT from_number FROM compaign_number WHERE compaign_id = '$sv_id' order by n_id ASC";
		$row_from_number_query  = $dbo->do_query($from_number_query);
		$pool_numberArray  		= $dbo->get_result_array($row_from_number_query);
		$total_number 			=  count($pool_numberArray);
		
		
		if($index == '' || $index == null || $total_number <= $index){ $last_index = 0; }else{$last_index    = intval($index);}

		foreach($exe_contact_qry_array as $key => $contact_row)
		{

			$contact_phone	= $contact_row['phone_number'];	
			$contact_id		= $contact_row['id'];


			if ( substr ( $contact_phone, 0, 1 ) != '+' ) {
				$contact_phone		=	'+'.trim($contact_phone);
			}
			
			  $comp_phone_from = $pool_numberArray[$last_index]['from_number'];	
			 

			///////////////// borad cast  sms to indiviual //////
			$SmsUrl = ru.'Services/sms_report.php?user_id='.$user_id;
			try{
			 $client->account->messages->create(array( 
				'To' => $contact_phone, 
				'From' => $comp_phone_from,  
				'StatusCallback'  => $SmsUrl, 
				'Body' => $sms_Text   
			));
			
			}catch(Exception $e){
							//echo '<pre>';print_r($e);
							//$Decode = json_decode($sms);
                            //$STATUS = $Decode->status;
							//$body = $e->getJsonBody();
							//$error=$body['error']['message'];
							//mail("amirs@zamsol.com", "SMS Responce", $body);
							}
			
			
			$table_name = 'sms_log'; 				
			$Insert_data = array(
				'contact_id'   => $contact_id,
				'sms_id'	   => $sv_id,
				'group_id'     => $group_id,
				'to_number'    => $contact_phone,
				'from_number'  => $comp_phone_from,
				//'dated' 	   => $curreent_date
			);

			$dbo->insert_data($table_name , $Insert_data);

			if($repeat == '4'){
				$date = date('m-d-Y', strtotime('+1 month'));
				$table = 'sms_voice_broadcast';
				$editDate    = array( 'status' => 'new',
				'date'   => $date);
				$where = 'WHERE sv_id ='. $sv_id;
				$dbo->update_data($table,$editDate,$where);
			}


			if($repeat == '2'){
				$date = date('m-d-Y', strtotime('+1 week'));

				$table = 'sms_voice_broadcast';
				$editDate    = array( 'status' => 'new',
				'date'   => $date);
				$where = 'WHERE sv_id ='. $sv_id;
				$dbo->update_data($table,$editDate,$where);
			}





			if($repeat == '0'){
				$table = 'sms_voice_broadcast';
				$editDate    = array( 'status'     => 'SENT',
				'time'   => $currnettime );
				$where = 'WHERE sv_id ='. $sv_id;
				$dbo->update_data($table,$editDate,$where);
			}
		}
		
		$last_index++;
		if($total_number <= $last_index){ $last_index = 0; }
			  
		/////////// update index 
		$dbo->delete_data('current_index','compaign_id',$sv_id);
		$Insert_index = array('index_id'   =>  $last_index , 'compaign_id'	   => $sv_id);
		$dbo->insert_data('current_index' , $Insert_index);

}
?>