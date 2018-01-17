<?php
require_once("../connect/connect.php");
require_once('Twilio.php');

/*$postData = '';

foreach($_REQUEST as $key => $val)
{
	$postData .= $key." => ".$val."\n \r";
	
}
mail("amirs@zamsol.com", "SMS Responce", $postData);
*/
//$response 		= new Services_Twilio_Twiml;
$SmsSid         = $_REQUEST['SmsSid'];
$user_id        = $_REQUEST['user_id'];
$user_phone  	= $_REQUEST['To'];
$twilio_Number	= $_REQUEST['From'];
$Body  		    = $_REQUEST['Body'];
$sms_status	    = $_REQUEST['SmsStatus'];
$MessageStatus 	= $_REQUEST['MessageStatus'];
$ErrorCode 	    = $_REQUEST['ErrorCode'];
$curreent_date  = date('m-d-Y H:i:s');


							
if($SmsSid != '' ){

$ErrorCodeArray = array(
						'30001' => 'Queue overflow',
						'30002' => 'Account suspended',
						'30003' => 'Unreachable destination handset',
						'30004' => 'Message blocked',
						'30005' => 'Unknown destination handset',
						'30006' => 'Landline or unreachable carrier',
						'30007' => 'Carrier violation',
						'30008' => 'Unknown error',
						'30009' => 'Missing segment',
						'30010' => 'Message price exceeds max price.');


$ErrorMsg 	   = $ErrorCodeArray[$ErrorCode];

$query_smsid   = "SELECT * FROM broadcastsms_report WHERE SmsSid = '$SmsSid' ";
$run_query     = $dbo->do_query($query_smsid);
$total_rows	= $run_query->num_rows;

if ($total_rows > 0 ){
	
	  $Update_qry = array(
						  'sms_from' 	  	=> $user_phone ,
						  'sms_to' 		   	=> $twilio_Number, 
						  'sms_text'		=> $Body ,
						  'sms_status'	   	=> $sms_status,
						  'MessageStatus'	=> $MessageStatus,
						  'ErrorCode'		=> $ErrorCode,
						  'ErrorMsg'		=> $ErrorMsg,
						  'sms_date'	    => $curreent_date,
						  'userId' 			=> $user_id,);
	 $where = "WHERE SmsSid ='".$SmsSid."'";
  	 $dbo->update_data('broadcastsms_report',$Update_qry,$where);
  
  //mail("amirs@zamsol.com", "SMS report up", $Update_qry);
 }else{
	  $Insert_data = array(
								'sms_from' 		=> $user_phone ,
								'sms_to' 		=> $twilio_Number, 
								'sms_text' 		=> $Body ,
								'sms_status'	=> $sms_status,
								'MessageStatus'	=> $MessageStatus,
								'ErrorCode'		=> $ErrorCode,
								'ErrorMsg'		=> $ErrorMsg,
								'SmsSid'		=> $SmsSid,
								'sms_date'	    => $curreent_date,
								'userId' 		=> $user_id,);
			$dbo->insert_data('broadcastsms_report' , $Insert_data);
    
	//mail("amirs@zamsol.com", "SMS report Ins", $Insert_data);
	 }	

 }
    ?>