<?php
require_once("../../connect/connect.php");
require_once('../Twilio.php');


/*$postData = '';
foreach($_REQUEST as $key => $val)
{
	$postData .= $key." => ".$val."\n \r";
}
mail("amirs@zamsol.com", "Call Responce", $postData);*/


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

//getting the matched incoming call number for custom ivr
if($Direction == 'outbound-api'){
	
	$query_ivr 	= "SELECT * FROM  `ivr_flow`  where flow_number  ='".$user_phone."' AND  flow_direction = 'outbound' AND flow_status = 'Active'";
	
}else{
	
	$query_ivr 	= "SELECT * FROM  `ivr_flow`  where flow_id = (SELECT compaign_id FROM compaign_number  WHERE from_number ='$twilio_Number') AND flow_direction = 'inbound' AND flow_status = 'Active'";
}


$exec_query	= $dbo->do_query( $query_ivr );
$row = $dbo->get_row_array( $exec_query );
	  if( $dbo->num_rows($exec_query))
	  {
	  
		foreach($row  as $k => $v)
		{
			$$k =  addslashes(trim($v));
		}

        $flow_id =   $row['flow_id'];
		$user_id =   $row['user_id'];
		$group_id =   $row['group_id'];
		$flow_name =   $row['flow_name'];
		
		mysql_query(  "insert into ivr_log  set user_id =$user_id,voice_log_from='$twilio_Number',flow_name='$flow_name'
			,ivr_flow_id=$flow_id,voice_log_to='$user_phone',group_id=$group_id");
		
	
		
							
		if($Direction == 'outbound-api')
		{
			$response->redirect('outbound_multinumbrer.php?flow_id='.$flow_id.'&tcx='.strtotime('now'));
		}else{
		    	$response->redirect('ivrmenu.php?flow_id='.$flow_id.'&tcx='.strtotime('now'));  
		   //	$response->redirect('ivrmenu.php?flow_id='.$ivr_data[1]['flow_id'].'&tcx='.strtotime('now'));
		}
		
	  
	  }
	  else 
	  {	
		$response->reject(array("reason"=>"busy"));
	  }
header('Content-type: text/xml');
print $response;
exit;

?>