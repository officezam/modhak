<?php
include("../connect/connect.php");
require_once('Twilio.php');
 $data ='';
 foreach($_REQUEST as $key=>$val){
	 $data .=  " $key=>$val " ."\r\n";
 }
 //mail('amirs@zamsol.com', 'c2c' ,$data );
// get the phone number from the page request parameters, if given

/////////////////////////// checking ivr  ///////////////////
$response 		= new Services_Twilio_Twiml;
$twilio_Number  = trim($_REQUEST['To']);
$user_phone 	= trim($_REQUEST['From']);
$Direction 		= $_REQUEST['Direction'];

if ( substr ( $twilio_Number, 0, 1) != "+" ) {
	$twilio_Number		=	'+'.trim($twilio_Number);
}
if ( substr ( $user_phone, 0, 1 ) != "+" ) {
	$user_phone		=	'+'.trim($user_phone);
}


$query = " INSERT INTO incoming_call SET call_from = '$user_phone',call_to = '$twilio_Number' ";
$dbo->do_query( $query );



//getting the matched incoming call number for custom ivr
if($Direction == 'inbound'){
	$numbrqry 	   = "SELECT compaign_id FROM compaign_number WHERE from_number = '$twilio_Number' AND type ='ivr' ";
	$row_numbrqry = $dbo->do_query($numbrqry);
	$rs_numbrqry  = $dbo->get_row_array($row_numbrqry);
	$flow_id    	   = $rs_numbrqry['compaign_id'];

	$query_ivr 	= "SELECT * FROM  `ivr_flow`  where flow_id  ='$flow_id' AND 	flow_direction = 'inbound' AND flow_status = 'Active'";


$exec_query	= $dbo->do_query( $query_ivr 	);
$row = $dbo->get_row_array( $exec_query	);


	  if( $dbo->num_rows($exec_query))
	  {
	  
		foreach($row  as $k => $v)
		{
			$$k =  addslashes(trim($v));
		}
		
		
		
		$table_name = 'ivr_log';
							$Data = array(
										  'user_id' 			=> $user_id ,
										  'voice_log_from'      => $twilio_Number ,
										  'ivr_flow_id' 		=> $flow_id,
										  'campaign_title' 		=> $flow_name,
										  'voice_log_to' 		=> $user_phone,
										  'group_id'			=> $group_id,
										 // 'start_date'			=> $flow_start_date
										  );
							$dbo->insert_data($table_name , $Data);
							
			
	  
		$response->redirect('IVR/ivrmenu.php?flow_id='.$flow_id.'&tcx='.strtotime('now'));		
		
		header('Content-type: text/xml');
                print $response;
                exit;
	  
	  }
}



/////////////////////////// ivr ends ///////////////////

if (isset($_REQUEST['PhoneNumber'])) 
{
	
    $number = htmlspecialchars($_REQUEST['PhoneNumber']);
	$CallerId = htmlspecialchars($_REQUEST['CallerId']);
	
	
	// wrap the phone number or client name in the appropriate TwiML verb
	// by checking if the number given has only digits and format symbols
	if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
		$numberOrClient = "<Number>" . $number . "</Number>";
	} else {
		$numberOrClient = "<Client>" . $number . "</Client>";
	}
	$callerId = trim($callerId) ;
	if ( substr ( $callerId, 0, 1 ) != "+" )
	{
	$callerId  =	'+'.trim($callerId);
	}
	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8"?>';


	echo '<Response>';

		echo '<Dial callerId="'.  $CallerId .'" action="incoming_call.php" method="POST">';
		echo $numberOrClient;
		echo '</Dial>';
	echo '</Response>';

}else{
	
	$To = $_REQUEST['To'];
	$callerId = $_REQUEST['From'];
	if ( substr ( $callerId, 0, 1 ) != "+" )
	{
	$callerId  =	'+'.trim($callerId);
	}
	
	
	$select_name = "SELECT username FROM user as u,twilio_numbers as t Where t.userId = u.userId  AND t.t_phone ='$To'";

	$run_query   = $dbo->do_query($select_name);
	$user_name	 = $run_query->fetch_assoc();

	// put your default Twilio Client name here, for when a phone number isn't given
	$number      = $user_name['username']; 
	
	if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
		$numberOrClient = "<Number>" . $number . "</Number>";
	} else {
		$numberOrClient = "<Client>" . $number . "</Client>";
	}
	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8"?>';

	echo '<Response>';

	echo '<Dial callerId="'.  $callerId .'" action="incoming_call.php" method="POST">';
	echo $numberOrClient;
	echo '</Dial>';
	echo '</Response>';

}




 
?>