<?php
require_once("../connect/connect.php");
require_once('Twilio.php');



if(isset($_POST['To']) && $_POST['To'] != ''){
	
	
/*	$postData = '';
foreach($_REQUEST as $key => $val)
{
	$postData .= $key." => ".$val."\n \r";
}

mail("amirs@zamsol.com", "Call Responce", $postData);*/

$response 		= new Services_Twilio_Twiml;
$twilio_Number  = $_REQUEST['To'];
$user_phone 	= $_REQUEST['From'];
$CallStatus 	= $_REQUEST['DialCallStatus'];
$Direction 	    = $_REQUEST['Direction'];
$CallDuration 	= $_REQUEST['DialCallDuration'];
$DialCallSid 	= $_REQUEST['DialCallSid'];


if ( substr ( $twilio_Number, 0, 1) != "+" ) {
	$twilio_Number		=	'+'.trim($twilio_Number);
}
if ( substr ( $user_phone, 0, 1 ) != "+" ) {
	$user_phone		=	'+'.trim($user_phone);
}


/*$query = "INSERT INTO incoming_call SET 
								call_from	  = '$user_phone' , 
								call_to 	  = '$twilio_Number' , 
								call_status   = '$CallStatus' , 
								Direction     = '$Direction' , 
								CallDuration  = '$CallDuration',
								Callsid  	  = '$DialCallSid' ";
$dbo->do_query( $query );*/
$query   = mysql_query("SELECT * FROM  twilio_numbers WHERE t_phone = '$twilio_Number' ");
$res     = mysql_fetch_array($query);
$UserId  = $res['userId'];

		$update_data = array(
							 'call_from'	=> $user_phone , 
							 'call_to' 	    => $twilio_Number , 
							 'call_status'  => $CallStatus, 
							 'Direction'    => $Direction , 
							 'CallDuration' => $CallDuration,
							 'userId' => $UserId );
		
		$where = " WHERE Callsid ='".$DialCallSid."'";
		$dbo->update_data('incoming_call',$update_data,$where);


}

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';


echo '<Response>';

    
echo '</Response>';
?>